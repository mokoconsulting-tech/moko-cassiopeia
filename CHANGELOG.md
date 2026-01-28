<!-- Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia.Documentation
 PATH: ./CHANGELOG.md
 VERSION: 03.06.00
 BRIEF: Changelog file documenting version history of Moko-Cassiopeia
 -->

# Changelog — Moko-Cassiopeia (VERSION: 03.06.00)

## [03.06.00] 2026-01-28
### Changed
- Updated version to 03.06.00 across all files

## [03.05.01] 2026-01-09
### Added
- Added `dependency-review.yml` workflow for dependency vulnerability scanning
- Added `standards-compliance.yml` workflow for MokoStandards validation
- Added `.github/dependabot.yml` configuration for automated security updates
- Added `docs/README.md` as documentation index

### Changed
- Removed custom `codeql-analysis.yml` workflow (repository uses GitHub's default CodeQL setup)

### Changed
- Enforced repository compliance with MokoStandards requirements
- Improved security posture with automated scanning and dependency management

## [03.05.00] 2026-01-04
- Created `.github/workflows`
- Replaced `./CODE_OF_CONDUCT.md` from `MokoStandards`
- Replaced `./CONTRIBUTING.md` from `MokoStandards`
- TODO split to own file

## [03.01.00] 2025-12-16
- Created `.github/workflows/`

## [03.00] 2025-12-09
### Removed
 - `./CODE_OF_CONDUCT.md`
 - `./CONTRIBUTING.md`
 
### Updated
 - Copyright Headers to MokoCodingDefaults standards
 - Fixed `./templates/moko-cassiopeia/index.php` color style injection
 - Upgraded `FontAwesome 6` to `FontAwesome 7 Free`
 - Added `Font Awesome 7 Free` style fallback

## [02.01.05] 2025-09-04
- Removed vmbasic.css
- Repaired temaplte.css and colors_standard.css

## [2.00.00] 2025-08-30
### Added
* **Dark Mode Toggle**
	* Frontend toggle switch included in template.
	* JavaScript handles switching between light/dark modes.
	* Dark mode CSS rules applied across template styles.
	* Automatic persistence of user choice (via localStorage).

* **Header Parameters Update**

	* Added **logo parameter support** in template settings.
	* Updated metadata & copyright header.

* **Expanded TOC (Table of Contents)**

	* Automatic TOC injection when enabled.
	* User selects placement via article > options > layout (`toc-left` or `toc-right`).

### Updated

* Cleaned up `index.php` by removing **skip-to-content** duplicate calls.
* Consolidated JavaScript asset loading (ensuring dark-mode script is loaded correctly from external JS file).
* Streamlined CSS for **toggle switch**, ensuring it inherits Bootstrap/Cassiopeia defaults.
* General accessibility refinements in typography and color contrast.
* Fixed missing **logo param** in header output.
* Corrected stylesheet inconsistencies between Bootstrap 5 helpers and template overrides.
* Patched redundant calls in script includes.

## 01.00.00

* **Initial Public Release** with:

	* Font Awesome 6
	* Bootstrap 5 helpers
	* Automatic Table of Contents (TOC) utility
	* Moko Expansions: Google Tag Manager / GA4 hooks

For the full development roadmap, visit:
[Moko-Cassiopeia Roadmap](https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap)
