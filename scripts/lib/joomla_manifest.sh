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
# INGROUP: Joomla.Manifest
# REPO: https://github.com/mokoconsulting-tech
# PATH: /scripts/lib/joomla_manifest.sh
# VERSION: 01.00.00
# BRIEF: Joomla manifest parsing and validation utilities
# NOTE: Provides reusable functions for working with Joomla extension manifests
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
# Manifest discovery
# ----------------------------------------------------------------------------

# Find the primary Joomla manifest in the given directory
# Usage: find_manifest <src_dir>
# Returns: path to manifest file or exits with error
find_manifest() {
local src_dir="${1:-src}"

[ -d "${src_dir}" ] || die "Source directory missing: ${src_dir}"

# Candidate discovery policy: prefer explicit known names
local candidates=""

# Template
if [ -f "${src_dir}/templateDetails.xml" ]; then
candidates="${src_dir}/templateDetails.xml"
fi

# Package
if [ -z "${candidates}" ]; then
candidates="$(find "${src_dir}" -maxdepth 4 -type f -name 'pkg_*.xml' 2>/dev/null | head -1 || true)"
fi

# Component
if [ -z "${candidates}" ]; then
candidates="$(find "${src_dir}" -maxdepth 4 -type f -name 'com_*.xml' 2>/dev/null | head -1 || true)"
fi

# Module
if [ -z "${candidates}" ]; then
candidates="$(find "${src_dir}" -maxdepth 4 -type f -name 'mod_*.xml' 2>/dev/null | head -1 || true)"
fi

# Plugin
if [ -z "${candidates}" ]; then
candidates="$(find "${src_dir}" -maxdepth 6 -type f -name 'plg_*.xml' 2>/dev/null | head -1 || true)"
fi

# Fallback: any XML containing <extension ...>
if [ -z "${candidates}" ]; then
candidates="$(grep -Rsl --include='*.xml' '<extension' "${src_dir}" 2>/dev/null | head -1 || true)"
fi

[ -n "${candidates}" ] || die "No Joomla manifest XML found under ${src_dir}"
[ -s "${candidates}" ] || die "Manifest is empty: ${candidates}"

printf '%s\n' "${candidates}"
}

# ----------------------------------------------------------------------------
# Manifest parsing
# ----------------------------------------------------------------------------

# Extract version from manifest XML
# Usage: get_manifest_version <manifest_path>
# Returns: version string or exits with error
get_manifest_version() {
	local manifest="$1"
	
	[ -f "${manifest}" ] || die "Manifest not found: ${manifest}"
	
	require_cmd python3
	
	python3 - "${manifest}" <<'PY'
import sys
import xml.etree.ElementTree as ET

manifest_path = sys.argv[1]

try:
  tree = ET.parse(manifest_path)
  root = tree.getroot()
  version_el = root.find("version")
  if version_el is not None and version_el.text:
    print(version_el.text.strip())
    sys.exit(0)
except Exception:
  pass

sys.exit(1)
PY
}

# Extract extension name from manifest XML
# Usage: get_manifest_name <manifest_path>
# Returns: name string or exits with error
get_manifest_name() {
	local manifest="$1"
	
	[ -f "${manifest}" ] || die "Manifest not found: ${manifest}"
	
	require_cmd python3
	
	python3 - "${manifest}" <<'PY'
import sys
import xml.etree.ElementTree as ET

manifest_path = sys.argv[1]

try:
  tree = ET.parse(manifest_path)
  root = tree.getroot()
  name_el = root.find("name")
  if name_el is not None and name_el.text:
    print(name_el.text.strip())
    sys.exit(0)
except Exception:
  pass

sys.exit(1)
PY
}

# Extract extension type from manifest XML
# Usage: get_manifest_type <manifest_path>
# Returns: type string (template, component, module, plugin, etc.) or exits with error
get_manifest_type() {
	local manifest="$1"
	
	[ -f "${manifest}" ] || die "Manifest not found: ${manifest}"
	
	require_cmd python3
	
	python3 - "${manifest}" <<'PY'
import sys
import xml.etree.ElementTree as ET

manifest_path = sys.argv[1]

try:
  tree = ET.parse(manifest_path)
  root = tree.getroot()
  ext_type = root.attrib.get("type", "").strip().lower()
  if ext_type:
    print(ext_type)
    sys.exit(0)
except Exception:
  pass

sys.exit(1)
PY
}
