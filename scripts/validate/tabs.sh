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
