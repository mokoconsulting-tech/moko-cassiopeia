#!/usr/bin/env bash
#
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
# DEFGROUP: Release.Automation
# INGROUP: Date.Normalization
# REPO: https://github.com/mokoconsulting-tech/MokoStandards
# PATH: /scripts/release/update_dates.sh
# VERSION: 01.00.00
# BRIEF: Normalize release dates across manifests and CHANGELOG using a single authoritative UTC date.
# NOTE: Repo-controlled script only. CI-fatal on malformed inputs. Outputs a JSON report to stdout.

set -euo pipefail

TODAY_UTC="${1:-}"
VERSION="${2:-}"

usage() {
	echo "ERROR: Usage: update_dates.sh <YYYY-MM-DD> <VERSION>" >&2
}

if [ -z "${TODAY_UTC}" ] || [ -z "${VERSION}" ]; then
	usage
	exit 1
fi

# Validate date format strictly
if ! echo "${TODAY_UTC}" | grep -Eq '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'; then
	echo "ERROR: Invalid date format. Expected YYYY-MM-DD, got '${TODAY_UTC}'" >&2
	exit 1
fi

# Validate version format strictly
if ! echo "${VERSION}" | grep -Eq '^[0-9]+\.[0-9]+\.[0-9]+$'; then
	echo "ERROR: Invalid version format. Expected X.Y.Z, got '${VERSION}'" >&2
	exit 1
fi

# Cross-platform sed in-place helper (GNU and BSD)
# - Ubuntu runners use GNU sed, but this keeps local execution deterministic.
sed_inplace() {
	local expr="$1"
	local file="$2"

	if sed --version >/dev/null 2>&1; then
		sed -i -E "${expr}" "${file}"
	else
		sed -i '' -E "${expr}" "${file}"
	fi
}

echo "Normalizing dates to ${TODAY_UTC} for version ${VERSION}"

# Update CHANGELOG.md heading date
if [ ! -f CHANGELOG.md ]; then
	echo "ERROR: CHANGELOG.md not found" >&2
	exit 1
fi

if ! grep -Eq "^## \[${VERSION}\]" CHANGELOG.md; then
	echo "ERROR: CHANGELOG.md does not contain heading for version [${VERSION}]" >&2
	exit 1
fi

# Use a delimiter that will not collide with the pattern (the heading starts with "##")
sed_inplace "s|^(## \[${VERSION}\]) .*|\1 ${TODAY_UTC}|" CHANGELOG.md

# Update XML manifest dates
XML_SCANNED=0
XML_TOUCHED=0

while IFS= read -r -d '' FILE; do
	XML_SCANNED=$((XML_SCANNED + 1))

	BEFORE_HASH=""
	AFTER_HASH=""

	# Best-effort content hash for change detection without external deps.
	if command -v sha256sum >/dev/null 2>&1; then
		BEFORE_HASH="$(sha256sum "${FILE}" | awk '{print $1}')"
	fi

	# Use # delimiter because XML does not include # in these tags.
	sed -i "s#<creationDate>[^<]*</creationDate>#<creationDate>${TODAY_UTC}</creationDate>#g" "${FILE}" || true
	sed -i "s#<date>[^<]*</date>#<date>${TODAY_UTC}</date>#g" "${FILE}" || true
	sed -i "s#<buildDate>[^<]*</buildDate>#<buildDate>${TODAY_UTC}</buildDate>#g" "${FILE}" || true

	if [ -n "${BEFORE_HASH}" ]; then
		AFTER_HASH="$(sha256sum "${FILE}" | awk '{print $1}')"
		if [ "${BEFORE_HASH}" != "${AFTER_HASH}" ]; then
			XML_TOUCHED=$((XML_TOUCHED + 1))
		fi
	fi

done < <(
	find . -type f -name "*.xml" \
		-not -path "./.git/*" \
		-not -path "./.github/*" \
		-not -path "./dist/*" \
		-not -path "./node_modules/*" \
		-print0
)

# JSON report to stdout (workflow can capture or include in summary)
printf '{"today_utc":"%s","version":"%s","changelog":"%s","xml_scanned":%s,"xml_touched":%s}
' \
	"${TODAY_UTC}" \
	"${VERSION}" \
	"CHANGELOG.md" \
	"${XML_SCANNED}" \
	"${XML_TOUCHED}"

echo "Date normalization complete."
