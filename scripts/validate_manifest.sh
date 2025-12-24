#!/usr/bin/env bash
# -----------------------------------------------------------------------------
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# This file is part of a Moko Consulting project.
#
# SPDX-License-Identifier: GPL-3.0-or-later
#
# This program is free software; you can redistribute it and/or modify it under
# the terms of the GNU General Public License as published by the Free Software
# Foundation; either version 3 of the License, or (at your option) any later
# version.
#
# This program is distributed in the hope that it will be useful, but WITHOUT
# ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
# FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License (./LICENSE.md).
# -----------------------------------------------------------------------------
# FILE INFORMATION
# DEFGROUP: MokoStandards
# INGROUP: Joomla.Validation
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/validate_manifest.sh
# VERSION: 03.05.00
# BRIEF: Validate a Joomla project manifest XML for structural and governance compliance
# Purpose:
# - Validate the XML manifest for a Joomla project.
# - Supports common Joomla extension types: template, component, module, plugin, package.
# - Performs syntax validation, required field checks, and type specific attribute checks.
# - Designed for CI enforcement and local pre commit validation.
#
# Usage:
#   ./scripts/validate_manifest.sh [MANIFEST_XML]
#   ./scripts/validate_manifest.sh --auto
#
# Examples:
#   ./scripts/validate_manifest.sh ./src/templateDetails.xml
#   ./scripts/validate_manifest.sh --auto
# =============================================================================

set -euo pipefail

AUTO=false
MANIFEST=""

info() { echo "INFO: $*"; }
warn() { echo "WARN: $*" 1>&2; }
err()  { echo "ERROR: $*" 1>&2; }

die() {
  err "$*"
  exit 1
}

have() {
  command -v "$1" >/dev/null 2>&1
}

usage() {
  cat <<'USAGE'
Usage:
  ./scripts/validate_manifest.sh [MANIFEST_XML]
  ./scripts/validate_manifest.sh --auto

Notes:
  - If MANIFEST_XML is omitted, --auto is recommended.
  - Exits nonzero on validation failure.
USAGE
}

parse_args() {
  if [[ $# -eq 0 ]]; then
	AUTO=true
	return 0
  fi

  if [[ $# -eq 1 && "$1" == "--auto" ]]; then
	AUTO=true
	return 0
  fi

  if [[ $# -eq 1 && "$1" == "-h" || $# -eq 1 && "$1" == "--help" ]]; then
	usage
	exit 0
  fi

  if [[ $# -eq 1 ]]; then
	MANIFEST="$1"
	return 0
  fi

  usage
  die "Invalid arguments."
}

find_manifest_auto() {
  local root="./src"
  [[ -d "$root" ]] || die "Auto detect requires ./src directory."

  # First pass: known canonical names
  local candidates
  candidates=$(find "$root" -type f \( -name 'templateDetails.xml' -o -name '*.xml' \) -not -path '*/.git/*' -print 2>/dev/null || true)

  # Filter to those that look like Joomla manifests by checking for a root <extension> element.
  local matches=()
  while IFS= read -r f; do
	[[ -f "$f" ]] || continue
	if grep -qE '<extension(\s|>)' "$f"; then
	  matches+=("$f")
	fi
  done <<< "$candidates"

  if [[ ${#matches[@]} -eq 0 ]]; then
	die "No manifest XML detected under ./src. Provide a manifest path explicitly."
  fi

  if [[ ${#matches[@]} -gt 1 ]]; then
	err "Multiple candidate manifest XML files found. Provide the intended file explicitly:"
	for m in "${matches[@]}"; do
	  err "- $m"
	done
	exit 2
  fi

  MANIFEST="${matches[0]}"
}

xmllint_check() {
  local f="$1"
  if ! have xmllint; then
	die "xmllint is required for XML validation. Install libxml2 utils in the runner environment."
  fi

  # Syntax validation
  xmllint --noout "$f" >/dev/null
}

xpath() {
  local f="$1"
  local expr="$2"
  xmllint --xpath "$expr" "$f" 2>/dev/null || true
}

trim() {
  local s="$1"
  # shellcheck disable=SC2001
  echo "$s" | sed -e 's/^[[:space:]]\+//' -e 's/[[:space:]]\+$//'
}

required_text() {
  local f="$1"
  local label="$2"
  local expr="$3"

  local out
  out="$(xpath "$f" "$expr")"
  out="$(trim "$out")"
  [[ -n "$out" ]] || die "Missing or empty required element: $label"
  echo "$out"
}

required_attr() {
  local f="$1"
  local label="$2"
  local expr="$3"

  local out
  out="$(xpath "$f" "$expr")"
  out="$(trim "$out")"
  [[ -n "$out" ]] || die "Missing required attribute: $label"
  echo "$out"
}

validate_root_and_type() {
  local f="$1"

  local root
  root="$(xpath "$f" 'name(/*)')"
  root="$(trim "$root")"

  [[ "$root" == "extension" ]] || die "Invalid root element '$root'. Expected: extension"

  local type
  type="$(required_attr "$f" 'extension@type' 'string(/extension/@type)')"

  case "$type" in
	template|component|module|plugin|package)
	  info "Detected manifest type: $type"
	  ;;
	*)
	  die "Unsupported or invalid Joomla manifest type '$type'. Expected one of: template, component, module, plugin, package"
	  ;;
  esac

  echo "$type"
}

validate_required_fields() {
  local f="$1"

  # Joomla manifests typically include these fields.
  required_text "$f" 'name' 'string(/extension/name)'
  required_text "$f" 'version' 'string(/extension/version)'

  # Author is not always mandatory, but it is governance relevant.
  local author
  author="$(xpath "$f" 'string(/extension/author)')"
  author="$(trim "$author")"
  if [[ -z "$author" ]]; then
	warn "author is missing. Governance recommended: include <author>"
  fi

  # Creation date is common and helps auditability.
  local cdate
  cdate="$(xpath "$f" 'string(/extension/creationDate)')"
  cdate="$(trim "$cdate")"
  if [[ -z "$cdate" ]]; then
	warn "creationDate is missing. Governance recommended: include <creationDate>"
  fi

  # Basic packaging elements: at least one of files, folders, fileset, or languages.
  local has_files
  has_files="$(xpath "$f" 'count(/extension/files)')"
  local has_folders
  has_folders="$(xpath "$f" 'count(/extension/folders)')"
  local has_filesets
  has_filesets="$(xpath "$f" 'count(/extension/fileset | /extension/filesets | /extension/file)')"
  local has_lang
  has_lang="$(xpath "$f" 'count(/extension/languages | /extension/administration/languages)')"

  # xmllint returns numbers as strings
  has_files="$(trim "$has_files")"
  has_folders="$(trim "$has_folders")"
  has_filesets="$(trim "$has_filesets")"
  has_lang="$(trim "$has_lang")"

  if [[ "${has_files:-0}" == "0" && "${has_folders:-0}" == "0" && "${has_filesets:-0}" == "0" && "${has_lang:-0}" == "0" ]]; then
	die "Manifest appears to lack payload declarations. Expected one of: <files>, <folders>, <fileset>, or <languages>."
  fi
}

validate_version_format() {
  local v="$1"

  # Governance check: allow semantic style versions, warn if non standard.
  if [[ ! "$v" =~ ^[0-9]+\.[0-9]+(\.[0-9]+)?([-.][A-Za-z0-9\.]+)?$ ]]; then
	warn "Version '$v' does not look like a conventional semantic version."
  fi
}

validate_type_specific() {
  local f="$1"
  local type="$2"

  case "$type" in
	plugin)
	  local group
	  group="$(required_attr "$f" 'extension@group' 'string(/extension/@group)')"
	  info "Plugin group: $group"
	  ;;
	module)
	  # client is optional for some older manifests, but recommended.
	  local client
	  client="$(xpath "$f" 'string(/extension/@client)')"
	  client="$(trim "$client")"
	  if [[ -z "$client" ]]; then
		warn "Module client attribute is missing. Governance recommended: set client=site or client=administrator."
	  else
		info "Module client: $client"
	  fi
	  ;;
	template)
	  local client
	  client="$(xpath "$f" 'string(/extension/@client)')"
	  client="$(trim "$client")"
	  if [[ -z "$client" ]]; then
		warn "Template client attribute is missing. Governance recommended: set client=site."
	  else
		info "Template client: $client"
	  fi
	  ;;
	component)
	  # method=upgrade is a common governance default.
	  local method
	  method="$(xpath "$f" 'string(/extension/@method)')"
	  method="$(trim "$method")"
	  if [[ -z "$method" ]]; then
		warn "Component method attribute is missing. Governance recommended: set method=upgrade."
	  else
		info "Component method: $method"
	  fi
	  ;;
	package)
	  # Packages should declare contained extensions.
	  local count
	  count="$(xpath "$f" 'count(/extension/files/file)')"
	  count="$(trim "$count")"
	  if [[ "${count:-0}" == "0" ]]; then
		warn "Package manifest contains no /extension/files/file entries. Validate that it declares packaged extensions."
	  else
		info "Package files entries: $count"
	  fi
	  ;;
  esac
}

main() {
  parse_args "$@"

  if [[ "$AUTO" == "true" ]]; then
	find_manifest_auto
  fi

  [[ -n "$MANIFEST" ]] || die "Manifest path not resolved."
  [[ -f "$MANIFEST" ]] || die "Manifest file not found: $MANIFEST"

  info "Validating Joomla manifest: $MANIFEST"

  xmllint_check "$MANIFEST"

  local type
  type="$(validate_root_and_type "$MANIFEST")"

  validate_required_fields "$MANIFEST"

  local version
  version="$(required_text "$MANIFEST" 'version' 'string(/extension/version)')"
  validate_version_format "$version"

  validate_type_specific "$MANIFEST" "$type"

  info "Manifest validation PASSED."
}

main "$@"
