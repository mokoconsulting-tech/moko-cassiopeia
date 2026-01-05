# CI/CD Migration to Centralized Repositories

## Purpose

This document outlines the plan and preparation steps for migrating CI/CD workflows to centralized repositories. The organization uses a **dual-repository architecture**:

### Repository Architecture

**`.github-private`** (Private & Secure Centralization)
- **Purpose**: Sensitive/proprietary workflows and deployment logic
- **Visibility**: Private (organization members only)
- **Content**: Deployment workflows, release pipelines, sensitive scripts
- **Use Case**: Production deployments, security-sensitive operations

**`MokoStandards`** (Public Centralization)
- **Purpose**: Public/shareable workflows and best practices
- **Visibility**: Public (community accessible)
- **Content**: Quality checks, testing workflows, public templates
- **Use Case**: Open-source projects, community contributions, standardization

This dual approach provides:

- **Security**: Keep sensitive CI/CD logic and configurations private in `.github-private`
- **Community**: Share public workflows and standards via `MokoStandards`
- **Reusability**: Share common workflows across multiple repositories
- **Maintainability**: Centralize CI/CD updates in one location per type
- **Organization**: Separate CI/CD infrastructure from application code
- **Flexibility**: Choose appropriate repository based on workflow sensitivity

## Current State

### Workflows in `.github/workflows/`
1. `php_quality.yml` - PHP code quality checks (PHPCS, PHPStan, PHP Compatibility)
2. `release_pipeline.yml` - Release and build pipeline
3. `ci.yml` - Continuous integration checks
4. `repo_health.yml` - Repository health monitoring
5. `version_branch.yml` - Version branch management
6. `joomla_testing.yml` - Joomla-specific testing
7. `deploy_staging.yml` - Staging deployment

### Scripts in `scripts/`
- `scripts/lib/` - Shared Python libraries (common.py, extension_utils.py, joomla_manifest.py)
- `scripts/release/` - Release automation scripts
- `scripts/validate/` - Validation scripts
- `scripts/run/` - Runtime scripts

## Migration Strategy

### Phase 1: Preparation (Current)

**Files to Keep in Main Repository:**
- Simple trigger workflows that call reusable workflows
- Repository-specific configuration files
- Application scripts that are part of the product

**Files to Move to Centralized Repositories:**

**To `.github-private` (Private):**
- Deployment workflows (deploy_staging.yml, production deployments)
- Release pipelines with sensitive logic (release_pipeline.yml)
- Workflows containing proprietary business logic
- Scripts with deployment credentials handling

**To `MokoStandards` (Public):**
- Quality check workflows (php_quality.yml)
- Testing workflows (joomla_testing.yml)
- Public CI/CD templates and examples
- Shared utility scripts (extension_utils.py, common.py)

### Phase 2: Structure Setup

Create both centralized repositories with appropriate structure:

**`.github-private` Repository Structure:**
```
.github-private/
├── .github/
│   └── workflows/
│       ├── reusable-release-pipeline.yml     (sensitive)
│       ├── reusable-deploy-staging.yml       (sensitive)
│       └── reusable-deploy-production.yml    (sensitive)
├── scripts/
│   ├── deployment/
│   │   ├── deploy.sh
│   │   └── rollback.sh
│   └── release/
│       └── publish.py
└── docs/
    ├── WORKFLOWS.md
    └── DEPLOYMENT.md
```

**`MokoStandards` Repository Structure:**
```
MokoStandards/
├── .github/
│   └── workflows/
│       ├── reusable-php-quality.yml          (public)
│       ├── reusable-joomla-testing.yml       (public)
│       ├── reusable-dolibarr-testing.yml     (public)
│       └── reusable-security-scan.yml        (public)
├── scripts/
│   ├── shared/
│   │   ├── extension_utils.py
│   │   ├── common.py
│   │   └── validators/
│   └── templates/
│       ├── workflow-templates/
│       └── action-templates/
└── docs/
    ├── STANDARDS.md
    ├── WORKFLOWS.md
    └── CONTRIBUTING.md
```

### Phase 3: Conversion

**Main Repository Workflows (Simplified Callers):**

**Example 1: Public Quality Workflow** (calls `MokoStandards`)

`php_quality.yml`:
```yaml
name: PHP Code Quality

on:
  pull_request:
    branches: [ main, dev/*, rc/* ]
  push:
    branches: [ main, dev/*, rc/* ]

jobs:
  quality:
    uses: mokoconsulting-tech/MokoStandards/.github/workflows/reusable-php-quality.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
    secrets: inherit
```

**Example 2: Private Deployment Workflow** (calls `.github-private`)

`deploy.yml`:
```yaml
name: Deploy to Staging

on:
  workflow_dispatch:
    inputs:
      environment:
        required: true
        type: choice
        options: [staging, production]

jobs:
  deploy:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-deploy.yml@main
    with:
      environment: ${{ inputs.environment }}
      platform: 'joomla'
    secrets: inherit
```

**Centralized Reusable Workflow Examples:**

**In `MokoStandards` (Public):**

Located in `MokoStandards/.github/workflows/reusable-php-quality.yml`:
```yaml
name: Reusable PHP Quality Workflow

on:
  workflow_call:
    inputs:
      php-versions:
        required: false
        type: string
        default: '["8.0", "8.1", "8.2", "8.3"]'

jobs:
  # Full implementation here
```

**In `.github-private` (Private):**

Located in `.github-private/.github/workflows/reusable-deploy.yml`:
```yaml
name: Reusable Deployment Workflow

on:
  workflow_call:
    inputs:
      environment:
        required: true
        type: string
    secrets:
      FTP_SERVER:
        required: true
      FTP_USER:
        required: true

jobs:
  # Deployment logic here
```

### Phase 4: Migration Steps

1. **Create both centralized repositories**
   - **`.github-private`**: Private repository (mokoconsulting-tech/.github-private)
   - **`MokoStandards`**: Public repository (mokoconsulting-tech/MokoStandards)
   - Initialize each with README and LICENSE
   - Set up appropriate branch protection rules
   - Configure access: private (team only) and public (community)

2. **Categorize and move workflows**
   - **Sensitive workflows → `.github-private`**:
     - deployment workflows, release pipelines
     - Convert to reusable workflows with `workflow_call` triggers
     - Add security controls and audit logging
   
   - **Public workflows → `MokoStandards`**:
     - quality checks, testing workflows
     - Add comprehensive documentation and examples
     - Enable community contributions
   
   - Test each workflow in isolation
   - Add proper input parameters and secrets handling

3. **Update main repository workflows**
   - Replace with simplified caller workflows pointing to appropriate repository
   - Update documentation with new workflow references
   - Test integration end-to-end

4. **Migrate shared scripts**
   - **Deployment/sensitive scripts → `.github-private/scripts/`**
   - **Public utilities → `MokoStandards/scripts/shared/`**
   - Keep product-specific scripts in main repo
   - Update import paths and references

5. **Update documentation**
   - Document workflow calling conventions
   - Update development guides
   - Create troubleshooting guides

## Configuration Requirements

### Secrets to Configure

**In .github-private repository:**
- Deployment credentials (FTP_SERVER, FTP_USER, FTP_KEY, etc.)
- API tokens for external services
- Signing keys

**In main repository:**
- Inherit secrets from organization level
- Repository-specific overrides only

### Variables to Configure

**Organization-level variables:**
- DEPLOY_DRY_RUN
- FTP_PATH_SUFFIX
- PHP_VERSIONS (default)

**Repository-level variables:**
- Repository-specific configurations
- Feature flags

## Workflow Categorization

### Workflows to Centralize

#### To `MokoStandards` (Public Repository)

1. **php_quality.yml** ✓
   - Reason: Standardized quality checks across all PHP projects
   - Type: Reusable workflow
   - Sensitivity: Low (no secrets, publicly shareable)
   - Target: `MokoStandards/.github/workflows/reusable-php-quality.yml`
   
2. **joomla_testing.yml** ✓
   - Reason: Shared across Joomla projects, community benefit
   - Type: Reusable workflow
   - Sensitivity: Low (testing patterns, no sensitive data)
   - Target: `MokoStandards/.github/workflows/reusable-joomla-testing.yml`

3. **ci.yml** (partially)
   - Reason: Generic CI patterns can be shared
   - Type: Reusable workflow template
   - Sensitivity: Low (standard CI practices)
   - Target: `MokoStandards/.github/workflows/reusable-ci-base.yml`

#### To `.github-private` (Private Repository)

1. **release_pipeline.yml** ✓
   - Reason: Complex release logic, contains sensitive deployment steps
   - Type: Reusable workflow
   - Sensitivity: High (deployment credentials, business logic)
   - Target: `.github-private/.github/workflows/reusable-release-pipeline.yml`

2. **deploy_staging.yml** ✓
   - Reason: Contains deployment credentials and proprietary logic
   - Type: Reusable workflow
   - Sensitivity: High (FTP credentials, server details)
   - Target: `.github-private/.github/workflows/reusable-deploy-staging.yml`

3. **deploy_production.yml** ✓
   - Reason: Production deployment with strict security requirements
   - Type: Reusable workflow
   - Sensitivity: Critical (production access)
   - Target: `.github-private/.github/workflows/reusable-deploy-production.yml`

### Workflows to Keep Local (Main Repository)

1. **ci.yml** (project-specific parts)
   - Reason: Repository-specific CI steps
   - Can call centralized workflows from both `MokoStandards` and `.github-private`

2. **repo_health.yml**
   - Reason: Repository-specific health checks and metrics
   - Keep local with option to extend from `MokoStandards` base

3. **version_branch.yml**
   - Reason: Project-specific versioning strategy
   - Keep local

## Scripts Categorization

### Scripts to Centralize

#### To `MokoStandards` (Public)

1. **scripts/lib/extension_utils.py** ✓
   - Shared across all extension projects
   - Platform detection logic (Joomla/Dolibarr)
   - Target: `MokoStandards/scripts/shared/extension_utils.py`

2. **scripts/lib/common.py** ✓
   - Universal utility functions
   - No project-specific or sensitive logic
   - Target: `MokoStandards/scripts/shared/common.py`

3. **scripts/release/detect_platform.py** ✓
   - Platform detection helper
   - Publicly useful for other projects
   - Target: `MokoStandards/scripts/shared/detect_platform.py`

#### To `.github-private` (Private)

1. **scripts/release/deployment/** ✓
   - Deployment scripts with credential handling
   - Target: `.github-private/scripts/deployment/`

2. **scripts/release/publish.py** (if sensitive)
   - Release publishing with proprietary logic
   - Target: `.github-private/scripts/release/publish.py`

### Scripts to Keep Local

1. **scripts/lib/joomla_manifest.py**
   - Joomla-specific, but project may have customizations
   - Evaluate based on actual usage

2. **scripts/validate/** (most)
   - Project-specific validation rules
   - Keep local unless truly generic

3. **scripts/release/package_extension.py**
   - Uses shared libraries but has project-specific logic
   - Keep local, depend on centralized libs

## Benefits After Migration

### For Development Team
- ✅ Simplified workflow files in main repository
- ✅ Easier to understand and maintain
- ✅ Consistent CI/CD across all projects
- ✅ Faster updates (update once, applies everywhere)

### For Security
- ✅ Sensitive credentials isolated in private repository
- ✅ Controlled access to deployment logic
- ✅ Audit trail for CI/CD changes

### For Organization
- ✅ Centralized CI/CD governance
- ✅ Standardized processes across projects
- ✅ Reduced duplication
- ✅ Easier onboarding for new projects

## Testing Plan

### Pre-Migration Testing
1. ✅ Document all current workflows and their triggers
2. ✅ Identify all secrets and variables used
3. ✅ Create inventory of external dependencies

### During Migration
1. Create .github-private repository in test organization first
2. Convert one workflow at a time
3. Test with feature branches
4. Validate all trigger conditions work
5. Verify secret inheritance

### Post-Migration Validation
1. Run full CI/CD pipeline on test branch
2. Verify all workflows execute correctly
3. Check deployment to staging
4. Monitor for any broken integrations
5. Update runbooks and documentation

## Rollback Plan

If issues arise during migration:

1. **Immediate Rollback**: Revert caller workflow to inline implementation
2. **Keep Both**: Maintain both local and centralized workflows temporarily
3. **Gradual Migration**: Move workflows one at a time with validation periods

## Timeline

- **Week 1**: Create .github-private repository, set up structure
- **Week 2**: Convert and test php_quality.yml
- **Week 3**: Convert and test release_pipeline.yml and deploy_staging.yml
- **Week 4**: Convert remaining workflows, finalize documentation
- **Week 5**: Complete migration, monitor, and optimize

## Action Items

### Immediate (This PR)
- [x] Create migration plan document
- [ ] Review and approve migration strategy
- [ ] Identify team members responsible for migration

### Next Steps
- [ ] Create .github-private repository
- [ ] Set up repository structure
- [ ] Configure secrets and variables at organization level
- [ ] Begin workflow conversion (starting with php_quality.yml)
- [ ] Test reusable workflow pattern
- [ ] Document lessons learned

## Technical Architecture

### Communication Flow

```
┌─────────────────────────────────────────────────────────────┐
│ Main Repository (.github/workflows/)                         │
│  ┌───────────────────────────────────────────────────────┐  │
│  │ Caller Workflow (php_quality.yml)                     │  │
│  │ - Defines triggers (push, PR, etc.)                   │  │
│  │ - Sets permissions                                     │  │
│  │ - Passes inputs and secrets                           │  │
│  └───────────────────┬───────────────────────────────────┘  │
│                      │ uses: org/.github-private/...@main   │
└──────────────────────┼──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│ .github-private Repository (.github/workflows/)              │
│  ┌───────────────────────────────────────────────────────┐  │
│  │ Reusable Workflow (reusable-php-quality.yml)          │  │
│  │ - workflow_call trigger                               │  │
│  │ - Receives inputs from caller                         │  │
│  │ - Inherits secrets from organization                  │  │
│  │ - Executes CI/CD logic                                │  │
│  │ - Returns job outputs                                 │  │
│  └───────────────────┬───────────────────────────────────┘  │
│                      │                                       │
│  ┌───────────────────▼───────────────────────────────────┐  │
│  │ Shared Scripts (scripts/shared/)                      │  │
│  │ - extension_utils.py                                  │  │
│  │ - deployment utilities                                │  │
│  │ - validation helpers                                  │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### Secret and Variable Inheritance Model

```
Organization Level (Settings > Secrets and Variables)
├── Secrets
│   ├── FTP_SERVER                    (inherited by all repos)
│   ├── FTP_USER                    (inherited by all repos)
│   ├── FTP_KEY                     (inherited by all repos)
│   ├── FTP_PASSWORD                (inherited by all repos)
│   ├── FTP_PATH                    (inherited by all repos)
│   └── API_TOKENS                  (inherited by all repos)
│
├── Variables
│   ├── DEPLOY_DRY_RUN: false       (can be overridden)
│   ├── FTP_PROTOCOL: sftp          (can be overridden)
│   ├── FTP_PORT: 22                (can be overridden)
│   └── PHP_VERSIONS: ["8.0","8.1","8.2","8.3"]
│
└── Repository Level (Override if needed)
    ├── moko-cassiopeia
    │   └── Variables
    │       └── DEPLOY_DRY_RUN: true  (override for this repo)
    │
    └── other-project
        └── Variables
            └── FTP_PATH_SUFFIX: /custom  (repo-specific)
```

### Workflow Version Pinning Strategy

#### Option 1: Track Main Branch (Automatic Updates)
```yaml
uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@main
```
**Pros**: Always get latest features and fixes
**Cons**: Breaking changes may affect workflows
**Use Case**: Development branches, staging deployments

#### Option 2: Pin to Semantic Version (Stable)
```yaml
uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@v1
# or
uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@v1.2
```
**Pros**: Stable, predictable behavior
**Cons**: Manual updates required
**Use Case**: Production deployments, critical workflows

#### Option 3: Pin to Specific Commit SHA (Maximum Stability)
```yaml
uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@a1b2c3d
```
**Pros**: Immutable, guaranteed consistency
**Cons**: No automatic updates, harder to maintain
**Use Case**: Compliance requirements, audit trails

### Detailed Workflow Conversion Examples

#### Before: Inline Workflow (Current State)

**`.github/workflows/php_quality.yml` (93 lines)**
```yaml
name: PHP Code Quality

on:
  pull_request:
    branches: [ main, dev/*, rc/* ]
  push:
    branches: [ main, dev/*, rc/* ]

permissions:
  contents: read

jobs:
  php-compatibility-check:
    name: PHP Compatibility Check
    runs-on: ubuntu-latest
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: mbstring, xml, curl, zip

      - name: Install PHP_CodeSniffer and PHPCompatibility
        run: |
          composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies
          composer global require "phpcompatibility/php-compatibility:^9.0" --with-all-dependencies
          phpcs --config-set installed_paths ~/.composer/vendor/phpcompatibility/php-compatibility

      - name: Check PHP 8.0+ Compatibility
        run: phpcs --standard=PHPCompatibility --runtime-set testVersion 8.0- src/

  phpcs:
    name: PHP_CodeSniffer
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php-version: ['8.0', '8.1', '8.2', '8.3']
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP ${{ matrix.php-version }}
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
          extensions: mbstring, xml, curl, zip

      - name: Install PHP_CodeSniffer
        run: composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies

      - name: Run PHP_CodeSniffer
        run: phpcs --standard=phpcs.xml src/

  # ... additional jobs
```

#### After: Caller Workflow (Target State)

**`.github/workflows/php_quality.yml` (15 lines - 84% reduction)**
```yaml
name: PHP Code Quality

on:
  pull_request:
    branches: [ main, dev/*, rc/* ]
  push:
    branches: [ main, dev/*, rc/* ]

permissions:
  contents: read

jobs:
  quality-checks:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
      php-extensions: 'mbstring, xml, curl, zip'
      source-directory: 'src'
      phpcs-standard: 'phpcs.xml'
      enable-phpcompat: true
      enable-phpstan: true
      phpstan-level: 'max'
    secrets: inherit
```

**Benefits**:
- 84% reduction in code
- Centralized maintenance
- Consistent across all repositories
- Easy to add new checks (update once in .github-private)
- Version control with semantic versioning

#### Reusable Workflow (in .github-private)

**`.github-private/.github/workflows/reusable-php-quality.yml`**
```yaml
name: Reusable PHP Quality Checks

on:
  workflow_call:
    inputs:
      php-versions:
        description: 'JSON array of PHP versions to test against'
        required: false
        type: string
        default: '["8.0", "8.1", "8.2", "8.3"]'
      php-extensions:
        description: 'Comma-separated list of PHP extensions'
        required: false
        type: string
        default: 'mbstring, xml, curl, zip'
      source-directory:
        description: 'Source code directory to analyze'
        required: false
        type: string
        default: 'src'
      phpcs-standard:
        description: 'PHPCS standard configuration file'
        required: false
        type: string
        default: 'phpcs.xml'
      enable-phpcompat:
        description: 'Enable PHP Compatibility checks'
        required: false
        type: boolean
        default: true
      enable-phpstan:
        description: 'Enable PHPStan static analysis'
        required: false
        type: boolean
        default: true
      phpstan-level:
        description: 'PHPStan analysis level'
        required: false
        type: string
        default: 'max'
      phpstan-config:
        description: 'PHPStan configuration file'
        required: false
        type: string
        default: 'phpstan.neon'
    outputs:
      phpcs-passed:
        description: 'Whether PHPCS checks passed'
        value: ${{ jobs.phpcs.outputs.passed }}
      phpstan-passed:
        description: 'Whether PHPStan checks passed'
        value: ${{ jobs.phpstan.outputs.passed }}

permissions:
  contents: read

jobs:
  php-compatibility-check:
    name: PHP Compatibility Check
    runs-on: ubuntu-latest
    if: ${{ inputs.enable-phpcompat }}
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: ${{ inputs.php-extensions }}
          coverage: none

      - name: Cache Composer dependencies
        uses: actions/cache@v4
        with:
          path: |
            ~/.composer/cache
            ~/.composer/vendor
          key: ${{ runner.os }}-composer-phpcompat-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            ${{ runner.os }}-composer-phpcompat-

      - name: Install PHP_CodeSniffer and PHPCompatibility
        run: |
          composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies
          composer global require "phpcompatibility/php-compatibility:^9.0" --with-all-dependencies
          phpcs --config-set installed_paths ~/.composer/vendor/phpcompatibility/php-compatibility

      - name: Check PHP 8.0+ Compatibility
        run: |
          phpcs --standard=PHPCompatibility \
                --runtime-set testVersion 8.0- \
                --report=full \
                --report-file=phpcompat-report.txt \
                ${{ inputs.source-directory }}/

      - name: Upload PHPCompatibility Report
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: phpcompat-report
          path: phpcompat-report.txt
          retention-days: 30

  phpcs:
    name: PHP_CodeSniffer (PHP ${{ matrix.php-version }})
    runs-on: ubuntu-latest
    outputs:
      passed: ${{ steps.check.outputs.passed }}
    strategy:
      fail-fast: false
      matrix:
        php-version: ${{ fromJson(inputs.php-versions) }}
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP ${{ matrix.php-version }}
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
          extensions: ${{ inputs.php-extensions }}
          coverage: none

      - name: Cache Composer dependencies
        uses: actions/cache@v4
        with:
          path: |
            ~/.composer/cache
            ~/.composer/vendor
          key: ${{ runner.os }}-php${{ matrix.php-version }}-composer-phpcs-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            ${{ runner.os }}-php${{ matrix.php-version }}-composer-phpcs-

      - name: Install PHP_CodeSniffer
        run: composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies

      - name: Run PHP_CodeSniffer
        id: check
        run: |
          phpcs --standard=${{ inputs.phpcs-standard }} \
                --report=full \
                --report-file=phpcs-report-${{ matrix.php-version }}.txt \
                ${{ inputs.source-directory }}/
          echo "passed=true" >> $GITHUB_OUTPUT

      - name: Upload PHPCS Report
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: phpcs-report-php${{ matrix.php-version }}
          path: phpcs-report-${{ matrix.php-version }}.txt
          retention-days: 30

  phpstan:
    name: PHPStan (PHP ${{ matrix.php-version }})
    runs-on: ubuntu-latest
    if: ${{ inputs.enable-phpstan }}
    outputs:
      passed: ${{ steps.check.outputs.passed }}
    strategy:
      fail-fast: false
      matrix:
        php-version: ${{ fromJson(inputs.php-versions) }}
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP ${{ matrix.php-version }}
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
          extensions: ${{ inputs.php-extensions }}
          coverage: none

      - name: Cache Composer dependencies
        uses: actions/cache@v4
        with:
          path: |
            ~/.composer/cache
            ~/.composer/vendor
          key: ${{ runner.os }}-php${{ matrix.php-version }}-composer-phpstan-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            ${{ runner.os }}-php${{ matrix.php-version }}-composer-phpstan-

      - name: Install PHPStan
        run: composer global require "phpstan/phpstan:^1.0" --with-all-dependencies

      - name: Run PHPStan
        id: check
        run: |
          phpstan analyse \
                  --configuration=${{ inputs.phpstan-config }} \
                  --level=${{ inputs.phpstan-level }} \
                  --error-format=table \
                  --no-progress \
                  --no-interaction \
                  ${{ inputs.source-directory }}/ \
                  > phpstan-report-${{ matrix.php-version }}.txt 2>&1
          echo "passed=true" >> $GITHUB_OUTPUT

      - name: Upload PHPStan Report
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: phpstan-report-php${{ matrix.php-version }}
          path: phpstan-report-${{ matrix.php-version }}.txt
          retention-days: 30
```

## Advanced Patterns and Best Practices

### Pattern 1: Conditional Workflow Execution

Allow repositories to enable/disable specific checks:

```yaml
# Caller workflow
jobs:
  quality:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@v1
    with:
      enable-phpcompat: ${{ github.event_name == 'pull_request' }}  # Only on PRs
      enable-phpstan: ${{ contains(github.event.head_commit.message, '[phpstan]') }}  # Only if commit message contains [phpstan]
      phpstan-level: ${{ github.ref == 'refs/heads/main' && 'max' || '6' }}  # Stricter on main
```

### Pattern 2: Matrix Strategy Inheritance

Pass complex matrix configurations:

```yaml
# Caller workflow
jobs:
  test:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-test.yml@v1
    with:
      test-matrix: |
        {
          "php": ["8.0", "8.1", "8.2", "8.3"],
          "joomla": ["4.4", "5.0"],
          "database": ["mysql:8.0", "postgresql:14"]
        }
```

### Pattern 3: Composite Actions for Reusability

Break down workflows into composite actions for even more reusability:

**`.github-private/.github/actions/setup-php-quality/action.yml`**
```yaml
name: 'Setup PHP Quality Tools'
description: 'Install PHP CodeSniffer, PHPCompatibility, and PHPStan'

inputs:
  php-version:
    description: 'PHP version to setup'
    required: true
  enable-phpstan:
    description: 'Install PHPStan'
    required: false
    default: 'true'

runs:
  using: 'composite'
  steps:
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: ${{ inputs.php-version }}
        extensions: mbstring, xml, curl, zip
        coverage: none

    - name: Install PHPCS and PHPCompatibility
      shell: bash
      run: |
        composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies
        composer global require "phpcompatibility/php-compatibility:^9.0" --with-all-dependencies
        phpcs --config-set installed_paths ~/.composer/vendor/phpcompatibility/php-compatibility

    - name: Install PHPStan
      if: inputs.enable-phpstan == 'true'
      shell: bash
      run: composer global require "phpstan/phpstan:^1.0" --with-all-dependencies
```

**Usage in reusable workflow:**
```yaml
- name: Setup PHP Quality Tools
  uses: mokoconsulting-tech/.github-private/.github/actions/setup-php-quality@v1
  with:
    php-version: ${{ matrix.php-version }}
    enable-phpstan: true
```

### Pattern 4: Workflow Outputs and Chaining

Use outputs to chain workflows:

```yaml
# Caller workflow
jobs:
  quality:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'

  deploy:
    needs: quality
    if: ${{ needs.quality.outputs.phpcs-passed == 'true' && needs.quality.outputs.phpstan-passed == 'true' }}
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-deploy.yml@v1
    with:
      environment: staging
```

## Security Considerations

### Principle of Least Privilege

**Organization Secrets Access**:
- Only grant `.github-private` repository access to necessary secrets
- Use environment-specific secrets (staging, production)
- Rotate secrets regularly

**Repository Permissions**:
```yaml
# .github-private repository settings
Permissions:
  - Read: All organization members (for viewing workflows)
  - Write: DevOps team only
  - Admin: Organization owners only

Branch Protection (main):
  - Require pull request reviews (2 approvals)
  - Require status checks to pass
  - Require branches to be up to date
  - No force pushes
  - No deletions
```

### Secret Masking

Ensure secrets are never exposed in logs:

```yaml
# BAD - Exposes secret in logs
- name: Deploy
  run: echo "Deploying with password: ${{ secrets.FTP_PASSWORD }}"

# GOOD - Secret is masked
- name: Deploy
  run: |
    echo "::add-mask::${{ secrets.FTP_PASSWORD }}"
    ./deploy.sh --password "${{ secrets.FTP_PASSWORD }}"
```

### Audit Trail

Track all workflow executions:

```yaml
# Add to all reusable workflows
- name: Audit Log
  if: always()
  run: |
    echo "Workflow executed by: ${{ github.actor }}"
    echo "Repository: ${{ github.repository }}"
    echo "Branch: ${{ github.ref }}"
    echo "Commit: ${{ github.sha }}"
    echo "Workflow: ${{ github.workflow }}"
    echo "Run ID: ${{ github.run_id }}"
    echo "Timestamp: $(date -u +%Y-%m-%dT%H:%M:%SZ)"
```

## Performance Optimization

### Caching Strategy

**Composer Dependencies**:
```yaml
- name: Cache Composer
  uses: actions/cache@v4
  with:
    path: |
      ~/.composer/cache
      ~/.composer/vendor
    key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
    restore-keys: |
      ${{ runner.os }}-composer-
```

**Tool Installations**:
```yaml
- name: Cache Quality Tools
  uses: actions/cache@v4
  with:
    path: |
      ~/.composer/vendor/squizlabs/php_codesniffer
      ~/.composer/vendor/phpstan/phpstan
    key: ${{ runner.os }}-php-tools-v1
```

### Parallel Execution

Maximize parallelism:

```yaml
strategy:
  fail-fast: false  # Don't stop other jobs if one fails
  max-parallel: 10  # Run up to 10 jobs simultaneously
  matrix:
    php-version: ['8.0', '8.1', '8.2', '8.3']
    joomla-version: ['4.4', '5.0']
```

### Job Concurrency Control

Prevent wasted resources:

```yaml
concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: ${{ github.ref != 'refs/heads/main' }}  # Cancel old runs except on main
```

## Monitoring and Observability

### Workflow Status Notifications

**Slack Integration**:
```yaml
- name: Notify Slack on Failure
  if: failure()
  uses: slackapi/slack-github-action@v1
  with:
    payload: |
      {
        "text": "Workflow failed: ${{ github.workflow }}",
        "blocks": [
          {
            "type": "section",
            "text": {
              "type": "mrkdwn",
              "text": "*Workflow*: ${{ github.workflow }}\n*Repository*: ${{ github.repository }}\n*Branch*: ${{ github.ref }}\n*Actor*: ${{ github.actor }}\n*Run*: <${{ github.server_url }}/${{ github.repository }}/actions/runs/${{ github.run_id }}|View Run>"
            }
          }
        ]
      }
  env:
    SLACK_WEBHOOK_URL: ${{ secrets.SLACK_WEBHOOK_URL }}
```

### Metrics Collection

Track workflow execution metrics:

```yaml
- name: Record Metrics
  if: always()
  run: |
    curl -X POST "${{ secrets.METRICS_ENDPOINT }}" \
         -H "Content-Type: application/json" \
         -d '{
           "workflow": "${{ github.workflow }}",
           "repository": "${{ github.repository }}",
           "status": "${{ job.status }}",
           "duration": "${{ steps.start-time.outputs.elapsed }}",
           "timestamp": "$(date -u +%Y-%m-%dT%H:%M:%SZ)"
         }'
```

## Troubleshooting Guide

### Common Issues and Solutions

#### Issue 1: "Workflow not found" error

**Symptom**:
```
Error: Unable to resolve action `mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@main`,
unable to find version `main`
```

**Solutions**:
1. Verify `.github-private` repository exists and is accessible
2. Check repository permissions (must have at least read access)
3. Verify branch name (main vs master)
4. Ensure workflow file exists at specified path

**Verification Commands**:
```bash
# Check repository access
gh api repos/mokoconsulting-tech/.github-private

# List workflow files
gh api repos/mokoconsulting-tech/.github-private/contents/.github/workflows

# Check branch exists
gh api repos/mokoconsulting-tech/.github-private/branches/main
```

#### Issue 2: Secrets not inherited

**Symptom**:
```
Error: Secret FTP_PASSWORD is not set
```

**Solutions**:
1. Ensure `secrets: inherit` is set in caller workflow
2. Verify secret exists at organization level
3. Check `.github-private` repository has access to organization secrets
4. Verify secret names match exactly (case-sensitive)

**Verification**:
```bash
# List organization secrets
gh api orgs/mokoconsulting-tech/actions/secrets

# Check repository secret access
gh api repos/mokoconsulting-tech/.github-private/actions/secrets
```

#### Issue 3: Workflow runs on wrong trigger

**Symptom**:
Workflow runs when it shouldn't, or doesn't run when expected

**Solutions**:
1. Review `on:` triggers in caller workflow
2. Check branch protection rules
3. Verify path filters if using `paths:` or `paths-ignore:`
4. Test with different trigger events

**Example Fix**:
```yaml
# Before (runs on all pushes)
on:
  push:

# After (runs only on main and feature branches)
on:
  push:
    branches:
      - main
      - 'feature/**'
```

#### Issue 4: Matrix strategy not working

**Symptom**:
Only one job runs instead of multiple matrix jobs

**Solutions**:
1. Verify JSON syntax in matrix definition
2. Use `fromJson()` for string inputs
3. Check for empty arrays
4. Validate matrix variable references

**Example**:
```yaml
# Caller workflow
with:
  php-versions: '["8.0", "8.1", "8.2"]'  # Must be JSON string

# Reusable workflow
matrix:
  php-version: ${{ fromJson(inputs.php-versions) }}  # Convert to array
```

## References

- [GitHub Reusable Workflows Documentation](https://docs.github.com/en/actions/using-workflows/reusing-workflows)
- [GitHub Security Best Practices](https://docs.github.com/en/actions/security-guides/security-hardening-for-github-actions)
- [Workflow Syntax](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions)
- [workflow_call Event](https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#workflow_call)
- [GitHub Actions Caching](https://docs.github.com/en/actions/using-workflows/caching-dependencies-to-speed-up-workflows)
- [Composite Actions](https://docs.github.com/en/actions/creating-actions/creating-a-composite-action)

## Support

For questions or issues during migration:
- Review this document
- Check GitHub Actions documentation
- Contact: DevOps team
- Slack: #devops-support

---

**Status**: Ready for Implementation  
**Author**: GitHub Copilot  
**Date**: 2026-01-05  
**Version**: 2.0
**Last Updated**: 2026-01-05
