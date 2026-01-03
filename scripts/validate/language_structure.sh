set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"
LANG_ROOT="${LANG_ROOT:-${SRC_DIR}/language}"

json_escape() {
  python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

[ -d "${SRC_DIR}" ] || {
  printf '{"status":"fail","error":%s}
' "$(json_escape "src directory missing")"
  exit 1
}

python3 - <<'PY' "${LANG_ROOT}"
import json
import sys
import re
from pathlib import Path

lang_root = Path(sys.argv[1])

# Language directory is optional for some extension types
if not lang_root.exists():
  print(json.dumps({"status":"ok","lang_root":str(lang_root),"languages":[],"warnings":["language_root_missing"]}, ensure_ascii=False))
  sys.exit(0)

if not lang_root.is_dir():
  print(json.dumps({"status":"fail","error":"language_root_not_directory","lang_root":str(lang_root)}, ensure_ascii=False))
  sys.exit(1)

lang_dirs = sorted([p for p in lang_root.iterdir() if p.is_dir()])

# Joomla language tags: en-GB, fr-FR, etc.
pattern = re.compile(r'^[a-z]{2}-[A-Z]{2}$')
invalid = [p.name for p in lang_dirs if not pattern.match(p.name)]

warnings = []

# Soft expectation: en-GB exists if any language directories exist
if lang_dirs and not (lang_root / 'en-GB').exists():
  warnings.append('en-GB_missing')

# Validate INI naming
missing_ini = []
nonmatching_ini = []

for d in lang_dirs:
  ini_files = [p for p in d.glob('*.ini') if p.is_file()]
  if not ini_files:
    missing_ini.append(d.name)
    continue
  for ini in ini_files:
    if not (ini.name.startswith(d.name + '.') or ini.name == f"{d.name}.ini"):
      nonmatching_ini.append(str(ini))

result = {
  "status": "ok",
  "lang_root": str(lang_root),
  "languages": [d.name for d in lang_dirs],
  "warnings": warnings,
}

# Hard failures
if invalid:
  result.update({"status":"fail","error":"invalid_language_tag_dir","invalid":invalid})
  print(json.dumps(result, ensure_ascii=False))
  sys.exit(1)

if nonmatching_ini:
  result.update({"status":"fail","error":"ini_name_mismatch","nonmatching_ini":nonmatching_ini[:50]})
  print(json.dumps(result, ensure_ascii=False))
  sys.exit(1)

if missing_ini:
  result.update({"status":"fail","error":"missing_ini_files","missing_ini":missing_ini})
  print(json.dumps(result, ensure_ascii=False))
  sys.exit(1)

print(json.dumps(result, ensure_ascii=False))
PY

echo "language_structure: ok"
