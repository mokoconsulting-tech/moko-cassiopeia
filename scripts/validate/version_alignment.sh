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
# DEFGROUP: Script.Validate
# INGROUP: Version.Management
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/validate/version_alignment.sh
# VERSION: 01.00.00
# BRIEF: Checks that manifest version is documented in CHANGELOG.md
# NOTE: Ensures version consistency across repository
# ============================================================================

set -euo pipefail

# Validate that the package/manifest version is present in CHANGELOG.md
# Uses a safe, quoted heredoc for the embedded Python to avoid shell
# interpolation and CRLF termination issues.

if ! command -v python3 >/dev/null 2>&1; then
  echo "ERROR: python3 not found" >&2
  exit 1
fi

python3 - <<'PY'
import sys, re, json, glob

# Locate a likely manifest under src
candidates = [
    'src/templateDetails.xml',
    'src/manifest.xml'
]
manifest = None
for p in candidates:
    try:
        with open(p, 'r', encoding='utf-8') as fh:
            manifest = fh.read()
            break
    except FileNotFoundError:
        pass

if manifest is None:
    # Fallback: search for an XML file under src that contains a version attribute
    for fn in glob.glob('src/**/*.xml', recursive=True):
        try:
            with open(fn, 'r', encoding='utf-8') as fh:
                txt = fh.read()
                if 'version=' in txt:
                    manifest = txt
                    break
        except Exception:
            continue

if manifest is None:
    print('WARNING: No manifest found, skipping version alignment check')
    sys.exit(0)

m = re.search(r'version=["\']([0-9]+\.[0-9]+\.[0-9]+)["\']', manifest)
if not m:
    print('ERROR: could not find semantic version in manifest')
    sys.exit(2)

manifest_version = m.group(1)

try:
    with open('CHANGELOG.md', 'r', encoding='utf-8') as fh:
        changelog = fh.read()
except FileNotFoundError:
    print('ERROR: CHANGELOG.md not found')
    sys.exit(2)

if f'## [{manifest_version}]' not in changelog:
    print(f'ERROR: version {manifest_version} missing from CHANGELOG.md')
    sys.exit(2)

print(json.dumps({'status': 'ok', 'version': manifest_version}))
sys.exit(0)
PY
