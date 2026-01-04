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
# INGROUP: CI.Validation
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/validate/workflows.sh
# VERSION: 01.00.00
# BRIEF: Validate GitHub Actions workflow files
# NOTE: Checks YAML syntax, structure, and best practices
# ============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# ----------------------------------------------------------------------------
# Functions
# ----------------------------------------------------------------------------

validate_yaml_syntax() {
  local file="$1"
  
  if ! command -v python3 >/dev/null 2>&1; then
    log_warn "python3 not found, skipping YAML syntax validation"
    return 0
  fi
  
  python3 - "$file" <<'PYEOF'
import sys
import yaml

file_path = sys.argv[1]

try:
    with open(file_path, 'r') as f:
        yaml.safe_load(f)
    print(f"✓ Valid YAML: {file_path}")
    sys.exit(0)
except yaml.YAMLError as e:
    print(f"✗ YAML Error in {file_path}: {e}", file=sys.stderr)
    sys.exit(1)
except Exception as e:
    print(f"✗ Error reading {file_path}: {e}", file=sys.stderr)
    sys.exit(1)
PYEOF
}

check_no_tabs() {
  local file="$1"
  
  if grep -q $'\t' "$file"; then
    log_error "✗ File contains tab characters: $file"
    return 1
  fi
  
  return 0
}

check_workflow_structure() {
  local file="$1"
  local filename=$(basename "$file")
  
  # Check for required top-level keys
  if ! grep -q "^name:" "$file"; then
    log_warn "Missing 'name:' in $filename"
  fi
  
  if ! grep -q "^on:" "$file"; then
    log_error "✗ Missing 'on:' trigger in $filename"
    return 1
  fi
  
  if ! grep -q "^jobs:" "$file"; then
    log_error "✗ Missing 'jobs:' in $filename"
    return 1
  fi
  
  return 0
}

validate_workflow_file() {
  local file="$1"
  local filename=$(basename "$file")
  
  log_info "Validating: $filename"
  
  local errors=0
  
  # Check YAML syntax
  if ! validate_yaml_syntax "$file"; then
    errors=$((errors + 1))
  fi
  
  # Check for tabs
  if ! check_no_tabs "$file"; then
    errors=$((errors + 1))
  fi
  
  # Check structure
  if ! check_workflow_structure "$file"; then
    errors=$((errors + 1))
  fi
  
  if [ $errors -eq 0 ]; then
    log_info "✓ $filename passed all checks"
    return 0
  else
    log_error "✗ $filename failed $errors check(s)"
    return 1
  fi
}

# ----------------------------------------------------------------------------
# Main
# ----------------------------------------------------------------------------

log_info "GitHub Actions Workflow Validation"
log_info "==================================="
log_info ""

WORKFLOWS_DIR=".github/workflows"

if [ ! -d "$WORKFLOWS_DIR" ]; then
  log_error "Workflows directory not found: $WORKFLOWS_DIR"
  exit 1
fi

total=0
passed=0
failed=0

for workflow in "$WORKFLOWS_DIR"/*.yml "$WORKFLOWS_DIR"/*.yaml; do
  if [ ! -f "$workflow" ]; then
    continue
  fi
  
  total=$((total + 1))
  
  if validate_workflow_file "$workflow"; then
    passed=$((passed + 1))
  else
    failed=$((failed + 1))
  fi
  
  echo ""
done

log_info "==================================="
log_info "Summary:"
log_info "  Total workflows: $total"
log_info "  Passed: $passed"
log_info "  Failed: $failed"
log_info "==================================="

if [ $failed -gt 0 ]; then
  log_error "Workflow validation failed"
  exit 1
fi

log_info "All workflows validated successfully"
exit 0
