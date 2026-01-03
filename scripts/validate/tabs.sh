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
# PATH: /scripts/validate/tabs.sh
# VERSION: 01.00.00
# BRIEF: Detects tab characters in text files under src and fails if any are present.
# NOTE:
# ============================================================================

set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"

json_escape() {
	python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

[ -d "${SRC_DIR}" ] || {
	printf '{"status":"fail","error":%s}
' "$(json_escape "src directory missing")"
	exit 1
}

python3 - <<'PY' "${SRC_DIR}"
import json
import sys
from pathlib import Path

src = Path(sys.argv[1])
exclude_dirs = {'vendor','node_modules','dist','.git','build','tmp'}

hits = []
scanned = 0

for p in src.rglob('*'):
	if not p.is_file():
		continue
	if any(part in exclude_dirs for part in p.parts):
		continue
	try:
		data = p.read_bytes()
	except Exception:
		continue
	if b'
