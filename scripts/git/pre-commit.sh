#!/usr/bin/env bash
# Pre-commit hook script for Moko Cassiopeia
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# SPDX-License-Identifier: GPL-3.0-or-later
#
# FILE INFORMATION
# DEFGROUP: Moko-Cassiopeia.Scripts
# INGROUP: Scripts.Git
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# FILE: ./scripts/git/pre-commit.sh
# VERSION: 01.00.00
# BRIEF: Pre-commit hook for local validation

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
. "${SCRIPT_DIR}/lib/common.sh"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log_success() {
    echo -e "${GREEN}✓${NC} $*"
}

log_warning() {
    echo -e "${YELLOW}⚠${NC} $*"
}

log_error() {
    echo -e "${RED}✗${NC} $*"
}

log_header() {
    echo ""
    echo "================================"
    echo "$*"
    echo "================================"
}

# Parse arguments
SKIP_TESTS=false
SKIP_QUALITY=false
QUICK_MODE=false

while [[ $# -gt 0 ]]; do
    case $1 in
        --skip-tests)
            SKIP_TESTS=true
            shift
            ;;
        --skip-quality)
            SKIP_QUALITY=true
            shift
            ;;
        --quick)
            QUICK_MODE=true
            shift
            ;;
        *)
            echo "Unknown option: $1"
            echo "Usage: pre-commit.sh [--skip-tests] [--skip-quality] [--quick]"
            exit 1
            ;;
    esac
done

log_header "Pre-commit Validation"

# Get list of staged files
STAGED_FILES=$(git diff --cached --name-only --diff-filter=ACMR)

if [ -z "$STAGED_FILES" ]; then
    log_warning "No staged files to check"
    exit 0
fi

echo "Checking staged files:"
echo "$STAGED_FILES" | sed 's/^/  - /'
echo ""

# Track failures
FAILURES=0

# Check 1: PHP Syntax
log_header "Checking PHP Syntax"
PHP_FILES=$(echo "$STAGED_FILES" | grep '\.php$' || true)

if [ -n "$PHP_FILES" ]; then
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            if php -l "$file" > /dev/null 2>&1; then
                log_success "PHP syntax OK: $file"
            else
                log_error "PHP syntax error: $file"
                php -l "$file"
                FAILURES=$((FAILURES + 1))
            fi
        fi
    done <<< "$PHP_FILES"
else
    echo "  No PHP files to check"
fi

# Check 2: XML Well-formedness
log_header "Checking XML Files"
XML_FILES=$(echo "$STAGED_FILES" | grep '\.xml$' || true)

if [ -n "$XML_FILES" ]; then
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            if xmllint --noout "$file" 2>/dev/null; then
                log_success "XML well-formed: $file"
            else
                log_error "XML malformed: $file"
                xmllint --noout "$file" || true
                FAILURES=$((FAILURES + 1))
            fi
        fi
    done <<< "$XML_FILES"
else
    echo "  No XML files to check"
fi

# Check 3: YAML Syntax
log_header "Checking YAML Files"
YAML_FILES=$(echo "$STAGED_FILES" | grep -E '\.(yml|yaml)$' || true)

if [ -n "$YAML_FILES" ]; then
    while IFS= read -r file; do
        if [ -f "$file" ]; then
            # Use printf to safely pass the file path, avoiding injection
            if python3 -c "import sys, yaml; yaml.safe_load(open(sys.argv[1]))" "$file" 2>/dev/null; then
                log_success "YAML valid: $file"
            else
                log_error "YAML invalid: $file"
                python3 -c "import sys, yaml; yaml.safe_load(open(sys.argv[1]))" "$file" || true
                FAILURES=$((FAILURES + 1))
            fi
        fi
    done <<< "$YAML_FILES"
else
    echo "  No YAML files to check"
fi

# Check 4: Trailing Whitespace
log_header "Checking for Trailing Whitespace"
TEXT_FILES=$(echo "$STAGED_FILES" | grep -vE '\.(png|jpg|jpeg|gif|svg|ico|zip|gz|woff|woff2|ttf)$' || true)

if [ -n "$TEXT_FILES" ]; then
    TRAILING_WS=$(echo "$TEXT_FILES" | xargs grep -n '[[:space:]]$' 2>/dev/null || true)
    if [ -n "$TRAILING_WS" ]; then
        log_warning "Files with trailing whitespace found:"
        echo "$TRAILING_WS" | sed 's/^/  /'
        echo ""
        echo "  Run: sed -i 's/[[:space:]]*$//' <file> to fix"
    else
        log_success "No trailing whitespace"
    fi
else
    echo "  No text files to check"
fi

# Check 5: SPDX License Headers (if not quick mode)
if [ "$QUICK_MODE" = false ]; then
    log_header "Checking SPDX License Headers"
    SOURCE_FILES=$(echo "$STAGED_FILES" | grep -E '\.(php|sh|js|ts|css)$' || true)
    
    if [ -n "$SOURCE_FILES" ]; then
        MISSING_SPDX=""
        while IFS= read -r file; do
            if [ -f "$file" ]; then
                if ! head -n 20 "$file" | grep -q 'SPDX-License-Identifier:'; then
                    MISSING_SPDX="${MISSING_SPDX}  - ${file}\n"
                fi
            fi
        done <<< "$SOURCE_FILES"
        
        if [ -n "$MISSING_SPDX" ]; then
            log_warning "Files missing SPDX license header:"
            echo -e "$MISSING_SPDX"
        else
            log_success "All source files have SPDX headers"
        fi
    else
        echo "  No source files to check"
    fi
fi

# Check 6: No Secrets
log_header "Checking for Secrets"
if [ -x "${SCRIPT_DIR}/validate/no_secrets.sh" ]; then
    if "${SCRIPT_DIR}/validate/no_secrets.sh" > /dev/null 2>&1; then
        log_success "No secrets detected"
    else
        log_error "Potential secrets detected!"
        "${SCRIPT_DIR}/validate/no_secrets.sh" || true
        FAILURES=$((FAILURES + 1))
    fi
else
    echo "  Secret scanner not available"
fi

# Check 7: PHP_CodeSniffer (if not skipped)
if [ "$SKIP_QUALITY" = false ] && command -v phpcs >/dev/null 2>&1; then
    log_header "Running PHP_CodeSniffer"
    PHP_FILES=$(echo "$STAGED_FILES" | grep '\.php$' || true)
    
    if [ -n "$PHP_FILES" ]; then
        # Use process substitution to avoid issues with filenames containing spaces
        if echo "$PHP_FILES" | tr '\n' '\0' | xargs -0 phpcs --standard=phpcs.xml -q 2>/dev/null; then
            log_success "PHPCS passed"
        else
            log_warning "PHPCS found issues (non-blocking)"
            echo "$PHP_FILES" | tr '\n' '\0' | xargs -0 phpcs --standard=phpcs.xml --report=summary || true
        fi
    else
        echo "  No PHP files to check"
    fi
else
    if [ "$SKIP_QUALITY" = true ]; then
        echo "  Skipping PHPCS (--skip-quality)"
    else
        echo "  PHPCS not available (install with: composer global require squizlabs/php_codesniffer)"
    fi
fi

# Check 8: PHPStan (if not skipped and not quick mode)
if [ "$SKIP_QUALITY" = false ] && [ "$QUICK_MODE" = false ] && command -v phpstan >/dev/null 2>&1; then
    log_header "Running PHPStan"
    PHP_FILES=$(echo "$STAGED_FILES" | grep '\.php$' || true)
    
    if [ -n "$PHP_FILES" ]; then
        if phpstan analyse --configuration=phpstan.neon --no-progress > /dev/null 2>&1; then
            log_success "PHPStan passed"
        else
            log_warning "PHPStan found issues (non-blocking)"
            phpstan analyse --configuration=phpstan.neon --no-progress || true
        fi
    else
        echo "  No PHP files to check"
    fi
else
    if [ "$SKIP_QUALITY" = true ]; then
        echo "  Skipping PHPStan (--skip-quality)"
    elif [ "$QUICK_MODE" = true ]; then
        echo "  Skipping PHPStan (--quick mode)"
    else
        echo "  PHPStan not available (install with: composer global require phpstan/phpstan)"
    fi
fi

# Summary
log_header "Pre-commit Summary"

if [ $FAILURES -gt 0 ]; then
    log_error "Pre-commit checks failed with $FAILURES error(s)"
    echo ""
    echo "To commit anyway, use: git commit --no-verify"
    echo "To run quick checks only: ./scripts/git/pre-commit.sh --quick"
    echo "To skip quality checks: ./scripts/git/pre-commit.sh --skip-quality"
    exit 1
else
    log_success "All pre-commit checks passed!"
    echo ""
    echo "Tip: Use 'make validate' for comprehensive validation"
    exit 0
fi
