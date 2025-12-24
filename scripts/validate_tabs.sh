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
# INGROUP: GitHub.Actions.CI
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/validate_tabs.sh
# VERSION: 03.05.00
# BRIEF: CI validator that blocks tab characters in YAML files and enforces two-space indentation policy.
# NOTE: YAML is indentation sensitive; tabs are noncompliant. This validator fails the job when any tab is detected.
# ============================================================================

set -euo pipefail

log() {
  printf '%s\n' "$*"
}

fail=0

log "[validate_tabs] Scope: *.yml, *.yaml"
log "[validate_tabs] Policy: tab characters are noncompliant; replace with two spaces"

# Find YAML files tracked in git first. If not in a git repo, fall back to filesystem search.
yaml_files=""
if command -v git >/dev/null 2>&1 && git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  yaml_files="$(git ls-files '*.yml' '*.yaml' 2>/dev/null || true)"
else
  yaml_files="$(find . -type f \( -name '*.yml' -o -name '*.yaml' \) -print 2>/dev/null || true)"
fi

if [ -z "${yaml_files}" ]; then
  log "[validate_tabs] No YAML files found. Status: PASS"
  exit 0
fi

log "[validate_tabs] YAML files discovered: $(printf '%s\n' "${yaml_files}" | wc -l | tr -d ' ')"

while IFS= read -r f; do
  [ -n "$f" ] || continue

  # Skip deleted paths in edge cases
  [ -f "$f" ] || continue

  # Detect literal tab characters and report with line numbers.
  if LC_ALL=C grep -n $'\t' -- "$f" >/dev/null 2>&1; then
	 log "[validate_tabs] FAIL: tab detected in: $f"

	 # Emit an actionable audit trail: line number plus the exact line content.
	 # Use sed to avoid grep prefix repetition and keep the output deterministic.
	 LC_ALL=C grep -n $'\t' -- "$f" | while IFS= read -r hit; do
		log "  ${hit}"
	 done

	 log "[validate_tabs] Remediation: replace each tab with exactly two spaces"
	 log "[validate_tabs] Example: sed -i 's/\\t/  /g' \"$f\""

	 fail=1
  else
	 log "[validate_tabs] PASS: $f"
  fi
done <<< "${yaml_files}"

if [ "$fail" -ne 0 ]; then
  log "[validate_tabs] Status: FAIL"
  exit 1
fi

log "[validate_tabs] Status: PASS"
