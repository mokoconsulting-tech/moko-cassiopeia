# Reusable Workflow Templates for .github-private

This directory contains example templates for reusable workflows that will be moved to the `.github-private` repository.

## Structure

```
.github-private/
├── .github/
│   └── workflows/
│       ├── reusable-php-quality.yml
│       ├── reusable-release-pipeline.yml
│       ├── reusable-joomla-testing.yml
│       └── reusable-deploy-staging.yml
└── docs/
    └── USAGE.md
```

## Usage in Main Repositories

### Basic Pattern

```yaml
name: Workflow Name

on:
  push:
    branches: [ main ]

jobs:
  job-name:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-workflow-name.yml@main
    with:
      # Input parameters
      parameter-name: value
    secrets: inherit
```

### Example: PHP Quality Check

**In main repository** (`.github/workflows/php_quality.yml`):
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
  php-quality:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@main
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
      php-extensions: 'mbstring, xml, curl, zip'
      working-directory: '.'
    secrets: inherit
```

### Example: Release Pipeline

**In main repository** (`.github/workflows/release.yml`):
```yaml
name: Release Pipeline

on:
  workflow_dispatch:
    inputs:
      release_classification:
        description: 'Release classification'
        required: true
        default: 'auto'
        type: choice
        options:
          - auto
          - rc
          - stable

permissions:
  contents: write
  id-token: write
  attestations: write

jobs:
  release:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-release-pipeline.yml@main
    with:
      release_classification: ${{ inputs.release_classification }}
      platform: 'joomla'  # or 'dolibarr'
    secrets: inherit
```

## Reusable Workflow Template Examples

### Template: reusable-php-quality.yml

```yaml
name: Reusable PHP Quality Workflow

on:
  workflow_call:
    inputs:
      php-versions:
        description: 'JSON array of PHP versions to test'
        required: false
        type: string
        default: '["8.0", "8.1", "8.2", "8.3"]'
      php-extensions:
        description: 'PHP extensions to install'
        required: false
        type: string
        default: 'mbstring, xml, curl, zip'
      working-directory:
        description: 'Working directory'
        required: false
        type: string
        default: '.'

permissions:
  contents: read

jobs:
  compatibility-check:
    name: PHP Compatibility Check
    runs-on: ubuntu-latest
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: ${{ inputs.php-extensions }}

      - name: Install dependencies
        run: |
          composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies
          composer global require "phpcompatibility/php-compatibility:^9.0" --with-all-dependencies
          phpcs --config-set installed_paths ~/.composer/vendor/phpcompatibility/php-compatibility

      - name: Check PHP 8.0+ Compatibility
        working-directory: ${{ inputs.working-directory }}
        run: phpcs --standard=PHPCompatibility --runtime-set testVersion 8.0- src/

  phpcs:
    name: PHP_CodeSniffer
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php-version: ${{ fromJson(inputs.php-versions) }}
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
          extensions: ${{ inputs.php-extensions }}

      - name: Install PHP_CodeSniffer
        run: composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies

      - name: Run PHP_CodeSniffer
        working-directory: ${{ inputs.working-directory }}
        run: phpcs --standard=phpcs.xml src/

  phpstan:
    name: PHPStan Static Analysis
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php-version: ${{ fromJson(inputs.php-versions) }}
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
          extensions: ${{ inputs.php-extensions }}

      - name: Install PHPStan
        run: |
          composer global require phpstan/phpstan "^1.0" --with-all-dependencies

      - name: Run PHPStan
        working-directory: ${{ inputs.working-directory }}
        run: phpstan analyse --configuration=phpstan.neon
```

### Template: reusable-release-pipeline.yml

```yaml
name: Reusable Release Pipeline

on:
  workflow_call:
    inputs:
      release_classification:
        description: 'Release classification (auto, rc, stable)'
        required: false
        type: string
        default: 'auto'
      platform:
        description: 'Extension platform (joomla, dolibarr)'
        required: false
        type: string
        default: 'joomla'
    secrets:
      FTP_HOST:
        required: true
      FTP_USER:
        required: true
      FTP_KEY:
        required: false
      FTP_PASSWORD:
        required: false
      FTP_PATH:
        required: true
      FTP_PROTOCOL:
        required: false
      FTP_PORT:
        required: false

permissions:
  contents: write
  id-token: write
  attestations: write

jobs:
  guard:
    name: Guardrails and Metadata
    runs-on: ubuntu-latest
    outputs:
      version: ${{ steps.meta.outputs.version }}
      # ... other outputs
    steps:
      # Guard logic here

  build-and-release:
    name: Build and Release
    needs: guard
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Detect Platform
        id: platform
        run: |
          python3 scripts/release/detect_platform.py src

      - name: Build ZIP
        run: |
          # Build logic here

      # ... remaining steps
```

## Benefits of Centralized Workflows

1. **Single Source of Truth**: Update workflow logic in one place
2. **Version Control**: Pin to specific versions (@v1, @main, @sha)
3. **Testing**: Test changes in .github-private before rolling out
4. **Security**: Keep sensitive logic private
5. **Reusability**: Use same workflow across multiple repositories

## Migration Checklist

- [ ] Create .github-private repository
- [ ] Set up repository permissions and protection rules
- [ ] Move workflow files and convert to reusable format
- [ ] Update main repository workflows to call reusable workflows
- [ ] Configure secrets at organization level
- [ ] Test all workflows end-to-end
- [ ] Update documentation
- [ ] Train team on new workflow structure

## Troubleshooting

### Workflow Not Found
- Ensure .github-private repository has correct permissions
- Verify workflow path is correct
- Check that target branch/tag exists

### Secrets Not Available
- Use `secrets: inherit` in caller workflow
- Configure secrets at organization or repository level
- Verify secret names match between caller and reusable workflow

### Inputs Not Passed Correctly
- Check input types (string, boolean, number)
- Verify required vs optional inputs
- Use correct JSON format for arrays

## References

- [Reusing Workflows](https://docs.github.com/en/actions/using-workflows/reusing-workflows)
- [workflow_call Event](https://docs.github.com/en/actions/using-workflows/events-that-trigger-workflows#workflow_call)
- [Calling Reusable Workflows](https://docs.github.com/en/actions/using-workflows/reusing-workflows#calling-a-reusable-workflow)
