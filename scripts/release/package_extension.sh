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
# DEFGROUP: Script.Release
# INGROUP: Extension.Packaging
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/release/package_extension.sh
# VERSION: 01.00.00
# BRIEF: Package Joomla extension as distributable ZIP
# USAGE: ./scripts/release/package_extension.sh [output_dir] [version]
# ============================================================================

set -euo pipefail

# Load shared library functions (optional)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LIB_DIR="${SCRIPT_DIR}/../lib"

# Configuration
SRC_DIR="${SRC_DIR:-src}"
OUTPUT_DIR="${1:-dist}"
VERSION="${2:-}"
REPO_NAME="${REPO_NAME:-$(basename "$(git rev-parse --show-toplevel)")}"

json_escape() {
    python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

log_info() {
    echo "[INFO] $*" >&2
}

log_error() {
    echo "[ERROR] $*" >&2
}

# Validate prerequisites
validate_prerequisites() {
    if [ ! -d "${SRC_DIR}" ]; then
        log_error "Source directory '${SRC_DIR}' not found"
        printf '{"status":"fail","error":%s}\n' "$(json_escape "src directory missing")"
        exit 1
    fi

    if ! command -v zip >/dev/null 2>&1; then
        log_error "zip command not found. Please install zip utility."
        printf '{"status":"fail","error":%s}\n' "$(json_escape "zip command not found")"
        exit 1
    fi
}

# Find and validate manifest
find_manifest_file() {
    local manifest=""
    
    # Priority order for finding manifest
    if [ -f "${SRC_DIR}/templateDetails.xml" ]; then
        manifest="${SRC_DIR}/templateDetails.xml"
    elif [ -f "${SRC_DIR}/templates/templateDetails.xml" ]; then
        manifest="${SRC_DIR}/templates/templateDetails.xml"
    else
        # Try finding any Joomla manifest
        manifest=$(find "${SRC_DIR}" -maxdepth 3 -type f \( \
            -name 'templateDetails.xml' -o \
            -name 'pkg_*.xml' -o \
            -name 'mod_*.xml' -o \
            -name 'com_*.xml' -o \
            -name 'plg_*.xml' \
        \) | head -n 1)
    fi

    if [ -z "${manifest}" ]; then
        log_error "No Joomla manifest XML found in ${SRC_DIR}"
        printf '{"status":"fail","error":%s}\n' "$(json_escape "manifest not found")"
        exit 1
    fi

    echo "${manifest}"
}

# Extract extension metadata from manifest
get_extension_metadata() {
    local manifest="$1"
    local ext_type=""
    local ext_name=""
    local ext_version=""

    # Extract extension type
    ext_type=$(grep -Eo 'type="[^"]+"' "${manifest}" | head -n 1 | cut -d '"' -f2 || echo "unknown")
    
    # Extract extension name
    ext_name=$(grep -oP '<name>\K[^<]+' "${manifest}" | head -n 1 || echo "unknown")
    
    # Extract version
    ext_version=$(grep -oP '<version>\K[^<]+' "${manifest}" | head -n 1 || echo "unknown")

    echo "${ext_type}|${ext_name}|${ext_version}"
}

# Create package
create_package() {
    local manifest="$1"
    local output_dir="$2"
    local version="$3"
    
    # Get extension metadata
    local metadata
    metadata=$(get_extension_metadata "${manifest}")
    local ext_type=$(echo "${metadata}" | cut -d '|' -f1)
    local ext_name=$(echo "${metadata}" | cut -d '|' -f2)
    local manifest_version=$(echo "${metadata}" | cut -d '|' -f3)
    
    # Use provided version or fall back to manifest version
    if [ -z "${version}" ]; then
        version="${manifest_version}"
    fi
    
    # Create output directory
    mkdir -p "${output_dir}"
    
    # Generate package filename
    local timestamp=$(date +%Y%m%d-%H%M%S)
    local zip_name="${REPO_NAME}-${version}-${ext_type}.zip"
    
    # Get absolute path for zip file
    local abs_output_dir
    if [[ "${output_dir}" = /* ]]; then
        abs_output_dir="${output_dir}"
    else
        abs_output_dir="$(pwd)/${output_dir}"
    fi
    local zip_path="${abs_output_dir}/${zip_name}"
    
    log_info "Creating package: ${zip_name}"
    log_info "Extension: ${ext_name} (${ext_type})"
    log_info "Version: ${version}"
    
    # Create ZIP archive excluding unnecessary files
    (cd "${SRC_DIR}" && zip -r -q -X "${zip_path}" . \
        -x '*.git*' \
        -x '*/.github/*' \
        -x '*.DS_Store' \
        -x '*/__MACOSX/*' \
        -x '*/node_modules/*' \
        -x '*/vendor/*' \
        -x '*/tests/*' \
        -x '*/.phpunit.result.cache' \
        -x '*/codeception.yml' \
        -x '*/composer.json' \
        -x '*/composer.lock' \
        -x '*/package.json' \
        -x '*/package-lock.json')
    
    # Get file size
    local zip_size
    if command -v stat >/dev/null 2>&1; then
        zip_size=$(stat -f%z "${zip_path}" 2>/dev/null || stat -c%s "${zip_path}" 2>/dev/null || echo "unknown")
    else
        zip_size="unknown"
    fi
    
    log_info "Package created successfully: ${zip_path}"
    log_info "Package size: ${zip_size} bytes"
    
    # Output JSON result
    printf '{"status":"ok","package":%s,"type":%s,"version":%s,"size":%s,"manifest":%s}\n' \
        "$(json_escape "${zip_path}")" \
        "$(json_escape "${ext_type}")" \
        "$(json_escape "${version}")" \
        "${zip_size}" \
        "$(json_escape "${manifest}")"
}

# Main execution
main() {
    log_info "Starting Joomla extension packaging"
    
    validate_prerequisites
    
    local manifest
    manifest=$(find_manifest_file)
    log_info "Using manifest: ${manifest}"
    
    create_package "${manifest}" "${OUTPUT_DIR}" "${VERSION}"
    
    log_info "Packaging completed successfully"
}

# Run main function
main "$@"
