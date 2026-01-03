#!/usr/bin/env bash
set -euo pipefail

# Detect TAB characters in source files tracked by Git. Uses careful
# handling of filenames and avoids heredoc pitfalls.

# Check only YAML/YML files where tabs are not allowed by the YAML specification.
# Note: Other file types (PHP, JS, etc.) allow tabs per .editorconfig.
files=$(git ls-files '*.yml' '*.yaml' || true)

if [ -z "${files}" ]; then
  echo "No files to check"
  exit 0
fi

bad=0
while IFS= read -r f; do
  if grep -n $'\t' -- "$f" >/dev/null 2>&1; then
    echo "TAB found in $f"
    bad=1
  fi
done <<< "${files}"

if [ "${bad}" -ne 0 ]; then
  echo "ERROR: Tabs found in repository files" >&2
  exit 2
fi

echo "tabs: ok"
