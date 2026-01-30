<!-- Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia.Documentation
 PATH: ./CHANGELOG.md
 VERSION: 03.06.03
 BRIEF: Changelog file documenting version history of MokoCassiopeia
 -->

# Changelog — MokoCassiopeia (VERSION: 03.06.03)

All notable changes to the MokoCassiopeia Joomla template are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [03.06.03] - 2026-01-30

### Changed
- **README**: Updated title to "README - MokoCassiopeia (VERSION: 03.06.03)"
- **README**: Fixed custom color variables instructions with correct file paths
- **README**: Updated example CSS variables to use actual template variable names (e.g., `--color-link` instead of `--cassiopeia-color-link`)
- **README**: Added note that custom color files are excluded from version control via `.gitignore`
- **TOC CSS**: Updated bootstrap-toc.css to use template color variables for proper theme integration
- **Version**: Updated version to 03.06.03 across all files

### Documentation
- Clarified that `colors_custom.css` files are gitignored to prevent fork-specific customizations from being committed

## [03.06.02] - 2026-01-30

### Major Rebrand
This release includes a complete rebrand from "Moko-Cassiopeia" (hyphenated) to "MokoCassiopeia" (camelCase).

### Changed
- **Naming Convention**: Changed template identifier from `moko-cassiopeia` to `mokocassiopeia` across all files
- **Display Name**: Updated from "Moko-Cassiopeia" to "MokoCassiopeia" in all documentation and language files
- **Language Constants**: Renamed all language keys from `TPL_MOKO-CASSIOPEIA_*` to `TPL_MOKOCASSIOPEIA_*`
- **Language Files**: Renamed from `tpl_moko-cassiopeia.*` to `tpl_mokocassiopeia.*` (4 files)
- **Media Paths**: Updated from `media/templates/site/moko-cassiopeia/` to `media/templates/site/mokocassiopeia/`
- **Repository URLs**: Updated all references to use `MokoCassiopeia` casing
- **Template Element**: Changed Joomla extension element name from `moko-cassiopeia` to `mokocassiopeia`
- **Documentation**: Updated all markdown files, XML manifests, and code comments

### Removed
- **Default Assets**: Removed `logo.svg` and `favicon.ico` to allow clean installations
- **Template Overrides**: Removed all template override files (48 files, ~4,500 lines)
  - Removed `src/templates/html/` folder entirely
  - Removed overrides for: com_content, com_contact, com_engage, mod_menu, mod_custom, mod_gabble, layouts/chromes
  - Template now inherits all rendering from Joomla Cassiopeia defaults
  - Updated `templateDetails.xml` to remove html folder reference

### Breaking Changes
⚠️ **Important**: This release contains breaking changes:
- Existing installations will see template name change in Joomla admin
- Custom code referencing old language constants (`TPL_MOKO-CASSIOPEIA_*`) will need updates
- Custom code referencing old media paths will need updates
- Sites relying on custom template overrides will revert to Cassiopeia defaults
- Extension element name changed (may require reinstallation in some cases)

### Migration Notes
- Backup your site before upgrading
- Review any custom code for references to old naming convention
- Test thoroughly after upgrade, especially if using custom overrides

## [03.06.00] - 2026-01-28

### Changed
- Updated version to 03.06.00 across all files
- Standardized version numbering format

## [03.05.01] - 2026-01-09

### Added
- Added `dependency-review.yml` workflow for dependency vulnerability scanning
- Added `standards-compliance.yml` workflow for MokoStandards validation
- Added `.github/dependabot.yml` configuration for automated security updates
- Added `docs/README.md` as documentation index

### Changed
- Removed custom `codeql-analysis.yml` workflow (repository uses GitHub's default CodeQL setup)
- Enforced repository compliance with MokoStandards requirements
- Improved security posture with automated scanning and dependency management

## [03.05.00] - 2026-01-04

### Added
- Created `.github/workflows` directory structure

### Changed
- Replaced `./CODE_OF_CONDUCT.md` from `MokoStandards`
- Replaced `./CONTRIBUTING.md` from `MokoStandards`
- TODO split to own file

## [03.01.00] - 2025-12-16

### Added
- Created `.github/workflows/` directory for GitHub Actions

## [03.00.00] - 2025-12-09

### Changed
- Copyright Headers updated to MokoCodingDefaults standards
- Fixed `./templates/mokocassiopeia/index.php` color style injection
- Upgraded Font Awesome 6 to Font Awesome 7 Free
- Added Font Awesome 7 Free style fallback

### Removed
- Removed `./CODE_OF_CONDUCT.md` (replaced with MokoStandards version)
- Removed `./CONTRIBUTING.md` (replaced with MokoStandards version)

## [02.01.05] - 2025-09-04

### Changed
- Repaired template.css and colors_standard.css

### Removed
- Removed vmbasic.css

## [02.00.00] - 2025-08-30

### Added - Dark Mode Toggle
- Frontend toggle switch included in template
- JavaScript handles switching between light/dark modes
- Dark mode CSS rules applied across template styles
- Automatic persistence of user choice (via localStorage)
- Admins can override default mode in template settings

### Added - Header Parameters Update
- Added logo parameter support in template settings
- Updated metadata & copyright header

### Added - Expanded TOC (Table of Contents)
- Automatic TOC injection when enabled
- User selects placement via article > options > layout (`toc-left` or `toc-right`)

### Changed
- Cleaned up `index.php` by removing skip-to-content duplicate calls
- Consolidated JavaScript asset loading (ensuring dark-mode script is loaded correctly from external JS file)
- Streamlined CSS for toggle switch, ensuring it inherits Bootstrap/Cassiopeia defaults
- General accessibility refinements in typography and color contrast
- Fixed missing logo param in header output
- Corrected stylesheet inconsistencies between Bootstrap 5 helpers and template overrides
- Patched redundant calls in script includes

## [01.00.00] - 2025-01-01

### Added - Initial Public Release
- Font Awesome 6 integration (later upgraded to FA7)
- Bootstrap 5 helpers (grid, utility classes)
- Automatic Table of Contents (TOC) utility
- Moko Expansions: Google Tag Manager / GA4 hooks
- Built on top of Joomla's default Cassiopeia template
- Minimal core template overrides for maximum upgrade compatibility

---

## Links

- **Full Roadmap**: [MokoCassiopeia Roadmap](https://mokoconsulting.tech/support/joomla-cms/mokocassiopeia-roadmap)
- **Repository**: [GitHub](https://github.com/mokoconsulting-tech/MokoCassiopeia)
- **Issue Tracker**: [GitHub Issues](https://github.com/mokoconsulting-tech/MokoCassiopeia/issues)

## Version Format

This project uses semantic versioning: `MAJOR.MINOR.PATCH`
- **MAJOR**: Incompatible API changes or major overhauls
- **MINOR**: New features, backwards-compatible
- **PATCH**: Bug fixes, backwards-compatible
