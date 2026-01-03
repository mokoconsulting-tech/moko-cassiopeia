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

Usage:
```bash
./scripts/run/validate_all.sh
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
WARN: ✗ tabs (warnings/issues found)

=== Validation Summary ===
  Required checks passed: 2/2
  Optional checks passed: 2/8
[SUCCESS] SUCCESS: All required checks passed
```

## Best Practices

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

## Version History

| Version | Date       | Description                           |
| ------- | ---------- | ------------------------------------- |
| 01.00.00 | 2025-01-03 | Initial scripts documentation created |

## Metadata

- **Document:** scripts/README.md
- **Repository:** https://github.com/mokoconsulting-tech/moko-cassiopeia
- **Version:** 01.00.00
- **Status:** Active
