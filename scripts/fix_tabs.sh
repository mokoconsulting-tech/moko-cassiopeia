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
# You should have received a copy of the GNU General Public License (./LICENSE.md).
# -----------------------------------------------------------------------------
# FILE INFORMATION
# DEFGROUP: MokoStandards
# INGROUP: Generic.Script
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/fix_tabs.sh
# VERSION: 03.06.00
# BRIEF: Replace tab characters with two spaces in text files.
# Purpose:
# - Replace tab characters with two spaces in text files.
# - Designed for optional remediation workflows.
# - Skips binary files and version control metadata.
# - Preserves file contents aside from tab replacement.
#
# Usage:
#   ./scripts/fix_tabs.sh
#   ./scripts/fix_tabs.sh ./src
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
command -v grep >/dev/null 2>&1 || die "grep not available"

info "Scanning for tab characters under: $ROOT_DIR"

changed=0
scanned=0

while IFS= read -r -d '' f; do
	scanned=$((scanned + 1))

	if ! file "$f" | grep -qi "text"; then
		continue
	fi

	if grep -q $'\t' "$f"; then
		sed -i.bak $'s/\t/  /g' "$f" && rm -f "$f.bak"
		info "Replaced tabs in $f"
		changed=$((changed + 1))
	fi
done < <(
	find "$ROOT_DIR" \
		-type f \
		-not -path "*/.git/*" \
		-not -path "*/node_modules/*" \
		-print0
)

info "Scan complete. Files scanned: $scanned. Files changed: $changed."
