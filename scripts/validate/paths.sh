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
# INGROUP: Path.Normalization
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/validate/paths.sh
# VERSION: 01.00.00
# BRIEF: Detect Windows-style path separators (backslashes)
# NOTE: Ensures cross-platform path compatibility
# ============================================================================

set -euo pipefail

# Detect Windows-style path literals (backslashes) in repository files.
# Uses git ls-files -z and searches file contents for a literal backslash.

hits=()
hit_lines=()

while IFS= read -r -d '' f; do
  # Skip common binary files by mime-type
  if file --brief --mime-type "$f" | grep -qE '^(application|audio|image|video)/'; then
    continue
  fi
  # Find lines with backslashes and collect details
  if backslash_lines=$(grep -n -F $'\\' -- "$f" 2>/dev/null); then
    hits+=("$f")
    hit_lines+=("$backslash_lines")
  fi
done < <(git ls-files -z)

if [ "${#hits[@]}" -gt 0 ]; then
  echo "ERROR: Windows-style path literals detected" >&2
  echo "" >&2
  echo "Found backslashes in ${#hits[@]} file(s):" >&2
  for i in "${!hits[@]}"; do
    echo "" >&2
    echo "  File: ${hits[$i]}" >&2
    echo "  Lines with backslashes:" >&2
    echo "${hit_lines[$i]}" | head -5 | sed 's/^/    /' >&2
    if [ "$(echo "${hit_lines[$i]}" | wc -l)" -gt 5 ]; then
      echo "    ... and $(($(echo "${hit_lines[$i]}" | wc -l) - 5)) more" >&2
    fi
  done
  echo "" >&2
  echo "To fix:" >&2
  echo "  1. Run: ./scripts/fix/paths.sh" >&2
  echo "  2. Or manually replace backslashes (\\) with forward slashes (/)" >&2
  echo "  3. Ensure paths use POSIX separators for cross-platform compatibility" >&2
  echo "" >&2
  exit 2
fi

echo "paths: ok"
