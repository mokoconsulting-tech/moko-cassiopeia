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
# DEFGROUP: Scripts.Validate
# INGROUP: MokoStandards.Release
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/validate/changelog.sh
# VERSION: 01.00.00
# BRIEF: Validates CHANGELOG.md structure and confirms a release section exists for the current version inferred from branch, tag, or env.
# NOTE:
# ============================================================================

set -euo pipefail

json_escape() {
	python3 - <<'PY' "$1"
import json,sys
print(json.dumps(sys.argv[1]))
PY
}

fail() {
	local msg="$1"
	local extra="${2:-}"
	if [ -n "${extra}" ]; then
		printf '{"status":"fail","error":%s,%s}\n' "$(json_escape "${msg}")" "${extra}"
	else
		printf '{"status":"fail","error":%s}\n' "$(json_escape "${msg}")"
	fi
	exit 1
}

ok() {
	local extra="${1:-}"
	if [ -n "${extra}" ]; then
		printf '{"status":"ok",%s}\n' "${extra}"
	else
		printf '{"status":"ok"}\n'
	fi
}

# Version resolution order:
# 1) explicit env: RELEASE_VERSION or VERSION
# 2) branch name (GITHUB_REF_NAME): rc/x.y.z or version/x.y.z or dev/x.y.z
# 3) tag name (GITHUB_REF_NAME): vX.Y.Z or vX.Y.Z-rc
# 4) git describe tag fallback
VERSION_IN="${RELEASE_VERSION:-${VERSION:-}}"

ref_name="${GITHUB_REF_NAME:-}"

infer_from_ref() {
	local r="$1"
	if printf '%s' "${r}" | grep -Eq '^(dev|rc|version)/[0-9]+\.[0-9]+\.[0-9]+$'; then
		printf '%s' "${r#*/}"
		return 0
	fi
	if printf '%s' "${r}" | grep -Eq '^v[0-9]+\.[0-9]+\.[0-9]+(-rc)?$'; then
		r="${r#v}"
		r="${r%-rc}"
		printf '%s' "${r}"
		return 0
	fi
	return 1
}

VERSION_RESOLVED=""

if [ -n "${VERSION_IN}" ]; then
	if ! printf '%s' "${VERSION_IN}" | grep -Eq '^[0-9]+\.[0-9]+\.[0-9]+$'; then
		fail "Invalid version format in env" "\"version\":$(json_escape "${VERSION_IN}")"
	fi
	VERSION_RESOLVED="${VERSION_IN}"
else
	if [ -n "${ref_name}" ]; then
		if v="$(infer_from_ref "${ref_name}" 2>/dev/null)"; then
			VERSION_RESOLVED="${v}"
		fi
	fi

	if [ -z "${VERSION_RESOLVED}" ]; then
		tag="$(git describe --tags --abbrev=0 2>/dev/null || true)"
		if [ -n "${tag}" ]; then
			if v="$(infer_from_ref "${tag}" 2>/dev/null)"; then
				VERSION_RESOLVED="${v}"
			fi
		fi
	fi
fi

if [ -z "${VERSION_RESOLVED}" ]; then
	fail "Unable to infer version (set RELEASE_VERSION or VERSION, or use a versioned branch/tag)" "\"ref_name\":$(json_escape "${ref_name:-}" )"
fi

if [ ! -f "CHANGELOG.md" ]; then
	fail "CHANGELOG.md missing"
fi

if [ ! -s "CHANGELOG.md" ]; then
	fail "CHANGELOG.md is empty"
fi

# Core structural checks
# - Must contain at least one H2 heading with a bracketed version
# - Must contain an Unreleased section
# - Must contain a section for the resolved version

unreleased_ok=false
if grep -Eq '^## \[Unreleased\]' CHANGELOG.md; then
	unreleased_ok=true
fi

if [ "${unreleased_ok}" != "true" ]; then
	fail "CHANGELOG.md missing '## [Unreleased]' section"
fi

if ! grep -Eq '^## \[[0-9]+\.[0-9]+\.[0-9]+\]' CHANGELOG.md; then
	fail "CHANGELOG.md has no version sections (expected headings like: ## [x.y.z])"
fi

# Version section existence
if ! grep -Fq "## [${VERSION_RESOLVED}]" CHANGELOG.md; then
	fail "CHANGELOG.md missing version section" "\"version\":$(json_escape "${VERSION_RESOLVED}")"
fi

# Optional quality checks (warnings only)
warnings=()

# Expect a date on the same line as the version heading, like: ## [x.y.z] YYYY-MM-DD
if ! grep -Eq "^## \[${VERSION_RESOLVED}\] [0-9]{4}-[0-9]{2}-[0-9]{2}$" CHANGELOG.md; then
	warnings+=("version_heading_date_missing_or_nonstandard")
fi

# Minimal section content: require at least one non-empty line between this version heading and the next heading.
python3 - <<'PY' "${VERSION_RESOLVED}" || true
import re,sys
ver = sys.argv[1]
text = open('CHANGELOG.md','r',encoding='utf-8').read().splitlines()
start = None
for i,line in enumerate(text):
	if line.startswith(f"## [{ver}]"):
		start = i
		break
if start is None:
	sys.exit(0)
end = len(text)
for j in range(start+1,len(text)):
	if text[j].startswith('## ['):
		end = j
		break
block = [ln for ln in text[start+1:end] if ln.strip()]
# block contains at least one meaningful line (excluding blank)
if len(block) == 0:
	print('WARN: version_section_empty')
PY

if grep -Fq 'WARN: version_section_empty' <(python3 - <<'PY' "${VERSION_RESOLVED}" 2>/dev/null || true
import sys
ver = sys.argv[1]
lines = open('CHANGELOG.md','r',encoding='utf-8').read().splitlines()
start = None
for i,l in enumerate(lines):
	if l.startswith(f"## [{ver}]"):
		start=i
		break
if start is None:
	sys.exit(0)
end=len(lines)
for j in range(start+1,len(lines)):
	if lines[j].startswith('## ['):
		end=j
		break
block=[ln for ln in lines[start+1:end] if ln.strip()]
if len(block)==0:
	print('WARN: version_section_empty')
PY
); then
	warnings+=("version_section_empty")
fi

# Emit machine-readable report
if [ "${#warnings[@]}" -gt 0 ]; then
	# Build JSON array safely
	warn_json="["
	sep=""
	for w in "${warnings[@]}"; do
		warn_json+="${sep}$(json_escape "${w}")"
		sep=",";
	done
	warn_json+="]"
	ok "\"version\":$(json_escape "${VERSION_RESOLVED}"),\"ref_name\":$(json_escape "${ref_name:-}"),\"warnings\":${warn_json}"
else
	ok "\"version\":$(json_escape "${VERSION_RESOLVED}"),\"ref_name\":$(json_escape "${ref_name:-}"),\"warnings\":[]"
fi

printf '%s\n' "changelog: ok (version=${VERSION_RESOLVED})"
