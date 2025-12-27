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
# DEFGROUP: Shell.Script
# INGROUP: MokoStandards.Validation
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/validate_manifest.sh
# VERSION: 03.05.00
# BRIEF: Validate Joomla extension manifest governance before packaging.
# NOTE: Enforces: manifest discovery, extension type presence, version and creationDate presence, XML wellformedness when xmllint is available.
# ============================================================================

set -euo pipefail

# Purpose:
# - Locate the primary Joomla manifest under /src.
# - Validate that it contains a <extension ... type="..."> root.
# - Validate required fields exist: <version>, <creationDate>, <name>.
# - Validate XML is wellformed when xmllint is available.
#
# Usage:
#   ./scripts/validate_manifest.sh

log_json() {
  # shellcheck disable=SC2059
  printf '%s\n' "$1"
}

fail() {
  local msg="$1"
  echo "ERROR: ${msg}" >&2
  exit 1
}

[ -d "src" ] || fail "src directory missing"

# Discovery priority order.
manifest=""
if [ -f "src/templateDetails.xml" ]; then
  manifest="src/templateDetails.xml"
elif find src -maxdepth 4 -type f -name 'templateDetails.xml' | head -n 1 | grep -q .; then
  manifest="$(find src -maxdepth 4 -type f -name 'templateDetails.xml' | head -n 1)"
elif find src -maxdepth 4 -type f -name 'pkg_*.xml' | head -n 1 | grep -q .; then
  manifest="$(find src -maxdepth 4 -type f -name 'pkg_*.xml' | head -n 1)"
elif find src -maxdepth 4 -type f -name 'com_*.xml' | head -n 1 | grep -q .; then
  manifest="$(find src -maxdepth 4 -type f -name 'com_*.xml' | head -n 1)"
elif find src -maxdepth 4 -type f -name 'mod_*.xml' | head -n 1 | grep -q .; then
  manifest="$(find src -maxdepth 4 -type f -name 'mod_*.xml' | head -n 1)"
elif find src -maxdepth 6 -type f -name 'plg_*.xml' | head -n 1 | grep -q .; then
  manifest="$(find src -maxdepth 6 -type f -name 'plg_*.xml' | head -n 1)"
else
  manifest="$(grep -Rsl --include='*.xml' '<extension' src | head -n 1 || true)"
fi

[ -n "${manifest}" ] || fail "No Joomla manifest XML found under src"
[ -f "${manifest}" ] || fail "Manifest not found on disk: ${manifest}"

# Validate root tag presence.
if ! grep -Eq '<extension[^>]*>' "${manifest}"; then
  fail "Manifest does not contain <extension ...> root: ${manifest}"
fi

ext_type="$(grep -Eo 'type="[^"]+"' "${manifest}" | head -n 1 | cut -d '"' -f2 || true)"
[ -n "${ext_type}" ] || fail "Manifest missing required attribute type= on <extension>: ${manifest}"

# Required fields checks.
name_val="$(grep -Eo '<name>[^<]+' "${manifest}" | head -n 1 | sed 's/<name>//' || true)"
version_val="$(grep -Eo '<version>[^<]+' "${manifest}" | head -n 1 | sed 's/<version>//' || true)"
date_val="$(grep -Eo '<creationDate>[^<]+' "${manifest}" | head -n 1 | sed 's/<creationDate>//' || true)"

[ -n "${name_val}" ] || fail "Manifest missing <name>: ${manifest}"
[ -n "${version_val}" ] || fail "Manifest missing <version>: ${manifest}"
[ -n "${date_val}" ] || fail "Manifest missing <creationDate>: ${manifest}"

# Basic version format guardrail (00.00.00 style).
if ! printf '%s' "${version_val}" | grep -Eq '^[0-9]+\.[0-9]+\.[0-9]+$'; then
  fail "Manifest <version> is not semantic (x.y.z): ${version_val}"
fi

# Basic date format guardrail (YYYY-MM-DD).
if ! printf '%s' "${date_val}" | grep -Eq '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'; then
  fail "Manifest <creationDate> is not YYYY-MM-DD: ${date_val}"
fi

# XML wellformedness when available.
if command -v xmllint >/dev/null 2>&1; then
  xmllint --noout "${manifest}" || fail "xmllint reported invalid XML: ${manifest}"
else
  echo "WARN: xmllint not available, skipping strict wellformedness check" >&2
fi

log_json "{\"status\":\"ok\",\"manifest\":\"${manifest}\",\"type\":\"${ext_type}\",\"name\":\"${name_val}\",\"version\":\"${version_val}\",\"creationDate\":\"${date_val}\"}"
