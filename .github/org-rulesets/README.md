<!--
Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

This file is part of a Moko Consulting project.

SPDX-License-Identifier: GPL-3.0-or-later

FILE INFORMATION
DEFGROUP: GitHub.Configuration
INGROUP: Moko-Cassiopeia.Security
REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
PATH: .github/org-rulesets/README.md
VERSION: 01.00.00
BRIEF: Documentation for organization-level repository protection rulesets
-->

# Organization-Level Repository Rulesets

This directory contains JSON configuration files for GitHub organization-level repository protection rulesets. These rulesets provide centralized governance and security controls across repositories in the organization.

## Overview

Organization-level rulesets allow you to:
- Define branch protection rules once and apply them across multiple repositories
- Enforce security and compliance requirements consistently
- Reduce manual configuration and potential errors
- Centrally manage governance policies

## Prerequisites

- **Permissions Required:** Organization owner permissions
- **GitHub Plan:** GitHub Team or Enterprise plan
- **Feature Access:** Repository rulesets feature must be enabled for your organization

## Available Rulesets

### default-protection-ruleset.json

**Purpose:** Provides comprehensive branch protection for main development branches.

**Protected Branches:**
- `main` (default branch)
- `dev/**` (development branches)
- `rc/**` (release candidate branches)
- `version/**` (version branches)

**Enforced Rules:**
1. **Pull Request Requirements**
   - At least 1 approving review required
   - Dismiss stale reviews on push
   - Require code owner review (via CODEOWNERS file)
   - Require conversation resolution before merging

2. **Status Check Requirements**
   - `validation` - Repository structure and standards validation
   - `quality` - PHP code quality checks (PHPStan, PHP_CodeSniffer)
   - Strict mode enabled (branches must be up to date before merging)

3. **Protection Against:**
   - Force pushes (non-fast-forward updates)
   - Branch deletion
   - Non-linear history (enforces clean git history)
   - Unsigned commits (requires commit signing)

4. **Bypass Permissions**
   - Repository administrators can bypass rules when necessary
   - Bypass mode: always (for emergency situations)

## How to Apply Organization-Level Rulesets

### Method 1: GitHub Web UI

1. **Navigate to Organization Settings**
   - Go to your organization on GitHub
   - Click **Settings** → **Repository** → **Rulesets**

2. **Import Ruleset**
   - Click **New ruleset** → **Import a ruleset**
   - Upload the JSON file from this directory
   - Review the configuration
   - Click **Create** to apply

3. **Configure Repository Targeting** (Optional)
   - By default, rules apply to branches matching the patterns
   - You can further restrict to specific repositories if needed

### Method 2: GitHub API

You can also apply rulesets programmatically using the GitHub REST API:

```bash
# Set your organization name and PAT
ORG_NAME="mokoconsulting-tech"
GITHUB_TOKEN="your_personal_access_token"

# Import the ruleset
curl -X POST \
  -H "Accept: application/vnd.github+json" \
  -H "Authorization: Bearer $GITHUB_TOKEN" \
  -H "X-GitHub-Api-Version: 2022-11-28" \
  "https://api.github.com/orgs/$ORG_NAME/rulesets" \
  -d @.github/org-rulesets/default-protection-ruleset.json
```

### Method 3: GitHub CLI

```bash
# List existing rulesets
gh api /orgs/mokoconsulting-tech/rulesets

# Create a new ruleset
gh api /orgs/mokoconsulting-tech/rulesets \
  --method POST \
  --input .github/org-rulesets/default-protection-ruleset.json
```

## Validation

After applying the ruleset, validate it by:

1. **Check Ruleset Status**
   - Go to Organization Settings → Rulesets
   - Verify the ruleset appears with "Active" status
   - Check that target branches are correctly matched

2. **Test Protection**
   - Try to push directly to a protected branch (should fail)
   - Try to create a PR without required checks (should be blocked)
   - Verify status checks appear on pull requests

3. **Monitor Enforcement**
   - GitHub provides audit logs for ruleset enforcement
   - Check Settings → Audit log to see rule triggers

## Customization

To customize the rulesets for your needs:

1. **Edit the JSON file** with your preferred settings
2. **Adjust branch patterns** in the `conditions.ref_name.include` array
3. **Modify rules** in the `rules` array as needed
4. **Update bypass actors** to match your organization structure

### Common Customizations

**Change required approvals:**
```json
"required_approving_review_count": 2
```

**Add more status checks:**
```json
"required_status_checks": [
  { "context": "validation", "integration_id": null },
  { "context": "quality", "integration_id": null },
  { "context": "security-scan", "integration_id": null }
]
```

**Relax commit signing requirement:**
Remove or comment out the `required_signatures` rule.

## Monitoring and Maintenance

- **Regular Reviews:** Review rulesets quarterly to ensure they align with current practices
- **Updates:** When updating rulesets, use the GitHub UI or API to update existing configurations
- **Audit Logs:** Monitor organization audit logs for bypass usage and rule violations
- **Team Communication:** Inform team members when rulesets change

## Troubleshooting

### Ruleset Not Applying

- Verify you have organization owner permissions
- Check that the organization has the appropriate GitHub plan
- Ensure branch patterns match your actual branch names
- Review enforcement status (should be "active")

### Status Checks Failing

- Verify workflow names match the required status check contexts
- Check that workflows are configured to run on protected branches
- Ensure workflows have appropriate permissions

### Bypass Not Working

- Verify actor_id matches your organization's role IDs
- Check bypass_mode is set correctly
- Review user/team permissions in organization settings

## References

- [GitHub Docs: Creating rulesets for repositories in your organization](https://docs.github.com/en/organizations/managing-organization-settings/creating-rulesets-for-repositories-in-your-organization)
- [GitHub Docs: Managing rulesets for repositories in your organization](https://docs.github.com/en/organizations/managing-organization-settings/managing-rulesets-for-repositories-in-your-organization)
- [GitHub Ruleset Recipes](https://github.com/github/ruleset-recipes)
- [GitHub REST API: Rulesets](https://docs.github.com/en/rest/orgs/rules)

## Metadata

* **Directory:** .github/org-rulesets/
* **Repository:** https://github.com/mokoconsulting-tech/moko-cassiopeia
* **Maintainer:** Moko Consulting Engineering
* **Version:** 01.00.00
* **Status:** Active
* **Last Updated:** 2026-01-11

## Revision History

| Date       | Change Summary                                              | Author          |
| ---------- | ----------------------------------------------------------- | --------------- |
| 2026-01-11 | Initial creation of organization-level ruleset configuration | Moko Consulting |
