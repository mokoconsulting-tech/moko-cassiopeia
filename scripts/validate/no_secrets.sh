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

# High-signal patterns only. Any match is a hard fail.
patterns=(
	'-----BEGIN (RSA|DSA|EC|OPENSSH) PRIVATE KEY-----'
	'PuTTY-User-Key-File-'
	'AKIA[0-9A-Z]{16}'
	'ASIA[0-9A-Z]{16}'
	'ghp_[A-Za-z0-9]{36}'
	'gho_[A-Za-z0-9]{36}'
	'github_pat_[A-Za-z0-9_]{20,}'
	'xox[baprs]-[0-9A-Za-z-]{10,48}'
	'sk_live_[0-9a-zA-Z]{20,}'
)

regex="$(IFS='|'; echo "${patterns[*]}")"

set +e
hits=$(grep -RInE --exclude-dir=vendor --exclude-dir=node_modules --exclude-dir=dist "${regex}" "${SRC_DIR}" 2>/dev/null)
set -e

if [ -n "${hits}" ]; then
	{
		echo '{"status":"fail","error":"secret_pattern_detected","hits":['
		echo "${hits}" | head -n 50 | python3 - <<'PY'
import json,sys
lines=[l.rstrip('
') for l in sys.stdin.readlines() if l.strip()]
print("
".join([json.dumps({"hit":l})+"," for l in lines]).rstrip(','))
PY
		echo ']}'
	}
	exit 1
fi

printf '{"status":"ok","src_dir":%s}
' "$(json_escape "${SRC_DIR}")"
echo "no_secrets: ok"
