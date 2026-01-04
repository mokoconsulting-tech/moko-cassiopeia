<!-- Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

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
along with this program. If not, see https://www.gnu.org/licenses/ .

FILE INFORMATION
DEFGROUP: Moko-Cassiopeia.Documentation
INGROUP: Scripts.Documentation
REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
FILE: ./scripts/README.md
VERSION: 01.00.00
BRIEF: Documentation for repository automation scripts
-->

# Scripts Directory

This directory contains automation scripts for repository management, validation,
and release processes for the Moko-Cassiopeia Joomla template.

## Directory Structure

```
scripts/
├── fix/           # Scripts that automatically fix common issues
├── lib/           # Shared library functions
├── release/       # Release automation scripts
├── run/           # Execution and testing scripts
└── validate/      # Validation and linting scripts
```

## Library Files (`lib/`)

### `common.sh`
Core utilities used by all scripts:
- Environment normalization
- Logging functions (`log_info`, `log_warn`, `log_error`, `die`)
- Validation helpers (`assert_file_exists`, `assert_dir_exists`)
- JSON utilities (`json_escape`, `json_output`)
- Path helpers (`script_root`, `normalize_path`)

Usage:
```bash
. "$(dirname "$0")/../lib/common.sh"
log_info "Starting process..."
```

### `joomla_manifest.sh`
Joomla manifest parsing utilities:
- `find_manifest <src_dir>` - Find primary Joomla manifest XML
- `get_manifest_version <manifest>` - Extract version from manifest
- `get_manifest_name <manifest>` - Extract extension name
- `get_manifest_type <manifest>` - Extract extension type

Usage:
```bash
. "${SCRIPT_DIR}/lib/joomla_manifest.sh"
MANIFEST="$(find_manifest src)"
VERSION="$(get_manifest_version "${MANIFEST}")"
```

### `logging.sh`
Enhanced logging with structured output:
- Colored output support (when in terminal)
- Log levels: `log_debug`, `log_success`, `log_step`
- Structured logging: `log_kv`, `log_item`, `log_section`

Usage:
```bash
. "${SCRIPT_DIR}/lib/logging.sh"
log_section "Starting validation"
log_kv "Version" "${VERSION}"
log_success "All checks passed"
```

## Validation Scripts (`validate/`)

These scripts validate repository structure, code quality, and compliance.
They are typically run in CI pipelines.

### `manifest.sh`
Validates Joomla manifest XML structure and required fields.

### `version_alignment.sh`
Checks that manifest version is documented in CHANGELOG.md.

### `php_syntax.sh`
Validates PHP syntax using `php -l` on all PHP files.

### `xml_wellformed.sh`
Validates that all XML files are well-formed.

### `tabs.sh`
Detects tab characters in source files (enforces spaces).

### `paths.sh`
Detects Windows-style path separators (backslashes).

### `no_secrets.sh`
Scans for accidentally committed secrets and credentials.

### `license_headers.sh`
Checks that source files contain SPDX license identifiers.

### `language_structure.sh`
Validates Joomla language directory structure and INI files.

### `changelog.sh`
Validates CHANGELOG.md structure and version entries.

### `version_hierarchy.sh`
Validates version hierarchy across branch prefixes:
- Checks for version conflicts across dev/, rc/, and version/ branches
- Ensures no version exists in multiple priority levels simultaneously
- Reports any violations of the hierarchy rules

Usage:
```bash
./scripts/validate/version_hierarchy.sh
```

### `workflows.sh`
Validates GitHub Actions workflow files:
- Checks YAML syntax using Python's yaml module
- Ensures no tab characters are present
- Validates required workflow structure (name, on, jobs)

Usage:
```bash
./scripts/validate/workflows.sh
```

## Fix Scripts (`fix/`)

These scripts automatically fix common issues detected by validation scripts.

### `tabs.sh`
Replaces tab characters with spaces in YAML files.

Usage:
```bash
./scripts/fix/tabs.sh
```

### `paths.sh`
Normalizes Windows-style path separators to forward slashes.

Usage:
```bash
./scripts/fix/paths.sh [directory]
```

### `versions.sh`
Updates version numbers across repository files.

Usage:
```bash
./scripts/fix/versions.sh <VERSION>
```

Example:
```bash
./scripts/fix/versions.sh 3.5.0
```

Updates:
- Manifest XML `<version>` tag
- `package.json` version field
- Version references in README.md

## Release Scripts (`release/`)

Scripts for release automation and version management.

### `update_changelog.sh`
Inserts a new version entry in CHANGELOG.md.

Usage:
```bash
./scripts/release/update_changelog.sh <VERSION>
```

Example:
```bash
./scripts/release/update_changelog.sh 03.05.00
```

### `update_dates.sh`
Normalizes release dates across manifests and CHANGELOG.

Usage:
```bash
./scripts/release/update_dates.sh <YYYY-MM-DD> <VERSION>
```

Example:
```bash
./scripts/release/update_dates.sh 2025-01-15 03.05.00
```

### `package_extension.sh`
Package the Joomla template as a distributable ZIP file.

Usage:
```bash
./scripts/release/package_extension.sh [output_dir] [version]
```

Parameters:
- `output_dir` (optional): Output directory for ZIP file (default: `dist`)
- `version` (optional): Version string (default: extracted from manifest)

Examples:
```bash
# Package with defaults (dist directory, auto-detect version)
./scripts/release/package_extension.sh

# Package to specific directory with version
./scripts/release/package_extension.sh /tmp/packages 3.5.0

# Package to dist with specific version
./scripts/release/package_extension.sh dist 3.5.0
```

Features:
- Automatically detects extension type from manifest
- Excludes development files (node_modules, vendor, tests, etc.)
- Validates manifest before packaging
- Creates properly structured Joomla installation package
- Outputs JSON status for automation

## Run Scripts (`run/`)

Execution and testing scripts.

### `smoke_test.sh`
Runs basic smoke tests to verify repository health:
- Repository structure validation
- Manifest validation
- Version alignment checks
- PHP syntax validation

Usage:
```bash
./scripts/run/smoke_test.sh
```

Example output:
```
INFO: Running smoke tests for Moko-Cassiopeia repository
INFO: Checking repository structure...
INFO: ✓ Repository structure valid
INFO: Checking Joomla manifest...
INFO: Found manifest: src/templates/templateDetails.xml
INFO: Extension: moko-cassiopeia (template) v03.05.00
INFO: ✓ Manifest validation passed
INFO: =========================================
INFO: Smoke tests completed successfully
INFO: =========================================
```

### `validate_all.sh`
Runs all validation scripts and provides a comprehensive report:
- Executes all required validation checks
- Executes all optional validation checks
- Provides colored output with pass/fail indicators
- Returns summary with counts
- Supports verbose mode for detailed output

Usage:
```bash
./scripts/run/validate_all.sh        # Standard mode
./scripts/run/validate_all.sh -v     # Verbose mode (shows all output)
```

Example output:
```
=== Repository Validation Suite ===
INFO: Running all validation checks...

=== Required Checks ===
[SUCCESS] ✓ manifest
[SUCCESS] ✓ xml_wellformed

=== Optional Checks ===
[SUCCESS] ✓ no_secrets
[SUCCESS] ✓ php_syntax
WARN: ✗ tabs (warnings/issues found - run with -v for details)

=== Validation Summary ===
  Required checks passed: 2/2
  Optional checks passed: 2/8
[SUCCESS] SUCCESS: All required checks passed
```

### `script_health.sh`
Validates that all scripts follow enterprise standards:
- Checks for copyright headers
- Validates SPDX license identifiers
- Ensures FILE INFORMATION sections are present
- Verifies error handling (set -euo pipefail)
- Checks executable permissions

Usage:
```bash
./scripts/run/script_health.sh       # Standard mode
./scripts/run/script_health.sh -v    # Verbose mode (shows details)
```

Example output:
```
=== Script Health Summary ===
  Total scripts checked: 21
  Missing copyright: 0
  Missing SPDX identifier: 0
  Missing FILE INFORMATION: 0
  Missing error handling: 0
  Not executable: 0
[SUCCESS] SUCCESS: All scripts follow enterprise standards
```

### `list_versions.sh`
Lists all version branches organized by prefix:
- Displays dev/, rc/, and version/ branches
- Shows versions sorted in ascending order
- Provides a summary count of each branch type

Usage:
```bash
./scripts/run/list_versions.sh
```

Example output:
```
========================================
Version Branches Summary
========================================

📦 Stable Versions (version/)
----------------------------------------
  ✓ version/03.00.00
  ✓ version/03.01.00

🔧 Release Candidates (rc/)
----------------------------------------
  ➜ rc/03.02.00

🚧 Development Versions (dev/)
----------------------------------------
  ⚡ dev/03.05.00
  ⚡ dev/04.00.00

========================================
Total: 2 stable, 1 RC, 2 dev
========================================
```

### `check_version.sh`
Checks if a version can be created in a specific branch prefix:
- Validates against version hierarchy rules
- Checks for existing branches
- Reports conflicts with higher priority branches

Usage:
```bash
./scripts/run/check_version.sh <BRANCH_PREFIX> <VERSION>
```

Examples:
```bash
./scripts/run/check_version.sh dev/ 03.05.00
./scripts/run/check_version.sh rc/ 03.01.00
./scripts/run/check_version.sh version/ 02.00.00
```

Exit codes:
- 0: Version can be created (no conflicts)
- 1: Version cannot be created (conflicts found)
- 2: Invalid arguments

## Best Practices

### Enterprise Standards

For comprehensive enterprise-grade scripting standards, see
[ENTERPRISE.md](./ENTERPRISE.md).

Key highlights:
- **Error Handling**: Fail fast with clear, actionable messages
- **Security**: Input validation, no hardcoded secrets
- **Logging**: Structured output with timestamps
- **Portability**: Cross-platform compatibility
- **Documentation**: Usage functions and inline comments

### Writing New Scripts

1. **Use the library functions**:
   ```bash
   #!/usr/bin/env bash
   set -euo pipefail
   
   SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
   . "${SCRIPT_DIR}/lib/common.sh"
   ```

2. **Include proper headers**:
   - Copyright notice
   - SPDX license identifier
   - FILE INFORMATION section with DEFGROUP, INGROUP, PATH, VERSION, BRIEF

3. **Follow error handling conventions**:
   ```bash
   [ -f "${FILE}" ] || die "File not found: ${FILE}"
   require_cmd python3
   ```

4. **Use structured output**:
   ```bash
   log_info "Starting process..."
   log_success "Process completed"
   ```

5. **Make scripts executable**:
   ```bash
   chmod +x scripts/new-script.sh
   ```

### Testing Scripts Locally

Run all validation scripts:
```bash
./scripts/run/validate_all.sh
```

Run individual validation scripts:
```bash
./scripts/validate/manifest.sh
./scripts/validate/php_syntax.sh
./scripts/validate/tabs.sh
```

Run smoke tests:
```bash
./scripts/run/smoke_test.sh
```

### CI Integration

Scripts are automatically executed in GitHub Actions workflows:
- `.github/workflows/ci.yml` - Continuous integration
- `.github/workflows/repo_health.yml` - Repository health checks

## Enterprise Features

The scripts in this repository follow enterprise-grade standards:

### Dependency Checking

Scripts validate required dependencies at startup using `check_dependencies`:

```bash
check_dependencies python3 git sed
```

### Timestamp Logging

All major operations include timestamps for audit trails:

```bash
log_info "Start time: $(log_timestamp)"
```

### Usage Documentation

All user-facing scripts include comprehensive help:

```bash
./scripts/run/validate_all.sh --help
./scripts/fix/versions.sh --help
```

### Standardized Exit Codes

- `0` - Success
- `1` - Fatal error
- `2` - Invalid arguments

### Enhanced Error Messages

Clear, actionable error messages with context:

```bash
die "Required file not found: ${CONFIG_FILE}. Run setup first."
```

See [ENTERPRISE.md](./ENTERPRISE.md) for complete standards documentation.

## Version History

| Version | Date       | Description                           |
| ------- | ---------- | ------------------------------------- |
| 01.00.00 | 2025-01-03 | Initial scripts documentation created |

## Metadata

- **Document:** scripts/README.md
- **Repository:** https://github.com/mokoconsulting-tech/moko-cassiopeia
- **Version:** 01.00.00
- **Status:** Active
