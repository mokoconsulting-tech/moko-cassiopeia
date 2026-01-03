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
# DEFGROUP: Script.Fix
# INGROUP: Version.Management
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/fix/versions.sh
# VERSION: 01.00.00
# BRIEF: Update version numbers across repository files
# NOTE: Updates manifest, package.json, and other version references
# ============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# ----------------------------------------------------------------------------
# Usage and validation
# ----------------------------------------------------------------------------

usage() {
cat <<-USAGE
Usage: $0 <VERSION>

Update version numbers across repository files.

Arguments:
  VERSION    Semantic version in format X.Y.Z (e.g., 3.5.0)

Examples:
  $0 3.5.0
  $0 1.2.3
USAGE
exit 1
}

validate_version() {
local v="$1"
if ! printf '%s' "$v" | grep -Eq '^[0-9]+\.[0-9]+\.[0-9]+$'; then
die "Invalid version format: $v (expected X.Y.Z)"
fi
}

# ----------------------------------------------------------------------------
# Main
# ----------------------------------------------------------------------------

[ $# -eq 1 ] || usage

VERSION="$1"
validate_version "${VERSION}"

log_info "Updating version to: ${VERSION}"

# Source Joomla manifest utilities
. "${SCRIPT_DIR}/lib/joomla_manifest.sh"

# Find and update manifest
MANIFEST="$(find_manifest src)"
log_info "Updating manifest: ${MANIFEST}"

# Cross-platform sed helper
sed_inplace() {
local expr="$1"
local file="$2"

if sed --version >/dev/null 2>&1; then
sed -i -E "${expr}" "${file}"
else
sed -i '' -E "${expr}" "${file}"
fi
}

# Update version in manifest XML
if grep -q '<version>' "${MANIFEST}"; then
sed_inplace "s|<version>[^<]*</version>|<version>${VERSION}</version>|g" "${MANIFEST}"
log_info "✓ Updated manifest version"
else
log_warn "No <version> tag found in manifest"
fi

# Update package.json if it exists
if [ -f "package.json" ]; then
if command -v python3 >/dev/null 2>&1; then
python3 - <<PY "${VERSION}"
import json
import sys

version = sys.argv[1]

try:
with open('package.json', 'r') as f:
data = json.load(f)

data['version'] = version

with open('package.json', 'w') as f:
json.dump(data, f, indent=2)
f.write('\n')

print(f"✓ Updated package.json to version {version}")
except Exception as e:
print(f"WARN: Failed to update package.json: {e}")
sys.exit(0)
PY
fi
fi

# Update README.md version references
if [ -f "README.md" ]; then
# Look for version references in format VERSION: XX.XX.XX
if grep -q 'VERSION: [0-9]' README.md; then
# Convert to zero-padded format if needed
PADDED_VERSION="$(printf '%s' "${VERSION}" | awk -F. '{printf "%02d.%02d.%02d", $1, $2, $3}')"
sed_inplace "s|VERSION: [0-9]{2}\.[0-9]{2}\.[0-9]{2}|VERSION: ${PADDED_VERSION}|g" README.md
log_info "✓ Updated README.md version references"
fi
fi

log_info "========================================="
log_info "Version update completed: ${VERSION}"
log_info "Files updated:"
log_info "  - ${MANIFEST}"
[ -f "package.json" ] && log_info "  - package.json"
[ -f "README.md" ] && log_info "  - README.md"
log_info "========================================="
