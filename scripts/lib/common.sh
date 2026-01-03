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
# INGROUP: Common
# REPO: https://github.com/mokoconsulting-tech
# PATH: /scripts/lib/common.sh
# VERSION: 01.00.00
# BRIEF: Unified shared shell utilities for all CI and local scripts
# NOTE:
# ============================================================================

set -eu

# ----------------------------------------------------------------------------
# Environment normalization
# ----------------------------------------------------------------------------

export LC_ALL=C
export LANG=C

is_ci() {
	[ "${CI:-}" = "true" ]
}

require_cmd() {
	command -v "$1" >/dev/null 2>&1 || {
		printf '%s\n' "ERROR: Required command not found: $1" >&2
		exit 1
	}
}

# ----------------------------------------------------------------------------
# Logging
# ----------------------------------------------------------------------------

log_info() {
	printf '%s\n' "INFO: $*"
}

log_warn() {
	printf '%s\n' "WARN: $*" >&2
}

log_error() {
	printf '%s\n' "ERROR: $*" >&2
}

die() {
	log_error "$*"
	exit 1
}

# ----------------------------------------------------------------------------
# Validation helpers
# ----------------------------------------------------------------------------

assert_file_exists() {
	[ -f "$1" ] || die "Required file missing: $1"
}

assert_dir_exists() {
	[ -d "$1" ] || die "Required directory missing: $1"
}

assert_non_empty() {
	[ -n "${1:-}" ] || die "Expected non empty value"
}

# ----------------------------------------------------------------------------
# Path helpers
# ----------------------------------------------------------------------------

script_root() {
	cd "$(dirname "$0")/.." && pwd
}

normalize_path() {
	printf '%s\n' "$1" | sed 's|\\|/|g'
}

# ----------------------------------------------------------------------------
# JSON utilities
# ----------------------------------------------------------------------------

json_escape() {
	require_cmd python3
	python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

json_output() {
	local status="$1"
	shift
	require_cmd python3
	python3 - <<PY "$status" "$@"
import json
import sys
status = sys.argv[1]
pairs = sys.argv[2:]
data = {"status": status}
for pair in pairs:
	if "=" in pair:
		k, v = pair.split("=", 1)
		data[k] = v
print(json.dumps(data, ensure_ascii=False))
PY
}

# ----------------------------------------------------------------------------
# Guardrails
# ----------------------------------------------------------------------------

fail_if_root() {
	[ "$(id -u)" -eq 0 ] && die "Script must not run as root"
}

# ----------------------------------------------------------------------------
# Enterprise features
# ----------------------------------------------------------------------------

# Check for required dependencies at script start
check_dependencies() {
	local missing=0
	for cmd in "$@"; do
		if ! command -v "$cmd" >/dev/null 2>&1; then
			log_error "Required command not found: $cmd"
			missing=$((missing + 1))
		fi
	done
	[ "$missing" -eq 0 ] || die "Missing $missing required command(s)"
}

# Timeout wrapper for long-running commands
run_with_timeout() {
	local timeout="$1"
	shift
	if command -v timeout >/dev/null 2>&1; then
		timeout "$timeout" "$@"
	else
		"$@"
	fi
}

# Add script execution timestamp
log_timestamp() {
	if command -v date >/dev/null 2>&1; then
		printf '%s\n' "$(date -u '+%Y-%m-%d %H:%M:%S UTC')"
	fi
}
