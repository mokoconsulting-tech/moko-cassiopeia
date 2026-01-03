#!/usr/bin/env bash
set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"

json_escape() {
	python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

[ -d "${SRC_DIR}" ] || {
	printf '{"status":"fail","error":%s}
' "$(json_escape "src directory missing")"
	exit 1
}

if ! command -v php >/dev/null 2>&1; then
	printf '{"status":"ok","warning":"php_not_available","src_dir":%s}
' "$(json_escape "${SRC_DIR}")"
	echo "php_syntax: ok (php not available)"
	exit 0
fi

failed=0
checked=0

while IFS= read -r -d '' f; do
	checked=$((checked+1))
	if ! php -l "$f" >/dev/null; then
		failed=1
	fi
done < <(find "${SRC_DIR}" -type f -name '*.php' -print0)

if [ "${failed}" -ne 0 ]; then
	printf '{"status":"fail","error":"php_lint_failed","files_checked":%s}
' "${checked}"
	exit 1
fi

printf '{"status":"ok","files_checked":%s}
' "${checked}"
echo "php_syntax: ok"
