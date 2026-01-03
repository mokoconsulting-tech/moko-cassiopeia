#!/usr/bin/env bash
set -euo pipefail

# Validate that the package/manifest version is present in CHANGELOG.md
# Uses a safe, quoted heredoc for the embedded Python to avoid shell
# interpolation and CRLF termination issues.

if ! command -v python3 >/dev/null 2>&1; then
  echo "ERROR: python3 not found" >&2
  exit 1
fi

python3 - <<'PY'
import sys, re, json, glob

# Locate a likely manifest under src
candidates = [
    'src/templateDetails.xml',
    'src/manifest.xml'
]
manifest = None
for p in candidates:
    try:
        with open(p, 'r', encoding='utf-8') as fh:
            manifest = fh.read()
            break
    except FileNotFoundError:
        pass

if manifest is None:
    # Fallback: search for an XML file under src that contains a version attribute
    for fn in glob.glob('src/**/*.xml', recursive=True):
        try:
            with open(fn, 'r', encoding='utf-8') as fh:
                txt = fh.read()
                if 'version=' in txt:
                    manifest = txt
                    break
        except Exception:
            continue

if manifest is None:
    print('WARNING: No manifest found, skipping version alignment check')
    sys.exit(0)

m = re.search(r'version=["\']([0-9]+\.[0-9]+\.[0-9]+)["\']', manifest)
if not m:
    print('ERROR: could not find semantic version in manifest')
    sys.exit(2)

manifest_version = m.group(1)

try:
    with open('CHANGELOG.md', 'r', encoding='utf-8') as fh:
        changelog = fh.read()
except FileNotFoundError:
    print('ERROR: CHANGELOG.md not found')
    sys.exit(2)

if f'## [{manifest_version}]' not in changelog:
    print(f'ERROR: version {manifest_version} missing from CHANGELOG.md')
    sys.exit(2)

print(json.dumps({'status': 'ok', 'version': manifest_version}))
sys.exit(0)
PY
