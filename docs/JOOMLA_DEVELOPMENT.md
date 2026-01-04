# Joomla Development Workflows and Scripts

This document describes the Joomla-aware development workflows and scripts available in this repository.

## Table of Contents

- [Overview](#overview)
- [Requirements](#requirements)
- [Scripts](#scripts)
- [GitHub Actions Workflows](#github-actions-workflows)
- [Testing](#testing)
- [Code Quality](#code-quality)
- [Deployment](#deployment)

## Overview

This repository includes comprehensive Joomla development workflows and scripts for:

1. **Extension Packaging** - Create distributable ZIP packages
2. **Joomla Testing** - Automated testing with multiple Joomla versions
3. **Code Quality** - PHPStan, PHP_CodeSniffer, and compatibility checks
4. **Deployment** - Staging and production deployment workflows

## Requirements

### Local Development

- PHP 8.0 or higher
- Composer (for PHPStan and PHP_CodeSniffer)
- Node.js 18+ (for some workflows)
- MySQL/MariaDB (for Joomla testing)

### CI/CD (GitHub Actions)

All requirements are automatically installed in CI/CD pipelines.

## Scripts

### Extension Packaging

Package the Joomla template as a distributable ZIP file:

```bash
./scripts/release/package_extension.sh [output_dir] [version]
```

**Parameters:**
- `output_dir` (optional): Output directory for the ZIP file (default: `dist`)
- `version` (optional): Version string to use (default: extracted from manifest)

**Example:**
```bash
./scripts/release/package_extension.sh dist 3.5.0
```

This creates a ZIP file in the `dist` directory with all necessary template files, excluding development files.

## GitHub Actions Workflows

### 1. PHP Code Quality (`php_quality.yml`)

Runs on every push and pull request to main branches.

**Jobs:**
- **PHP_CodeSniffer** - Checks code style and standards
- **PHPStan** - Static analysis at level 5
- **PHP Compatibility** - Ensures PHP 8.0+ compatibility

**Matrix Testing:**
- PHP versions: 8.0, 8.1, 8.2, 8.3

**Trigger:**
```bash
# Automatically runs on push/PR
git push origin dev/3.5.0
```

### 2. Joomla Testing (`joomla_testing.yml`)

Tests template with multiple Joomla and PHP versions.

**Jobs:**
- **Joomla Setup** - Installs Joomla CMS
- **Template Installation** - Installs template into Joomla
- **Validation** - Validates template functionality
- **Codeception** - Runs test framework

**Matrix Testing:**
- PHP versions: 8.0, 8.1, 8.2, 8.3
- Joomla versions: 4.4 (LTS), 5.0, 5.1
- MySQL version: 8.0

**Example:**
```bash
# Automatically runs on push to main branches
git push origin main
```

### 3. Deploy to Staging (`deploy_staging.yml`)

Manual deployment to staging/development environments.

**Parameters:**
- `environment`: Target environment (staging, development, preview)
- `version`: Version to deploy (optional, defaults to latest)

**Usage:**
1. Go to Actions → Deploy to Staging
2. Click "Run workflow"
3. Select environment and version
4. Click "Run workflow"

**Required Secrets:**
For staging deployment, configure these repository secrets:
- `STAGING_HOST` - SFTP server hostname
- `STAGING_USER` - SFTP username
- `STAGING_KEY` - SSH private key (recommended) or use `STAGING_PASSWORD`
- `STAGING_PATH` - Remote path for deployment
- `STAGING_PORT` - SSH port (optional, default: 22)

## Testing

### Codeception Framework

The repository is configured with Codeception for acceptance and unit testing.

#### Running Tests Locally

1. Install Codeception:
```bash
composer global require codeception/codeception
```

2. Run tests:
```bash
# Run all tests
codecept run

# Run acceptance tests only
codecept run acceptance

# Run unit tests only
codecept run unit

# Run with verbose output
codecept run --debug
```

#### Test Structure

```
tests/
├── _data/              # Test data and fixtures
├── _output/            # Test reports and screenshots
├── _support/           # Helper classes
├── acceptance/         # Acceptance tests
│   └── TemplateInstallationCest.php
├── unit/               # Unit tests
│   └── TemplateConfigurationTest.php
├── acceptance.suite.yml
└── unit.suite.yml
```

#### Writing Tests

**Unit Test Example:**
```php
<?php
namespace Tests\Unit;

use Codeception\Test\Unit;

class MyTemplateTest extends Unit
{
    public function testSomething()
    {
        $this->assertTrue(true);
    }
}
```

**Acceptance Test Example:**
```php
<?php
namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class MyAcceptanceCest
{
    public function testPageLoad(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome');
    }
}
```

## Code Quality

### PHPStan

Static analysis configuration in `phpstan.neon`:

```bash
# Run PHPStan locally
phpstan analyse --configuration=phpstan.neon
```

**Configuration:**
- Analysis level: 5
- Target paths: `src/`
- PHP version: 8.0+

### PHP_CodeSniffer

Coding standards configuration in `phpcs.xml`:

```bash
# Check code style
phpcs --standard=phpcs.xml

# Fix auto-fixable issues
phpcbf --standard=phpcs.xml
```

**Standards:**
- PSR-12 as base
- PHP 8.0+ compatibility checks
- Joomla coding conventions (when available)

### Running Quality Checks Locally

1. Install tools:
```bash
composer global require squizlabs/php_codesniffer
composer global require phpstan/phpstan
composer global require phpcompatibility/php-compatibility
```

2. Configure PHPCompatibility:
```bash
phpcs --config-set installed_paths ~/.composer/vendor/phpcompatibility/php-compatibility
```

3. Run checks:
```bash
# PHP syntax check
./scripts/validate/php_syntax.sh

# CodeSniffer
phpcs --standard=phpcs.xml src/

# PHPStan
phpstan analyse --configuration=phpstan.neon

# PHP Compatibility
phpcs --standard=PHPCompatibility --runtime-set testVersion 8.0- src/
```

## Deployment

### Manual Deployment

Use the package script to create a distribution:

```bash
# Create package
./scripts/release/package_extension.sh dist 3.5.0

# Upload to server
scp dist/moko-cassiopeia-3.5.0-template.zip user@server:/path/to/joomla/
```

### Automated Deployment

Use the GitHub Actions workflow:

1. **Staging Deployment:**
   - Go to Actions → Deploy to Staging
   - Select "staging" environment
   - Click "Run workflow"

2. **Development Testing:**
   - Select "development" environment
   - Useful for quick testing without affecting staging

3. **Preview Deployment:**
   - Select "preview" environment
   - For showcasing features before staging

### Post-Deployment Steps

After deployment to Joomla:

1. Log in to Joomla administrator
2. Go to System → Extensions → Discover
3. Click "Discover" to find the template
4. Click "Install" to complete installation
5. Go to System → Site Templates
6. Configure template settings
7. Set as default template if desired

## CI/CD Pipeline Details

### Build Process

1. **Validation** - All scripts validate before packaging
2. **Packaging** - Create ZIP with proper structure
3. **Testing** - Run on multiple PHP/Joomla versions
4. **Quality** - PHPStan and PHPCS analysis
5. **Deployment** - SFTP upload to target environment

### Matrix Testing Strategy

- **PHP Versions:** 8.0, 8.1, 8.2, 8.3
- **Joomla Versions:** 4.4 LTS, 5.0, 5.1
- **Exclusions:** PHP 8.3 not tested with Joomla 4.4 (incompatible)

## Troubleshooting

### Common Issues

**Issue: PHP_CodeSniffer not found**
```bash
composer global require squizlabs/php_codesniffer
export PATH="$PATH:$HOME/.composer/vendor/bin"
```

**Issue: PHPStan errors**
```bash
# Increase analysis memory
php -d memory_limit=1G $(which phpstan) analyse
```

**Issue: Joomla installation fails in CI**
- Check MySQL service is running
- Verify database credentials
- Ensure PHP extensions are installed

**Issue: SFTP deployment fails**
- Verify SSH key is correctly formatted
- Check firewall allows port 22
- Ensure STAGING_PATH exists on server

## Contributing

When adding new workflows or scripts:

1. Follow existing script structure
2. Add proper error handling
3. Include usage documentation
4. Test with multiple PHP versions
5. Update this documentation

## Support

For issues or questions:
- Open an issue on GitHub
- Check existing workflow runs for examples
- Review test output in Actions tab

## License

All scripts and workflows are licensed under GPL-3.0-or-later, same as the main project.
