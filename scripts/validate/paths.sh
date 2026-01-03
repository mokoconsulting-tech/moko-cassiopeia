#!/usr/bin/env bash
set -euo pipefail

# Detect Windows-style path literals (backslashes) in repository files.
# Uses git ls-files -z and searches file contents for a literal backslash.

hits=()
while IFS= read -r -d '' f; do
  # Skip binary files
  if file --brief --mime-type "$f" | grep -qE '^(application|audio|image|video)/'; then
    continue
  fi
  if grep -F $'\\' -- "$f" >/dev/null 2>&1; then
    hits+=("$f")
  fi
done < <(git ls-files -z)

if [ "${#hits[@]}" -gt 0 ]; then
  echo "ERROR: windows_path_literal_detected"
  for h in "${hits[@]}"; do
    echo " - ${h}"
  done
  exit 2
fi

echo "paths: ok"
