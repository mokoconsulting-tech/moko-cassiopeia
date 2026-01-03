#!/usr/bin/env sh

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
# DEFGROUP: Script.Library
# INGROUP: Logging
# REPO: https://github.com/mokoconsulting-tech
# PATH: /scripts/lib/logging.sh
# VERSION: 01.00.00
# BRIEF: Enhanced logging utilities with structured output support
# NOTE: Provides colored output, log levels, and structured logging
# ============================================================================

set -eu

# Resolve script directory properly - works when sourced
if [ -n "${SCRIPT_DIR:-}" ]; then
	# Already set by caller
	SCRIPT_LIB_DIR="${SCRIPT_DIR}/lib"
else
	# Determine from this file's location
	SCRIPT_LIB_DIR="$(cd "$(dirname "${BASH_SOURCE[0]:-$0}")" && pwd)"
fi

# Shared utilities
. "${SCRIPT_LIB_DIR}/common.sh"

# ----------------------------------------------------------------------------
# Color codes (if terminal supports it)
# ----------------------------------------------------------------------------

# Check if we're in a terminal and colors are supported
use_colors() {
	[ -t 1 ] && [ "${CI:-false}" != "true" ]
}

if use_colors; then
	COLOR_RESET='\033[0m'
	COLOR_RED='\033[0;31m'
	COLOR_YELLOW='\033[0;33m'
	COLOR_GREEN='\033[0;32m'
	COLOR_BLUE='\033[0;34m'
	COLOR_CYAN='\033[0;36m'
else
	COLOR_RESET=''
	COLOR_RED=''
	COLOR_YELLOW=''
	COLOR_GREEN=''
	COLOR_BLUE=''
	COLOR_CYAN=''
fi

# ----------------------------------------------------------------------------
# Enhanced logging functions
# ----------------------------------------------------------------------------

log_debug() {
	if [ "${DEBUG:-false}" = "true" ]; then
		printf '%b[DEBUG]%b %s\n' "${COLOR_CYAN}" "${COLOR_RESET}" "$*"
	fi
}

log_success() {
	printf '%b[SUCCESS]%b %s\n' "${COLOR_GREEN}" "${COLOR_RESET}" "$*"
}

log_step() {
	printf '%b[STEP]%b %s\n' "${COLOR_BLUE}" "${COLOR_RESET}" "$*"
}

# ----------------------------------------------------------------------------
# Structured logging
# ----------------------------------------------------------------------------

# Log a key-value pair
log_kv() {
	local key="$1"
	local value="$2"
	printf '  %b%s:%b %s\n' "${COLOR_BLUE}" "${key}" "${COLOR_RESET}" "${value}"
}

# Log a list item
log_item() {
	printf '  %b•%b %s\n' "${COLOR_GREEN}" "${COLOR_RESET}" "$*"
}

# Log a separator line
log_separator() {
	printf '%s\n' "========================================="
}

# Log a section header
log_section() {
	printf '\n%b=== %s ===%b\n' "${COLOR_BLUE}" "$*" "${COLOR_RESET}"
}

