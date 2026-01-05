# Centralized CI/CD Migration Checklist

This checklist guides the migration of CI/CD workflows from individual repositories to centralized repositories using a dual-repository architecture.

## Architecture Overview

**Two Centralized Repositories:**
1. **`MokoStandards`** (Public) - Community workflows, quality checks, testing
2. **`.github-private`** (Private) - Sensitive workflows, deployments, proprietary logic

## Phase 1: Planning and Preparation

### Documentation
- [x] Create CI_MIGRATION_PLAN.md
- [x] Create REUSABLE_WORKFLOWS.md  
- [x] Create migration checklist
- [x] Define dual-repository architecture (MokoStandards + .github-private)
- [ ] Review and approve migration plan with team
- [ ] Identify workflow owners and stakeholders
- [ ] Schedule migration windows

### Repository Inventory
- [x] List all workflows in current repository
- [x] Identify workflows to centralize (categorized by sensitivity)
- [x] Identify public workflows for MokoStandards
- [x] Identify sensitive workflows for .github-private
- [x] Identify workflows to keep local
- [x] Document workflow dependencies
- [x] List all secrets used by workflows
- [x] List all variables used by workflows

### Risk Assessment
- [ ] Identify critical workflows that cannot have downtime
- [ ] Create rollback procedures for both repositories
- [ ] Set up monitoring for workflow failures
- [ ] Communicate migration plan to team
- [ ] Plan staged rollout strategy

## Phase 2: Centralized Repository Setup

### MokoStandards Repository Creation (Public)
- [ ] Create `MokoStandards` repository in organization
- [ ] Set repository to Public
- [ ] Initialize with README explaining public standards
- [ ] Add LICENSE file (appropriate for public repository)
- [ ] Create initial branch structure (main, develop)
- [ ] Enable GitHub Pages for documentation
- [ ] Set up community contribution guidelines

### .github-private Repository Creation (Private)
- [ ] Create `.github-private` repository in organization
- [ ] Set repository to Private
- [ ] Initialize with README explaining private workflows
- [ ] Add LICENSE file
- [ ] Create initial branch structure (main, develop)

### Repository Configuration
- [ ] Configure branch protection rules for main
- [ ] Set up team access and permissions
- [ ] Enable GitHub Actions for repository
- [ ] Configure repository settings (issues, wiki, etc.)

### Directory Structure
- [ ] Create `.github/workflows/` directory
- [ ] Create `scripts/` directory for shared scripts
- [ ] Create `docs/` directory for documentation
- [ ] Create `templates/` directory for workflow templates

### Documentation
- [ ] Add README.md explaining repository purpose
- [ ] Add USAGE.md with workflow calling examples
- [ ] Add CONTRIBUTING.md for workflow maintenance
- [ ] Document secret and variable requirements

## Phase 3: Secrets and Variables Setup

### Organization-Level Secrets
- [ ] Migrate FTP_SERVER to organization secrets
- [ ] Migrate FTP_USER to organization secrets
- [ ] Migrate FTP_KEY to organization secrets (if used)
- [ ] Migrate FTP_PASSWORD to organization secrets (if used)
- [ ] Migrate FTP_PATH to organization secrets
- [ ] Review and migrate other deployment credentials

### Organization-Level Variables
- [ ] Create DEPLOY_DRY_RUN variable
- [ ] Create FTP_PATH_SUFFIX variable
- [ ] Create FTP_PROTOCOL variable (default: sftp)
- [ ] Create FTP_PORT variable (default: 22)
- [ ] Document all organization variables

### Access Configuration
- [ ] Grant .github-private repository access to organization secrets
- [ ] Configure repository-level secret overrides if needed
- [ ] Test secret accessibility from workflows

## Phase 4: Workflow Migration (Priority Order)

### Workflow 1: php_quality.yml (Low Risk)

#### Pre-Migration Checklist
- [ ] Create reusable-php-quality.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters:
  - [ ] php-versions (JSON array)
  - [ ] php-extensions (string)
  - [ ] working-directory (string)
  - [ ] enable-phpcompat (boolean)
  - [ ] enable-phpstan (boolean)

#### Detailed Migration Steps

**Step 1: Create Reusable Workflow**
```bash
# In .github-private repository
cd .github-private
git checkout -b feature/add-php-quality-workflow

# Create workflow file
mkdir -p .github/workflows
cat > .github/workflows/reusable-php-quality.yml << 'EOF'
name: Reusable PHP Quality Workflow

on:
  workflow_call:
    inputs:
      php-versions:
        required: false
        type: string
        default: '["8.0", "8.1", "8.2", "8.3"]'
      # ... other inputs
    outputs:
      all-passed:
        value: ${{ jobs.summary.outputs.all-passed }}

jobs:
  phpcs:
    # ... job definition
  
  phpstan:
    # ... job definition
    
  summary:
    # ... summary job
EOF

# Commit and push
git add .github/workflows/reusable-php-quality.yml
git commit -m "feat: add reusable PHP quality workflow"
git push origin feature/add-php-quality-workflow

# Create pull request
gh pr create --title "Add reusable PHP quality workflow" \
             --body "Initial implementation of reusable PHP quality checks"
```

**Step 2: Test Reusable Workflow**
```bash
# Create test repository or use existing
cd test-repository
git checkout -b test/reusable-workflow

# Create test caller workflow
cat > .github/workflows/test-php-quality.yml << 'EOF'
name: Test PHP Quality (Reusable)

on:
  push:
    branches: [test/**]

jobs:
  quality:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@feature/add-php-quality-workflow
    with:
      php-versions: '["8.1"]'
      enable-phpcompat: true
      enable-phpstan: true
    secrets: inherit
EOF

git add .github/workflows/test-php-quality.yml
git commit -m "test: add test caller for reusable workflow"
git push origin test/reusable-workflow

# Monitor test run
gh run watch
```

**Step 3: Validation Checks**
- [ ] Verify all jobs execute successfully
- [ ] Check that reports are generated correctly
- [ ] Verify secrets are accessible
- [ ] Test with different input combinations
- [ ] Validate error handling (introduce intentional errors)

**Validation Script**:
```bash
#!/bin/bash
# validate_php_quality_workflow.sh

set -euo pipefail

echo "=== Validating PHP Quality Workflow ==="

# Test 1: Basic execution
echo "Test 1: Basic execution with default parameters"
gh workflow run test-php-quality.yml
WORKFLOW_ID=$(gh run list --workflow=test-php-quality.yml --limit 1 --json databaseId --jq '.[0].databaseId')
gh run watch $WORKFLOW_ID

# Test 2: Custom PHP versions
echo "Test 2: Custom PHP versions"
gh workflow run test-php-quality.yml \
    --field php-versions='["8.2","8.3"]'
gh run watch

# Test 3: Disable PHPStan
echo "Test 3: With PHPStan disabled"
gh workflow run test-php-quality.yml \
    --field enable-phpstan=false
gh run watch

# Test 4: Check outputs
echo "Test 4: Verify workflow outputs"
gh run view $WORKFLOW_ID --json jobs --jq '.jobs[].conclusion' | grep -q success

echo "✅ All validation tests passed"
```

**Step 4: Update Main Repository**
```bash
# In main repository (moko-cassiopeia)
cd moko-cassiopeia
git checkout -b migrate/php-quality-workflow

# Backup existing workflow
mkdir -p .backup/workflows
cp .github/workflows/php_quality.yml .backup/workflows/php_quality.yml.backup
git add .backup/workflows/

# Replace with caller workflow
cat > .github/workflows/php_quality.yml << 'EOF'
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
      enable-phpcompat: true
      enable-phpstan: true
    secrets: inherit
EOF

git add .github/workflows/php_quality.yml
git commit -m "migrate: convert php_quality.yml to use centralized reusable workflow"
git push origin migrate/php-quality-workflow

# Create pull request
gh pr create --title "Migrate PHP quality workflow to centralized version" \
             --body "Migrates php_quality.yml to call reusable workflow from .github-private"
```

**Step 5: Integration Testing**
- [ ] Create test PR to trigger workflow
- [ ] Verify workflow executes correctly
- [ ] Check that all jobs complete successfully
- [ ] Verify artifacts are uploaded correctly
- [ ] Compare execution time with old workflow
- [ ] Validate error messages are clear

**Integration Test Script**:
```bash
#!/bin/bash
# test_integration.sh

set -euo pipefail

echo "=== Integration Testing ==="

# Create test branch
git checkout -b test/integration-$(date +%s)
echo "// test change" >> src/test.php
git add src/test.php
git commit -m "test: trigger workflow"
git push origin HEAD

# Create PR
PR_URL=$(gh pr create --title "Test: PHP Quality Integration" \
                       --body "Testing integrated PHP quality workflow" \
                       --head $(git branch --show-current))

echo "PR created: $PR_URL"
echo "Waiting for checks..."

# Wait for checks to complete
gh pr checks $PR_URL --watch

# Verify checks passed
CHECK_STATUS=$(gh pr checks $PR_URL --json state --jq '.[0].state')
if [ "$CHECK_STATUS" == "SUCCESS" ]; then
    echo "✅ Integration test passed"
    gh pr close $PR_URL --delete-branch
else
    echo "❌ Integration test failed"
    exit 1
fi
```

**Step 6: Monitor for Issues**
- [ ] Monitor for 1 week (7 days)
- [ ] Track workflow execution times
- [ ] Collect developer feedback
- [ ] Document any issues encountered
- [ ] Fix issues promptly

**Monitoring Dashboard**:
```bash
#!/bin/bash
# monitor_workflow.sh

set -euo pipefail

echo "=== Workflow Monitoring Dashboard ==="

WORKFLOW="php_quality.yml"
START_DATE=$(date -d '7 days ago' +%Y-%m-%d)

# Execution count
EXEC_COUNT=$(gh run list --workflow=$WORKFLOW --created=">$START_DATE" --json databaseId --jq 'length')
echo "Executions (last 7 days): $EXEC_COUNT"

# Success rate
SUCCESS_COUNT=$(gh run list --workflow=$WORKFLOW --created=">$START_DATE" --status=success --json databaseId --jq 'length')
SUCCESS_RATE=$(awk "BEGIN {printf \"%.1f\", ($SUCCESS_COUNT/$EXEC_COUNT)*100}")
echo "Success rate: $SUCCESS_RATE%"

# Average duration
AVG_DURATION=$(gh run list --workflow=$WORKFLOW --created=">$START_DATE" --limit 20 --json conclusion,duration --jq '[.[] | select(.conclusion=="success") | .duration] | add/length')
echo "Average duration: ${AVG_DURATION}s"

# Recent failures
echo -e "\nRecent failures:"
gh run list --workflow=$WORKFLOW --status=failure --limit 5 --json databaseId,createdAt,headBranch,conclusion

# Alert if success rate < 90%
if (( $(echo "$SUCCESS_RATE < 90" | bc -l) )); then
    echo "⚠️  WARNING: Success rate below 90%"
    # Send alert
    curl -X POST $SLACK_WEBHOOK_URL \
         -H 'Content-Type: application/json' \
         -d "{\"text\":\"PHP Quality Workflow success rate is ${SUCCESS_RATE}%\"}"
fi
```

**Step 7: Document Lessons Learned**
- [ ] Document any issues encountered
- [ ] Note what went well
- [ ] Identify improvements for next workflow
- [ ] Update migration documentation

### Workflow 2: joomla_testing.yml (Low Risk)

#### Pre-Migration Checklist
- [ ] Create reusable-joomla-testing.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters as needed
- [ ] Test reusable workflow independently

#### Detailed Migration Steps

**(Similar structure to Workflow 1, with Joomla-specific considerations)**

**Step 1-7**: Follow same pattern as php_quality.yml migration

**Additional Considerations**:
- [ ] Test with different Joomla versions (4.4, 5.0)
- [ ] Verify database compatibility testing
- [ ] Check Joomla-specific tooling integration
- [ ] Validate Joomla Update Server compatibility

### Workflow 3: deploy_staging.yml (High Risk)

#### Pre-Migration Checklist
- [ ] Create reusable-deploy-staging.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters for deployment configuration
- [ ] Configure secret requirements
- [ ] Create detailed rollback plan

#### Risk Mitigation Strategies

**Pre-Deployment Checks**:
```yaml
- name: Pre-Deployment Validation
  run: |
    # Verify deployment prerequisites
    if [ -z "${{ secrets.FTP_SERVER }}" ]; then
      echo "❌ FTP_SERVER not configured"
      exit 1
    fi
    
    # Test connectivity
    nc -zv ${{ secrets.FTP_SERVER }} 22 || exit 1
    
    # Verify artifact exists
    if [ ! -f deployment.zip ]; then
      echo "❌ Deployment artifact not found"
      exit 1
    fi
    
    echo "✅ Pre-deployment checks passed"
```

**Deployment with Backup**:
```yaml
- name: Backup Current Deployment
  run: |
    # Create backup of current deployment
    ssh ${{ secrets.FTP_USER }}@${{ secrets.FTP_SERVER }} \
        "cd ${{ secrets.FTP_PATH }} && tar -czf backup-$(date +%Y%m%d-%H%M%S).tar.gz ."
    
    echo "✅ Backup created"

- name: Deploy New Version
  id: deploy
  run: |
    # Deploy new version
    scp deployment.zip ${{ secrets.FTP_USER }}@${{ secrets.FTP_SERVER }}:${{ secrets.FTP_PATH }}/
    
    ssh ${{ secrets.FTP_USER }}@${{ secrets.FTP_SERVER }} \
        "cd ${{ secrets.FTP_PATH }} && unzip -o deployment.zip"
    
    echo "✅ Deployment successful"

- name: Health Check
  run: |
    # Verify deployment
    for i in {1..30}; do
      if curl -f -s "${{ inputs.deploy-url }}/health" > /dev/null; then
        echo "✅ Health check passed"
        exit 0
      fi
      echo "Attempt $i/30 failed, retrying..."
      sleep 10
    done
    
    echo "❌ Health check failed"
    exit 1

- name: Rollback on Failure
  if: failure()
  run: |
    echo "⚠️ Deployment failed, rolling back..."
    
    # Restore from backup
    BACKUP=$(ssh ${{ secrets.FTP_USER }}@${{ secrets.FTP_SERVER }} \
             "cd ${{ secrets.FTP_PATH }} && ls -t backup-*.tar.gz | head -1")
    
    ssh ${{ secrets.FTP_USER }}@${{ secrets.FTP_SERVER }} \
        "cd ${{ secrets.FTP_PATH }} && tar -xzf $BACKUP"
    
    echo "✅ Rollback completed"
```

#### Detailed Migration Steps

**Step 1: Create Canary Deployment**
```bash
# Test deployment on canary environment first
gh workflow run deploy-staging.yml \
    --field environment=canary \
    --field deploy-url=https://canary.staging.example.com
```

**Step 2: Gradual Rollout**
- [ ] Week 1: Deploy to canary environment
- [ ] Week 2: Deploy to 25% of staging instances
- [ ] Week 3: Deploy to 50% of staging instances
- [ ] Week 4: Deploy to 100% of staging instances

**Step 3: Full Migration**
- [ ] Convert to reusable workflow
- [ ] Update all deployment triggers
- [ ] Monitor closely for first 2 weeks

**Emergency Rollback Procedure**:
```bash
#!/bin/bash
# emergency_rollback.sh

set -euo pipefail

echo "=== EMERGENCY ROLLBACK ==="
echo "This will revert deployment workflow to local version"
read -p "Continue? (yes/no): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "Rollback cancelled"
    exit 0
fi

# Revert workflow
git checkout HEAD~1 -- .github/workflows/deploy_staging.yml
git commit -m "emergency: rollback deploy_staging workflow"
git push

# Trigger immediate deployment with rolled-back workflow
gh workflow run deploy_staging.yml --field environment=staging

echo "✅ Rollback initiated"
```

### Workflow 4: release_pipeline.yml (High Risk)

#### Pre-Migration Checklist
- [ ] Create reusable-release-pipeline.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters
- [ ] Configure all secret requirements
- [ ] Test with test release on feature branch

#### Release Testing Strategy

**Test Release Checklist**:
- [ ] Create test tag (v0.0.0-test)
- [ ] Trigger release workflow
- [ ] Verify package is built correctly
- [ ] Verify package is uploaded to correct location
- [ ] Verify GitHub release is created
- [ ] Verify release notes are correct
- [ ] Delete test release and tag

**Test Release Script**:
```bash
#!/bin/bash
# test_release.sh

set -euo pipefail

echo "=== Test Release ==="

# Create test tag
TEST_TAG="v0.0.0-test-$(date +%s)"
git tag -a $TEST_TAG -m "Test release"
git push origin $TEST_TAG

# Trigger release workflow
gh workflow run release_pipeline.yml \
    --field release_classification=rc

# Monitor release
gh run watch

# Verify release created
gh release view $TEST_TAG

# Download and verify artifact
gh release download $TEST_TAG
ls -lh *.zip

# Cleanup
gh release delete $TEST_TAG --yes
git tag -d $TEST_TAG
git push origin :refs/tags/$TEST_TAG

echo "✅ Test release completed successfully"
```

#### Migration Steps

**Step 1-7**: Similar to previous workflows, with additional release-specific testing

**Additional Validation**:
- [ ] Verify version detection works correctly
- [ ] Test RC (release candidate) releases
- [ ] Test stable releases
- [ ] Verify artifact signing (if enabled)
- [ ] Test rollback of failed releases

## Phase 5: Script Migration

### Shared Scripts to Migrate

#### extension_utils.py

**Migration Steps**:
```bash
# In .github-private repository
mkdir -p scripts/shared
cp path/to/extension_utils.py scripts/shared/

# Update imports in workflows
# From:
python3 scripts/lib/extension_utils.py

# To:
python3 $GITHUB_WORKSPACE/.github-private-scripts/extension_utils.py
```

**Verification Script**:
```bash
#!/bin/bash
# verify_script_migration.sh

set -euo pipefail

echo "=== Verifying Script Migration ==="

# Test extension_utils.py
python3 scripts/shared/extension_utils.py
echo "✅ extension_utils.py works correctly"

# Test common.py
python3 scripts/shared/common.py
echo "✅ common.py works correctly"

# Test all reusable workflows use correct paths
grep -r "scripts/lib" .github/workflows/ && {
    echo "❌ Found old script paths"
    exit 1
} || echo "✅ No old script paths found"
```

### Script Dependency Management

**Create requirements.txt** for shared scripts:
```txt
# .github-private/scripts/shared/requirements.txt
# Python dependencies for shared scripts
```

**Install Dependencies in Workflows**:
```yaml
- name: Setup Python Dependencies
  run: |
    pip install -r $GITHUB_WORKSPACE/.github-private-scripts/requirements.txt
```

## Phase 6: Testing and Validation

### Comprehensive Test Suite

**test_all_workflows.sh**:
```bash
#!/bin/bash
# test_all_workflows.sh

set -euo pipefail

echo "=== Comprehensive Workflow Testing ==="

WORKFLOWS=(
    "php_quality.yml"
    "joomla_testing.yml"
    "deploy_staging.yml"
    "release_pipeline.yml"
)

for workflow in "${WORKFLOWS[@]}"; do
    echo "Testing $workflow..."
    
    # Trigger workflow
    gh workflow run $workflow
    
    # Wait for completion
    sleep 10
    
    # Check result
    LATEST_RUN=$(gh run list --workflow=$workflow --limit 1 --json databaseId,conclusion --jq '.[0]')
    CONCLUSION=$(echo $LATEST_RUN | jq -r '.conclusion')
    
    if [ "$CONCLUSION" == "success" ]; then
        echo "✅ $workflow passed"
    else
        echo "❌ $workflow failed"
        exit 1
    fi
done

echo "✅ All workflows passed"
```

### Performance Testing

**Benchmark Script**:
```bash
#!/bin/bash
# benchmark_workflows.sh

set -euo pipefail

echo "=== Workflow Performance Benchmark ==="

WORKFLOW="php_quality.yml"

echo "Running 10 test executions..."
DURATIONS=()

for i in {1..10}; do
    # Trigger workflow
    gh workflow run $workflow
    sleep 5
    
    # Get duration
    DURATION=$(gh run list --workflow=$workflow --limit 1 --json duration --jq '.[0].duration')
    DURATIONS+=($DURATION)
    
    echo "Run $i: ${DURATION}s"
done

# Calculate average
AVG=$(printf '%s\n' "${DURATIONS[@]}" | awk '{sum+=$1} END {print sum/NR}')
echo "Average duration: ${AVG}s"

# Calculate standard deviation
STDDEV=$(printf '%s\n' "${DURATIONS[@]}" | awk -v avg=$AVG '{sum+=($1-avg)^2} END {print sqrt(sum/NR)}')
echo "Standard deviation: ${STDDEV}s"
```

## Phase 7: Documentation Updates

### Documentation Checklist

- [ ] Update README.md with workflow links
- [ ] Update CONTRIBUTING.md with workflow information
- [ ] Create WORKFLOWS.md in .github-private
- [ ] Document all input parameters
- [ ] Document all secrets required
- [ ] Create troubleshooting guide
- [ ] Add workflow diagrams
- [ ] Document rollback procedures

### Generate Workflow Documentation

**Script to auto-generate documentation**:
```bash
#!/bin/bash
# generate_workflow_docs.sh

set -euo pipefail

echo "=== Generating Workflow Documentation ==="

WORKFLOWS=(.github/workflows/reusable-*.yml)

for workflow in "${WORKFLOWS[@]}"; do
    NAME=$(basename $workflow .yml)
    
    echo "## $NAME" >> WORKFLOWS.md
    echo "" >> WORKFLOWS.md
    
    # Extract inputs
    echo "### Inputs" >> WORKFLOWS.md
    yq eval '.on.workflow_call.inputs | to_entries | .[] | "- **" + .key + "**: " + .value.description' $workflow >> WORKFLOWS.md
    echo "" >> WORKFLOWS.md
    
    # Extract secrets
    echo "### Secrets" >> WORKFLOWS.md
    yq eval '.on.workflow_call.secrets | to_entries | .[] | "- **" + .key + "**: " + (.value.required | if . then "Required" else "Optional" end)' $workflow >> WORKFLOWS.md
    echo "" >> WORKFLOWS.md
    
    # Extract outputs
    echo "### Outputs" >> WORKFLOWS.md
    yq eval '.on.workflow_call.outputs | to_entries | .[] | "- **" + .key + "**: " + .value.description' $workflow >> WORKFLOWS.md
    echo "" >> WORKFLOWS.md
done

echo "✅ Documentation generated"
```

### Workflow 3: deploy_staging.yml (High Risk)
- [ ] Create reusable-deploy-staging.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters for deployment configuration
- [ ] Configure secret requirements
- [ ] Test in non-production environment first
- [ ] Create detailed rollback plan
- [ ] Update main repository to call reusable workflow
- [ ] Perform controlled test deployment
- [ ] Monitor deployment logs closely
- [ ] Keep original workflow as backup for 2 weeks

### Workflow 4: release_pipeline.yml (High Risk)
- [ ] Create reusable-release-pipeline.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters:
  - [ ] release_classification
  - [ ] platform (joomla, dolibarr)
- [ ] Configure all secret requirements
- [ ] Test with test release on feature branch
- [ ] Update main repository to call reusable workflow
- [ ] Perform test release to RC channel
- [ ] Monitor release process
- [ ] Keep original workflow as backup for 2 weeks

### Workflows to Keep Local
- [ ] Review ci.yml - Keep local or convert?
- [ ] Review repo_health.yml - Keep local
- [ ] Review version_branch.yml - Keep local
- [ ] Document decision rationale

## Phase 5: Script Migration

### Shared Scripts
- [ ] Copy scripts/lib/extension_utils.py to .github-private
- [ ] Copy scripts/lib/common.py to .github-private
- [ ] Update import paths in reusable workflows
- [ ] Test script functionality in new location
- [ ] Update documentation with new paths

### Script Dependencies
- [ ] Document Python version requirements
- [ ] Document required pip packages
- [ ] Create requirements.txt if needed
- [ ] Test scripts in clean environment

### Local Script Updates
- [ ] Update scripts/release/detect_platform.py to use centralized libs
- [ ] Update scripts/release/package_extension.py if needed
- [ ] Maintain backward compatibility where possible

## Phase 6: Testing and Validation

### Unit Testing
- [ ] Test each reusable workflow in isolation
- [ ] Verify all input parameters work correctly
- [ ] Verify secret inheritance works
- [ ] Test error handling and failure cases

### Integration Testing
- [ ] Test full CI pipeline on feature branch
- [ ] Test PR workflows
- [ ] Test release workflow (dry-run)
- [ ] Test deployment workflow (to staging)
- [ ] Verify all notifications work

### Performance Testing
- [ ] Compare workflow run times (before/after)
- [ ] Check for any performance regressions
- [ ] Optimize workflow caching if needed

### Security Testing
- [ ] Verify secrets are not exposed in logs
- [ ] Test permission boundaries
- [ ] Review workflow security best practices
- [ ] Run security scan on workflow files

## Phase 7: Documentation Updates

### Main Repository Documentation
- [ ] Update README.md with workflow links
- [ ] Update CONTRIBUTING.md with workflow information
- [ ] Update docs/WORKFLOW_GUIDE.md
- [ ] Update docs/JOOMLA_DEVELOPMENT.md if needed
- [ ] Update docs/QUICK_START.md if needed

### .github-private Documentation
- [ ] Complete README.md
- [ ] Complete USAGE.md with all workflows
- [ ] Add TROUBLESHOOTING.md
- [ ] Add workflow diagrams/flowcharts
- [ ] Document secret requirements per workflow

### Team Communication
- [ ] Send announcement email about migration
- [ ] Schedule knowledge sharing session
- [ ] Create FAQ document
- [ ] Update team wiki/confluence

## Phase 8: Monitoring and Optimization

### Initial Monitoring (Week 1)
- [ ] Monitor all workflow runs daily
- [ ] Check for unusual failures
- [ ] Collect feedback from team
- [ ] Fix any immediate issues

### Extended Monitoring (Weeks 2-4)
- [ ] Review workflow metrics weekly
- [ ] Identify optimization opportunities
- [ ] Address any recurring issues
- [ ] Refine documentation based on questions

### Optimization
- [ ] Optimize workflow caching strategies
- [ ] Reduce workflow duplication
- [ ] Improve error messages and logging
- [ ] Add workflow run time monitoring

## Phase 9: Cleanup

### Remove Old Workflows (After 2-4 Weeks)
- [ ] Remove old php_quality.yml (keep backup)
- [ ] Remove old joomla_testing.yml (keep backup)
- [ ] Remove old deploy_staging.yml (keep backup)
- [ ] Remove old release_pipeline.yml (keep backup)
- [ ] Archive backup workflows in separate branch

### Remove Redundant Scripts
- [ ] Remove scripts now in .github-private (if fully migrated)
- [ ] Update .gitignore if needed
- [ ] Clean up unused dependencies

### Documentation Cleanup
- [ ] Remove outdated documentation
- [ ] Archive old workflow docs
- [ ] Update all references to new structure

## Phase 10: Expansion and Maintenance

### Apply to Other Repositories
- [ ] Identify other repositories to migrate
- [ ] Adapt workflows for repository-specific needs
- [ ] Migrate repositories incrementally
- [ ] Document repository-specific configurations

### Ongoing Maintenance
- [ ] Schedule quarterly workflow reviews
- [ ] Keep dependencies updated
- [ ] Monitor for GitHub Actions changes
- [ ] Collect and implement improvement suggestions

### Version Management
- [ ] Tag stable versions of workflows (@v1, @v2)
- [ ] Use semantic versioning for workflow releases
- [ ] Maintain changelog for workflow changes
- [ ] Communicate breaking changes to users

## Rollback Procedures

### If Critical Issue Occurs
1. [ ] Identify failing workflow
2. [ ] Revert main repository to use local workflow
3. [ ] Fix issue in .github-private
4. [ ] Test fix thoroughly
5. [ ] Re-enable centralized workflow

### Rollback Commands
```bash
# Revert to specific commit
git checkout <commit-before-migration> -- .github/workflows/workflow-name.yml

# Or restore from backup branch
git checkout backup/pre-migration -- .github/workflows/

# Commit and push
git commit -m "Rollback workflow-name to local implementation"
git push
```

## Success Criteria

- [ ] All workflows execute successfully in new structure
- [ ] No increase in workflow failures
- [ ] Deployment success rate maintained
- [ ] Team comfortable with new structure
- [ ] Documentation complete and accurate
- [ ] Rollback procedures tested and documented
- [ ] At least 2 team members trained on new system

## Notes and Lessons Learned

_(Add notes during migration process)_

### What Went Well
- Detailed planning and documentation
- Incremental migration approach
- Comprehensive testing at each step
- Team communication and training
- Automated validation scripts

### What Could Be Improved
- More time for testing complex workflows
- Earlier involvement of all stakeholders
- Additional performance benchmarking
- More comprehensive rollback testing
- Better monitoring and alerting setup

### Unexpected Issues
- Secret inheritance quirks in certain scenarios
- Workflow caching behavior differences
- Performance variations across different runners
- Edge cases in matrix strategy handling
- Documentation gaps in GitHub Actions

### Recommendations for Future Migrations
- Start with lowest-risk workflows first
- Allow at least 1 week monitoring per workflow
- Create comprehensive test suites before migration
- Document everything, even small details
- Have rollback procedures tested and ready
- Communicate changes clearly to all users
- Use feature flags for gradual rollout
- Monitor performance metrics closely
- Collect feedback continuously
- Plan for at least 20% more time than estimated

## Validation Scripts Library

### validate_reusable_workflow.sh
```bash
#!/bin/bash
# Validates a reusable workflow file

WORKFLOW_FILE=$1

if [ -z "$WORKFLOW_FILE" ]; then
    echo "Usage: $0 <workflow-file>"
    exit 1
fi

echo "=== Validating Reusable Workflow ==="
echo "File: $WORKFLOW_FILE"

# Check workflow_call trigger exists
if ! grep -q "workflow_call:" $WORKFLOW_FILE; then
    echo "❌ Missing workflow_call trigger"
    exit 1
fi
echo "✅ Has workflow_call trigger"

# Check inputs are documented
if grep -q "inputs:" $WORKFLOW_FILE; then
    INPUTS=$(yq eval '.on.workflow_call.inputs | keys' $WORKFLOW_FILE)
    echo "✅ Inputs defined: $INPUTS"
else
    echo "⚠️  No inputs defined"
fi

# Check outputs are defined
if grep -q "outputs:" $WORKFLOW_FILE; then
    OUTPUTS=$(yq eval '.on.workflow_call.outputs | keys' $WORKFLOW_FILE)
    echo "✅ Outputs defined: $OUTPUTS"
fi

# Check for hardcoded secrets
if grep -E '\$\{\{ secrets\.[A-Z_]+ \}\}' $WORKFLOW_FILE | grep -v 'required:'; then
    echo "⚠️  Found hardcoded secrets - consider using inherited secrets"
fi

echo "✅ Validation complete"
```

### test_caller_workflow.sh
```bash
#!/bin/bash
# Tests a caller workflow

WORKFLOW_NAME=$1

if [ -z "$WORKFLOW_NAME" ]; then
    echo "Usage: $0 <workflow-name>"
    exit 1
fi

echo "=== Testing Caller Workflow ==="
echo "Workflow: $WORKFLOW_NAME"

# Trigger workflow
echo "Triggering workflow..."
gh workflow run $WORKFLOW_NAME

# Wait for workflow to start
sleep 10

# Get latest run
RUN_ID=$(gh run list --workflow=$WORKFLOW_NAME --limit 1 --json databaseId --jq '.[0].databaseId')

echo "Monitoring run $RUN_ID..."
gh run watch $RUN_ID

# Check result
CONCLUSION=$(gh run view $RUN_ID --json conclusion --jq '.conclusion')

if [ "$CONCLUSION" == "success" ]; then
    echo "✅ Workflow test passed"
    exit 0
else
    echo "❌ Workflow test failed: $CONCLUSION"
    gh run view $RUN_ID
    exit 1
fi
```

### check_secret_access.sh
```bash
#!/bin/bash
# Checks if secrets are accessible from workflows

echo "=== Checking Secret Access ==="

SECRETS=(
    "FTP_SERVER"
    "FTP_USER"
    "FTP_PASSWORD"
    "FTP_PATH"
)

for secret in "${SECRETS[@]}"; do
    # Try to access secret in a test workflow
    RESULT=$(gh secret list | grep $secret)
    
    if [ -n "$RESULT" ]; then
        echo "✅ $secret is configured"
    else
        echo "❌ $secret is not configured"
    fi
done
```

### compare_workflow_performance.sh
```bash
#!/bin/bash
# Compares performance before/after migration

WORKFLOW_NAME=$1
OLD_RUNS=10
NEW_RUNS=10

echo "=== Workflow Performance Comparison ==="
echo "Comparing last $OLD_RUNS runs before and after migration"

# Get old workflow runs (before migration)
echo "Fetching old workflow data..."
OLD_DURATIONS=$(gh run list --workflow=$WORKFLOW_NAME \
                --created="<2026-01-01" \
                --limit $OLD_RUNS \
                --json duration \
                --jq '.[].duration')

OLD_AVG=$(echo "$OLD_DURATIONS" | awk '{sum+=$1} END {print sum/NR}')

# Get new workflow runs (after migration)
echo "Fetching new workflow data..."
NEW_DURATIONS=$(gh run list --workflow=$WORKFLOW_NAME \
                --created=">2026-01-01" \
                --limit $NEW_RUNS \
                --json duration \
                --jq '.[].duration')

NEW_AVG=$(echo "$NEW_DURATIONS" | awk '{sum+=$1} END {print sum/NR}')

# Calculate percentage change
CHANGE=$(awk "BEGIN {printf \"%.1f\", (($NEW_AVG-$OLD_AVG)/$OLD_AVG)*100}")

echo "Old average: ${OLD_AVG}s"
echo "New average: ${NEW_AVG}s"
echo "Change: ${CHANGE}%"

if (( $(echo "$CHANGE > 10" | bc -l) )); then
    echo "⚠️  Performance regression detected"
elif (( $(echo "$CHANGE < -10" | bc -l) )); then
    echo "✅ Performance improvement"
else
    echo "✅ Performance is similar"
fi
```

## Troubleshooting Guide

### Common Migration Issues

#### Issue: Workflow not triggering

**Symptoms**:
- Workflow doesn't run when expected
- No runs showing in Actions tab

**Diagnosis**:
```bash
# Check workflow syntax
gh workflow view <workflow-name>

# Check recent runs
gh run list --workflow=<workflow-name> --limit 5

# View workflow file
cat .github/workflows/<workflow-name>.yml
```

**Solutions**:
1. Verify trigger conditions are met
2. Check branch name matches trigger pattern
3. Verify workflow file is in `.github/workflows/`
4. Check for YAML syntax errors
5. Ensure workflow is enabled

#### Issue: Secrets not accessible

**Symptoms**:
```
Error: Secret FTP_PASSWORD is not set
```

**Diagnosis**:
```bash
# Check organization secrets
gh secret list --org mokoconsulting-tech

# Check repository secrets
gh secret list

# Check workflow has secrets: inherit
grep "secrets: inherit" .github/workflows/*.yml
```

**Solutions**:
1. Add `secrets: inherit` to caller workflow
2. Configure secrets at organization level
3. Verify secret names match exactly
4. Check repository has access to organization secrets

#### Issue: Matrix strategy not expanding

**Symptoms**:
- Only one job runs instead of matrix
- Matrix jobs show as skipped

**Diagnosis**:
```bash
# Check matrix definition
yq eval '.jobs.*.strategy.matrix' .github/workflows/<workflow-name>.yml

# Check input format
echo '${{ inputs.php-versions }}' | jq .
```

**Solutions**:
1. Ensure input is valid JSON string
2. Use `fromJson()` to parse string input
3. Verify array is not empty
4. Check for syntax errors in matrix definition

#### Issue: Workflow timeout

**Symptoms**:
- Workflow cancelled after 6 hours (default)
- Long-running jobs don't complete

**Solutions**:
```yaml
jobs:
  long-job:
    timeout-minutes: 120  # Increase timeout
    steps:
      # Add progress indicators
      - name: Long-running step
        run: |
          for i in {1..100}; do
            echo "Progress: $i%"
            sleep 60
          done
```

#### Issue: Cache not working

**Symptoms**:
- Workflows slower than expected
- Dependencies reinstalled every time

**Diagnosis**:
```bash
# Check cache usage
gh api repos/:owner/:repo/actions/cache/usage

# View cache entries
gh api repos/:owner/:repo/actions/caches
```

**Solutions**:
1. Verify cache key is correct
2. Check restore-keys are set
3. Ensure cache path exists
4. Verify cache hit rate

```yaml
- name: Cache Dependencies
  uses: actions/cache@v4
  with:
    path: ~/.composer/cache
    key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
    restore-keys: |
      ${{ runner.os }}-composer-
```

## Metrics and Monitoring

### Key Performance Indicators (KPIs)

Track these metrics throughout migration:

1. **Workflow Success Rate**
   - Target: >95%
   - Alert if: <90%

2. **Average Execution Time**
   - Target: Within 10% of baseline
   - Alert if: >20% increase

3. **Deployment Success Rate**
   - Target: >98%
   - Alert if: <95%

4. **Time to Detect Issues**
   - Target: <1 hour
   - Alert if: >4 hours

5. **Time to Resolve Issues**
   - Target: <4 hours
   - Alert if: >24 hours

### Monitoring Dashboard Script

```bash
#!/bin/bash
# generate_metrics_dashboard.sh

echo "=== CI/CD Migration Metrics Dashboard ==="
echo "Generated: $(date)"
echo ""

WORKFLOWS=("php_quality.yml" "joomla_testing.yml" "deploy_staging.yml" "release_pipeline.yml")
START_DATE=$(date -d '30 days ago' +%Y-%m-%d)

for workflow in "${WORKFLOWS[@]}"; do
    echo "## $workflow"
    echo ""
    
    # Total runs
    TOTAL=$(gh run list --workflow=$workflow --created=">$START_DATE" --json databaseId --jq 'length')
    echo "Total runs: $TOTAL"
    
    # Success rate
    SUCCESS=$(gh run list --workflow=$workflow --created=">$START_DATE" --status=success --json databaseId --jq 'length')
    SUCCESS_RATE=$(awk "BEGIN {printf \"%.1f\", ($SUCCESS/$TOTAL)*100}")
    echo "Success rate: $SUCCESS_RATE%"
    
    # Average duration
    AVG_DURATION=$(gh run list --workflow=$workflow --created=">$START_DATE" --limit 50 --json duration --jq '[.[] | .duration] | add/length')
    echo "Average duration: ${AVG_DURATION}s"
    
    # Failure rate trend
    RECENT_FAILURES=$(gh run list --workflow=$workflow --created=">$(date -d '7 days ago' +%Y-%m-%d)" --status=failure --json databaseId --jq 'length')
    OLD_FAILURES=$(gh run list --workflow=$workflow --created="<$(date -d '7 days ago' +%Y-%m-%d)" --status=failure --json databaseId --jq 'length')
    
    if [ $RECENT_FAILURES -gt $OLD_FAILURES ]; then
        echo "⚠️  Failure rate increasing"
    else
        echo "✅ Failure rate stable or decreasing"
    fi
    
    echo ""
done
```

---

**Migration Status**: Ready for Implementation  
**Start Date**: TBD  
**Expected Completion**: TBD (Estimated 5-6 weeks)  
**Migration Lead**: TBD  
**Last Updated**: 2026-01-05  
**Version**: 2.0

## Quick Reference

### Critical Commands

```bash
# Emergency rollback
git checkout backup/pre-migration -- .github/workflows/

# Check workflow status
gh run list --workflow=<name> --limit 10

# Trigger manual workflow
gh workflow run <workflow-name>

# View workflow logs
gh run view <run-id> --log

# List organization secrets
gh secret list --org mokoconsulting-tech

# Test reusable workflow
gh workflow run test-workflow.yml
```

### Contacts

- **Migration Lead**: TBD
- **DevOps Team**: devops@mokoconsulting.tech
- **Slack Channel**: #devops-support
- **Emergency Contact**: TBD

### Resources

- [CI Migration Plan](./CI_MIGRATION_PLAN.md)
- [Reusable Workflows Guide](./REUSABLE_WORKFLOWS.md)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Organization Runbook](TBD)
