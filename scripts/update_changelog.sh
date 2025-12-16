#!/usr/bin/env bash
set -euo pipefail

# scripts/update_changelog.sh
#
# Purpose:
# - Apply the MokoWaaS-Brand CHANGELOG template entry for a given version.
# - Insert a new header at the top of CHANGELOG.md, immediately after "# Changelog".
# - Avoid duplicates if an entry for the version already exists.
# - Preserve the rest of the file verbatim.
#
# Usage:
#   ./scripts/update_changelog.sh <VERSION>
#
# Example:
#   ./scripts/update_changelog.sh 01.05.00

VERSION="${1:-}"

if [[ -z "${VERSION}" ]]; then
  echo "ERROR: Version argument is required. Usage: scripts/update_changelog.sh <VERSION>" >&2
  exit 1
fi

CHANGELOG_FILE="CHANGELOG.md"

DATE_UTC="$(date -u +"%Y-%m-%d")"
HEADER="## ${VERSION} - ${DATE_UTC}"

if [[ ! -f "${CHANGELOG_FILE}" ]]; then
  {
    echo "# Changelog"
    echo
  } > "${CHANGELOG_FILE}"
fi

# Do not duplicate existing entries
if grep -qE "^##[[:space:]]+${VERSION}[[:space:]]+-[[:space:]]+" "${CHANGELOG_FILE}"; then
  echo "CHANGELOG.md already contains an entry for ${VERSION}. No changes made."
  exit 0
fi

TMP_FILE="${CHANGELOG_FILE}.tmp"

# Insert after the first '# Changelog' line.
# If '# Changelog' is missing, prepend it.
if grep -qE "^# Changelog$" "${CHANGELOG_FILE}"; then
  awk -v header="${HEADER}" '
    BEGIN { inserted=0 }
    {
      print
      if (!inserted && $0 ~ /^# Changelog$/) {
        print ""
        print header
        print ""
        inserted=1
      }
    }
    END {
      if (!inserted) {
        # fallback: append at end
        print ""
        print header
        print ""
      }
    }
  ' "${CHANGELOG_FILE}" > "${TMP_FILE}"
else
  {
    echo "# Changelog"
    echo
    echo "${HEADER}"
    echo
    cat "${CHANGELOG_FILE}"
  } > "${TMP_FILE}"
fi

mv "${TMP_FILE}" "${CHANGELOG_FILE}"

echo "Applied changelog header: ${HEADER}"
