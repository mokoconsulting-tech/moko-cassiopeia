set -euo pipefail

log() { printf '%s\n' "$*"; }

fail() {
  log "ERROR: $*" >&2
  exit 1
}

SRC_DIR="${SRC_DIR:-src}"

if [ ! -d "${SRC_DIR}" ]; then
  fail "${SRC_DIR} directory missing"
fi

# Candidate discovery policy: prefer explicit known names, otherwise fall back to extension-root manifests.
# Goal: choose ONE manifest deterministically.
manifest_candidates=()

# Template
if [ -f "${SRC_DIR}/templateDetails.xml" ]; then
  manifest_candidates+=("${SRC_DIR}/templateDetails.xml")
fi

# Package
while IFS= read -r f; do
  [ -n "${f}" ] && manifest_candidates+=("${f}")
done < <(find "${SRC_DIR}" -maxdepth 4 -type f -name 'pkg_*.xml' 2>/dev/null | sort || true)

# Component
while IFS= read -r f; do
  [ -n "${f}" ] && manifest_candidates+=("${f}")
done < <(find "${SRC_DIR}" -maxdepth 4 -type f -name 'com_*.xml' 2>/dev/null | sort || true)

# Module
while IFS= read -r f; do
  [ -n "${f}" ] && manifest_candidates+=("${f}")
done < <(find "${SRC_DIR}" -maxdepth 4 -type f -name 'mod_*.xml' 2>/dev/null | sort || true)

# Plugin
while IFS= read -r f; do
  [ -n "${f}" ] && manifest_candidates+=("${f}")
done < <(find "${SRC_DIR}" -maxdepth 6 -type f -name 'plg_*.xml' 2>/dev/null | sort || true)

# Fallback: any XML containing <extension ...>
if [ "${#manifest_candidates[@]}" -eq 0 ]; then
  while IFS= read -r f; do
    [ -n "${f}" ] && manifest_candidates+=("${f}")
  done < <(grep -Rsl --include='*.xml' '<extension' "${SRC_DIR}" 2>/dev/null | sort || true)
fi

if [ "${#manifest_candidates[@]}" -eq 0 ]; then
  fail "No Joomla manifest XML found under ${SRC_DIR}"
fi

# De-duplicate while preserving order.
unique_candidates=()
for c in "${manifest_candidates[@]}"; do
  seen=false
  for u in "${unique_candidates[@]}"; do
    if [ "${u}" = "${c}" ]; then
      seen=true
      break
    fi
  done
  if [ "${seen}" = "false" ]; then
    unique_candidates+=("${c}")
  fi
done
manifest_candidates=("${unique_candidates[@]}")

# Enforce single primary manifest.
if [ "${#manifest_candidates[@]}" -gt 1 ]; then
  {
    log "ERROR: Multiple manifest candidates detected. Resolve to exactly one primary manifest." >&2
    log "Candidates:" >&2
    for c in "${manifest_candidates[@]}"; do
      log "- ${c}" >&2
    done
  }
  exit 1
fi

MANIFEST="${manifest_candidates[0]}"

if [ ! -s "${MANIFEST}" ]; then
  fail "Manifest is empty: ${MANIFEST}"
fi

# Parse with python for portability (xmllint not guaranteed).
python3 - <<'PY' "${MANIFEST}" || exit 1
import sys
import json
import xml.etree.ElementTree as ET
from pathlib import Path

manifest_path = Path(sys.argv[1])

def fail(msg, **ctx):
  payload = {"status":"fail","error":msg, **ctx}
  print(json.dumps(payload, ensure_ascii=False))
  sys.exit(1)

try:
  tree = ET.parse(manifest_path)
  root = tree.getroot()
except Exception as e:
  fail("XML parse failed", manifest=str(manifest_path), detail=str(e))

if root.tag != "extension":
  fail("Root element must be <extension>", manifest=str(manifest_path), root=str(root.tag))

ext_type = (root.attrib.get("type") or "").strip().lower() or "unknown"
allowed_types = {"template","component","module","plugin","package","library","file","files"}

# Minimal required fields across most extension types.
name_el = root.find("name")
version_el = root.find("version")

name = (name_el.text or "").strip() if name_el is not None else ""
version = (version_el.text or "").strip() if version_el is not None else ""

missing = []
if not name:
  missing.append("name")
if not version:
  missing.append("version")

if ext_type not in allowed_types and ext_type != "unknown":
  fail("Unsupported extension type", manifest=str(manifest_path), ext_type=ext_type)

# Type-specific expectations.
warnings = []

if ext_type == "plugin":
  group = (root.attrib.get("group") or "").strip()
  if not group:
    missing.append("plugin.group")

  files_el = root.find("files")
  if files_el is None:
    missing.append("files")

elif ext_type in {"component","module","template"}:
  files_el = root.find("files")
  if files_el is None:
    missing.append("files")

elif ext_type == "package":
  files_el = root.find("files")
  if files_el is None:
    missing.append("files")
  else:
    # Package should reference at least one child manifest.
    file_nodes = files_el.findall("file")
    if not file_nodes:
      warnings.append("package.files has no <file> entries")

# Optional but commonly expected.
method = (root.attrib.get("method") or "").strip().lower()
if method and method not in {"upgrade","install"}:
  warnings.append(f"unexpected extension method={method}")

# Provide a stable, machine-readable report.
if missing:
  fail("Missing required fields", manifest=str(manifest_path), ext_type=ext_type, missing=missing, warnings=warnings)

print(json.dumps({
  "status": "ok",
  "manifest": str(manifest_path),
  "ext_type": ext_type,
  "name": name,
  "version": version,
  "warnings": warnings,
}, ensure_ascii=False))
PY

# Human-friendly summary (kept short for CI logs).
log "manifest: ok (${MANIFEST})"
