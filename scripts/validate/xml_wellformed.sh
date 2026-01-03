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
# INGROUP: XML.Validation
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/validate/xml_wellformed.sh
# VERSION: 01.00.00
# BRIEF: Validates that all XML files are well-formed
# NOTE: Uses Python ElementTree for portable XML parsing
# ============================================================================

set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"

json_escape() {
  python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
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

[ -d "${SRC_DIR}" ] || fail "src directory missing" "\"src_dir\":$(json_escape "${SRC_DIR}")"

python3 - <<'PY' "${SRC_DIR}"
import json
import sys
from pathlib import Path
import xml.etree.ElementTree as ET

src = Path(sys.argv[1])
xml_files = sorted([p for p in src.rglob('*.xml') if p.is_file()])

bad = []
for p in xml_files:
  try:
    ET.parse(p)
  except Exception as e:
    bad.append({"path": str(p), "error": str(e)})

if bad:
  print(json.dumps({
    "status": "fail",
    "error": "XML parse failed",
    "src_dir": str(src),
    "xml_count": len(xml_files),
    "bad_count": len(bad),
    "bad": bad[:25],
  }, ensure_ascii=False))
  sys.exit(1)

print(json.dumps({
  "status": "ok",
  "src_dir": str(src),
  "xml_count": len(xml_files),
}, ensure_ascii=False))
PY

echo "xml_wellformed: ok"
