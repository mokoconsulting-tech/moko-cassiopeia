#!/usr/bin/env python3
"""
Run all validation scripts.

Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

This file is part of a Moko Consulting project.

SPDX-License-Identifier: GPL-3.0-or-later

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program (./LICENSE.md).

FILE INFORMATION
DEFGROUP: Script.Run
INGROUP: Validation.Runner
REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
PATH: /scripts/run/validate_all.py
VERSION: 01.00.00
BRIEF: Run all validation scripts
"""

import subprocess
import sys
from pathlib import Path
from typing import Tuple

# Add lib directory to path
sys.path.insert(0, str(Path(__file__).parent.parent / "lib"))

try:
	import common
except ImportError:
	print("ERROR: Cannot import required libraries", file=sys.stderr)
	sys.exit(1)


# Required validation scripts (must pass)
REQUIRED_SCRIPTS = [
	"scripts/validate/manifest.py",
	"scripts/validate/xml_wellformed.py",
	"scripts/validate/workflows.py",
]

# Optional validation scripts (failures are warnings)
OPTIONAL_SCRIPTS = [
	"scripts/validate/changelog.py",
	"scripts/validate/language_structure.py",
	"scripts/validate/license_headers.py",
	"scripts/validate/no_secrets.py",
	"scripts/validate/paths.py",
	"scripts/validate/php_syntax.py",
	"scripts/validate/tabs.py",
	"scripts/validate/version_alignment.py",
	"scripts/validate/version_hierarchy.py",
]


def run_validation_script(script_path: str) -> Tuple[bool, str]:
	"""
	Run a validation script.
	
	Args:
		script_path: Path to script
		
	Returns:
		Tuple of (success, output)
	"""
	script = Path(script_path)
	
	if not script.exists():
		return (False, f"Script not found: {script_path}")
	
	try:
		result = subprocess.run(
			["python3", str(script)],
			capture_output=True,
			text=True,
			check=False
		)
		
		output = result.stdout + result.stderr
		success = result.returncode == 0
		
		return (success, output)
	except Exception as e:
		return (False, f"Error running script: {e}")


def main() -> int:
	"""Main entry point."""
	common.log_section("Running All Validations")
	print()
	
	total_passed = 0
	total_failed = 0
	total_skipped = 0
	
	# Run required scripts
	common.log_info("=== Required Validations ===")
	print()
	
	for script in REQUIRED_SCRIPTS:
		script_name = Path(script).name
		common.log_info(f"Running {script_name}...")
		
		success, output = run_validation_script(script)
		
		if success:
			common.log_success(f"✓ {script_name} passed")
			total_passed += 1
		else:
			common.log_error(f"✗ {script_name} FAILED")
			if output:
				print(output)
			total_failed += 1
		print()
	
	# Run optional scripts
	common.log_info("=== Optional Validations ===")
	print()
	
	for script in OPTIONAL_SCRIPTS:
		script_name = Path(script).name
		
		if not Path(script).exists():
			common.log_warn(f"⊘ {script_name} not found (skipped)")
			total_skipped += 1
			continue
		
		common.log_info(f"Running {script_name}...")
		
		success, output = run_validation_script(script)
		
		if success:
			common.log_success(f"✓ {script_name} passed")
			total_passed += 1
		else:
			common.log_warn(f"⚠ {script_name} failed (optional)")
			if output:
				print(output[:500])  # Limit output
			total_failed += 1
		print()
	
	# Summary
	common.log_section("Validation Summary")
	common.log_kv("Total Passed", str(total_passed))
	common.log_kv("Total Failed", str(total_failed))
	common.log_kv("Total Skipped", str(total_skipped))
	print()
	
	# Check if any required validations failed
	required_failed = sum(
		1 for script in REQUIRED_SCRIPTS
		if Path(script).exists() and not run_validation_script(script)[0]
	)
	
	if required_failed > 0:
		common.log_error(f"{required_failed} required validation(s) failed")
		return 1
	
	common.log_success("All required validations passed!")
	
	if total_failed > 0:
		common.log_warn(f"{total_failed} optional validation(s) failed")
	
	return 0


if __name__ == "__main__":
	sys.exit(main())
