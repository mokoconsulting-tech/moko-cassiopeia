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
# INGROUP: Generic.Script
# REPO: https://github.com/mokoconsulting-tech/MokoDefaults
# PATH: /scripts/update_changelog.sh
# VERSION: 01.00.00
# BRIEF: Insert a versioned CHANGELOG.md entry immediately after the main Changelog heading.
# NOTES
# # Purpose:
# - Apply the MokoWaaS-Brand CHANGELOG template entry for a given version.
# - Insert a new header at the top of CHANGELOG.md, immediately after "# Changelog".
# - Avoid duplicates if an entry for the version already exists.
# - Preserve the rest of the file verbatim.
#
# Usage:
#   ./scripts/update_changelog.sh <VERSION>
#
# Example:
#   ./scripts/update_changelog.sh 01.05.00
# =============================================================================

set -euo pipefail

CHANGELOG_FILE="CHANGELOG.md"

die() {
  echo "ERROR: $*" 1>&2
  exit 1
}

info() {
  echo "INFO: $*"
}

require_cmd() {
  command -v "$1" >/dev/null 2>&1 || die "Missing required command: $1"
}

validate_version() {
  local v="$1"
  [[ "$v" =~ ^[0-9]{2}\.[0-9]{2}\.[0-9]{2}$ ]] || die "Invalid version '$v'. Expected NN.NN.NN (example 03.01.00)."
}

main() {
  require_cmd awk
  require_cmd grep
  require_cmd mktemp
  require_cmd date

  [[ $# -eq 1 ]] || die "Usage: $0 <VERSION>"
  local version="$1"
  validate_version "$version"

  [[ -f "$CHANGELOG_FILE" ]] || die "Missing $CHANGELOG_FILE in repo root."

  if ! grep -qE '^# Changelog[[:space:]]*$' "$CHANGELOG_FILE"; then
    die "$CHANGELOG_FILE must contain a top level heading exactly: # Changelog"
  fi

  if grep -qE "^## \[$version\][[:space:]]" "$CHANGELOG_FILE"; then
    info "CHANGELOG.md already contains an entry for version $version. No action taken."
    exit 0
  fi

  local stamp
  stamp="$(date '+%Y-%m-%d')"

  local tmp
  tmp="$(mktemp)"
  trap 'rm -f "$tmp"' EXIT

  awk -v v="$version" -v d="$stamp" '
    BEGIN { inserted=0 }
    {
	print $0
	if (inserted==0 && $0 ~ /^# Changelog[[:space:]]*$/) {
	  print ""
	  print "## [" v "] " d
	  print "- Version bump."
	  print ""
	  inserted=1
	}
    }
    END {
	if (inserted==0) {
	  exit 3
	}
    }
  ' "$CHANGELOG_FILE" > "$tmp" || {
    rc=$?
    if [[ $rc -eq 3 ]]; then
	die "Insertion point not found. Expected: # Changelog"
    fi
    die "Failed to update $CHANGELOG_FILE (awk exit code $rc)."
  }

  mv "$tmp" "$CHANGELOG_FILE"
  trap - EXIT

  info "Inserted CHANGELOG.md entry for version $version on $stamp."
}

main "$@"
