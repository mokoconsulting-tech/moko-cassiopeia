#!/usr/bin/env bash
#
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# This file is part of a Moko Consulting project.
#
# SPDX-License-Identifier: GPL-3.0-or-later
#
# FILE INFORMATION
# DEFGROUP: MokoStandards
# INGROUP: Tooling.Changelog
# FILE: verify_changelog.sh
# BRIEF: Validate CHANGELOG.md governance rules for CI enforcement
#
# PURPOSE:
# Validate that CHANGELOG.md contains only released, properly ordered entries and complies with MokoStandards governance rules.

set -euo pipefail

CHANGELOG="CHANGELOG.md"

if [ ! -f "$CHANGELOG" ]; then
  echo "ERROR: CHANGELOG.md not found at repository root" >&2
  exit 1
fi

CONTENT="$(cat "$CHANGELOG")"

if echo "$CONTENT" | grep -Eiq '^##[[:space:]]*\[?TODO\]?'; then
  echo "ERROR: TODO section detected in CHANGELOG.md." >&2
  echo "CHANGELOG.md must contain released versions only." >&2
  echo "Move all TODO items to TODO.md and remove the section from CHANGELOG.md." >&2
  exit 1
fi

if echo "$CONTENT" | grep -Eiq 'UNRELEASED'; then
  echo "ERROR: UNRELEASED placeholder detected in CHANGELOG.md." >&2
  exit 1
fi

for token in "TBD" "TO BE DETERMINED" "PLACEHOLDER"; do
  if echo "$CONTENT" | grep -Eiq "$token"; then
    echo "ERROR: Unresolved placeholder detected: $token" >&2
    exit 1
  fi
done

mapfile -t versions < <(
  grep -E '^## \[[0-9]+\.[0-9]+\.[0-9]+\] [0-9]{4}-[0-9]{2}-[0-9]{2}$' "$CHANGELOG" \
  | sed -E 's/^## \[([0-9]+\.[0-9]+\.[0-9]+)\].*/\1/'
)

if [ "${#versions[@]}" -eq 0 ]; then
  echo "ERROR: No valid version headings found in CHANGELOG.md" >&2
  exit 1
fi

sorted_versions="$(printf '%s\n' "${versions[@]}" | sort -Vr)"

if [ "$(printf '%s\n' "${versions[@]}")" != "$sorted_versions" ]; then
  echo "ERROR: Versions are not ordered from newest to oldest" >&2
  exit 1
fi

echo "CHANGELOG.md validation passed"
exit 0
