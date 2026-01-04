# .github-private Migration Checklist

This checklist guides the migration of CI/CD workflows from individual repositories to a centralized `.github-private` repository.

## Phase 1: Planning and Preparation

### Documentation
- [x] Create CI_MIGRATION_PLAN.md
- [x] Create REUSABLE_WORKFLOWS.md  
- [x] Create migration checklist
- [ ] Review and approve migration plan with team
- [ ] Identify workflow owners and stakeholders
- [ ] Schedule migration windows

### Repository Inventory
- [x] List all workflows in current repository
- [x] Identify workflows to centralize
- [x] Identify workflows to keep local
- [x] Document workflow dependencies
- [x] List all secrets used by workflows
- [x] List all variables used by workflows

### Risk Assessment
- [ ] Identify critical workflows that cannot have downtime
- [ ] Create rollback procedures
- [ ] Set up monitoring for workflow failures
- [ ] Communicate migration plan to team

## Phase 2: .github-private Repository Setup

### Repository Creation
- [ ] Create `.github-private` repository in organization
- [ ] Set repository to Private
- [ ] Initialize with README
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
- [ ] Migrate FTP_HOST to organization secrets
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
- [ ] Create reusable-php-quality.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters:
  - [ ] php-versions (JSON array)
  - [ ] php-extensions (string)
  - [ ] working-directory (string)
- [ ] Test reusable workflow independently
- [ ] Update main repository to call reusable workflow
- [ ] Test end-to-end integration
- [ ] Monitor for issues (1 week)
- [ ] Document lessons learned

### Workflow 2: joomla_testing.yml (Low Risk)
- [ ] Create reusable-joomla-testing.yml in .github-private
- [ ] Convert workflow to use workflow_call trigger
- [ ] Add input parameters as needed
- [ ] Test reusable workflow independently
- [ ] Update main repository to call reusable workflow
- [ ] Test end-to-end integration
- [ ] Monitor for issues (1 week)

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
- 

### What Could Be Improved
- 

### Unexpected Issues
- 

### Recommendations for Future Migrations
- 

---

**Migration Status**: Not Started  
**Start Date**: TBD  
**Expected Completion**: TBD  
**Migration Lead**: TBD  
**Last Updated**: 2026-01-04
