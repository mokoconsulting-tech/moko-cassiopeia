<!--
 Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 FILE: docs/ROADMAP.md
 VERSION: 03.05.01
 BRIEF: Version-specific roadmap for Moko-Cassiopeia template
 PATH: /docs/ROADMAP.md
-->

# Moko-Cassiopeia Roadmap (VERSION: 03.05.01)

This document provides a comprehensive, version-specific roadmap for the Moko-Cassiopeia Joomla template, tracking feature evolution, current capabilities, and planned enhancements.

## Table of Contents

- [Version Timeline](#version-timeline)
- [Current Release (v03.05.01)](#current-release-v030501)
- [Implemented Features](#implemented-features)
- [Planned Features](#planned-features)
- [Development Priorities](#development-priorities)
- [Long-term Vision](#long-term-vision)
- [External Resources](#external-resources)

---

## Version Timeline

### v03.05.01 (2026-01-09) - Standards & Security
**Status**: Current Release

**Added**:
- Dependency review workflow for vulnerability scanning
- Standards compliance workflow for MokoStandards validation
- Dependabot configuration for automated security updates
- Documentation index (`docs/README.md`)

**Changed**:
- Removed custom CodeQL workflow (using GitHub's default setup)
- Enforced repository compliance with MokoStandards
- Improved security posture with automated scanning

### v03.05.00 (2026-01-04) - Workflow & Governance
**Added**:
- `.github/workflows` directory structure
- CODE_OF_CONDUCT.md from MokoStandards
- CONTRIBUTING.md from MokoStandards

**Changed**:
- TODO items to be split to separate file (tracked)

### v03.01.00 (2025-12-16) - CI/CD Foundation
**Added**:
- Initial GitHub Actions workflows

### v03.00.00 (2025-12-09) - Font Awesome 7 Upgrade
**Updated**:
- Copyright headers to MokoCodingDefaults standards
- Fixed color style injection in `index.php`
- Upgraded Font Awesome 6 to Font Awesome 7 Free
- Added Font Awesome 7 Free style fallback

**Removed**:
- Deprecated CODE_OF_CONDUCT.md
- Deprecated CONTRIBUTING.md

### v02.01.05 (2025-09-04) - CSS Refinement
**Fixed**:
- Removed vmbasic.css
- Repaired template.css and colors_standard.css

### v02.00.00 (2025-08-30) - Dark Mode & TOC
**Major Features**:
- **Dark Mode Toggle System**
  - Frontend toggle switch with localStorage persistence
  - Admin-configurable default mode
  - CSS rules for light/dark themes
  - JavaScript-powered mode switching

- **Enhanced Template Parameters**
  - Logo parameter support
  - GTM container ID configuration
  - Dark mode defaults in settings
  - Updated metadata and copyright headers

- **Expanded Table of Contents**
  - Automatic TOC injection
  - User-selectable placement (`toc-left` or `toc-right`)
  - Article options integration

**Improvements**:
- Cleaned up `index.php` (removed duplicate skip-to-content calls)
- Consolidated JavaScript asset loading
- Streamlined CSS for toggle switch
- Accessibility refinements (typography, color contrast)
- Fixed missing logo parameter in header
- Corrected stylesheet inconsistencies
- Patched redundant script includes

### v01.00.00 - Initial Public Release
**Core Features**:
- Font Awesome 6 integration
- Bootstrap 5 helpers and utilities
- Automatic Table of Contents (TOC) utility
- Moko Expansions: Google Tag Manager / GA4 hooks
- Built on Joomla's Cassiopeia template

---

## Current Release (v03.05.01)

### System Requirements
- **Joomla**: 4.4.x or 5.x
- **PHP**: 8.0+
- **Database**: MySQL/MariaDB compatible

### Architecture
- **Base Template**: Joomla Cassiopeia
- **Enhancement Layer**: Non-invasive overrides
- **Asset Management**: Joomla Web Asset Manager (WAM)
- **Frontend Framework**: Bootstrap 5
- **Icon Library**: Font Awesome 7 Free

---

## Implemented Features

### 🎨 Theming & Visual Design

#### Color Palette System
- **3 Built-in Palettes**: Standard, Alternative, Custom
- **Dual Mode Support**: Separate light and dark configurations
- **Custom Palettes**: User-definable via `colors_custom.css`
- **Location**: `src/media/css/colors/{light|dark}/`

#### Dark Mode System
- **Toggle Controls**: Switch (Light↔Dark) or Radios (Light/Dark/System)
- **Default Mode**: Admin-configurable (system, light, or dark)
- **Persistence**: localStorage for user preferences
- **Auto-Detection**: Optional system preference detection
- **Meta Tags**: `color-scheme` and `theme-color` support
- **ARIA Bridge**: Bootstrap ARIA compatibility

#### Typography
- **Font Schemes**:
  - Local: Roboto
  - Web (Google Fonts): Fira Sans, Roboto + Noto Sans
- **Admin-Configurable**: Template settings dropdown

#### Branding
- **Logo Support**: Custom logo upload
- **Site Title**: Text-based branding option
- **Site Description**: Tagline/subtitle field
- **Font Awesome Kit**: Optional custom kit integration

### 📐 Layout & Structure

#### Module Positions (23 Total)
**Header Area**:
- topbar, below-topbar, below-logo, menu, search, banner

**Content Area**:
- top-a, top-b, main-top, main-bottom, breadcrumbs
- sidebar-left, sidebar-right

**Footer Area**:
- bottom-a, bottom-b, footer-menu, footer

**Special**:
- debug, offline-header, offline, offline-footer
- drawer-left, drawer-right

#### Layout Options
- **Container Type**: Fluid or Static
- **Sticky Header**: Optional fixed navigation
- **Back-to-Top Button**: Scrollable page support

### 📝 Content Features

#### Table of Contents (TOC)
- **Automatic Generation**: From article headings
- **Placement Options**: `toc-left` or `toc-right` layouts
- **Article Integration**: Via Options → Layout dropdown
- **Responsive**: Mobile-friendly sidebar placement

#### Article Layouts
- **Default**: Standard Cassiopeia layout
- **TOC Variants**: Left-sidebar or right-sidebar TOC
- **Custom Overrides**: Located in `html/com_content/article/`

### 📊 Analytics & Tracking

#### Google Tag Manager (GTM)
- **Enable/Disable**: Admin toggle
- **Container ID**: Template parameter field
- **Implementation**: Head and body script injection
- **GDPR-Ready**: Configurable consent defaults

#### Google Analytics 4 (GA4)
- **Enable/Disable**: Admin toggle
- **Property ID**: Template parameter field
- **Universal Analytics Fallback**: Legacy UA support
- **Privacy-First**: Conditional loading based on settings

### 🎛️ Customization & Developer Tools

#### Custom Code Injection
- **Head Start**: Custom HTML/JS before `</head>`
- **Head End**: Custom HTML/JS at end of `<head>`
- **Raw HTML**: Unfiltered code injection for advanced users

#### Drawer System
- **Left/Right Drawers**: Offcanvas menu areas
- **Icon Customization**: Font Awesome icon selection
- **Default Icons**:
  - Left: `fa-solid fa-chevron-right`
  - Right: `fa-solid fa-chevron-left`

#### Asset Management
- **Joomla WAM**: Complete asset registry in `joomla.asset.json`
- **Development/Production Modes**: Minified and unminified assets
- **Dependency Management**: Automatic script/style loading

### 🏗️ Template Overrides

#### Component Overrides
**Content (com_content)**:
- Article layouts (default, toc-left, toc-right)
- Category layouts (blog, list)
- Featured articles

**Contact (com_contact)**:
- Contact form layouts

**Engage (com_engage)**:
- Comment system integration

#### Module Overrides
**Menu (mod_menu)**:
- Metis dropdown menu
- Offcanvas navigation

**VirtueMart**:
- Product display (`mod_virtuemart_product`)
- Shopping cart (`mod_virtuemart_cart`)
- Manufacturer display (`mod_virtuemart_manufacturer`)
- Category display (`mod_virtuemart_category`)
- Currency selector (`mod_virtuemart_currencies`)

**Other Modules**:
- Custom HTML (`mod_custom`)
- GABble social integration (`mod_gabble`)

**Membership System (OS Membership)**:
- Plan layouts (default, pricing tables)
- Member management interfaces

### 🔧 Configuration Parameters

#### Theme Tab
**General**:
- `theme_enabled` - Enable/disable theme system
- `theme_control_type` - Toggle UI type (switch/radios/none)
- `theme_default_choice` - Default mode (system/light/dark)
- `theme_auto_dark` - Auto-detect system preference
- `theme_meta_color_scheme` - Inject `color-scheme` meta tag
- `theme_meta_theme_color` - Inject `theme-color` meta tag
- `theme_bridge_bs_aria` - Bootstrap ARIA compatibility

**Variables & Palettes**:
- `colorLightName` - Light mode color scheme
- `colorDarkName` - Dark mode color scheme

**Typography**:
- `useFontScheme` - Font selection (local/web)

**Branding & Icons**:
- `brand` - Show/hide branding
- `logoFile` - Logo upload path
- `siteTitle` - Site title text
- `siteDescription` - Site tagline
- `fA6KitCode` - Font Awesome kit code

**Header & Navigation**:
- `stickyHeader` - Fixed navigation
- `backTop` - Back-to-top button

**Toggle UI**:
- `theme_fab_enabled` - Floating action button for theme toggle
- `theme_fab_pos` - FAB position (br/bl/tr/tl)

#### Google Tab
- `googletagmanager` - Enable GTM
- `googletagmanagerid` - GTM container ID
- `googleanalytics` - Enable GA4
- `googleanalyticsid` - GA4 property ID

#### Custom Code Tab
- `custom_head_start` - Custom code at head start
- `custom_head_end` - Custom code at head end

#### Drawers Tab
- `drawerLeftIcon` - Left drawer icon (Font Awesome class)
- `drawerRightIcon` - Right drawer icon (Font Awesome class)

#### Advanced Tab
- `fluidContainer` - Container layout (static/fluid)

### 🛠️ Development Tools

#### Quality Assurance
- **Codeception**: Automated testing framework
- **PHPStan**: Static analysis (level 8+)
- **PHPCS**: Code style validation (PSR-12)
- **PHPCompatibility**: PHP 8.0+ compatibility checks

#### CI/CD Workflows
- **Dependency Review**: Vulnerability scanning
- **Standards Compliance**: MokoStandards validation
- **CodeQL**: Security analysis (GitHub default)
- **Dependabot**: Automated dependency updates

#### Documentation
- **Quick Start**: 5-minute developer setup
- **Workflow Guide**: Git strategy, branching, releases
- **Joomla Development**: Testing, packaging, multi-version support

---

## Planned Features

### 🚧 In Development

#### Soft Offline Mode (v2.1.5 - Mentioned)
**Status**: Planned/In Development  
**Description**: Keep selected categories accessible during maintenance mode
**Use Cases**:
- Legal documents remain viewable during downtime
- Policy pages accessible for compliance
- Terms of service always available
**Configuration**:
- Admin-selectable categories
- Per-category offline access control

#### TODO Tracking System
**Status**: Mentioned in CHANGELOG (v03.05.00)  
**Description**: Separate TODO tracking file  
**Purpose**: Centralized issue and feature tracking outside changelog

### 🔮 Future Enhancements

#### Development Mode (Commented Out)
**Status**: Code exists but disabled  
**Location**: `templateDetails.xml` line 91  
**Description**: Comprehensive development mode toggle  
**Potential Features**:
- Unminified asset loading
- Debug output
- Performance profiling
- Template cache bypass

#### Potential Features (Community Requested)
*Note: These are conceptual and not yet officially planned*

**Enhanced Accessibility**:
- WCAG 2.1 AAA compliance mode
- High-contrast themes
- Screen reader optimizations
- Keyboard navigation improvements

**Performance Optimizations**:
- Critical CSS inlining
- Lazy loading for below-the-fold content
- WebP image support with fallbacks
- Service worker for offline functionality

**Additional Integrations**:
- More e-commerce platform support
- Newsletter integration (Mailchimp, Sendinblue)
- Social media feed aggregation
- Advanced SEO tools

**Content Builder**:
- Visual page builder integration
- Drag-and-drop layout editor
- Preset layout templates
- Style presets library

---

## Development Priorities

### High Priority (Next Release)
1. **TODO Tracking System**: Implement separate file for issue tracking
2. **Soft Offline Mode**: Complete category-based offline access
3. **Security Updates**: Maintain Dependabot and CodeQL scans
4. **Documentation**: Keep docs synchronized with features

### Medium Priority (Future Releases)
1. **Development Mode**: Enable and enhance developer experience
2. **Performance**: Optimize asset loading and rendering
3. **Accessibility**: WCAG 2.1 compliance improvements
4. **Testing**: Expand Codeception test coverage

### Low Priority (Long-term)
1. **Visual Builder**: Integrate page builder functionality
2. **Advanced SEO**: Schema markup and meta optimization
3. **Multi-language**: Enhanced RTL and translation support
4. **Theme Marketplace**: Community-contributed color schemes

---

## Long-term Vision

### Mission Statement
Moko-Cassiopeia aims to be the **most developer-friendly, user-customizable, and standards-compliant Joomla template** while maintaining minimal core overrides for maximum upgrade compatibility.

### Core Principles
1. **Non-Invasive**: Minimal Cassiopeia overrides
2. **Standards-First**: MokoStandards compliance
3. **Accessibility**: WCAG 2.1 compliance
4. **Performance**: Fast, optimized delivery
5. **Security**: Proactive vulnerability management
6. **Developer Experience**: Clear docs, easy setup, powerful tools

### Strategic Goals

#### 2026
- Complete soft offline mode implementation
- Achieve 100% WCAG 2.1 AA compliance
- Expand Codeception test coverage to 80%+
- Release community style pack (5+ color schemes)

#### 2027
- Visual page builder integration
- Advanced SEO module
- Multi-site template synchronization
- Enterprise support tier

#### 2028+
- Joomla 6.x compatibility (when released)
- AI-powered accessibility checker
- Headless CMS mode (API-first rendering)
- Marketplace ecosystem for extensions

---

## External Resources

### Official Links
- **Full Roadmap**: [https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap](https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap)
- **Repository**: [https://github.com/mokoconsulting-tech/moko-cassiopeia](https://github.com/mokoconsulting-tech/moko-cassiopeia)
- **Issue Tracker**: [GitHub Issues](https://github.com/mokoconsulting-tech/moko-cassiopeia/issues)
- **Changelog**: [CHANGELOG.md](../CHANGELOG.md)

### Community
- **Email Support**: hello@mokoconsulting.tech
- **Contributing**: [CONTRIBUTING.md](../CONTRIBUTING.md)
- **Code of Conduct**: [CODE_OF_CONDUCT.md](../CODE_OF_CONDUCT.md)

### Documentation
- **Quick Start**: [docs/QUICK_START.md](./QUICK_START.md)
- **Workflow Guide**: [docs/WORKFLOW_GUIDE.md](./WORKFLOW_GUIDE.md)
- **Joomla Development**: [docs/JOOMLA_DEVELOPMENT.md](./JOOMLA_DEVELOPMENT.md)
- **Main README**: [README.md](../README.md)

---

## Contributing to the Roadmap

Have ideas for future features? We welcome community input!

**How to Suggest Features**:
1. Check the [GitHub Issues](https://github.com/mokoconsulting-tech/moko-cassiopeia/issues) for existing requests
2. Open a new issue with the `enhancement` label
3. Provide clear use cases and benefits
4. Engage in community discussion

**Feature Evaluation Criteria**:
- Alignment with core principles
- User demand and use cases
- Technical feasibility
- Maintenance burden
- Performance impact
- Security implications

---

## Metadata

* Document: docs/ROADMAP.md
* Repository: [https://github.com/mokoconsulting-tech/moko-cassiopeia](https://github.com/mokoconsulting-tech/moko-cassiopeia)
* Path: /docs/ROADMAP.md
* Owner: Moko Consulting
* Version: 03.05.01
* Status: Active
* Last Updated: 2026-01-27
* Classification: Public Open Source Documentation

## Revision History

| Date       | Change Summary                                        | Author          |
| ---------- | ----------------------------------------------------- | --------------- |
| 2026-01-27 | Initial version-specific roadmap generated from codebase scan. | GitHub Copilot |
