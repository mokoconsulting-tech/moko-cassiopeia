#!/usr/bin/env bash
# -----------------------------------------------------------------------------
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# This file is part of a Moko Consulting project.
#
# SPDX-License-Identifier: GPL-3.0-or-later
#
# This program is free software; you can redistribute it and/or modify it under
# the terms of the GNU General Public License as published by the Free Software
# Foundation; either version 3 of the License, or (at your option) any later
# version.
#
# This program is distributed in the hope that it will be useful, but WITHOUT
# ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
# FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License (./LICENSE).
# -----------------------------------------------------------------------------
# FILE INFORMATION
# DEFGROUP: MokoStandards
# INGROUP: Generic.Script
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/fix_paths.sh
# VERSION: 03.05.00
# BRIEF: Replace Windows-style path separators with POSIX separators in text files.#
# Purpose:
# - Normalize path separators in text files to forward slashes (/).
# - Intended for CI validation and optional remediation workflows.
# - Skips binary files and version control metadata.
# - Preserves file contents aside from path separator normalization.
#
# Usage:
#   ./scripts/fix_paths.sh
# =============================================================================

set -euo pipefail

ROOT_DIR="${1:-.}"

info() {
	echo "INFO: $*"
}

warn() {
	echo "WARN: $*" 1>&2
}

die() {
	echo "ERROR: $*" 1>&2
	exit 1
}

command -v find >/dev/null 2>&1 || die "find not available"
command -v sed >/dev/null 2>&1 || die "sed not available"
command -v file >/dev/null 2>&1 || die "file not available"

info "Scanning for text files under: $ROOT_DIR"

while IFS= read -r -d '' file; do
	if file "$file" | grep -qi "text"; then
		if grep -q '\\\\' "$file"; then
			sed -i.bak 's#\\\\#/#g' "$file" && rm -f "$file.bak"
			info "Normalized paths in $file"
		fi
	fi
done < <(
	find "$ROOT_DIR" \
		-type f \
		-not -path "*/.git/*" \
		-not -path "*/node_modules/*" \
		-print0
)

info "Path normalization complete."
