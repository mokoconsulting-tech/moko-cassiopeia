#!/usr/bin/env bash

set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"

json_escape() {
  python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

fail() {
  local msg="$1"
  local extra="${2:-}"
  if [ -n "${extra}" ]; then
    printf '{"status":"fail","error":%s,%s}\n' "$(json_escape "${msg}")" "${extra}"
  else
    printf '{"status":"fail","error":%s}\n' "$(json_escape "${msg}")"
  fi
  exit 1
}

[ -d "${SRC_DIR}" ] || fail "src directory missing" "\"src_dir\":$(json_escape "${SRC_DIR}")"

python3 - <<'PY' "${SRC_DIR}"
import json
import sys
from pathlib import Path
import xml.etree.ElementTree as ET

src = Path(sys.argv[1])
xml_files = sorted([p for p in src.rglob('*.xml') if p.is_file()])

bad = []
for p in xml_files:
  try:
    ET.parse(p)
  except Exception as e:
    bad.append({"path": str(p), "error": str(e)})

if bad:
  print(json.dumps({
    "status": "fail",
    "error": "XML parse failed",
    "src_dir": str(src),
    "xml_count": len(xml_files),
    "bad_count": len(bad),
    "bad": bad[:25],
  }, ensure_ascii=False))
  sys.exit(1)

print(json.dumps({
  "status": "ok",
  "src_dir": str(src),
  "xml_count": len(xml_files),
}, ensure_ascii=False))
PY

echo "xml_wellformed: ok"
