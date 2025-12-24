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
# PATH: /scripts/update_dates.sh
# VERSION: 01.00.00
# BRIEF: Normalize release dates across manifests and CHANGELOG using a single authoritative UTC date.
# NOTE: Repo-controlled script only. No fallback logic. CI-fatal on malformed inputs.

set -euo pipefail

TODAY_UTC="${1:-}"
VERSION="${2:-}"

if [ -z "${TODAY_UTC}" ] || [ -z "${VERSION}" ]; then
	echo "ERROR: Usage: update_dates.sh <YYYY-MM-DD> <VERSION>" >&2
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

echo "Normalizing dates to ${TODAY_UTC} for version ${VERSION}"

# Update CHANGELOG.md heading date
if [ -f CHANGELOG.md ]; then
	if grep -Eq "^## \\[${VERSION}\\]" CHANGELOG.md; then
		sed -i -E "s#^(## \\[${VERSION}\\]) .*#\\1 ${TODAY_UTC}#" CHANGELOG.md
	else
		echo "ERROR: CHANGELOG.md does not contain heading for version [${VERSION}]" >&2
		exit 1
	fi
else
	echo "ERROR: CHANGELOG.md not found" >&2
	exit 1
fi

# Update XML manifest dates
find . -type f -name "*.xml" \
	-not -path "./.git/*" \
	-not -path "./.github/*" \
	-print0 | while IFS= read -r -d '' FILE; do
		sed -i "s#<creationDate>[^<]*</creationDate>#<creationDate>${TODAY_UTC}</creationDate>#g" "${FILE}" || true
		sed -i "s#<date>[^<]*</date>#<date>${TODAY_UTC}</date>#g" "${FILE}" || true
		sed -i "s#<buildDate>[^<]*</buildDate>#<buildDate>${TODAY_UTC}</buildDate>#g" "${FILE}" || true
	done

echo "Date normalization complete."
