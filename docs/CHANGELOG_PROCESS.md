<!--
 Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 FILE: docs/CHANGELOG_PROCESS.md
 VERSION: 03.06.00
 BRIEF: Process guide for maintaining changelog based on pull requests
 PATH: /docs/CHANGELOG_PROCESS.md
-->

# Changelog Process Guide

This guide explains how to maintain the changelog based on pull requests, ensuring that all changes are properly documented.

## Table of Contents

- [Overview](#overview)
- [Changelog Format](#changelog-format)
- [PR-Based Changelog Workflow](#pr-based-changelog-workflow)
- [Writing Good Changelog Entries](#writing-good-changelog-entries)
- [Categories Explained](#categories-explained)
- [Examples](#examples)
- [Automation](#automation)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)
- [Quick Reference](#quick-reference)
- [Resources](#resources)
- [Related Documentation](#related-documentation)

## Overview

The Moko-Cassiopeia project follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) format for maintaining the CHANGELOG.md file. This ensures that changes are:

- **Human-readable** - Users can quickly understand what changed
- **Grouped by category** - Changes are organized by type (Added, Changed, etc.)
- **Version-based** - Changes are associated with specific releases
- **PR-linked** - Each entry references the pull request that introduced it

## Changelog Format

The changelog follows this structure:

```markdown
# Changelog — Moko-Cassiopeia (VERSION: X.Y.Z)

## [Unreleased]
### Added
- New feature description (#123)

### Changed
- Modified functionality description (#124)

### Fixed
- Bug fix description (#125)

## [X.Y.Z] YYYY-MM-DD
### Added
- Released feature description (#120)

### Changed
- Released modification description (#121)
```

## PR-Based Changelog Workflow

### For Contributors

When creating a pull request:

1. **Fill out the PR template** - Include all required sections
2. **Add a Changelog Entry** - In the "Changelog Entry" section of the PR template, provide:
   ```markdown
   ### Added
   - New feature that does X (#PR-number)
   ```
3. **Use the correct category** - Choose from: Added, Changed, Deprecated, Removed, Fixed, Security
4. **Be descriptive** - Explain what changed from a user's perspective
5. **Check the changelog checkbox** - Confirm you've provided an entry

### For Maintainers

When merging a pull request:

1. **Review the changelog entry** - Ensure it's clear and accurate
2. **Copy to CHANGELOG.md** - Add the entry to the `[Unreleased]` section
3. **Add PR number** - Include the PR number in parentheses: `(#123)`
4. **Maintain category order** - Keep categories in standard order
5. **Update version on release** - Move `[Unreleased]` entries to versioned section

### Release Process

When creating a new release:

1. **Move unreleased entries** - Transfer from `[Unreleased]` to `[X.Y.Z] YYYY-MM-DD`
2. **Update version header** - Change the top-level version number
3. **Add release date** - Use format: `[X.Y.Z] YYYY-MM-DD`
4. **Clear unreleased section** - Leave `[Unreleased]` empty or remove it
5. **Commit changelog** - Include in the release commit

## Writing Good Changelog Entries

### DO ✅

- **Use imperative mood** - "Add feature" not "Added feature"
- **Be specific** - Mention what component/file changed
- **Focus on user impact** - What does this mean for users?
- **Include PR reference** - Always add `(#123)`
- **Keep it concise** - One line per change when possible

**Good examples:**
```markdown
### Added
- Installation script for automated media folder cleanup during updates (#65)
- Document generation system as planned feature (#66)

### Changed
- Asset minification now linked to Joomla's global cache system (#62)
- Updated version to 03.08.00 across 24+ files (#65)

### Fixed
- Corrected stylesheet inconsistencies between Bootstrap 5 helpers and template overrides (#42)
```

### DON'T ❌

- **Be vague** - "Fixed bug" or "Updated file"
- **Use past tense** - "Added feature" should be "Add feature"
- **Skip the PR number** - Always include it
- **Duplicate entries** - Combine related changes
- **Include implementation details** - Focus on user-facing changes

**Bad examples:**
```markdown
### Changed
- Updated some files (no PR reference)
- Fixed it (too vague)
- Modified AssetMinifier.php parameter logic (implementation detail)
```

## Categories Explained

### Added
New features, files, or capabilities added to the template.

**Examples:**
- New template parameters
- New layout options
- New helper classes
- New documentation files
- New configuration options

### Changed
Modifications to existing functionality that change behavior.

**Examples:**
- Updated dependencies
- Modified default settings
- Changed CSS styles
- Refactored code (when it affects behavior)
- Updated documentation

### Deprecated
Features that will be removed in future versions but still work.

**Examples:**
- Template parameters marked for removal
- Old API methods still supported
- Legacy configuration options

### Removed
Features, files, or capabilities that have been deleted.

**Examples:**
- Removed deprecated parameters
- Deleted unused files
- Removed old workarounds
- Deleted legacy code

### Fixed
Bug fixes and corrections.

**Examples:**
- Fixed CSS rendering issues
- Corrected PHP errors
- Fixed broken links
- Resolved accessibility issues
- Patched security vulnerabilities (use Security for serious ones)

### Security
Security-related changes and vulnerability fixes.

**Examples:**
- Patched XSS vulnerabilities
- Updated vulnerable dependencies
- Fixed security misconfigurations
- Added security hardening

## Examples

### Example 1: Feature Addition PR

**PR #65: Add Installation Script**

In the PR template:
```markdown
### Changelog Entry

### Added
- Installation script for automated media folder cleanup during template updates (#65)
  - Implements InstallerScriptInterface with lifecycle hooks
  - Recursive cleanup of empty directories
  - Operation logging to logs/moko_cassiopeia_cleanup.php

### Changed
- Updated version to 03.08.00 across 24+ files (#65)
```

In CHANGELOG.md (after merge):
```markdown
## [Unreleased]
### Added
- Installation script for automated media folder cleanup during template updates (#65)
  - Implements InstallerScriptInterface with lifecycle hooks
  - Recursive cleanup of empty directories
  - Operation logging to logs/moko_cassiopeia_cleanup.php

### Changed
- Updated version to 03.08.00 across 24+ files (#65)
```

### Example 2: Bug Fix PR

**PR #123: Fix Dark Mode Toggle**

In the PR template:
```markdown
### Changelog Entry

### Fixed
- Dark mode toggle not persisting user preference in localStorage (#123)
- Toggle switch visual state not syncing with system theme preference (#123)
```

### Example 3: Multiple Changes PR

**PR #62: Cache Integration**

In the PR template:
```markdown
### Changelog Entry

### Changed
- Asset minification now linked to Joomla's global cache system (#62)
  - Cache enabled: minified assets (`.min` suffix) created and used
  - Cache disabled: non-minified assets used, minified files deleted

### Deprecated
- Template-specific `developmentmode` parameter (replaced by Joomla cache setting) (#62)
```

## Automation

### Current Automation

The repository now includes automated changelog validation:

- ✅ **GitHub Actions workflow** validates changelog entries in PRs
- ✅ **Automatic PR labeling** based on changelog status
- ✅ **PR comments** with guidance for missing/invalid entries
- ✅ **Smart detection** skips automated PRs (Dependabot, bots)

**Workflow:** `.github/workflows/changelog-validation.yml`

The workflow:
1. Checks PR description for changelog entry
2. Validates entry format and category
3. Comments on PR if entry is missing or invalid
4. Adds/removes "needs-changelog" label
5. Fails check if changelog is missing (except for automated PRs)

### Future Automation (Planned)

Future enhancements may include:

- **Semi-automated CHANGELOG.md updates** on PR merge
- **Release notes generation** from changelog entries
- **Changelog preview** in PR comments showing how entry will appear
- **Multi-format export** for release notes

## Best Practices

### For All Contributors

1. ✅ **Always provide a changelog entry** - Every PR should document its changes
2. ✅ **Review existing entries** - Check for similar changes to maintain consistency
3. ✅ **Test your entry format** - Ensure markdown renders correctly
4. ✅ **Link the PR** - Always include `(#PR-number)` at the end
5. ✅ **Think user-first** - Write from the perspective of someone using the template

### For Maintainers

1. ✅ **Review every changelog entry** - Don't merge PRs with poor/missing entries
2. ✅ **Keep categories in order** - Added, Changed, Deprecated, Removed, Fixed, Security
3. ✅ **Merge related entries** - Combine multiple PRs for the same feature
4. ✅ **Update promptly** - Add entries to CHANGELOG.md as PRs are merged
5. ✅ **Version regularly** - Move unreleased entries to version sections on release

### Version Management

1. ✅ **Use semantic versioning** - Major.Minor.Patch (03.06.00)
2. ✅ **Update version header** - Keep VERSION comment in sync
3. ✅ **Date releases** - Use YYYY-MM-DD format
4. ✅ **Link releases** - Add GitHub release links at bottom of changelog
5. ✅ **Keep history** - Never delete old version entries

### Quality Control

1. ✅ **Consistent language** - Maintain similar writing style across entries
2. ✅ **No duplicates** - Check for existing entries before adding
3. ✅ **Proper grammar** - Proofread entries before committing
4. ✅ **Clear categorization** - Ensure changes are in the right category
5. ✅ **Complete information** - Include all necessary context

## Troubleshooting

### Missing Changelog Entry

**Problem:** PR merged without changelog entry

**Solution:**
1. Create a follow-up commit to CHANGELOG.md
2. Add the missing entry with PR reference
3. Consider making changelog entry mandatory in PR checks

### Wrong Category

**Problem:** Entry is in the wrong category

**Solution:**
1. Move entry to correct category
2. Update PR template guidance if confusion is common
3. Provide examples in code review

### Duplicate Entries

**Problem:** Same change documented multiple times

**Solution:**
1. Consolidate entries into one comprehensive entry
2. Keep all PR references: `(#123, #124, #125)`
3. Ensure the combined entry captures all aspects

### Unclear Description

**Problem:** Changelog entry is too vague or technical

**Solution:**
1. Rewrite from user perspective
2. Ask the PR author for clarification
3. Add more context about the impact

## Quick Reference

### Changelog Entry Template

```markdown
### [Category]
- [Brief description of what changed from user perspective] (#PR-number)
  - [Optional: Additional detail]
  - [Optional: Additional detail]
```

### Common Phrases

- "Add [feature] to [component]"
- "Update [component] to [new behavior]"
- "Fix [issue] in [component]"
- "Remove [feature] from [component]"
- "Deprecate [feature] in favor of [replacement]"
- "Improve [component] performance/accessibility/security"

### Checklist

Before submitting PR:
- [ ] Changelog entry provided in PR template
- [ ] Entry uses correct category
- [ ] Entry is user-focused, not implementation-focused
- [ ] Entry includes PR number
- [ ] Entry uses imperative mood
- [ ] Entry is clear and concise

Before merging PR:
- [ ] Changelog entry is accurate
- [ ] Changelog entry is well-written
- [ ] Category is appropriate
- [ ] PR number is correct
- [ ] Entry will be copied to CHANGELOG.md

## Resources

- [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) - Official format guide
- [Semantic Versioning](https://semver.org/) - Version numbering standard
- [Conventional Commits](https://www.conventionalcommits.org/) - Commit message format
- [GitHub Flow](https://guides.github.com/introduction/flow/) - Branch and PR workflow

## Related Documentation

- [WORKFLOW_GUIDE.md](./WORKFLOW_GUIDE.md) - GitHub Actions and development workflows
- [CONTRIBUTING.md](../CONTRIBUTING.md) - General contribution guidelines
- [README.md](../README.md) - Project overview
- [ROADMAP.md](./ROADMAP.md) - Feature planning and version timeline

---

**Document Version:** 1.0.0  
**Last Updated:** 2026-01-28  
**Maintained by:** Moko Consulting Engineering
