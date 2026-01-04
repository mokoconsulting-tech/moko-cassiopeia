# Workflow Guide - Moko Cassiopeia

Quick reference guide for GitHub Actions workflows and common development tasks.

## Table of Contents

- [Overview](#overview)
- [Workflow Quick Reference](#workflow-quick-reference)
- [Common Development Tasks](#common-development-tasks)
- [Troubleshooting](#troubleshooting)
- [Best Practices](#best-practices)

## Overview

This repository uses GitHub Actions for continuous integration, testing, quality checks, and deployment. All workflows are located in `.github/workflows/`.

### Workflow Execution Model

```
┌─────────────────┐
│  Code Changes   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   CI Pipeline   │  ← Validation, Testing, Quality
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Version Branch  │  ← Create dev/X.Y.Z branch
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Release Pipeline│  ← dev → rc → version → main
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Distribution   │  ← ZIP package + GitHub Release
└─────────────────┘
```

## Workflow Quick Reference

### Continuous Integration (ci.yml)

**Trigger:** Automatic on push/PR to main, dev/*, rc/*, version/* branches

**Purpose:** Validates code quality and repository structure

**What it does:**
- ✅ Validates Joomla manifest XML
- ✅ Checks XML well-formedness
- ✅ Validates GitHub Actions workflows
- ✅ Runs PHP syntax checks
- ✅ Checks for secrets in code
- ✅ Validates license headers
- ✅ Verifies version alignment

**When to check:** After every commit

**How to view results:**
```bash
# Via GitHub CLI
gh run list --workflow=ci.yml --limit 5
gh run view <run-id> --log
```

### PHP Quality Checks (php_quality.yml)

**Trigger:** Automatic on push/PR to main, dev/*, rc/*, version/* branches

**Purpose:** Ensures PHP code quality and compatibility

**What it does:**
- 🔍 PHPStan static analysis (level 5)
- 📏 PHP_CodeSniffer with PSR-12 standards
- ✔️ PHP 8.0+ compatibility checks

**Matrix:** PHP 8.0, 8.1, 8.2, 8.3

**When to check:** Before committing PHP changes

**How to run locally:**
```bash
# Install tools
composer global require "squizlabs/php_codesniffer:^3.0" phpstan/phpstan

# Run checks
phpcs --standard=phpcs.xml src/
phpstan analyse --configuration=phpstan.neon
```

### Joomla Testing (joomla_testing.yml)

**Trigger:** Automatic on push/PR to main, dev/*, rc/* branches

**Purpose:** Tests template compatibility with Joomla versions

**What it does:**
- 📦 Downloads and installs Joomla (4.4, 5.0, 5.1)
- 🔧 Installs template into Joomla
- ✅ Validates template installation
- 🧪 Runs Codeception tests

**Matrix:** Joomla 4.4/5.0/5.1 × PHP 8.0/8.1/8.2/8.3

**When to check:** Before releasing new versions

**How to test locally:**
```bash
# See docs/JOOMLA_DEVELOPMENT.md for local testing setup
codecept run
```

### Version Branch Creation (version_branch.yml)

**Trigger:** Manual workflow dispatch

**Purpose:** Creates a new version branch and bumps version numbers

**What it does:**
- 🏷️ Creates dev/*, rc/*, or version/* branch
- 📝 Updates version in all files
- 📅 Updates manifest dates
- 📋 Moves CHANGELOG unreleased to version
- ✅ Validates version hierarchy

**When to use:** Starting work on a new version

**How to run:**
1. Go to Actions → Create version branch
2. Click "Run workflow"
3. Enter version (e.g., 03.05.00)
4. Select branch prefix (dev/, rc/, or version/)
5. Click "Run workflow"

**Example:**
```yaml
new_version: 03.06.00
branch_prefix: dev/
version_text: beta
```

### Release Pipeline (release_pipeline.yml)

**Trigger:** Manual workflow dispatch or release event

**Purpose:** Promotes branches through release stages and creates distributions

**What it does:**
- 🔄 Promotes branches: dev → rc → version → main
- 📅 Normalizes dates in manifest and CHANGELOG
- 📦 Builds distributable ZIP package
- 🚀 Uploads to SFTP server
- 🏷️ Creates Git tag
- 📋 Creates GitHub Release
- 🔒 Attests build provenance

**When to use:** Promoting a version through release stages

**How to run:**
1. Go to Actions → Release Pipeline
2. Click "Run workflow"
3. Select classification (auto/rc/stable)
4. Click "Run workflow"

**Release flow:**
```
dev/X.Y.Z → rc/X.Y.Z → version/X.Y.Z → main
  (dev)      (RC)        (stable)      (production)
```

### Deploy to Staging (deploy_staging.yml)

**Trigger:** Manual workflow dispatch

**Purpose:** Deploys template to staging/development environments

**What it does:**
- ✅ Validates deployment prerequisites
- 📦 Builds deployment package
- 🚀 Uploads via SFTP to environment
- 📝 Creates deployment summary

**When to use:** Testing in staging before production release

**How to run:**
1. Go to Actions → Deploy to Staging
2. Click "Run workflow"
3. Select environment (staging/development/preview)
4. Optionally specify version
5. Click "Run workflow"

**Required secrets:**
- `STAGING_HOST` - SFTP hostname
- `STAGING_USER` - SFTP username
- `STAGING_KEY` - SSH private key (or `STAGING_PASSWORD`)
- `STAGING_PATH` - Remote deployment path

### Repository Health (repo_health.yml)

**Trigger:** Manual workflow dispatch (admin only)

**Purpose:** Comprehensive repository health and configuration checks

**What it does:**
- 🔐 Validates release configuration
- 🌐 Tests SFTP connectivity
- 📂 Checks scripts governance
- 📄 Validates required artifacts
- 🔍 Extended checks (SPDX, ShellCheck, etc.)

**When to use:** Before major releases or when debugging deployment issues

**How to run:**
1. Go to Actions → Repo Health
2. Click "Run workflow"
3. Select profile (all/release/scripts/repo)
4. Click "Run workflow"

**Profiles:**
- `all` - Run all checks
- `release` - Release configuration and SFTP only
- `scripts` - Scripts governance only
- `repo` - Repository health only

## Common Development Tasks

### Starting a New Feature

```bash
# 1. Create a new version branch via GitHub Actions
# Actions → Create version branch → dev/X.Y.Z

# 2. Clone and checkout the new branch
git fetch origin
git checkout dev/X.Y.Z

# 3. Make your changes
vim src/templates/index.php

# 4. Validate locally
./scripts/validate/php_syntax.sh
./scripts/validate/manifest.sh

# 5. Commit and push
git add -A
git commit -m "feat: add new feature"
git push origin dev/X.Y.Z
```

### Running All Validations Locally

```bash
# Run comprehensive validation suite
./scripts/run/validate_all.sh

# Run with verbose output
./scripts/run/validate_all.sh -v

# Run smoke tests
./scripts/run/smoke_test.sh
```

### Creating a Release Package

```bash
# Package with auto-detected version
./scripts/release/package_extension.sh

# Package with specific version
./scripts/release/package_extension.sh dist 03.05.00

# Verify package contents
unzip -l dist/moko-cassiopeia-*.zip
```

### Updating Version Numbers

```bash
# Via GitHub Actions (recommended)
# Actions → Create version branch

# Or manually with scripts
./scripts/fix/versions.sh 03.05.00
```

### Updating CHANGELOG

```bash
# Add new version entry
./scripts/release/update_changelog.sh 03.05.00

# Update release dates
./scripts/release/update_dates.sh 2025-01-15 03.05.00
```

## Troubleshooting

### CI Failures

#### PHP Syntax Errors

```bash
# Check specific file
php -l src/templates/index.php

# Run validation script
./scripts/validate/php_syntax.sh
```

#### Manifest Validation Failed

```bash
# Validate manifest XML
./scripts/validate/manifest.sh

# Check XML well-formedness
./scripts/validate/xml_wellformed.sh
```

#### Version Alignment Issues

```bash
# Check version in manifest matches CHANGELOG
./scripts/validate/version_alignment.sh

# Fix versions
./scripts/fix/versions.sh 03.05.00
```

### Workflow Failures

#### "Permission denied" on scripts

```bash
# Fix script permissions
chmod +x scripts/**/*.sh
```

#### "Branch already exists"

```bash
# Check existing branches
git branch -r | grep dev/

# Delete remote branch if needed (carefully!)
git push origin --delete dev/03.05.00
```

#### "Missing required secrets"

Go to repository Settings → Secrets and variables → Actions, and add:
- `FTP_HOST`
- `FTP_USER`
- `FTP_KEY` or `FTP_PASSWORD`
- `FTP_PATH`

#### SFTP Connection Failed

1. Verify credentials in repo_health workflow:
   - Actions → Repo Health → profile: release

2. Check SSH key format (OpenSSH, not PuTTY PPK)

3. Verify server allows connections from GitHub IPs

### Quality Check Failures

#### PHPStan Errors

```bash
# Run locally to see full output
phpstan analyse --configuration=phpstan.neon

# Generate baseline to ignore existing issues
phpstan analyse --configuration=phpstan.neon --generate-baseline
```

#### PHPCS Violations

```bash
# Check violations
phpcs --standard=phpcs.xml src/

# Auto-fix where possible
phpcbf --standard=phpcs.xml src/

# Show specific error codes
phpcs --standard=phpcs.xml --report=source src/
```

#### Joomla Testing Failed

1. Check PHP/Joomla version matrix compatibility
2. Review MySQL connection errors
3. Verify template manifest structure
4. Check template file paths

## Best Practices

### Version Management

1. **Always use version branches:** dev/X.Y.Z, rc/X.Y.Z, version/X.Y.Z
2. **Follow hierarchy:** dev → rc → version → main
3. **Update CHANGELOG:** Document all changes in Unreleased section
4. **Semantic versioning:** Major.Minor.Patch (03.05.00)

### Code Quality

1. **Run validations locally** before pushing
2. **Fix PHPStan warnings** at level 5
3. **Follow PSR-12** coding standards
4. **Add SPDX license headers** to new files
5. **Keep functions small** and well-documented

### Workflow Usage

1. **Use CI for every commit** - automated validation
2. **Run repo_health before releases** - comprehensive checks
3. **Test on staging first** - never deploy directly to production
4. **Monitor workflow runs** - fix failures promptly
5. **Review workflow logs** - understand what changed

### Release Process

1. **Create dev branch** → Work on features
2. **Promote to rc** → Release candidate testing
3. **Promote to version** → Stable release
4. **Merge to main** → Production (auto-merged via PR)
5. **Create GitHub Release** → Public distribution

### Security

1. **Never commit secrets** - use GitHub Secrets
2. **Use SSH keys** for SFTP (not passwords)
3. **Scan for secrets** - runs automatically in CI
4. **Keep dependencies updated** - security patches
5. **Review security advisories** - GitHub Dependabot

### Documentation

1. **Update docs with code** - keep in sync
2. **Document workflow changes** - update this guide
3. **Add examples** - show, don't just tell
4. **Link to relevant docs** - cross-reference
5. **Keep README current** - first impression matters

## Quick Links

- [Main README](../README.md) - Project overview
- [Joomla Development Guide](./JOOMLA_DEVELOPMENT.md) - Testing and quality
- [Scripts README](../scripts/README.md) - Script documentation
- [CHANGELOG](../CHANGELOG.md) - Version history
- [CONTRIBUTING](../CONTRIBUTING.md) - Contribution guidelines

## Getting Help

1. **Check workflow logs** - Most issues have clear error messages
2. **Review this guide** - Common solutions documented
3. **Run validation scripts** - Identify specific issues
4. **Open an issue** - For bugs or questions
5. **Contact maintainers** - For access or configuration issues

---

**Document Version:** 1.0.0  
**Last Updated:** 2025-01-04  
**Maintained by:** Moko Consulting Engineering
