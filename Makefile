# Makefile for Moko Cassiopeia Development
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# SPDX-License-Identifier: GPL-3.0-or-later

.PHONY: help install validate test quality package clean dev-setup

# Default target
.DEFAULT_GOAL := help

# Version detection
VERSION := $(shell grep -oP '<version>\K[^<]+' src/templates/templateDetails.xml 2>/dev/null || echo "unknown")

## help: Show this help message
help:
	@echo "Moko Cassiopeia Development Makefile"
	@echo ""
	@echo "Version: $(VERSION)"
	@echo ""
	@echo "Available targets:"
	@sed -n 's/^##//p' $(MAKEFILE_LIST) | column -t -s ':' | sed -e 's/^/ /'

## install: Install development dependencies
install:
	@echo "Installing development dependencies..."
	@command -v composer >/dev/null 2>&1 || { echo "Error: composer not found. Please install composer first."; exit 1; }
	composer global require squizlabs/php_codesniffer
	composer global require phpstan/phpstan
	composer global require phpcompatibility/php-compatibility
	composer global require codeception/codeception
	phpcs --config-set installed_paths ~/.composer/vendor/phpcompatibility/php-compatibility
	@echo "✓ Dependencies installed"

## validate: Run all validation scripts
validate:
	@echo "Running validation scripts..."
	@python3 ./scripts/run/validate_all.py

## validate-required: Run only required validation scripts
validate-required:
	@echo "Running required validations..."
	@python3 ./scripts/validate/manifest.py
	@python3 ./scripts/validate/xml_wellformed.py
	@python3 ./scripts/validate/workflows.py
	@echo "✓ Required validations passed"

## test: Run all tests
test:
	@echo "Running tests..."
	@command -v codecept >/dev/null 2>&1 || { echo "Error: codecept not found. Run 'make install' first."; exit 1; }
	codecept run

## test-unit: Run unit tests only
test-unit:
	@echo "Running unit tests..."
	@command -v codecept >/dev/null 2>&1 || { echo "Error: codecept not found. Run 'make install' first."; exit 1; }
	codecept run unit

## test-acceptance: Run acceptance tests only
test-acceptance:
	@echo "Running acceptance tests..."
	@command -v codecept >/dev/null 2>&1 || { echo "Error: codecept not found. Run 'make install' first."; exit 1; }
	codecept run acceptance

## quality: Run code quality checks
quality:
	@echo "Running code quality checks..."
	@$(MAKE) phpcs
	@$(MAKE) phpstan
	@$(MAKE) phpcompat

## phpcs: Run PHP_CodeSniffer
phpcs:
	@echo "Running PHP_CodeSniffer..."
	@command -v phpcs >/dev/null 2>&1 || { echo "Error: phpcs not found. Run 'make install' first."; exit 1; }
	phpcs --standard=phpcs.xml src/

## phpcs-fix: Auto-fix PHPCS violations
phpcs-fix:
	@echo "Auto-fixing PHPCS violations..."
	@command -v phpcbf >/dev/null 2>&1 || { echo "Error: phpcbf not found. Run 'make install' first."; exit 1; }
	phpcbf --standard=phpcs.xml src/

## phpstan: Run PHPStan static analysis
phpstan:
	@echo "Running PHPStan..."
	@command -v phpstan >/dev/null 2>&1 || { echo "Error: phpstan not found. Run 'make install' first."; exit 1; }
	phpstan analyse --configuration=phpstan.neon

## phpcompat: Check PHP 8.0+ compatibility
phpcompat:
	@echo "Checking PHP 8.0+ compatibility..."
	@command -v phpcs >/dev/null 2>&1 || { echo "Error: phpcs not found. Run 'make install' first."; exit 1; }
	phpcs --standard=PHPCompatibility --runtime-set testVersion 8.0- src/

## package: Create distribution package
package:
	@echo "Creating distribution package..."
	@python3 ./scripts/release/package_extension.py dist $(VERSION)
	@echo "✓ Package created: dist/moko-cassiopeia-$(VERSION)-*.zip"

## smoke-test: Run smoke tests
smoke-test:
	@echo "Running smoke tests..."
	@python3 ./scripts/run/smoke_test.py

## script-health: Check script health
script-health:
	@echo "Checking script health..."
	@python3 ./scripts/run/script_health.py

## version-check: Display current version information
version-check:
	@echo "Version Information:"
	@echo "  Manifest: $(VERSION)"
	@echo "  Latest CHANGELOG entry:"
	@grep -m 1 "^## \[" CHANGELOG.md || echo "  (not found)"

## fix-permissions: Fix script executable permissions
fix-permissions:
	@echo "Fixing script permissions..."
	@find scripts -type f -name "*.py" -exec chmod +x {} \;
	@echo "✓ Permissions fixed"

## clean: Remove generated files and caches
clean:
	@echo "Cleaning generated files..."
	@rm -rf dist/
	@rm -rf tests/_output/
	@rm -rf .phpunit.cache/
	@find . -type f -name "*.log" -delete
	@find . -type f -name ".DS_Store" -delete
	@echo "✓ Cleaned"

## dev-setup: Complete development environment setup
dev-setup:
	@echo "Setting up development environment..."
	@$(MAKE) install
	@$(MAKE) fix-permissions
	@echo ""
	@echo "✓ Development environment ready!"
	@echo ""
	@echo "Quick start:"
	@echo "  make validate    - Run all validations"
	@echo "  make test        - Run tests"
	@echo "  make quality     - Check code quality"
	@echo "  make package     - Create distribution package"

## format: Format code (PHPCS auto-fix)
format: phpcs-fix

## check: Quick check (required validations + quality)
check:
	@echo "Running quick checks..."
	@$(MAKE) validate-required
	@$(MAKE) quality
	@echo "✓ All checks passed"

## all: Run everything (validate, test, quality, package)
all:
	@echo "Running complete build pipeline..."
	@$(MAKE) validate
	@$(MAKE) test
	@$(MAKE) quality
	@$(MAKE) package
	@echo "✓ Complete pipeline successful"

## watch: Watch for changes and run validations (requires entr)
watch:
	@echo "Watching for changes... (Ctrl+C to stop)"
	@command -v entr >/dev/null 2>&1 || { echo "Error: entr not found. Install with: apt-get install entr"; exit 1; }
	@find src -type f | entr -c make validate-required

## list-scripts: List all available scripts
list-scripts:
	@echo "Available validation scripts:"
	@find scripts/validate -type f -name "*.py" -exec basename {} \; | sort
	@echo ""
	@echo "Available fix scripts:"
	@find scripts/fix -type f -name "*.py" -exec basename {} \; | sort
	@echo ""
	@echo "Available run scripts (python):"
	@find scripts/run -type f -name "*.py" -exec basename {} \; | sort

## scaffold: Create new Joomla extension scaffolding
scaffold:
	@echo "Create new Joomla extension scaffolding"
	@echo ""
	@echo "Usage: make scaffold TYPE=<type> NAME=<name> AUTHOR=<author> DESC=<description>"
	@echo ""
	@echo "Types: component, module, plugin, template, package"
	@echo ""
	@echo "Example:"
	@echo "  make scaffold TYPE=module NAME='My Module' AUTHOR='John Doe' DESC='Module description'"

## scaffold-component: Create a component
scaffold-component:
	@python3 scripts/run/scaffold_extension.py component "$(NAME)" "$(DESC)" "$(AUTHOR)"

## scaffold-module: Create a module
scaffold-module:
	@python3 scripts/run/scaffold_extension.py module "$(NAME)" "$(DESC)" "$(AUTHOR)" --client $(CLIENT)

## scaffold-plugin: Create a plugin
scaffold-plugin:
	@python3 scripts/run/scaffold_extension.py plugin "$(NAME)" "$(DESC)" "$(AUTHOR)" --group $(GROUP)

## scaffold-template: Create a template
scaffold-template:
	@python3 scripts/run/scaffold_extension.py template "$(NAME)" "$(DESC)" "$(AUTHOR)"

## scaffold-package: Create a package
scaffold-package:
	@python3 scripts/run/scaffold_extension.py package "$(NAME)" "$(DESC)" "$(AUTHOR)"

## docs: Open documentation
docs:
	@echo "Documentation files:"
	@echo "  README.md                   - Project overview"
	@echo "  docs/JOOMLA_DEVELOPMENT.md  - Development guide"
	@echo "  docs/WORKFLOW_GUIDE.md      - Workflow reference"
	@echo "  scripts/README.md           - Scripts documentation"
	@echo "  CHANGELOG.md                - Version history"
	@echo "  CONTRIBUTING.md             - Contribution guide"
