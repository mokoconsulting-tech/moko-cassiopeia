#!/usr/bin/env bash
set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"

json_escape() {
	python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

[ -d "${SRC_DIR}" ] || {
	printf '{"status":"fail","error":%s}\n' "$(json_escape "src directory missing")"
	exit 1
}

python3 - <<'PY' "${SRC_DIR}"
import json
import sys
from pathlib import Path

src = Path(sys.argv[1])

exts = {'.php','.js','.css','.sh','.yml','.yaml','.xml'}
exclude_dirs = {'vendor','node_modules','dist','.git','build','tmp'}

missing = []
scanned = 0

for p in src.rglob('*'):
	if not p.is_file():
		continue
	if any(part in exclude_dirs for part in p.parts):
		continue
	if p.suffix.lower() not in exts:
		continue

	try:
		data = p.read_bytes()[:2048]
	except Exception:
		continue

	if b'\x00' in data:
		continue

	scanned += 1
	head = data.decode('utf-8', errors='replace')
	if 'SPDX-License-Identifier:' not in head:
		missing.append(str(p))

if missing:
	print(json.dumps({
		"status":"fail",
		"error":"missing_spdx_identifier",
		"scanned":scanned,
		"missing_count":len(missing),
		"missing":missing[:200]
	}, ensure_ascii=False))
	sys.exit(1)

print(json.dumps({"status":"ok","scanned":scanned,"missing_count":0}, ensure_ascii=False))
PY

echo "license_headers
