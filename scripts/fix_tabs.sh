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
# along with this program. If not, see <https://www.gnu.org/licenses/>.
#
# FILE INFORMATION
# DEFGROUP: MokoStandards
# INGROUP: GitHub.Actions.Utilities
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/fix_tabs.sh
# VERSION: 03.05.00
# BRIEF: Utility script to replace tab characters with two spaces in YAML files.
# NOTE: Intended for local developer use. Not executed automatically in CI.
# ============================================================================

set -euo pipefail

log() {
  printf '%s\n' "$*"
}

log "[fix_tabs] Scope: *.yml, *.yaml"
log "[fix_tabs] Action: replace tab characters with two spaces"

changed=0

# Determine file list
if command -v git >/dev/null 2>&1 && git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  files=$(git ls-files '*.yml' '*.yaml' 2>/dev/null || true)
else
  files=$(find . -type f \( -name '*.yml' -o -name '*.yaml' \) -print 2>/dev/null || true)
fi

if [ -z "${files}" ]; then
  log "[fix_tabs] No YAML files found. Nothing to fix."
  exit 0
fi

while IFS= read -r f; do
  [ -n "$f" ] || continue
  [ -f "$f" ] || continue

  if LC_ALL=C grep -q $'\t' -- "$f"; then
    log "[fix_tabs] Fixing tabs in: $f"
    # Replace literal tab characters with exactly two spaces
    sed -i 's/\t/  /g' "$f"
    changed=1
  else
    log "[fix_tabs] Clean: $f"
  fi
done <<< "${files}"

if [ "$changed" -eq 1 ]; then
  log "[fix_tabs] Completed with modifications"
else
  log "[fix_tabs] No changes required"
fi
