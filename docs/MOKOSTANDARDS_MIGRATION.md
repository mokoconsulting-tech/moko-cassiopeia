# MokoStandards Migration Guide

## Current Status

As of 2026-01-09, the consolidation has been **reverted** to restore the original three separate workflow files:
- `.github/workflows/ci.yml` (82 lines) - Repository validation
- `.github/workflows/php_quality.yml` (174 lines) - PHP quality checks
- `.github/workflows/joomla_testing.yml` (270 lines) - Joomla integration testing

## Why Revert the Consolidation?

The initial consolidation approach created a **monolithic** `ci.yml` file (492 lines) that combined all three workflows. While this reduced file count, it went **against the documented MokoStandards strategy** which advocates for:

1. **Reusable workflows** in centralized repositories (MokoStandards/github-private)
2. **Thin caller workflows** in project repositories
3. **Organization-wide reusability** and standardization

## MokoStandards Architecture

### Repository Structure

**MokoStandards** (Public - https://github.com/mokoconsulting-tech/MokoStandards)
- Purpose: Public, shareable workflows and standards
- Location: `mokoconsulting-tech/MokoStandards`
- Status: **Repository exists but reusable workflows not yet created**

**.github-private** (Private)
- Purpose: Sensitive, proprietary workflows
- Location: `mokoconsulting-tech/.github-private`
- Status: Not verified in this migration

## Migration Steps

### Phase 1: Create Reusable Workflows in MokoStandards ⚠️ TODO

The following reusable workflows need to be created in the MokoStandards repository:

#### 1. `reusable-php-quality.yml`

Location: `MokoStandards/.github/workflows/reusable-php-quality.yml`

Should consolidate:
- PHP_CodeSniffer checks (PHPCS)
- PHPStan static analysis
- PHP Compatibility checks

Input parameters:
- `php-versions`: JSON array of PHP versions (default: `["8.0", "8.1", "8.2", "8.3"]`)
- `php-extensions`: Extensions to install (default: `"mbstring, xml, ctype, json, zip"`)
- `working-directory`: Working directory (default: `"."`)
- `phpstan-level`: PHPStan level (default: `"5"`)

See `docs/REUSABLE_WORKFLOWS.md` lines 177-250 for template.

#### 2. `reusable-joomla-testing.yml`

Location: `MokoStandards/.github/workflows/reusable-joomla-testing.yml`

Should consolidate:
- Joomla installation matrix
- Template installation
- Codeception test framework

Input parameters:
- `php-versions`: JSON array of PHP versions
- `joomla-versions`: JSON array of Joomla versions (default: `["4.4", "5.0", "5.1"]`)
- `template-path`: Path to template files (default: `"src"`)

#### 3. `reusable-ci-validation.yml`

Location: `MokoStandards/.github/workflows/reusable-ci-validation.yml`

Should consolidate:
- Manifest validation
- XML well-formedness checks
- Workflow validation
- Python validation scripts

Input parameters:
- `validation-scripts-path`: Path to validation scripts (default: `"scripts/validate"`)

### Phase 2: Update Project Workflows to Call Reusable Workflows ⚠️ TODO

Once the reusable workflows are created in MokoStandards, update the project workflows:

#### Updated `php_quality.yml`

```yaml
name: PHP Code Quality

on:
  push:
    branches:
      - main
      - dev/**
      - rc/**
      - version/**
  pull_request:
    branches:
      - main
      - dev/**
      - rc/**
      - version/**

permissions:
  contents: read

jobs:
  quality:
    uses: mokoconsulting-tech/MokoStandards/.github/workflows/reusable-php-quality.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
      php-extensions: 'mbstring, xml, ctype, json, zip'
      working-directory: '.'
      phpstan-level: '5'
    secrets: inherit
```

**Result:** ~15 lines (down from 174 lines) - **91% reduction**

#### Updated `joomla_testing.yml`

```yaml
name: Joomla Testing

on:
  push:
    branches:
      - main
      - dev/**
      - rc/**
  pull_request:
    branches:
      - main
      - dev/**
      - rc/**

permissions:
  contents: read

jobs:
  testing:
    uses: mokoconsulting-tech/MokoStandards/.github/workflows/reusable-joomla-testing.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
      joomla-versions: '["4.4", "5.0", "5.1"]'
      template-path: 'src'
    secrets: inherit
```

**Result:** ~17 lines (down from 270 lines) - **94% reduction**

#### Updated `ci.yml`

```yaml
name: Continuous Integration

on:
  push:
    branches:
      - main
      - dev/**
      - rc/**
      - version/**
  pull_request:
    branches:
      - main
      - dev/**
      - rc/**
      - version/**

permissions:
  contents: read

jobs:
  validation:
    uses: mokoconsulting-tech/MokoStandards/.github/workflows/reusable-ci-validation.yml@v1
    with:
      validation-scripts-path: 'scripts/validate'
    secrets: inherit
```

**Result:** ~18 lines (down from 82 lines) - **78% reduction**

### Phase 3: Benefits After Migration

**Before (Current State):**
- 3 workflow files: 526 total lines
- Duplicated logic across projects
- Maintenance burden on each project
- Inconsistent standards

**After (MokoStandards Strategy):**
- 3 caller files: ~50 total lines (**90% reduction**)
- Shared, reusable workflows
- Centralized maintenance
- Consistent organization-wide standards
- Easy to propagate improvements

## Action Items

### For MokoStandards Repository Maintainers

- [ ] Create `reusable-php-quality.yml` in MokoStandards
- [ ] Create `reusable-joomla-testing.yml` in MokoStandards
- [ ] Create `reusable-ci-validation.yml` in MokoStandards
- [ ] Tag release (e.g., `v1.0.0`) for version stability
- [ ] Document usage in MokoStandards README

### For moko-cassiopeia Project

- [ ] Wait for reusable workflows to be available in MokoStandards
- [ ] Update `php_quality.yml` to call reusable workflow
- [ ] Update `joomla_testing.yml` to call reusable workflow
- [ ] Update `ci.yml` to call reusable workflow
- [ ] Test all workflows work correctly
- [ ] Update `WORKFLOW_GUIDE.md` to reflect new architecture

## References

- **Current Project Workflows:** `.github/workflows/`
- **MokoStandards Repository:** https://github.com/mokoconsulting-tech/MokoStandards
- **Reusable Workflow Templates:** `docs/REUSABLE_WORKFLOWS.md`
- **Migration Plan:** `docs/CI_MIGRATION_PLAN.md`
- **Migration Checklist:** `docs/MIGRATION_CHECKLIST.md`

## Timeline

**Current:** Workflows reverted to original state (3 separate files)

**Next Step:** Create reusable workflows in MokoStandards repository

**Final Step:** Update project workflows to thin callers

---

**Note:** This migration aligns with the documented strategy in `CI_MIGRATION_PLAN.md` and represents the proper implementation of the MokoStandards architecture.
