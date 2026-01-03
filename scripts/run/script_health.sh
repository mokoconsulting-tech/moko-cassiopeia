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
# INGROUP: Script.Health
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/run/script_health.sh
# VERSION: 01.00.00
# BRIEF: Validate scripts follow enterprise standards
# NOTE: Checks for copyright headers, error handling, and documentation
# ============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"
. "${SCRIPT_DIR}/lib/logging.sh"

# ----------------------------------------------------------------------------
# Usage
# ----------------------------------------------------------------------------

usage() {
cat <<-USAGE
Usage: $0 [OPTIONS]

Validate that all scripts follow enterprise standards.

Options:
  -v, --verbose    Show detailed output
  -h, --help       Show this help message

Checks performed:
  - Copyright headers present
  - SPDX license identifier present
  - FILE INFORMATION section present
  - set -euo pipefail present
  - Executable permissions set
  
Examples:
  $0              # Run all health checks
  $0 -v           # Verbose output
  $0 --help       # Show usage

Exit codes:
  0 - All checks passed
  1 - One or more checks failed
  2 - Invalid arguments

USAGE
exit 0
}

# ----------------------------------------------------------------------------
# Configuration
# ----------------------------------------------------------------------------

VERBOSE="${1:-}"

case "${VERBOSE}" in
	-h|--help)
		usage
		;;
	-v|--verbose)
		VERBOSE="true"
		;;
	"")
		VERBOSE="false"
		;;
	*)
		log_error "Invalid argument: ${VERBOSE}"
		echo ""
		usage
		exit 2
		;;
esac

log_info "Running script health checks"
log_info "Start time: $(log_timestamp)"

START_TIME=$(date +%s)

# ----------------------------------------------------------------------------
# Health checks
# ----------------------------------------------------------------------------

total_scripts=0
missing_copyright=0
missing_spdx=0
missing_fileinfo=0
missing_error_handling=0
not_executable=0

check_script() {
	local script="$1"
	local errors=0
	
	total_scripts=$((total_scripts + 1))
	
	# Check for copyright
	if ! grep -q "Copyright (C)" "$script"; then
		missing_copyright=$((missing_copyright + 1))
		errors=$((errors + 1))
		[ "${VERBOSE}" = "true" ] && log_warn "Missing copyright: $script"
	fi
	
	# Check for SPDX
	if ! grep -q "SPDX-License-Identifier" "$script"; then
		missing_spdx=$((missing_spdx + 1))
		errors=$((errors + 1))
		[ "${VERBOSE}" = "true" ] && log_warn "Missing SPDX: $script"
	fi
	
	# Check for FILE INFORMATION
	if ! grep -q "FILE INFORMATION" "$script"; then
		missing_fileinfo=$((missing_fileinfo + 1))
		errors=$((errors + 1))
		[ "${VERBOSE}" = "true" ] && log_warn "Missing FILE INFORMATION: $script"
	fi
	
	# Check for error handling (bash scripts only)
	if [[ "$script" == *.sh ]]; then
		if ! grep -q "set -e" "$script"; then
			missing_error_handling=$((missing_error_handling + 1))
			errors=$((errors + 1))
			[ "${VERBOSE}" = "true" ] && log_warn "Missing error handling: $script"
		fi
	fi
	
	# Check executable permission
	if [ ! -x "$script" ]; then
		not_executable=$((not_executable + 1))
		errors=$((errors + 1))
		[ "${VERBOSE}" = "true" ] && log_warn "Not executable: $script"
	fi
	
	return $errors
}

# Find all shell scripts
log_info "Scanning scripts directory..."

while IFS= read -r -d '' script; do
	check_script "$script" || true
done < <(find "${SCRIPT_DIR}" -type f -name "*.sh" -print0)

# ----------------------------------------------------------------------------
# Summary
# ----------------------------------------------------------------------------

END_TIME=$(date +%s)

log_separator
log_info "Script Health Summary"
log_separator
log_kv "Total scripts checked" "${total_scripts}"
log_kv "Missing copyright" "${missing_copyright}"
log_kv "Missing SPDX identifier" "${missing_spdx}"
log_kv "Missing FILE INFORMATION" "${missing_fileinfo}"
log_kv "Missing error handling" "${missing_error_handling}"
log_kv "Not executable" "${not_executable}"
log_separator
log_info "End time: $(log_timestamp)"
log_info "Duration: $(log_duration "$START_TIME" "$END_TIME")"

total_issues=$((missing_copyright + missing_spdx + missing_fileinfo + missing_error_handling + not_executable))

if [ "$total_issues" -eq 0 ]; then
	log_success "SUCCESS: All scripts follow enterprise standards"
	exit 0
else
	log_error "FAILED: Found ${total_issues} standard violation(s)"
	log_info "Run with -v flag for details on which scripts need updates"
	exit 1
fi
