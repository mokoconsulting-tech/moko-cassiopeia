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
# INGROUP: Repository.Release
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/run/migrate_unreleased.sh
# VERSION: 01.00.00
# BRIEF: Migrate unreleased changelog entries to a versioned section
# NOTE: Moves content from [Unreleased] section to a specified version heading
# ============================================================================

set -euo pipefail

# ----------------------------------------------------------------------------
# Usage
# ----------------------------------------------------------------------------

usage() {
cat <<-USAGE
Usage: $0 <VERSION> [OPTIONS]

Migrate unreleased changelog entries to a versioned section.

Arguments:
  VERSION       Version number in format NN.NN.NN (e.g., 03.05.00)

Options:
  -h, --help    Show this help message
  -d, --date    Date to use for version entry (default: today, format: YYYY-MM-DD)
  -n, --dry-run Show what would be done without making changes
  -k, --keep    Keep the [Unreleased] section after migration (default: empty it)

Examples:
  $0 03.05.00                    # Migrate unreleased to version 03.05.00
  $0 03.05.00 --date 2026-01-04  # Use specific date
  $0 03.05.00 --dry-run          # Preview changes without applying
  $0 03.05.00 --keep             # Keep unreleased section after migration

USAGE
exit 0
}

# ----------------------------------------------------------------------------
# Argument parsing
# ----------------------------------------------------------------------------

VERSION=""
DATE=""
DRY_RUN=false
KEEP_UNRELEASED=false

while [[ $# -gt 0 ]]; do
	case "$1" in
		-h|--help)
			usage
			;;
		-d|--date)
			DATE="$2"
			shift 2
			;;
		-n|--dry-run)
			DRY_RUN=true
			shift
			;;
		-k|--keep)
			KEEP_UNRELEASED=true
			shift
			;;
		*)
			if [[ -z "$VERSION" ]]; then
				VERSION="$1"
				shift
			else
				echo "ERROR: Unknown argument: $1" >&2
				usage
			fi
			;;
	esac
done

# ----------------------------------------------------------------------------
# Validation
# ----------------------------------------------------------------------------

if [[ -z "$VERSION" ]]; then
	echo "ERROR: VERSION is required" >&2
	usage
fi

if ! [[ "$VERSION" =~ ^[0-9]{2}\.[0-9]{2}\.[0-9]{2}$ ]]; then
	echo "ERROR: Invalid version format: $VERSION" >&2
	echo "Expected format: NN.NN.NN (e.g., 03.05.00)" >&2
	exit 1
fi

if [[ -z "$DATE" ]]; then
	DATE=$(date '+%Y-%m-%d')
fi

if ! [[ "$DATE" =~ ^[0-9]{4}-[0-9]{2}-[0-9]{2}$ ]]; then
	echo "ERROR: Invalid date format: $DATE" >&2
	echo "Expected format: YYYY-MM-DD" >&2
	exit 1
fi

# ----------------------------------------------------------------------------
# Source common utilities
# ----------------------------------------------------------------------------

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# ----------------------------------------------------------------------------
# Main logic
# ----------------------------------------------------------------------------

CHANGELOG_FILE="CHANGELOG.md"

if [[ ! -f "$CHANGELOG_FILE" ]]; then
	log_error "CHANGELOG.md not found in repository root"
	exit 1
fi

log_info "Migrating unreleased changelog entries to version $VERSION"
log_info "Date: $DATE"
log_info "Dry run: $DRY_RUN"
log_info "Keep unreleased section: $KEEP_UNRELEASED"

# Use Python to process the changelog
python3 - <<PY
import os
import sys
from pathlib import Path

version = "${VERSION}"
stamp = "${DATE}"
dry_run = "${DRY_RUN}" == "true"
keep_unreleased = "${KEEP_UNRELEASED}" == "true"

changelog_path = Path("${CHANGELOG_FILE}")
lines = changelog_path.read_text(encoding="utf-8", errors="replace").splitlines(True)

def is_h2(line: str) -> bool:
    return line.lstrip().startswith("## ")

def norm(line: str) -> str:
    return line.strip().lower()

def find_idx(predicate):
    for i, ln in enumerate(lines):
        if predicate(ln):
            return i
    return None

unreleased_idx = find_idx(lambda ln: norm(ln) == "## [unreleased]")
version_idx = find_idx(lambda ln: ln.lstrip().startswith(f"## [{version}]"))

def version_header() -> list:
    return ["\n", f"## [{version}] {stamp}\n", "\n"]

if unreleased_idx is None:
    print(f"INFO: No [Unreleased] section found in {changelog_path}")
    if version_idx is None:
        print(f"INFO: Version section [{version}] does not exist")
        print(f"INFO: Creating new version section with placeholder content")
        if not dry_run:
            # Find insertion point after main heading
            insert_at = 0
            for i, ln in enumerate(lines):
                if ln.lstrip().startswith("# "):
                    insert_at = i + 1
                    while insert_at < len(lines) and lines[insert_at].strip() == "":
                        insert_at += 1
                    break
            entry = version_header() + ["- No changes recorded.\n", "\n"]
            lines[insert_at:insert_at] = entry
            changelog_path.write_text("".join(lines), encoding="utf-8")
            print(f"SUCCESS: Created version section [{version}]")
        else:
            print(f"DRY-RUN: Would create version section [{version}]")
    else:
        print(f"INFO: Version section [{version}] already exists")
    sys.exit(0)

# Extract unreleased content
u_start = unreleased_idx + 1
u_end = len(lines)
for j in range(u_start, len(lines)):
    if is_h2(lines[j]):
        u_end = j
        break

unreleased_body = "".join(lines[u_start:u_end]).strip()

if not unreleased_body:
    print(f"INFO: [Unreleased] section is empty, nothing to migrate")
    sys.exit(0)

print(f"INFO: Found unreleased content ({len(unreleased_body)} chars)")

# Create or find version section
if version_idx is None:
    print(f"INFO: Creating version section [{version}]")
    if not dry_run:
        lines[u_end:u_end] = version_header()
    else:
        print(f"DRY-RUN: Would create version section [{version}]")

version_idx = find_idx(lambda ln: ln.lstrip().startswith(f"## [{version}]"))
if version_idx is None and not dry_run:
    print("ERROR: Failed to locate version header after insertion", file=sys.stderr)
    sys.exit(1)

# Move unreleased content to version section
if unreleased_body:
    if not dry_run:
        insert_at = version_idx + 1
        while insert_at < len(lines) and lines[insert_at].strip() == "":
            insert_at += 1
        
        moved = ["\n"] + [ln + "\n" for ln in unreleased_body.split("\n") if ln != ""] + ["\n"]
        lines[insert_at:insert_at] = moved
        print(f"INFO: Moved {len([ln for ln in unreleased_body.split('\n') if ln])} lines to [{version}]")
    else:
        line_count = len([ln for ln in unreleased_body.split('\n') if ln])
        print(f"DRY-RUN: Would move {line_count} lines to [{version}]")
        print(f"DRY-RUN: Content preview:")
        for line in unreleased_body.split('\n')[:5]:
            if line:
                print(f"  {line}")

# Handle unreleased section
if not keep_unreleased:
    unreleased_idx = find_idx(lambda ln: norm(ln) == "## [unreleased]")
    if unreleased_idx is not None:
        if not dry_run:
            u_start = unreleased_idx + 1
            u_end = len(lines)
            for j in range(u_start, len(lines)):
                if is_h2(lines[j]):
                    u_end = j
                    break
            lines[u_start:u_end] = ["\n"]
            print(f"INFO: Emptied [Unreleased] section")
        else:
            print(f"DRY-RUN: Would empty [Unreleased] section")
else:
    print(f"INFO: Keeping [Unreleased] section as requested")

if not dry_run:
    changelog_path.write_text("".join(lines), encoding="utf-8")
    print(f"SUCCESS: Migrated unreleased content to [{version}]")
else:
    print(f"DRY-RUN: Changes not applied (use without --dry-run to apply)")

PY

if [[ $? -eq 0 ]]; then
	if [[ "$DRY_RUN" == "false" ]]; then
		log_info "✓ Migration completed successfully"
		log_info "✓ Changelog updated: $CHANGELOG_FILE"
	else
		log_info "✓ Dry run completed"
	fi
else
	log_error "Migration failed"
	exit 1
fi
