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
# PATH: /scripts/run/list_versions.sh
# VERSION: 01.00.00
# BRIEF: List all version branches organized by prefix
# NOTE: Displays dev/, rc/, and version/ branches in a structured format
# ============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# ----------------------------------------------------------------------------
# Functions
# ----------------------------------------------------------------------------

list_version_branches() {
  log_info "Fetching version branches from remote..."
  
  # Get all branches with version-like names
  local branches
  branches=$(git ls-remote --heads origin 2>/dev/null | awk '{print $2}' | sed 's|refs/heads/||' || echo "")
  
  if [ -z "$branches" ]; then
    log_warn "No remote branches found or unable to fetch branches"
    return 0
  fi
  
  # Categorize versions
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
  
  # Sort versions
  IFS=$'\n' dev_versions=($(sort -V <<< "${dev_versions[*]}" 2>/dev/null || echo "${dev_versions[@]}"))
  IFS=$'\n' rc_versions=($(sort -V <<< "${rc_versions[*]}" 2>/dev/null || echo "${rc_versions[@]}"))
  IFS=$'\n' stable_versions=($(sort -V <<< "${stable_versions[*]}" 2>/dev/null || echo "${stable_versions[@]}"))
  unset IFS
  
  # Display results
  echo ""
  echo "========================================"
  echo "Version Branches Summary"
  echo "========================================"
  echo ""
  
  echo "📦 Stable Versions (version/)"
  echo "----------------------------------------"
  if [ ${#stable_versions[@]} -eq 0 ]; then
    echo "  (none)"
  else
    for version in "${stable_versions[@]}"; do
      echo "  ✓ version/$version"
    done
  fi
  echo ""
  
  echo "🔧 Release Candidates (rc/)"
  echo "----------------------------------------"
  if [ ${#rc_versions[@]} -eq 0 ]; then
    echo "  (none)"
  else
    for version in "${rc_versions[@]}"; do
      echo "  ➜ rc/$version"
    done
  fi
  echo ""
  
  echo "🚧 Development Versions (dev/)"
  echo "----------------------------------------"
  if [ ${#dev_versions[@]} -eq 0 ]; then
    echo "  (none)"
  else
    for version in "${dev_versions[@]}"; do
      echo "  ⚡ dev/$version"
    done
  fi
  echo ""
  
  echo "========================================"
  echo "Total: ${#stable_versions[@]} stable, ${#rc_versions[@]} RC, ${#dev_versions[@]} dev"
  echo "========================================"
}

# ----------------------------------------------------------------------------
# Main
# ----------------------------------------------------------------------------

list_version_branches
