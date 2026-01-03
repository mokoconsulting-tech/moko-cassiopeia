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
FILE: ./scripts/ENTERPRISE.md
VERSION: 01.00.00
BRIEF: Enterprise-grade scripting standards and best practices
-->

# Enterprise Standards for Scripts

This document defines the enterprise-grade standards and best practices
implemented across all automation scripts in this repository.

## Table of Contents

- [Overview](#overview)
- [Core Principles](#core-principles)
- [Script Structure](#script-structure)
- [Error Handling](#error-handling)
- [Logging and Observability](#logging-and-observability)
- [Security Standards](#security-standards)
- [Dependency Management](#dependency-management)
- [Exit Codes](#exit-codes)
- [Documentation Requirements](#documentation-requirements)
- [Testing and Validation](#testing-and-validation)
- [Operational Considerations](#operational-considerations)

## Overview

All scripts in this repository follow enterprise-grade standards to ensure:
- **Reliability**: Predictable behavior in all environments
- **Security**: Protection against vulnerabilities and credential exposure
- **Observability**: Clear logging and error reporting
- **Maintainability**: Consistent patterns and documentation
- **Portability**: Cross-platform compatibility

## Core Principles

### 1. Fail Fast, Fail Clearly

Scripts must fail immediately when encountering errors and provide clear,
actionable error messages.

```bash
set -euo pipefail  # Required at top of all bash scripts
```

- `-e`: Exit on first error
- `-u`: Exit on undefined variable reference
- `-o pipefail`: Propagate pipeline failures

### 2. Zero Assumptions

- Always validate inputs
- Check for required dependencies
- Verify file/directory existence before access
- Never assume environment state

### 3. Idempotency Where Possible

Scripts should be safe to run multiple times without causing harm or
inconsistency.

### 4. Least Privilege

Scripts should:
- Never require root unless absolutely necessary
- Use minimal file system permissions
- Validate before modifying files

## Script Structure

### Standard Header Template

Every script must include:

```bash
#!/usr/bin/env bash

# ============================================================================
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# This file is part of a Moko Consulting project.
#
# SPDX-License-Identifier: GPL-3.0-or-later
#
# [Full license text...]
# ============================================================================

# ============================================================================
# FILE INFORMATION
# ============================================================================
# DEFGROUP: Script.Category
# INGROUP: Subcategory
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# PATH: /scripts/path/to/script.sh
# VERSION: XX.XX.XX
# BRIEF: One-line description of script purpose
# NOTE: Additional context or usage notes
# ============================================================================

set -euo pipefail
```

### Usage Function

User-facing scripts must provide a usage/help function:

```bash
usage() {
cat <<-USAGE
Usage: $0 [OPTIONS] <ARGS>

Description of what the script does.

Options:
  -h, --help    Show this help message
  -v, --verbose Enable verbose output

Arguments:
  ARG1    Description of first argument
  ARG2    Description of second argument

Examples:
  $0 example_value
  $0 -v example_value

Exit codes:
  0 - Success
  1 - Error
  2 - Invalid arguments

USAGE
exit 0
}
```

### Argument Parsing

```bash
# Parse arguments
if [ "${1:-}" = "-h" ] || [ "${1:-}" = "--help" ]; then
    usage
fi

[ $# -ge 1 ] || usage
```

### Library Sourcing

```bash
SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# Check dependencies
check_dependencies python3 git
```

## Error Handling

### Error Messages

Error messages must be:
- **Clear**: Explain what went wrong
- **Actionable**: Tell user how to fix it
- **Contextual**: Include relevant details
- **Verbose**: Provide comprehensive information by default

```bash
# Bad
die "Error"

# Good - Verbose with context and solutions
die "Required file not found: ${CONFIG_FILE}
    
    Current directory: $(pwd)
    Expected location: ./config/${CONFIG_FILE}
    
    To fix:
      1. Run setup script: ./scripts/setup.sh
      2. Or create the file manually: touch config/${CONFIG_FILE}
    "
```

### Error Output

- Always show full error output for failed operations
- Include line numbers and file paths
- Show error summaries with troubleshooting steps
- Provide installation guides for missing dependencies

Example verbose error from validation:
```
ERROR: PHP syntax validation failed
Files checked: 90
Files with errors: 2

Failed files and errors:
  File: src/test.php
  Error: Parse error: syntax error, unexpected '}' in src/test.php on line 42

  File: src/helper.php  
  Error: Parse error: syntax error, unexpected T_STRING in src/helper.php on line 15

To fix: Review and correct the syntax errors in the files listed above.
Run 'php -l <filename>' on individual files for detailed error messages.
```

### Validation

```bash
# Validate inputs
validate_version() {
    local v="$1"
    if ! printf '%s' "$v" | grep -Eq '^[0-9]+\.[0-9]+\.[0-9]+$'; then
        die "Invalid version format: $v (expected X.Y.Z)"
    fi
}

# Check file existence
assert_file_exists "${MANIFEST}" || die "Manifest not found: ${MANIFEST}"

# Verify directory
assert_dir_exists "${SRC_DIR}" || die "Source directory missing: ${SRC_DIR}"
```

## Logging and Observability

### Logging Functions

Use standard logging functions from `lib/common.sh`:

```bash
log_info "Starting process..."      # Informational messages
log_warn "Configuration missing"    # Warnings (non-fatal)
log_error "Validation failed"       # Errors (fatal)
die "Critical error occurred"       # Fatal with exit
```

### Timestamps

Include timestamps for audit trails:

```bash
log_info "Start time: $(log_timestamp)"
# ... work ...
log_info "End time: $(log_timestamp)"
```

### Structured Output

For machine-readable output, use JSON:

```bash
printf '{"status":"ok","files_checked":%s}\n' "${count}"
```

### Progress Reporting

For long-running operations:

```bash
log_section "Phase 1: Validation"
log_step "Checking manifests..."
log_success "✓ Manifests valid"
log_kv "Files processed" "${count}"
```

## Security Standards

### 1. No Hardcoded Secrets

- Never commit credentials
- Use environment variables for sensitive data
- Validate against secret patterns

### 2. Input Sanitization

```bash
# Validate user input
if [[ "${input}" =~ [^a-zA-Z0-9._-] ]]; then
    die "Invalid input: contains disallowed characters"
fi
```

### 3. File Operations

```bash
# Use explicit paths
FILE="/full/path/to/file"

# Avoid user-controlled paths without validation
# Validate before rm/mv operations
```

### 4. Command Injection Prevention

```bash
# Use arrays for command arguments
args=("$file1" "$file2")
command "${args[@]}"

# Quote all variables
grep "${pattern}" "${file}"
```

## Dependency Management

### Required Dependencies Check

```bash
# At script start
check_dependencies python3 git sed

# Or inline
require_cmd xmllint || die "xmllint not available"
```

### Graceful Degradation

When optional dependencies are missing:

```bash
if ! command -v php >/dev/null 2>&1; then
    log_warn "PHP not available, skipping syntax check"
    exit 0
fi
```

## Exit Codes

Standard exit codes across all scripts:

| Code | Meaning | Usage |
|------|---------|-------|
| 0 | Success | All operations completed successfully |
| 1 | Error | Fatal error occurred |
| 2 | Invalid arguments | Bad command-line arguments or usage |

```bash
# Success
exit 0

# Fatal error
die "Error message"  # Exits with code 1

# Invalid arguments
usage  # Exits with code 0 (help shown)
# or
log_error "Invalid argument"
exit 2
```

## Documentation Requirements

### 1. Script Headers

Must include:
- Copyright notice
- SPDX license identifier
- FILE INFORMATION section
- Version number
- Brief description

### 2. Inline Comments

Use comments for:
- Complex logic explanation
- Why decisions were made (not what code does)
- Security considerations
- Performance notes

```bash
# Use git ls-files for performance vs. find
files=$(git ls-files '*.yml' '*.yaml')

# NOTE: Binary detection prevents corrupting image files
if file --mime-type "$f" | grep -q '^application/'; then
    continue
fi
```

### 3. README Documentation

Update `scripts/README.md` when:
- Adding new scripts
- Changing script behavior
- Adding new library functions

## Testing and Validation

### Self-Testing

Scripts should validate their own requirements:

```bash
# Validate environment
[ -d "${SRC_DIR}" ] || die "Source directory not found"

# Validate configuration
[ -n "${VERSION}" ] || die "VERSION must be set"
```

### Script Health Checking

Use the script health checker to validate all scripts follow standards:

```bash
./scripts/run/script_health.sh        # Check all scripts
./scripts/run/script_health.sh -v     # Verbose mode with details
```

The health checker validates:
- Copyright headers present
- SPDX license identifiers
- FILE INFORMATION sections
- Error handling (set -euo pipefail)
- Executable permissions

### Integration Testing

Run validation suite before commits:

```bash
./scripts/run/validate_all.sh
```

### Smoke Testing

Basic health checks:

```bash
./scripts/run/smoke_test.sh
```

## Operational Considerations

### 1. Timeout Handling

For long-running operations:

```bash
run_with_timeout 300 long_running_command
```

### 2. Cleanup

Use traps for cleanup:

```bash
cleanup() {
    rm -f "${TEMP_FILE}"
}
trap cleanup EXIT
```

### 3. Lock Files

For singleton operations:

```bash
LOCK_FILE="/tmp/script.lock"
if [ -f "${LOCK_FILE}" ]; then
    die "Script already running (lock file exists)"
fi
touch "${LOCK_FILE}"
trap "rm -f ${LOCK_FILE}" EXIT
```

### 4. Signal Handling

```bash
handle_interrupt() {
    log_warn "Interrupted by user"
    cleanup
    exit 130
}
trap handle_interrupt INT TERM
```

### 5. Dry Run Mode

For destructive operations:

```bash
DRY_RUN="${DRY_RUN:-false}"

if [ "${DRY_RUN}" = "true" ]; then
    log_info "DRY RUN: Would execute: $command"
else
    "$command"
fi
```

## CI/CD Integration

### Environment Variables

Scripts should respect:

```bash
CI="${CI:-false}"              # Running in CI
VERBOSE="${VERBOSE:-false}"    # Verbose output
DEBUG="${DEBUG:-false}"        # Debug mode
```

### CI-Specific Behavior

```bash
if is_ci; then
    # CI-specific settings
    set -x  # Echo commands for debugging
fi
```

### Job Summaries

For GitHub Actions:

```bash
if [ -n "${GITHUB_STEP_SUMMARY:-}" ]; then
    echo "### Validation Results" >> "$GITHUB_STEP_SUMMARY"
    echo "Status: PASSED" >> "$GITHUB_STEP_SUMMARY"
fi
```

## Review Checklist

Before committing new or modified scripts:

- [ ] Includes proper copyright header
- [ ] Uses `set -euo pipefail`
- [ ] Has usage/help function (if user-facing)
- [ ] Validates all inputs
- [ ] Checks dependencies with `check_dependencies`
- [ ] Uses structured logging (`log_info`, `log_error`, etc.)
- [ ] Includes timestamps for audit trails
- [ ] Returns appropriate exit codes (0=success, 1=error, 2=invalid args)
- [ ] Includes inline comments for complex logic
- [ ] Documented in scripts/README.md
- [ ] Tested locally
- [ ] Passes `./scripts/run/script_health.sh`
- [ ] Passes all validation checks (`./scripts/run/validate_all.sh`)
- [ ] Passes `shellcheck` (if available)

Quick validation command:
```bash
# Run all checks
./scripts/run/script_health.sh && ./scripts/run/validate_all.sh
```

## Version History

| Version | Date       | Description |
| ------- | ---------- | ----------- |
| 01.00.00 | 2025-01-03 | Initial enterprise standards documentation |

## Metadata

- **Document:** scripts/ENTERPRISE.md
- **Repository:** https://github.com/mokoconsulting-tech/moko-cassiopeia
- **Version:** 01.00.00
- **Status:** Active
