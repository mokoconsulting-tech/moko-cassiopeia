# Quick Start Guide - Moko Cassiopeia Development

Get up and running with Moko Cassiopeia development in minutes.

## Prerequisites

Before you begin, ensure you have:

- **Git** - For version control
- **PHP 8.0+** - Required runtime
- **Composer** - PHP dependency manager
- **Make** (optional) - For convenient commands
- **Code Editor** - VS Code recommended (tasks pre-configured)

## 5-Minute Setup

### 1. Clone the Repository

```bash
git clone https://github.com/mokoconsulting-tech/moko-cassiopeia.git
cd moko-cassiopeia
```

### 2. Install Development Dependencies

```bash
# Using Make (recommended)
make dev-setup

# Or manually
composer global require "squizlabs/php_codesniffer:^3.0"
composer global require phpstan/phpstan
composer global require "phpcompatibility/php-compatibility:^9.0"
composer global require codeception/codeception
```

### 3. Install Git Hooks (Optional but Recommended)

```bash
./scripts/git/install-hooks.sh
```

### 4. Validate Everything Works

```bash
# Quick validation
make validate-required

# Or comprehensive validation
make validate
```

## Common Tasks

### Development Workflow

```bash
# 1. Make your changes
vim src/templates/index.php

# 2. Validate locally
make validate-required

# 3. Check code quality
make quality

# 4. Commit
git add -A
git commit -m "feat: add new feature"
# (pre-commit hook runs automatically)

# 5. Push
git push origin your-branch
```

### Testing

```bash
# Run all tests
make test

# Run unit tests only
make test-unit

# Run acceptance tests only
make test-acceptance
```

### Code Quality

```bash
# Check everything
make quality

# PHP CodeSniffer only
make phpcs

# Auto-fix PHPCS issues
make phpcs-fix

# PHPStan only
make phpstan

# PHP compatibility check
make phpcompat
```

### Creating a Release Package

```bash
# Package with auto-detected version
make package

# Or specify directory and version
./scripts/release/package_extension.sh dist 03.05.00

# Check package contents
ls -lh dist/
unzip -l dist/moko-cassiopeia-*.zip
```

## VS Code Integration

If using VS Code, press `Ctrl+Shift+P` (or `Cmd+Shift+P` on Mac) and type "Run Task" to see available tasks:

- **Validate All** - Run all validation scripts (default test task)
- **Validate Required** - Run only required validations
- **PHP CodeSniffer** - Check code style
- **PHP CodeSniffer - Auto Fix** - Fix code style issues
- **PHPStan** - Static analysis
- **Run Tests** - Execute all tests
- **Create Package** - Build distribution ZIP
- **Install Git Hooks** - Set up pre-commit hooks

## Available Make Commands

Run `make help` to see all available commands:

```bash
make help              # Show all commands
make dev-setup         # Complete environment setup
make validate          # Run all validations
make test              # Run all tests
make quality           # Check code quality
make package           # Create distribution package
make clean             # Remove generated files
make check             # Quick check (validate + quality)
make all               # Complete build pipeline
```

## Project Structure

```
moko-cassiopeia/
├── src/                      # Joomla template source
│   ├── templates/            # Template files
│   ├── media/                # Assets (CSS, JS, images)
│   ├── language/             # Language files
│   └── administrator/        # Admin files
├── scripts/                  # Automation scripts
│   ├── validate/             # Validation scripts
│   ├── fix/                  # Fix/update scripts
│   ├── release/              # Release scripts
│   ├── run/                  # Execution scripts
│   ├── git/                  # Git hooks
│   └── lib/                  # Shared libraries
├── tests/                    # Test suites
├── docs/                     # Documentation
├── .github/workflows/        # CI/CD workflows
├── Makefile                  # Make commands
└── README.md                 # Project overview
```

## Next Steps

### Learning the Workflow

1. **Read the Workflow Guide**: [docs/WORKFLOW_GUIDE.md](./WORKFLOW_GUIDE.md)
2. **Review Joomla Development**: [docs/JOOMLA_DEVELOPMENT.md](./JOOMLA_DEVELOPMENT.md)
3. **Check Scripts Documentation**: [scripts/README.md](../scripts/README.md)

### Creating Your First Feature

1. **Create a version branch** via GitHub Actions:
   - Go to Actions → Create version branch
   - Enter version (e.g., 03.06.00)
   - Select branch prefix: `dev/`
   - Run workflow

2. **Checkout the branch**:
   ```bash
   git fetch origin
   git checkout dev/03.06.00
   ```

3. **Make changes and test**:
   ```bash
   # Edit files
   vim src/templates/index.php
   
   # Validate
   make validate-required
   
   # Check quality
   make quality
   ```

4. **Commit and push**:
   ```bash
   git add -A
   git commit -m "feat: your feature description"
   git push origin dev/03.06.00
   ```

5. **Watch CI**: Check GitHub Actions for automated testing

### Understanding the Release Process

```
Development → RC → Stable → Production
  (dev/)     (rc/)  (version/)  (main)
```

1. **dev/X.Y.Z** - Active development
2. **rc/X.Y.Z** - Release candidate testing
3. **version/X.Y.Z** - Stable release
4. **main** - Production (auto-merged from version/)

Use the Release Pipeline workflow to promote between stages.

## Troubleshooting

### Scripts Not Executable

```bash
make fix-permissions
# Or manually:
chmod +x scripts/**/*.sh
```

### PHPStan/PHPCS Not Found

```bash
make install
# Or manually:
composer global require "squizlabs/php_codesniffer:^3.0" phpstan/phpstan
```

### Pre-commit Hook Fails

```bash
# Run manually to see details
./scripts/git/pre-commit.sh

# Quick mode (skip some checks)
./scripts/git/pre-commit.sh --quick

# Skip quality checks
./scripts/git/pre-commit.sh --skip-quality

# Bypass hook (not recommended)
git commit --no-verify
```

### CI Workflow Fails

1. Check the workflow logs in GitHub Actions
2. Run the same checks locally:
   ```bash
   ./scripts/validate/manifest.sh
   ./scripts/validate/php_syntax.sh
   make quality
   ```

### Need Help?

- **Documentation**: Check [docs/](../docs/) directory
- **Issues**: Open an issue on GitHub
- **Contributing**: See [CONTRIBUTING.md](../CONTRIBUTING.md)

## Best Practices

### Before Committing

```bash
# Always validate first
make validate-required

# Check quality for PHP changes
make quality

# Run tests if you changed functionality
make test
```

### Code Style

- Follow PSR-12 standards
- Use `make phpcs-fix` to auto-fix issues
- Add SPDX license headers to new files
- Keep functions small and focused

### Documentation

- Update docs when changing workflows
- Add comments for complex logic
- Update CHANGELOG.md with changes
- Keep README.md current

### Version Management

- Use semantic versioning: Major.Minor.Patch (03.05.00)
- Update CHANGELOG.md with all changes
- Follow the version hierarchy: dev → rc → version → main
- Never skip stages in the release process

## Useful Resources

- [Joomla Documentation](https://docs.joomla.org/)
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)
- [Semantic Versioning](https://semver.org/)
- [Conventional Commits](https://www.conventionalcommits.org/)

## Quick Reference Card

```bash
# Setup
make dev-setup              # Initial setup
./scripts/git/install-hooks.sh  # Install hooks

# Development
make validate-required      # Quick validation
make quality               # Code quality
make test                  # Run tests

# Building
make package               # Create ZIP

# Maintenance
make clean                 # Clean generated files
make fix-permissions       # Fix script permissions

# Help
make help                  # Show all commands
./scripts/run/validate_all.sh --help  # Script help
```

---

**Document Version:** 1.0.0  
**Last Updated:** 2025-01-04  
**Get Started:** Run `make dev-setup` now!
