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
# DEFGROUP: Script.Run
# INGROUP: Repository.Validation
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/run/validate_all.sh
# VERSION: 01.00.00
# BRIEF: Run all validation scripts and report results
# NOTE: Helpful for developers to run all checks before committing
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

Run all validation scripts and report results.

Options:
  -v, --verbose    Show detailed output from validation scripts
  -h, --help       Show this help message

Examples:
  $0              # Run all validations in quiet mode
  $0 -v           # Run with verbose output
  $0 --help       # Show usage information

Exit codes:
  0 - All required checks passed
  1 - One or more required checks failed
  2 - Invalid arguments

USAGE
exit 0
}

# ----------------------------------------------------------------------------
# Configuration
# ----------------------------------------------------------------------------

VERBOSE="${1:-}"

# Parse arguments
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

# Check dependencies
check_dependencies python3

log_info "Start time: $(log_timestamp)"

REQUIRED_CHECKS=(
	"manifest"
	"xml_wellformed"
)

OPTIONAL_CHECKS=(
	"changelog"
	"language_structure"
	"license_headers"
	"no_secrets"
	"paths"
	"php_syntax"
	"tabs"
	"version_alignment"
)

# ----------------------------------------------------------------------------
# Run validations
# ----------------------------------------------------------------------------

log_section "Repository Validation Suite"
log_info "Running all validation checks..."
log_separator

required_passed=0
required_failed=0
optional_passed=0
optional_failed=0

# Required checks
log_section "Required Checks"
for check in "${REQUIRED_CHECKS[@]}"; do
	script="${SCRIPT_DIR}/validate/${check}.sh"
	if [ ! -f "${script}" ]; then
		log_error "Script not found: ${script}"
		required_failed=$((required_failed + 1))
		continue
	fi
	
	log_step "Running: ${check}"
	if [ "${VERBOSE}" = "true" ]; then
		if "${script}"; then
			log_success "✓ ${check}"
			required_passed=$((required_passed + 1))
		else
			log_error "✗ ${check} (FAILED)"
			required_failed=$((required_failed + 1))
		fi
	else
		if "${script}" >/dev/null 2>&1; then
			log_success "✓ ${check}"
			required_passed=$((required_passed + 1))
		else
			log_error "✗ ${check} (FAILED - run with -v for details)"
			required_failed=$((required_failed + 1))
		fi
	fi
done

echo ""

# Optional checks
log_section "Optional Checks"
for check in "${OPTIONAL_CHECKS[@]}"; do
	script="${SCRIPT_DIR}/validate/${check}.sh"
	if [ ! -f "${script}" ]; then
		log_warn "Script not found: ${script}"
		continue
	fi
	
	log_step "Running: ${check}"
	if [ "${VERBOSE}" = "true" ]; then
		if "${script}"; then
			log_success "✓ ${check}"
			optional_passed=$((optional_passed + 1))
		else
			log_warn "✗ ${check} (warnings/issues found)"
			optional_failed=$((optional_failed + 1))
		fi
	else
		if "${script}" >/dev/null 2>&1; then
			log_success "✓ ${check}"
			optional_passed=$((optional_passed + 1))
		else
			log_warn "✗ ${check} (warnings/issues found - run with -v for details)"
			optional_failed=$((optional_failed + 1))
		fi
	fi
done

# ----------------------------------------------------------------------------
# Summary
# ----------------------------------------------------------------------------

echo ""
log_separator
log_section "Validation Summary"
log_separator

log_kv "Required checks passed" "${required_passed}/${#REQUIRED_CHECKS[@]}"
log_kv "Required checks failed" "${required_failed}"
log_kv "Optional checks passed" "${optional_passed}/${#OPTIONAL_CHECKS[@]}"
log_kv "Optional checks with issues" "${optional_failed}"

log_separator

log_info "End time: $(log_timestamp)"

if [ "${required_failed}" -gt 0 ]; then
	log_error "FAILED: ${required_failed} required check(s) failed"
	exit 1
else
	log_success "SUCCESS: All required checks passed"
	if [ "${optional_failed}" -gt 0 ]; then
		log_warn "Note: ${optional_failed} optional check(s) found issues"
	fi
	exit 0
fi
