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
# INGROUP: Version.Management
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/run/check_version.sh
# VERSION: 01.00.00
# BRIEF: Check if a version can be created in a specific branch prefix
# NOTE: Validates against version hierarchy rules before creation
# ============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# ----------------------------------------------------------------------------
# Usage and validation
# ----------------------------------------------------------------------------

usage() {
cat <<-USAGE
Usage: $0 <BRANCH_PREFIX> <VERSION>

Check if a version can be created in the specified branch prefix.

Arguments:
  BRANCH_PREFIX  One of: dev/, rc/, version/
  VERSION        Version in format NN.NN.NN (e.g., 03.01.00)

Examples:
  $0 dev/ 03.05.00
  $0 rc/ 03.01.00
  $0 version/ 02.00.00

Exit codes:
  0 - Version can be created (no conflicts)
  1 - Version cannot be created (conflicts found)
  2 - Invalid arguments or usage

Version Hierarchy Rules:
  - version/X.Y.Z (stable) - always allowed, highest priority
  - rc/X.Y.Z (RC) - blocked if version/X.Y.Z exists
  - dev/X.Y.Z (dev) - blocked if version/X.Y.Z or rc/X.Y.Z exists

USAGE
exit 2
}

validate_prefix() {
  local prefix="$1"
  case "$prefix" in
    "dev/"|"rc/"|"version/")
      return 0
      ;;
    *)
      die "Invalid branch prefix: $prefix (must be dev/, rc/, or version/)"
      ;;
  esac
}

validate_version() {
  local v="$1"
  if ! printf '%s' "$v" | grep -Eq '^[0-9]{2}\.[0-9]{2}\.[0-9]{2}$'; then
    die "Invalid version format: $v (expected NN.NN.NN)"
  fi
}

# ----------------------------------------------------------------------------
# Main logic
# ----------------------------------------------------------------------------

check_version_can_be_created() {
  local prefix="$1"
  local version="$2"
  
  log_info "Checking if ${prefix}${version} can be created..."
  log_info ""
  
  # Check if the exact branch already exists
  if git ls-remote --exit-code --heads origin "${prefix}${version}" >/dev/null 2>&1; then
    log_error "✗ Branch already exists: ${prefix}${version}"
    return 1
  fi
  
  local conflicts=0
  
  case "$prefix" in
    "version/")
      log_info "Creating stable version - no hierarchy checks needed"
      log_info "✓ version/${version} can be created"
      ;;
    "rc/")
      log_info "Checking if version exists in stable..."
      if git ls-remote --exit-code --heads origin "version/${version}" >/dev/null 2>&1; then
        log_error "✗ CONFLICT: Version ${version} already exists in stable (version/${version})"
        log_error "   Cannot create RC for a version that exists in stable"
        conflicts=$((conflicts + 1))
      else
        log_info "✓ No conflict with stable versions"
        log_info "✓ rc/${version} can be created"
      fi
      ;;
    "dev/")
      log_info "Checking if version exists in stable..."
      if git ls-remote --exit-code --heads origin "version/${version}" >/dev/null 2>&1; then
        log_error "✗ CONFLICT: Version ${version} already exists in stable (version/${version})"
        log_error "   Cannot create dev for a version that exists in stable"
        conflicts=$((conflicts + 1))
      else
        log_info "✓ No conflict with stable versions"
      fi
      
      log_info "Checking if version exists in RC..."
      if git ls-remote --exit-code --heads origin "rc/${version}" >/dev/null 2>&1; then
        log_error "✗ CONFLICT: Version ${version} already exists in RC (rc/${version})"
        log_error "   Cannot create dev for a version that exists in RC"
        conflicts=$((conflicts + 1))
      else
        log_info "✓ No conflict with RC versions"
      fi
      
      if [ $conflicts -eq 0 ]; then
        log_info "✓ dev/${version} can be created"
      fi
      ;;
  esac
  
  log_info ""
  
  if [ $conflicts -gt 0 ]; then
    log_error "Version ${prefix}${version} cannot be created ($conflicts conflict(s) found)"
    return 1
  fi
  
  log_info "Version ${prefix}${version} can be created safely"
  return 0
}

# ----------------------------------------------------------------------------
# Main
# ----------------------------------------------------------------------------

# Parse arguments
if [ "${1:-}" = "-h" ] || [ "${1:-}" = "--help" ]; then
  usage
fi

[ $# -eq 2 ] || usage

BRANCH_PREFIX="$1"
VERSION="$2"

validate_prefix "$BRANCH_PREFIX"
validate_version "$VERSION"

log_info "Version Creation Check"
log_info "======================"
log_info ""

check_version_can_be_created "$BRANCH_PREFIX" "$VERSION"
exit_code=$?

log_info ""
log_info "======================"

exit $exit_code
