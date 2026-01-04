# CI/CD Migration to .github-private Repository

## Purpose

This document outlines the plan and preparation steps for migrating CI/CD workflows to a centralized `.github-private` repository. This approach provides:

- **Security**: Keep sensitive CI/CD logic and configurations private
- **Reusability**: Share common workflows across multiple repositories
- **Maintainability**: Centralize CI/CD updates in one location
- **Organization**: Separate CI/CD infrastructure from application code

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

**Files to Move to .github-private:**
- Complex workflow definitions
- Shared workflow templates
- Sensitive deployment scripts
- Organization-wide CI/CD tooling

### Phase 2: Structure Setup

Create `.github-private` repository with structure:
```
.github-private/
├── .github/
│   └── workflows/
│       ├── reusable-php-quality.yml
│       ├── reusable-release-pipeline.yml
│       ├── reusable-joomla-testing.yml
│       └── reusable-deploy-staging.yml
├── scripts/
│   ├── shared/
│   │   ├── extension_utils.py
│   │   └── deployment/
│   └── templates/
└── docs/
    └── WORKFLOWS.md
```

### Phase 3: Conversion

**Main Repository Workflows (Simplified Callers):**

Example `php_quality.yml`:
```yaml
name: PHP Code Quality

on:
  pull_request:
    branches: [ main, dev/*, rc/* ]
  push:
    branches: [ main, dev/*, rc/* ]

jobs:
  quality:
    uses: mokoconsulting-tech/.github-private/.github/workflows/reusable-php-quality.yml@main
    with:
      php-versions: '["8.0", "8.1", "8.2", "8.3"]'
    secrets: inherit
```

**Centralized Reusable Workflow:**

Located in `.github-private/.github/workflows/reusable-php-quality.yml`:
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

### Phase 4: Migration Steps

1. **Create .github-private repository**
   - Initialize with README and LICENSE
   - Set up branch protection rules
   - Configure team access

2. **Move workflows to .github-private**
   - Convert to reusable workflows with `workflow_call` triggers
   - Test each workflow in isolation
   - Add proper input parameters and secrets handling

3. **Update main repository workflows**
   - Replace with simplified caller workflows
   - Update documentation
   - Test integration

4. **Migrate shared scripts**
   - Move organization-wide scripts to .github-private
   - Keep product-specific scripts in main repo
   - Update import paths and references

5. **Update documentation**
   - Document workflow calling conventions
   - Update development guides
   - Create troubleshooting guides

## Configuration Requirements

### Secrets to Configure

**In .github-private repository:**
- Deployment credentials (FTP_HOST, FTP_USER, FTP_KEY, etc.)
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

### Workflows to Centralize (Move to .github-private)

1. **php_quality.yml** ✓
   - Reason: Standardized quality checks across all PHP projects
   - Type: Reusable workflow
   - Sensitivity: Low
   
2. **release_pipeline.yml** ✓
   - Reason: Complex release logic, contains sensitive deployment steps
   - Type: Reusable workflow
   - Sensitivity: High

3. **deploy_staging.yml** ✓
   - Reason: Contains deployment credentials and logic
   - Type: Reusable workflow
   - Sensitivity: High

4. **joomla_testing.yml** ✓
   - Reason: Shared across Joomla projects
   - Type: Reusable workflow
   - Sensitivity: Low

### Workflows to Keep Local (Main Repository)

1. **ci.yml**
   - Reason: Project-specific CI steps
   - Can call centralized reusable workflows if needed

2. **repo_health.yml**
   - Reason: Repository-specific health checks
   - Keep local with option to extend from centralized base

3. **version_branch.yml**
   - Reason: Project-specific versioning strategy
   - Keep local

## Scripts Categorization

### Scripts to Centralize

1. **scripts/lib/extension_utils.py** ✓
   - Shared across all extension projects
   - Platform detection logic

2. **scripts/lib/common.py** ✓
   - Universal utility functions
   - No project-specific logic

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

## References

- [GitHub Reusable Workflows Documentation](https://docs.github.com/en/actions/using-workflows/reusing-workflows)
- [GitHub Security Best Practices](https://docs.github.com/en/actions/security-guides/security-hardening-for-github-actions)
- [Workflow Syntax](https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions)

## Support

For questions or issues during migration:
- Review this document
- Check GitHub Actions documentation
- Contact: DevOps team

---

**Status**: Draft - Awaiting Review  
**Author**: GitHub Copilot  
**Date**: 2026-01-04  
**Version**: 1.0
