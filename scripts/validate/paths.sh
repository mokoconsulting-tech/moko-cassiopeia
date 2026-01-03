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
# PATH: /scripts/paths.sh
# VERSION: 03.05.00
# BRIEF: Detect Windows-style path separators in repository text files for CI enforcement.
#

set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel)"
EXIT_CODE=0

echo "Scanning repository for Windows-style path separators (\\)"

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

# Only scan likely-text files to reduce false positives from binaries.
# This list is intentionally broad for standards repos.
INCLUDE_GLOBS=(
   "*.md" "*.txt" "*.yml" "*.yaml" "*.json" "*.xml" "*.ini" "*.cfg"
   "*.sh" "*.bash" "*.ps1" "*.php" "*.js" "*.ts" "*.css" "*.scss"
   "*.html" "*.htm" "*.vue" "*.java" "*.go" "*.py" "*.rb" "*.c" "*.h" "*.cpp" "*.hpp"
)

GREP_INCLUDE_ARGS=()
for g in "${INCLUDE_GLOBS[@]}"; do
   GREP_INCLUDE_ARGS+=("--include=${g}")
done

# Search for backslashes. This is a governance check for repo docs and automation scripts.
# Note: This does not try to interpret programming-language string escapes.
MATCHES=$(grep -RIn "\\\\" "${ROOT_DIR}" "${EXCLUDE_ARGS[@]}" "${GREP_INCLUDE_ARGS[@]}" || true)

if [ -n "${MATCHES}" ]; then
   echo "Windows-style path separators detected in the following files:"
   echo "${MATCHES}"
   echo ""
   echo "CI policy violation: use forward slashes (/) in repository content unless required by runtime logic"
   EXIT_CODE=1
else
   echo "No Windows-style path separators found"
fi

exit "${EXIT_CODE}"
