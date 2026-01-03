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
# DEFGROUP: Script.Test
# INGROUP: Repository.Validation
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/run/smoke_test.sh
# VERSION: 01.00.00
# BRIEF: Basic smoke tests to verify repository structure and manifest validity
# NOTE: Quick validation checks for essential repository components
# ============================================================================

set -euo pipefail

# ----------------------------------------------------------------------------
# Usage
# ----------------------------------------------------------------------------

usage() {
cat <<-USAGE
Usage: $0 [OPTIONS]

Run basic smoke tests to verify repository structure and manifest validity.

Options:
  -h, --help    Show this help message

Examples:
  $0              # Run all smoke tests
  $0 --help       # Show usage information

USAGE
exit 0
}

# Parse arguments
if [ "${1:-}" = "-h" ] || [ "${1:-}" = "--help" ]; then
	usage
fi

# Source common utilities
SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# Check dependencies
check_dependencies python3

log_info "Running smoke tests for Moko-Cassiopeia repository"
log_info "Start time: $(log_timestamp)"

# ----------------------------------------------------------------------------
# Test: Repository structure
# ----------------------------------------------------------------------------

log_info "Checking repository structure..."

assert_dir_exists "src"
assert_file_exists "README.md"
assert_file_exists "CHANGELOG.md"
assert_file_exists "LICENSE"
assert_file_exists "CONTRIBUTING.md"

log_info "✓ Repository structure valid"

# ----------------------------------------------------------------------------
# Test: Manifest validation
# ----------------------------------------------------------------------------

log_info "Checking Joomla manifest..."

. "${SCRIPT_DIR}/lib/joomla_manifest.sh"

MANIFEST="$(find_manifest src)"
log_info "Found manifest: ${MANIFEST}"

VERSION="$(get_manifest_version "${MANIFEST}")"
NAME="$(get_manifest_name "${MANIFEST}")"
TYPE="$(get_manifest_type "${MANIFEST}")"

log_info "Extension: ${NAME} (${TYPE}) v${VERSION}"

# Verify manifest is well-formed XML
require_cmd python3
python3 - "${MANIFEST}" <<'PY'
import sys
import xml.etree.ElementTree as ET

manifest_path = sys.argv[1]

try:
  tree = ET.parse(manifest_path)
  root = tree.getroot()
  if root.tag != "extension":
    print(f"ERROR: Root element must be <extension>, got <{root.tag}>")
    sys.exit(1)
  print("✓ Manifest XML is well-formed")
except Exception as e:
  print(f"ERROR: Failed to parse manifest: {e}")
  sys.exit(1)
PY

log_info "✓ Manifest validation passed"

# ----------------------------------------------------------------------------
# Test: Version alignment
# ----------------------------------------------------------------------------

log_info "Checking version alignment..."

if [ ! -f "CHANGELOG.md" ]; then
log_warn "CHANGELOG.md not found, skipping version alignment check"
else
if grep -q "## \[${VERSION}\]" CHANGELOG.md; then
log_info "✓ Version ${VERSION} found in CHANGELOG.md"
else
log_warn "Version ${VERSION} not found in CHANGELOG.md"
fi
fi

# ----------------------------------------------------------------------------
# Test: Critical files syntax
# ----------------------------------------------------------------------------

log_info "Checking PHP syntax..."

if command -v php >/dev/null 2>&1; then
php_errors=0
while IFS= read -r -d '' f; do
if ! php -l "$f" >/dev/null 2>&1; then
log_error "PHP syntax error in: $f"
php_errors=$((php_errors + 1))
fi
done < <(find src -type f -name '*.php' -print0 2>/dev/null)

if [ "${php_errors}" -eq 0 ]; then
log_info "✓ PHP syntax validation passed"
else
die "Found ${php_errors} PHP syntax errors"
fi
else
log_warn "PHP not available, skipping PHP syntax check"
fi

# ----------------------------------------------------------------------------
# Summary
# ----------------------------------------------------------------------------

log_info "========================================="
log_info "Smoke tests completed successfully"
log_info "Extension: ${NAME}"
log_info "Version: ${VERSION}"
log_info "Type: ${TYPE}"
log_info "End time: $(log_timestamp)"
log_info "========================================="
