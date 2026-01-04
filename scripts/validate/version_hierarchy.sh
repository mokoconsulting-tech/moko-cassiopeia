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
# INGROUP: Version.Management
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/validate/version_hierarchy.sh
# VERSION: 01.00.00
# BRIEF: Validate version hierarchy across branch prefixes
# NOTE: Checks for version conflicts across dev/, rc/, and version/ branches
# ============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# ----------------------------------------------------------------------------
# Functions
# ----------------------------------------------------------------------------

check_version_hierarchy() {
  log_info "Checking version hierarchy across branch prefixes..."
  
  local violations=0
  
  # Get all branches with version-like names
  local branches
  branches=$(git ls-remote --heads origin 2>/dev/null | awk '{print $2}' | sed 's|refs/heads/||' || echo "")
  
  if [ -z "$branches" ]; then
    log_warn "No remote branches found or unable to fetch branches"
    return 0
  fi
  
  # Extract versions from branches
  local dev_versions=()
  local rc_versions=()
  local stable_versions=()
  
  while IFS= read -r branch; do
    if [[ "$branch" =~ ^dev/([0-9]{2}\.[0-9]{2}\.[0-9]{2})$ ]]; then
      dev_versions+=("${BASH_REMATCH[1]}")
    elif [[ "$branch" =~ ^rc/([0-9]{2}\.[0-9]{2}\.[0-9]{2})$ ]]; then
      rc_versions+=("${BASH_REMATCH[1]}")
    elif [[ "$branch" =~ ^version/([0-9]{2}\.[0-9]{2}\.[0-9]{2})$ ]]; then
      stable_versions+=("${BASH_REMATCH[1]}")
    fi
  done <<< "$branches"
  
  log_info "Found ${#dev_versions[@]} dev versions, ${#rc_versions[@]} RC versions, ${#stable_versions[@]} stable versions"
  
  # Check for violations:
  # 1. dev/ version that exists in rc/ or version/
  for version in "${dev_versions[@]}"; do
    # Check if exists in stable
    for stable in "${stable_versions[@]}"; do
      if [ "$version" = "$stable" ]; then
        log_error "VIOLATION: Version $version exists in both dev/ and version/ branches"
        violations=$((violations + 1))
      fi
    done
    
    # Check if exists in RC
    for rc in "${rc_versions[@]}"; do
      if [ "$version" = "$rc" ]; then
        log_error "VIOLATION: Version $version exists in both dev/ and rc/ branches"
        violations=$((violations + 1))
      fi
    done
  done
  
  # 2. rc/ version that exists in version/
  for version in "${rc_versions[@]}"; do
    for stable in "${stable_versions[@]}"; do
      if [ "$version" = "$stable" ]; then
        log_error "VIOLATION: Version $version exists in both rc/ and version/ branches"
        violations=$((violations + 1))
      fi
    done
  done
  
  if [ $violations -eq 0 ]; then
    log_info "✓ No version hierarchy violations found"
    return 0
  else
    log_error "✗ Found $violations version hierarchy violation(s)"
    return 1
  fi
}

# ----------------------------------------------------------------------------
# Main
# ----------------------------------------------------------------------------

log_info "Version Hierarchy Validation"
log_info "============================="
log_info ""

check_version_hierarchy
exit_code=$?

log_info ""
log_info "============================="
if [ $exit_code -eq 0 ]; then
  log_info "Version hierarchy validation passed"
else
  log_error "Version hierarchy validation failed"
fi

exit $exit_code
