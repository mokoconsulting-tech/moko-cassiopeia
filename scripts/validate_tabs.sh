#!/usr/bin/env bash
#
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
# along with this program. If not, see <https://www.gnu.org/licenses/>.
#
# FILE INFORMATION
# DEFGROUP: MokoStandards
# INGROUP: Scripts.Validation
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/validate_tabs.sh
# VERSION: 01.00.00
# BRIEF: Detect tab characters in repository files for CI enforcement.
#

set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel)"
EXIT_CODE=0

echo "Scanning repository for tab characters"

# Exclude common binary and vendor paths
EXCLUDES=(
   ".git"
   "node_modules"
   "vendor"
   "dist"
   "build"
)

EXCLUDE_ARGS=()
for path in "${EXCLUDES[@]}"; do
   EXCLUDE_ARGS+=("--exclude-dir=${path}")
done

# Search for literal tab characters
MATCHES=$(grep -RIn $'\t' "${ROOT_DIR}" "${EXCLUDE_ARGS[@]}" || true)

if [ -n "${MATCHES}" ]; then
   echo "Tab characters detected in the following files:"
   echo "${MATCHES}"
   echo ""
   echo "CI policy violation: tabs are not permitted"
   EXIT_CODE=1
else
   echo "No tab characters found"
fi

exit "${EXIT_CODE}"
