#!/usr/bin/env bash

# ============================================================================
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# This file is part of a Moko Consulting project.
#
# SPDX-License-Identifier: GPL-3.0-or-later
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program (./LICENSE.md).
# ============================================================================

# ============================================================================
# FILE INFORMATION
# ============================================================================
# DEFGROUP: Script.Validate
# INGROUP: Code.Quality
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/validate/tabs.sh
# VERSION: 01.00.00
# BRIEF: Detect TAB characters in YAML files where they are not allowed
# NOTE: YAML specification forbids tab characters
# ============================================================================

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
bad_files=()
bad_lines=()

while IFS= read -r f; do
  # Find lines with tabs and store them
  if tab_lines=$(grep -n $'\t' -- "$f" 2>/dev/null); then
    echo "TAB found in $f" >&2
    echo "  Lines with tabs:" >&2
    echo "$tab_lines" | head -5 | sed 's/^/    /' >&2
    if [ "$(echo "$tab_lines" | wc -l)" -gt 5 ]; then
      echo "    ... and $(($(echo "$tab_lines" | wc -l) - 5)) more" >&2
    fi
    echo "" >&2
    bad=1
    bad_files+=("$f")
  fi
done <<< "${files}"

if [ "${bad}" -ne 0 ]; then
  echo "" >&2
  echo "ERROR: Tabs found in repository files" >&2
  echo "" >&2
  echo "YAML specification forbids tab characters." >&2
  echo "Found tabs in ${#bad_files[@]} file(s):" >&2
  for f in "${bad_files[@]}"; do
    echo "  - $f" >&2
  done
  echo "" >&2
  echo "To fix:" >&2
  echo "  1. Run: ./scripts/fix/tabs.sh" >&2
  echo "  2. Or manually replace tabs with spaces in your editor" >&2
  echo "  3. Configure your editor to use spaces (not tabs) for YAML files" >&2
  echo "" >&2
  exit 2
fi

echo "tabs: ok"
