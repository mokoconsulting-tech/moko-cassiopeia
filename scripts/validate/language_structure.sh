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
# PATH: /scripts/validate/language_structure.sh
# VERSION: 01.00.00
# BRIEF: Validates Joomla language structure under src/language and enforces folder and INI naming conventions.
# NOTE:
# ============================================================================

set -euo pipefail

SRC_DIR="${SRC_DIR:-src}"
LANG_ROOT="${LANG_ROOT:-${SRC_DIR}/language}"

json_escape() {
  python3 -c 'import json,sys; print(json.dumps(sys.argv[1]))' "$1"
}

[ -d "${SRC_DIR}" ] || {
  printf '{"status":"fail","error":%s}
' "$(json_escape "src directory missing")"
  exit 1
}

python3 - <<'PY' "${LANG_ROOT}"
import json
import sys
import re
from pathlib import Path

lang_root = Path(sys.argv[1])

# Language directory is optional for some extension types
if not lang_root.exists():
  print(json.dumps({"status":"ok","lang_root":str(lang_root),"languages":[],"warnings":["language_root_missing"]}, ensure_ascii=False))
  sys.exit(0)

if not lang_root.is_dir():
  print(json.dumps({"status":"fail","error":"language_root_not_directory","lang_root":str(lang_root)}, ensure_ascii=False))
  sys.exit(1)

lang_dirs = sorted([p for p in lang_root.iterdir() if p.is_dir()])

# Joomla language tags: en-GB, fr-FR, etc.
pattern = re.compile(r'^[a-z]{2}-[A-Z]{2}$')
invalid = [p.name for p in lang_dirs if not pattern.match(p.name)]

warnings = []

# Soft expectation: en-GB exists if any language directories exist
if lang_dirs and not (lang_root / 'en-GB').exists():
  warnings.append('en-GB_missing')

# Validate INI naming
missing_ini = []
nonmatching_ini = []

for d in lang_dirs:
  ini_files = [p for p in d.glob('*.ini') if p.is_file()]
  if not ini_files:
    missing_ini.append(d.name)
    continue
  for ini in ini_files:
    if not (ini.name.startswith(d.name + '.') or ini.name == f"{d.name}.ini"):
      nonmatching_ini.append(str(ini))

result = {
  "status": "ok",
  "lang_root": str(lang_root),
  "languages": [d.name for d in lang_dirs],
  "warnings": warnings,
}

# Hard failures
if invalid:
  result.update({"status":"fail","error":"invalid_language_tag_dir","invalid":invalid})
  print(json.dumps(result, ensure_ascii=False))
  sys.exit(1)

if nonmatching_ini:
  result.update({"status":"fail","error":"ini_name_mismatch","nonmatching_ini":nonmatching_ini[:50]})
  print(json.dumps(result, ensure_ascii=False))
  sys.exit(1)

if missing_ini:
  result.update({"status":"fail","error":"missing_ini_files","missing_ini":missing_ini})
  print(json.dumps(result, ensure_ascii=False))
  sys.exit(1)

print(json.dumps(result, ensure_ascii=False))
PY

echo "language_structure: ok"
