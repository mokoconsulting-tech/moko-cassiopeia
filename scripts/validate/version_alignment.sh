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
# PATH: /scripts/validate/version_alignment.sh
# VERSION: 01.00.00
# BRIEF: Validates alignment between inferred version, CHANGELOG.md section, and manifest <version> value.
# NOTE:
# ============================================================================

set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"

json_escape() { python3 - <<'PY' "$1"; import json,sys; print(json.dumps(sys.argv[1])); PY; }

fail() {
	local msg="$1"; shift || true
	local extra="${1:-}"
	if [ -n "${extra}" ]; then
		printf '{"status":"fail","error":%s,%s}
' "$(json_escape "${msg}")" "${extra}"
	else
		printf '{"status":"fail","error":%s}
' "$(json_escape "${msg}")"
	fi
	exit 1
}

[ -d "${SRC_DIR}" ] || fail "src directory missing" "\"src_dir\":$(json_escape "${SRC_DIR}")"

infer_version_from_ref() {
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

VERSION_RESOLVED="${RELEASE_VERSION:-${VERSION:-}}"
if [ -z "${VERSION_RESOLVED}" ]; then
	if [ -n "${GITHUB_REF_NAME:-}" ]; then
		VERSION_RESOLVED="$(infer_version_from_ref "${GITHUB_REF_NAME}" 2>/dev/null || true)"
	fi
fi
if [ -z "${VERSION_RESOLVED}" ]; then
	tag="$(git describe --tags --abbrev=0 2>/dev/null || true)"
	if [ -n "${tag}" ]; then
		VERSION_RESOLVED="$(infer_version_from_ref "${tag}" 2>/dev/null || true)"
	fi
fi

[ -n "${VERSION_RESOLVED}" ] || fail "Unable to infer version" "\"ref_name\":$(json_escape "${GITHUB_REF_NAME:-}")"
echo "${VERSION_RESOLVED}" | grep -Eq '^[0-9]+\.[0-9]+\.[0-9]+$' || fail "Invalid version format" "\"version\":$(json_escape "${VERSION_RESOLVED}")"

[ -f CHANGELOG.md ] || fail "CHANGELOG.md missing"
if ! grep -Fq "## [${VERSION_RESOLVED}]" CHANGELOG.md; then
	fail "CHANGELOG.md missing version section" "\"version\":$(json_escape "${VERSION_RESOLVED}")"
fi

MANIFEST=""
if [ -f "${SRC_DIR}/templateDetails.xml" ]; then
	MANIFEST="${SRC_DIR}/templateDetails.xml"
else
	MANIFEST="$(find "${SRC_DIR}" -maxdepth 6 -type f \( -name 'templateDetails.xml' -o -name 'pkg_*.xml' -o -name 'com_*.xml' -o -name 'mod_*.xml' -o -name 'plg_*.xml' \) 2>/dev/null | sort | head -n 1 || true)"
fi

[ -n "${MANIFEST}" ] || fail "Manifest not found under src" "\"src_dir\":$(json_escape "${SRC_DIR}")"

manifest_version="$(python3 - <<'PY' "${MANIFEST}"
import sys
import xml.etree.ElementTree as ET
p=sys.argv[1]
root=ET.parse(p).getroot()
ver=root.findtext('version') or ''
print(ver.strip())
PY
)"

[ -n "${manifest_version}" ] || fail "Manifest missing <version>" "\"manifest\":$(json_escape "${MANIFEST}")"

if [ "${manifest_version}" != "${VERSION_RESOLVED}" ]; then
	fail "Version mismatch" "\"version\":$(json_escape "${VERSION_RESOLVED}"),\"manifest\":$(json_escape "${MANIFEST}"),\"manifest_version\":$(json_escape "${manifest_version}")"
fi

printf '{"status":"ok","version":%s,"manifest":%s}
' "$(json_escape "${VERSION_RESOLVED}")" "$(json_escape "${MANIFEST}")"
echo "version_alignment: ok"
