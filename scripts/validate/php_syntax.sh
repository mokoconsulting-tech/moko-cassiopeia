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
# PATH: /scripts/validate/php_syntax.sh
# VERSION: 01.00.00
# BRIEF: Validates PHP syntax using php -l on all PHP files
# NOTE: Requires PHP CLI to be available
# ============================================================================

set -euo pipefail

# Validation timeout (seconds) - prevents hanging on problematic files
TIMEOUT="${VALIDATION_TIMEOUT:-30}"
SRC_DIR="${SRC_DIR:-src}"

json_escape() {
	python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

[ -d "${SRC_DIR}" ] || {
	printf '{"status":"fail","error":%s}
' "$(json_escape "src directory missing")"
	exit 1
}

if ! command -v php >/dev/null 2>&1; then
	printf '{"status":"ok","warning":"php_not_available","src_dir":%s}
' "$(json_escape "${SRC_DIR}")"
	echo "php_syntax: ok (php not available)"
	exit 0
fi

failed=0
checked=0
failed_files=()

while IFS= read -r -d '' f; do
	checked=$((checked+1))
	
	# Use timeout if available to prevent hangs
	if command -v timeout >/dev/null 2>&1; then
		if ! timeout "${TIMEOUT}" php -l "$f" >/dev/null 2>&1; then
			failed=1
			failed_files+=("$f")
		fi
	else
		if ! php -l "$f" >/dev/null 2>&1; then
			failed=1
			failed_files+=("$f")
		fi
	fi
done < <(find "${SRC_DIR}" -type f -name '*.php' -print0)

if [ "${failed}" -ne 0 ]; then
	{
		printf '{"status":"fail","error":"php_lint_failed","files_checked":%s,"failed_count":%s,"failed_files":[' "${checked}" "${#failed_files[@]}"
		for i in "${!failed_files[@]}"; do
			printf '%s' "$(json_escape "${failed_files[$i]}")"
			[ "$i" -lt $((${#failed_files[@]} - 1)) ] && printf ','
		done
		printf ']}\n'
	}
	exit 1
fi

printf '{"status":"ok","files_checked":%s}
' "${checked}"
echo "php_syntax: ok"
