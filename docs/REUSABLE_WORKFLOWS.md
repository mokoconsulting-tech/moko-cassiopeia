# Reusable Workflow Templates for Centralized Repositories

This document contains example templates for reusable workflows that will be distributed across two centralized repositories based on sensitivity.

## Dual Repository Architecture

### `MokoStandards` (Public Repository)
- **Purpose**: Public, community-accessible workflows and standards
- **Content**: Quality checks, testing workflows, public CI/CD templates
- **Visibility**: Public (open source)
- **Target Audience**: Internal teams and external community

### `.github-private` (Private Repository)
- **Purpose**: Sensitive, proprietary workflows and deployment logic
- **Content**: Deployment workflows, release pipelines, credential handling
- **Visibility**: Private (organization only)
- **Target Audience**: Internal teams only

## Repository Structures

**`MokoStandards` (Public):**
```
MokoStandards/
├── .github/
│   └── workflows/
│       ├── reusable-php-quality.yml
│       ├── reusable-joomla-testing.yml
│       ├── reusable-dolibarr-testing.yml
│       └── reusable-security-scan.yml
├── scripts/
│   └── shared/
│       ├── extension_utils.py
│       └── common.py
└── docs/
    ├── STANDARDS.md
    └── USAGE.md
```

**`.github-private` (Private):**
```
.github-private/
├── .github/
│   └── workflows/
│       ├── reusable-release-pipeline.yml
│       ├── reusable-deploy-staging.yml
│       └── reusable-deploy-production.yml
├── scripts/
│   └── deployment/
└. docs/
    └── USAGE.md
```

## Usage in Main Repositories

### Basic Patterns

**Pattern 1: Calling Public Workflow from `MokoStandards`**

```yaml
name: Quality Checks

on:
  push:
    branches: [ main ]

jobs:
  quality:
    uses: mokoconsulting-tech/MokoStandards/.github/workflows/reusable-php-quality.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
    secrets: inherit
```

**Pattern 2: Calling Private Workflow from `.github-private`**

```yaml
name: Deploy

on:
  workflow_dispatch:

jobs:
  deploy:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-deploy.yml@main
    with:
      environment: staging
    secrets: inherit
```

**Pattern 3: Combining Both (Public Quality + Private Deployment)**

```yaml
name: CI/CD Pipeline

on:
  push:
    branches: [ main ]

jobs:
  quality:
    uses: mokoconsulting-tech/MokoStandards/.github/workflows/reusable-php-quality.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
    secrets: inherit

  deploy:
    needs: quality
    if: success()
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-deploy.yml@main
    with:
      environment: staging
    secrets: inherit
```

## Complete Workflow Examples by Repository

### Example 1: PHP Quality Check (MokoStandards - Public)

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

## Advanced Workflow Patterns

### Pattern 1: Multi-Stage Workflow with Approvals

**Scenario**: Deploy to staging automatically, but require approval for production.

**Caller Workflow** (`.github/workflows/deploy.yml`):
```yaml
name: Deploy Application

on:
  workflow_dispatch:
    inputs:
      environment:
        description: 'Deployment environment'
        required: true
        type: choice
        options:
          - staging
          - production

jobs:
  deploy-staging:
    if: ${{ inputs.environment == 'staging' }}
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-deploy.yml@v1
    with:
      environment: staging
      deploy-url: https://staging.example.com
      health-check-enabled: true
    secrets: inherit

  deploy-production:
    if: ${{ inputs.environment == 'production' }}
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-deploy.yml@v1
    with:
      environment: production
      deploy-url: https://production.example.com
      health-check-enabled: true
      require-approval: true
    secrets: inherit
```

**Reusable Workflow** (`.github-private/.github/workflows/reusable-deploy.yml`):
```yaml
name: Reusable Deployment Workflow

on:
  workflow_call:
    inputs:
      environment:
        required: true
        type: string
      deploy-url:
        required: true
        type: string
      health-check-enabled:
        required: false
        type: boolean
        default: true
      require-approval:
        required: false
        type: boolean
        default: false
    secrets:
      FTP_HOST:
        required: true
      FTP_USER:
        required: true
      FTP_PASSWORD:
        required: true
      FTP_PATH:
        required: true
    outputs:
      deployment-id:
        description: 'Unique deployment identifier'
        value: ${{ jobs.deploy.outputs.deployment-id }}
      deployment-url:
        description: 'URL where application was deployed'
        value: ${{ inputs.deploy-url }}

permissions:
  contents: read
  deployments: write

jobs:
  approval:
    name: Deployment Approval
    runs-on: ubuntu-latest
    if: ${{ inputs.require-approval }}
    environment:
      name: ${{ inputs.environment }}-approval
    steps:
      - name: Wait for Approval
        run: echo "Deployment to ${{ inputs.environment }} approved"

  deploy:
    name: Deploy to ${{ inputs.environment }}
    runs-on: ubuntu-latest
    needs: [approval]
    if: ${{ always() && (needs.approval.result == 'success' || needs.approval.result == 'skipped') }}
    outputs:
      deployment-id: ${{ steps.create-deployment.outputs.deployment-id }}
    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Create GitHub Deployment
        id: create-deployment
        uses: actions/github-script@v7
        with:
          script: |
            const deployment = await github.rest.repos.createDeployment({
              owner: context.repo.owner,
              repo: context.repo.repo,
              ref: context.sha,
              environment: '${{ inputs.environment }}',
              auto_merge: false,
              required_contexts: []
            });
            core.setOutput('deployment-id', deployment.data.id);
            return deployment.data.id;

      - name: Update Deployment Status (In Progress)
        uses: actions/github-script@v7
        with:
          script: |
            await github.rest.repos.createDeploymentStatus({
              owner: context.repo.owner,
              repo: context.repo.repo,
              deployment_id: ${{ steps.create-deployment.outputs.deployment-id }},
              state: 'in_progress',
              description: 'Deployment in progress'
            });

      - name: Deploy via SFTP
        run: |
          # Install lftp
          sudo apt-get update && sudo apt-get install -y lftp

          # Create deployment package
          tar -czf deployment.tar.gz src/

          # Upload via SFTP
          lftp -c "
            set sftp:auto-confirm yes;
            open sftp://${{ secrets.FTP_USER }}:${{ secrets.FTP_PASSWORD }}@${{ secrets.FTP_HOST }};
            cd ${{ secrets.FTP_PATH }};
            put deployment.tar.gz;
            quit
          "

      - name: Health Check
        if: ${{ inputs.health-check-enabled }}
        run: |
          echo "Performing health check on ${{ inputs.deploy-url }}"
          max_attempts=30
          attempt=0
          
          while [ $attempt -lt $max_attempts ]; do
            if curl -f -s "${{ inputs.deploy-url }}/health" > /dev/null; then
              echo "Health check passed!"
              exit 0
            fi
            echo "Health check failed, retrying... ($((attempt+1))/$max_attempts)"
            sleep 10
            attempt=$((attempt+1))
          done
          
          echo "Health check failed after $max_attempts attempts"
          exit 1

      - name: Update Deployment Status (Success)
        if: success()
        uses: actions/github-script@v7
        with:
          script: |
            await github.rest.repos.createDeploymentStatus({
              owner: context.repo.owner,
              repo: context.repo.repo,
              deployment_id: ${{ steps.create-deployment.outputs.deployment-id }},
              state: 'success',
              description: 'Deployment successful',
              environment_url: '${{ inputs.deploy-url }}'
            });

      - name: Update Deployment Status (Failure)
        if: failure()
        uses: actions/github-script@v7
        with:
          script: |
            await github.rest.repos.createDeploymentStatus({
              owner: context.repo.owner,
              repo: context.repo.repo,
              deployment_id: ${{ steps.create-deployment.outputs.deployment-id }},
              state: 'failure',
              description: 'Deployment failed'
            });
```

### Pattern 2: Dynamic Matrix from API/File

**Scenario**: Test against multiple versions dynamically fetched from external source.

**Caller Workflow**:
```yaml
name: Dynamic Matrix Testing

on:
  push:
    branches: [main, develop]

jobs:
  generate-matrix:
    runs-on: ubuntu-latest
    outputs:
      matrix: ${{ steps.set-matrix.outputs.matrix }}
    steps:
      - name: Fetch Supported Versions
        id: set-matrix
        run: |
          # Fetch from API or read from file
          MATRIX=$(curl -s https://api.example.com/supported-versions.json)
          echo "matrix=$MATRIX" >> $GITHUB_OUTPUT

  test:
    needs: generate-matrix
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-test.yml@v1
    with:
      test-matrix: ${{ needs.generate-matrix.outputs.matrix }}
    secrets: inherit
```

**Reusable Workflow**:
```yaml
name: Reusable Test Workflow

on:
  workflow_call:
    inputs:
      test-matrix:
        required: true
        type: string

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix: ${{ fromJson(inputs.test-matrix) }}
    steps:
      - name: Test with ${{ matrix.php }} and ${{ matrix.framework }}
        run: |
          echo "Testing PHP ${{ matrix.php }} with ${{ matrix.framework }}"
```

### Pattern 3: Workflow Chaining with Artifacts

**Scenario**: Build in one workflow, deploy in another, share artifacts.

**Build Workflow** (`.github-private/.github/workflows/reusable-build.yml`):
```yaml
name: Reusable Build Workflow

on:
  workflow_call:
    inputs:
      platform:
        required: true
        type: string
      build-config:
        required: false
        type: string
        default: 'production'
    outputs:
      artifact-name:
        description: 'Name of the build artifact'
        value: ${{ jobs.build.outputs.artifact-name }}
      artifact-sha256:
        description: 'SHA256 checksum of the artifact'
        value: ${{ jobs.build.outputs.artifact-sha256 }}

jobs:
  build:
    runs-on: ubuntu-latest
    outputs:
      artifact-name: ${{ steps.package.outputs.artifact-name }}
      artifact-sha256: ${{ steps.checksum.outputs.sha256 }}
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Detect Platform
        id: platform
        run: |
          python3 scripts/release/detect_platform.py src

      - name: Build Package
        id: package
        run: |
          python3 scripts/release/package_extension.py dist
          ARTIFACT_NAME=$(ls dist/*.zip | head -1)
          echo "artifact-name=$(basename $ARTIFACT_NAME)" >> $GITHUB_OUTPUT

      - name: Calculate Checksum
        id: checksum
        run: |
          cd dist
          SHA256=$(sha256sum *.zip | awk '{print $1}')
          echo "sha256=$SHA256" >> $GITHUB_OUTPUT
          echo "$SHA256  $(ls *.zip)" > checksums.txt

      - name: Upload Build Artifact
        uses: actions/upload-artifact@v4
        with:
          name: ${{ steps.package.outputs.artifact-name }}
          path: dist/*.zip
          retention-days: 30

      - name: Upload Checksums
        uses: actions/upload-artifact@v4
        with:
          name: checksums
          path: dist/checksums.txt
          retention-days: 30
```

**Deploy Workflow** (`.github-private/.github/workflows/reusable-deploy.yml`):
```yaml
name: Reusable Deploy Workflow

on:
  workflow_call:
    inputs:
      artifact-name:
        required: true
        type: string
      artifact-sha256:
        required: true
        type: string
      environment:
        required: true
        type: string
    secrets:
      FTP_HOST:
        required: true
      FTP_USER:
        required: true
      FTP_PASSWORD:
        required: true

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Download Build Artifact
        uses: actions/download-artifact@v4
        with:
          name: ${{ inputs.artifact-name }}
          path: ./artifacts

      - name: Verify Checksum
        run: |
          cd ./artifacts
          ACTUAL_SHA256=$(sha256sum *.zip | awk '{print $1}')
          EXPECTED_SHA256="${{ inputs.artifact-sha256 }}"
          
          if [ "$ACTUAL_SHA256" != "$EXPECTED_SHA256" ]; then
            echo "Checksum mismatch!"
            echo "Expected: $EXPECTED_SHA256"
            echo "Actual: $ACTUAL_SHA256"
            exit 1
          fi
          echo "Checksum verified successfully"

      - name: Deploy to ${{ inputs.environment }}
        run: |
          # Deploy logic here
          echo "Deploying ${{ inputs.artifact-name }} to ${{ inputs.environment }}"
```

**Caller Workflow** (chaining build and deploy):
```yaml
name: Build and Deploy

on:
  workflow_dispatch:
    inputs:
      environment:
        required: true
        type: choice
        options: [staging, production]

jobs:
  build:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-build.yml@v1
    with:
      platform: 'joomla'
      build-config: 'production'

  deploy:
    needs: build
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-deploy.yml@v1
    with:
      artifact-name: ${{ needs.build.outputs.artifact-name }}
      artifact-sha256: ${{ needs.build.outputs.artifact-sha256 }}
      environment: ${{ inputs.environment }}
    secrets: inherit
```

### Pattern 4: Conditional Steps Based on Repository

**Scenario**: Different behavior for different repositories calling the same workflow.

**Reusable Workflow**:
```yaml
name: Reusable CI Workflow

on:
  workflow_call:
    inputs:
      repository-type:
        description: 'Type of repository (template, component, plugin)'
        required: false
        type: string
        default: 'auto-detect'

jobs:
  ci:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Detect Repository Type
        id: detect
        run: |
          if [ "${{ inputs.repository-type }}" == "auto-detect" ]; then
            if [ -f "src/templates/templateDetails.xml" ]; then
              echo "type=template" >> $GITHUB_OUTPUT
            elif [ -f "src/component.xml" ]; then
              echo "type=component" >> $GITHUB_OUTPUT
            else
              echo "type=plugin" >> $GITHUB_OUTPUT
            fi
          else
            echo "type=${{ inputs.repository-type }}" >> $GITHUB_OUTPUT
          fi

      - name: Run Template-Specific Tests
        if: ${{ steps.detect.outputs.type == 'template' }}
        run: |
          echo "Running template-specific tests"
          # Template tests here

      - name: Run Component-Specific Tests
        if: ${{ steps.detect.outputs.type == 'component' }}
        run: |
          echo "Running component-specific tests"
          # Component tests here

      - name: Run Plugin-Specific Tests
        if: ${{ steps.detect.outputs.type == 'plugin' }}
        run: |
          echo "Running plugin-specific tests"
          # Plugin tests here

      - name: Common Tests
        run: |
          echo "Running common tests for all types"
          # Common tests here
```

## Complete Workflow Implementation Examples

### Example 1: Complete PHP Quality Workflow

**File**: `.github-private/.github/workflows/reusable-php-quality.yml`

```yaml
name: Reusable PHP Quality Workflow

on:
  workflow_call:
    inputs:
      php-versions:
        description: 'JSON array of PHP versions'
        required: false
        type: string
        default: '["8.0", "8.1", "8.2", "8.3"]'
      source-directory:
        description: 'Source directory to analyze'
        required: false
        type: string
        default: 'src'
      enable-cache:
        description: 'Enable dependency caching'
        required: false
        type: boolean
        default: true
      fail-fast:
        description: 'Stop all jobs if one fails'
        required: false
        type: boolean
        default: false
    outputs:
      all-passed:
        description: 'Whether all checks passed'
        value: ${{ jobs.summary.outputs.all-passed }}

permissions:
  contents: read
  checks: write

jobs:
  phpcs:
    name: PHPCS (PHP ${{ matrix.php-version }})
    runs-on: ubuntu-latest
    strategy:
      fail-fast: ${{ inputs.fail-fast }}
      matrix:
        php-version: ${{ fromJson(inputs.php-versions) }}
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
          extensions: mbstring, xml, curl, zip
          coverage: none
          tools: composer:v2

      - name: Get Composer Cache Directory
        id: composer-cache
        if: ${{ inputs.enable-cache }}
        run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

      - name: Cache Composer Dependencies
        if: ${{ inputs.enable-cache }}
        uses: actions/cache@v4
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: ${{ runner.os }}-composer-

      - name: Install Dependencies
        run: |
          composer global require "squizlabs/php_codesniffer:^3.0" --with-all-dependencies
          echo "$(composer config -g home)/vendor/bin" >> $GITHUB_PATH

      - name: Run PHPCS
        run: |
          phpcs --version
          phpcs --standard=phpcs.xml \
                --report=full \
                --report=checkstyle:phpcs-checkstyle.xml \
                ${{ inputs.source-directory }}/

      - name: Annotate Code
        if: always()
        uses: staabm/annotate-pull-request-from-checkstyle@v1
        with:
          files: phpcs-checkstyle.xml
          notices-as-warnings: true

  phpstan:
    name: PHPStan (PHP ${{ matrix.php-version }})
    runs-on: ubuntu-latest
    strategy:
      fail-fast: ${{ inputs.fail-fast }}
      matrix:
        php-version: ${{ fromJson(inputs.php-versions) }}
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}
          extensions: mbstring, xml, curl, zip
          coverage: none

      - name: Install Dependencies
        run: |
          composer global require "phpstan/phpstan:^1.0" --with-all-dependencies
          echo "$(composer config -g home)/vendor/bin" >> $GITHUB_PATH

      - name: Run PHPStan
        run: |
          phpstan --version
          phpstan analyse \
                  --configuration=phpstan.neon \
                  --error-format=github \
                  --no-progress \
                  ${{ inputs.source-directory }}/

  summary:
    name: Quality Check Summary
    runs-on: ubuntu-latest
    needs: [phpcs, phpstan]
    if: always()
    outputs:
      all-passed: ${{ steps.check.outputs.all-passed }}
    steps:
      - name: Check Results
        id: check
        run: |
          PHPCS_RESULT="${{ needs.phpcs.result }}"
          PHPSTAN_RESULT="${{ needs.phpstan.result }}"
          
          if [ "$PHPCS_RESULT" == "success" ] && [ "$PHPSTAN_RESULT" == "success" ]; then
            echo "all-passed=true" >> $GITHUB_OUTPUT
            echo "✅ All quality checks passed"
          else
            echo "all-passed=false" >> $GITHUB_OUTPUT
            echo "❌ Some quality checks failed"
            echo "PHPCS: $PHPCS_RESULT"
            echo "PHPStan: $PHPSTAN_RESULT"
            exit 1
          fi
```

### Example 2: Complete Release Pipeline Workflow

**File**: `.github-private/.github/workflows/reusable-release-pipeline.yml`

```yaml
name: Reusable Release Pipeline

on:
  workflow_call:
    inputs:
      release-classification:
        description: 'Release classification'
        required: false
        type: string
        default: 'auto'
      platform:
        description: 'Extension platform'
        required: false
        type: string
        default: 'auto-detect'
      skip-tests:
        description: 'Skip test execution'
        required: false
        type: boolean
        default: false
    secrets:
      FTP_HOST:
        required: true
      FTP_USER:
        required: true
      FTP_PASSWORD:
        required: true
      FTP_PATH:
        required: true
      GPG_PRIVATE_KEY:
        required: false
      GPG_PASSPHRASE:
        required: false
    outputs:
      version:
        description: 'Released version'
        value: ${{ jobs.metadata.outputs.version }}
      download-url:
        description: 'Download URL for release'
        value: ${{ jobs.release.outputs.download-url }}

permissions:
  contents: write
  id-token: write
  attestations: write

jobs:
  metadata:
    name: Extract Metadata
    runs-on: ubuntu-latest
    outputs:
      version: ${{ steps.version.outputs.version }}
      platform: ${{ steps.platform.outputs.platform }}
      changelog: ${{ steps.changelog.outputs.content }}
    steps:
      - name: Checkout
        uses: actions/checkout@v4
          fetch-depth: 0

      - name: Extract Version
        id: version
        run: |
          VERSION=$(python3 -c "
import sys
sys.path.insert(0, 'scripts/lib')
import extension_utils
info = extension_utils.get_extension_info('src')
print(info.version if info else 'unknown')
          ")
          echo "version=$VERSION" >> $GITHUB_OUTPUT

      - name: Detect Platform
        id: platform
        run: |
          python3 scripts/release/detect_platform.py src

      - name: Extract Changelog
        id: changelog
        run: |
          VERSION="${{ steps.version.outputs.version }}"
          CHANGELOG=$(awk "/^## \[${VERSION}\]/{flag=1;next}/^## \[/{flag=0}flag" CHANGELOG.md)
          echo "content<<EOF" >> $GITHUB_OUTPUT
          echo "$CHANGELOG" >> $GITHUB_OUTPUT
          echo "EOF" >> $GITHUB_OUTPUT

  test:
    name: Run Tests
    needs: metadata
    if: ${{ !inputs.skip-tests }}
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-test.yml@v1
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'

  build:
    name: Build Release Package
    needs: [metadata, test]
    if: always() && (needs.test.result == 'success' || needs.test.result == 'skipped')
    runs-on: ubuntu-latest
    outputs:
      package-name: ${{ steps.build.outputs.package-name }}
      package-sha256: ${{ steps.checksum.outputs.sha256 }}
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Build Package
        id: build
        run: |
          python3 scripts/release/package_extension.py dist
          PACKAGE=$(ls dist/*.zip | head -1)
          echo "package-name=$(basename $PACKAGE)" >> $GITHUB_OUTPUT

      - name: Calculate Checksum
        id: checksum
        run: |
          cd dist
          SHA256=$(sha256sum *.zip | awk '{print $1}')
          echo "sha256=$SHA256" >> $GITHUB_OUTPUT

      - name: Sign Package
        if: ${{ secrets.GPG_PRIVATE_KEY != '' }}
        run: |
          echo "${{ secrets.GPG_PRIVATE_KEY }}" | gpg --import
          cd dist
          echo "${{ secrets.GPG_PASSPHRASE }}" | gpg --batch --yes --passphrase-fd 0 \
               --armor --detach-sign *.zip

      - name: Upload Package
        uses: actions/upload-artifact@v4
        with:
          name: release-package
          path: dist/*
          retention-days: 90

  release:
    name: Create GitHub Release
    needs: [metadata, build]
    runs-on: ubuntu-latest
    outputs:
      download-url: ${{ steps.create-release.outputs.upload_url }}
    steps:
      - name: Download Package
        uses: actions/download-artifact@v4
        with:
          name: release-package
          path: ./release

      - name: Create Release
        id: create-release
        uses: softprops/action-gh-release@v2
        with:
          tag_name: v${{ needs.metadata.outputs.version }}
          name: Release ${{ needs.metadata.outputs.version }}
          body: ${{ needs.metadata.outputs.changelog }}
          draft: false
          prerelease: ${{ inputs.release-classification == 'rc' }}
          files: ./release/*

      - name: Attest Build Provenance
        uses: actions/attest-build-provenance@v2
        with:
          subject-path: ./release/*.zip
```

## Error Handling and Debugging

### Debugging Reusable Workflows

**Enable Debug Logging**:
```bash
# Set repository secret
gh secret set ACTIONS_STEP_DEBUG --body "true"
gh secret set ACTIONS_RUNNER_DEBUG --body "true"
```

**Add Debug Steps**:
```yaml
- name: Debug Information
  if: ${{ runner.debug == '1' }}
  run: |
    echo "=== Environment Variables ==="
    env | sort
    
    echo "=== GitHub Context ==="
    echo '${{ toJson(github) }}'
    
    echo "=== Inputs ==="
    echo '${{ toJson(inputs) }}'
    
    echo "=== Secrets (names only) ==="
    echo "FTP_HOST: ${{ secrets.FTP_HOST != '' && 'SET' || 'NOT SET' }}"
```

### Common Error Patterns and Solutions

#### Error: "Required input not provided"

**Problem**:
```
Error: Required input 'php-versions' was not provided
```

**Solution**:
```yaml
# In reusable workflow, make it optional with default
inputs:
  php-versions:
    required: false  # Changed from true
    type: string
    default: '["8.0", "8.1", "8.2", "8.3"]'
```

#### Error: "Invalid workflow file"

**Problem**:
```
Error: .github/workflows/reusable.yml: Invalid workflow file:
Unexpected value 'workflow_call'
```

**Solution**:
Ensure workflow file is in `.github/workflows/` directory and uses correct syntax:
```yaml
on:
  workflow_call:  # Must be at top level under 'on:'
    inputs:
      ...
```

#### Error: "Maximum timeout exceeded"

**Problem**:
Workflow runs too long and times out

**Solution**:
```yaml
jobs:
  long-running-job:
    runs-on: ubuntu-latest
    timeout-minutes: 120  # Increase from default 360
    steps:
      ...
```

### Performance Monitoring

**Add Timing Information**:
```yaml
- name: Start Timer
  id: start-time
  run: echo "start=$(date +%s)" >> $GITHUB_OUTPUT

- name: Your Task
  run: |
    # Task logic here

- name: Report Duration
  if: always()
  run: |
    START=${{ steps.start-time.outputs.start }}
    END=$(date +%s)
    DURATION=$((END - START))
    echo "Task completed in ${DURATION} seconds"
    
    # Send to monitoring system
    curl -X POST "${{ secrets.METRICS_ENDPOINT }}" \
         -d "{\"duration\": $DURATION, \"job\": \"${{ github.job }}\"}"
```

## Testing Reusable Workflows

### Local Testing with act

```bash
# Install act
curl https://raw.githubusercontent.com/nektos/act/master/install.sh | sudo bash

# Test workflow locally
act workflow_call \
    -s GITHUB_TOKEN="$(gh auth token)" \
    -W .github/workflows/reusable-php-quality.yml \
    --input php-versions='["8.1"]'
```

### Integration Testing Strategy

1. **Create test repository**:
   ```bash
   gh repo create test-reusable-workflows --private
   ```

2. **Add caller workflow**:
   ```yaml
   name: Test Reusable Workflow
   
   on:
     push:
       branches: [test/**]
   
   jobs:
     test:
       uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@test-branch
       with:
         php-versions: '["8.1"]'
       secrets: inherit
   ```

3. **Run test**:
   ```bash
   git checkout -b test/workflow-test
   git push origin test/workflow-test
   ```

4. **Monitor results**:
   ```bash
   gh run watch
   ```

## Migration from Inline to Reusable

### Step-by-Step Conversion Guide

1. **Identify common workflow patterns** across repositories
2. **Extract to reusable workflow** in .github-private
3. **Add input parameters** for customization
4. **Test in isolation** with various input combinations
5. **Create caller workflow** in one repository
6. **Test integration** thoroughly
7. **Roll out gradually** to other repositories
8. **Monitor and iterate** based on feedback

### Conversion Checklist

- [ ] Extract workflow to .github-private
- [ ] Convert triggers to `workflow_call`
- [ ] Identify parameters (make inputs)
- [ ] Identify secrets (add to secrets section)
- [ ] Add outputs if needed
- [ ] Test with different input combinations
- [ ] Document usage in README
- [ ] Create caller workflow
- [ ] Test end-to-end
- [ ] Deploy to production

## Best Practices

### 1. Naming Conventions

- Prefix reusable workflows with `reusable-`
- Use descriptive names: `reusable-php-quality.yml`, not `quality.yml`
- Use kebab-case for file names
- Use snake_case for inputs/outputs

### 2. Input Validation

```yaml
- name: Validate Inputs
  run: |
    if [ -z "${{ inputs.required-field }}" ]; then
      echo "Error: required-field is empty"
      exit 1
    fi
    
    if [[ ! "${{ inputs.php-version }}" =~ ^[0-9]+\.[0-9]+$ ]]; then
      echo "Error: Invalid PHP version format"
      exit 1
    fi
```

### 3. Comprehensive Outputs

Always provide useful outputs:
```yaml
outputs:
  status:
    value: ${{ jobs.main.outputs.status }}
  artifacts:
    value: ${{ jobs.main.outputs.artifacts }}
  duration:
    value: ${{ jobs.main.outputs.duration }}
  error-message:
    value: ${{ jobs.main.outputs.error-message }}
```

### 4. Documentation

Document every reusable workflow:
```yaml
# At the top of the file
# Reusable PHP Quality Workflow
#
# This workflow performs PHP code quality checks including:
# - PHP_CodeSniffer (PHPCS)
# - PHPStan static analysis
# - PHP Compatibility checks
#
# Usage:
#   jobs:
#     quality:
#       uses: org/.github-private/.github/workflows/reusable-php-quality.yml@v1
#       with:
#         php-versions: '["8.0", "8.1"]'
#
# Inputs:
#   php-versions: JSON array of PHP versions to test
#   source-directory: Directory to analyze (default: src)
#
# Outputs:
#   all-passed: Boolean indicating if all checks passed
#
# Secrets Required:
#   None for basic functionality
```

### 5. Version Management

Use semantic versioning:
- `v1` - Major version (may include breaking changes)
- `v1.2` - Minor version (backward compatible features)
- `v1.2.3` - Patch version (bug fixes only)

Tag releases properly:
```bash
git tag -a v1.2.3 -m "Release v1.2.3: Fix PHPCS caching"
git push origin v1.2.3

# Update major/minor tags
git tag -fa v1 -m "Update v1 to v1.2.3"
git push origin v1 --force
```

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
- [GitHub Actions Best Practices](https://docs.github.com/en/actions/security-guides/security-hardening-for-github-actions)
- [Workflow Syntax](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions)

---

**Last Updated**: 2026-01-05  
**Version**: 2.0  
**Maintainer**: DevOps Team
