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
while IFS= read -r -d '' f; do
  # Skip common binary files by mime-type
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
