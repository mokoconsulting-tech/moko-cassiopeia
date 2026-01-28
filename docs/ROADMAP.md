<!--
 Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 FILE: docs/ROADMAP.md
 VERSION: 03.06.00
 BRIEF: Version-specific roadmap for Moko-Cassiopeia template
 PATH: /docs/ROADMAP.md
-->

# Moko-Cassiopeia Roadmap (VERSION: 03.08.00)

This document provides a comprehensive, version-specific roadmap for the Moko-Cassiopeia Joomla template, tracking feature evolution, current capabilities, and planned enhancements.

## Table of Contents

- [Version Timeline](#version-timeline)
  - [Past Releases](#past-releases)
  - [Future Roadmap (5-Year Plan)](#future-roadmap-5-year-plan)
- [Current Release (v03.06.00)](#current-release-v030600)
- [Implemented Features](#implemented-features)
- [Planned Features](#planned-features)
- [Development Priorities](#development-priorities)
- [Long-term Vision](#long-term-vision)
- [External Resources](#external-resources)

---

## Version Timeline

### Past Releases

### v03.05.01 (2026-01-09) - Standards & Security
**Status**: Released (CHANGELOG entry exists, code files pending version update)

**Added**:
- Dependency review workflow for vulnerability scanning
- Standards compliance workflow for MokoStandards validation
- Dependabot configuration for automated security updates
- Documentation index (`docs/README.md`)

**Changed**:
- Removed custom CodeQL workflow (using GitHub's default setup)
- Enforced repository compliance with MokoStandards
- Improved security posture with automated scanning

### v03.08.00 (2026-01-28) - Installation Automation & Cache Integration
**Status**: In Development (Open PR #65, #62)

**Added**:
- Installation script (`src/templates/script.php`) for automated media folder cleanup during template updates (PR #65)
  - Implements `InstallerScriptInterface` with lifecycle hooks
  - Automatic removal of deprecated files/folders during updates
  - Recursive cleanup of empty directories
  - Operation logging to `logs/moko_cassiopeia_cleanup.php`
  - Validates Joomla 4.0+ and PHP 7.4+ requirements

**Changed**:
- Asset minification now linked to Joomla's global cache system (PR #62)
  - When cache enabled: minified assets (`.min` suffix) are created and used
  - When cache disabled: non-minified assets used, minified files deleted
  - Replaced template-specific `developmentmode` parameter with Joomla cache configuration
  - `AssetMinifier.php` updated with inverted parameter logic for cache semantics
- Updated version to 03.08.00 across 24+ files (CSS/JS, PHP/Config, templateDetails.xml, joomla.asset.json) (PR #65)

### v03.06.00 (2026-01-28) - Version Update
**Status**: Released

**Changed**:
- Updated version to 03.06.00 across all files

### v03.05.00 (2026-01-04) - Workflow & Governance
**Status**: Mentioned in CHANGELOG (v03.05.00)

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

### Future Roadmap (5-Year Plan)

The following versions represent our planned annual major releases, each building upon the previous version's foundation.

#### v04.00.00 (Q4 2027) - Enhanced Accessibility & Performance
**Status**: Planned  
**Target Release**: December 2027

**Major Template Features**:
- **WCAG 2.1 AA Compliance**
  - Full accessibility audit and remediation
  - High-contrast theme options
  - Screen reader optimizations
  - Keyboard navigation enhancements
  - ARIA landmark improvements
  - Skip navigation enhancements

- **Template Performance Optimizations**
  - Critical CSS inlining for faster first paint
  - Lazy loading for images and below-fold content
  - WebP image support with automatic fallbacks
  - Advanced asset bundling and minification
  - Template asset caching (CSS/JS bundles)

- **Enhanced Layout System**
  - Additional responsive grid layouts
  - Flexible module position system
  - Column layout presets (2-col, 3-col, 4-col variations)
  - Grid/masonry article layouts
  - Sticky sidebar options

- **Typography Enhancements**
  - Advanced typography controls in template settings
  - Additional font pairing presets
  - Custom font upload support
  - Line height and letter spacing controls
  - Responsive typography scaling

- **Developer Experience**
  - Development mode enablement (unminified assets, debug output)
  - Live reload during development
  - Enhanced error logging and diagnostics
  - Template debugging tools
  - Document generation system (style guides, API docs, parameter reference)
  - Automated documentation from code annotations

- **Content Display Features**
  - Soft offline mode (category-based access during maintenance)
  - Enhanced article layouts (grid, masonry, timeline)
  - Image caption styling options
  - Quote block styling variations
  - Enhanced breadcrumb customization

**Template Infrastructure**:
- Expanded template parameter validation
- Enhanced template override detection
- Automated template compatibility testing
- Template performance profiling tools

---

#### v05.00.00 (Q4 2028) - Advanced Layouts & Template Customization
**Status**: Planned  
**Target Release**: December 2028

**Major Template Features**:
- **Enhanced Layout Builder**
  - Template-based page layout variations
  - Configurable layout options via template parameters
  - Layout presets library (blog, portfolio, business, magazine)
  - Module position layout manager
  - Visual layout preview in admin

- **Advanced Styling System**
  - Extended color palette management (unlimited custom palettes)
  - CSS variable editor in template settings
  - Style presets for different site types
  - Border radius and spacing controls
  - Box shadow and effect controls

- **Template Component Enhancements**
  - Enhanced menu styling options (mega menu support)
  - Advanced header variations (transparent, sticky, minimal)
  - Footer layout options (column variations, widgets)
  - Sidebar styling and behavior options
  - Hero section templates and variations

- **Content Display Options**
  - Article intro/full text display controls
  - Category layout variations (grid, list, masonry, cards)
  - Featured content sections
  - Related articles display options
  - Author bio box styling

- **Responsive Design Improvements**
  - Mobile-first navigation patterns
  - Tablet-specific layout controls
  - Responsive image sizing options
  - Mobile header variations
  - Touch-friendly interface elements

- **Template Integration Features**
  - Enhanced VirtueMart template overrides
  - Contact form styling variations
  - Search result layout options
  - Error page customization
  - Archive page templates

**Template Infrastructure**:
- Joomla 6.x template compatibility (if released)
- PHP 8.2+ support
- Template child theme support
- Template preset import/export functionality

---

#### v06.00.00 (Q4 2029) - Template Extensions & Advanced Features
**Status**: Planned  
**Target Release**: December 2029

**Major Template Features**:
- **Template Marketplace & Extensions**
  - Template addon system for modular features
  - Community-contributed template extensions
  - Template preset marketplace
  - Style pack distribution system
  - Template component library

- **Advanced Module System**
  - Custom module chrome options
  - Module animation effects
  - Module visibility controls (scroll, time-based)
  - Module group management
  - Module style inheritance

- **Enhanced Media Handling**
  - Background image options per page/section
  - Image overlay controls
  - Parallax scrolling effects
  - Video background support
  - Gallery template variations

- **Template Branding Options**
  - Multiple logo upload (standard, retina, mobile)
  - Favicon and app icon management
  - Custom loading screen/animations
  - Watermark options
  - Brand color scheme generator

- **Advanced Header/Footer**
  - Multiple header layout presets
  - Sticky header variations and behaviors
  - Header transparency controls
  - Footer widget areas expansion
  - Floating action buttons

- **Content Enhancement Features**
  - Reading progress indicator
  - Social sharing buttons (template-integrated)
  - Print-friendly styles
  - Reading time estimation display
  - Content table enhancements

- **Template SEO Features**
  - Schema markup templates for common types
  - Open Graph tag management
  - Twitter Card support
  - Breadcrumb schema integration
  - Meta tag template controls

**Template Infrastructure**:
- Template versioning system
- Template backup/restore functionality
- Template A/B testing support
- Multi-language template variations
- Enhanced document generation (multi-format export, interactive docs)

---

#### v07.00.00 (Q4 2030) - Modern Template Standards & Enhancements
**Status**: Planned  
**Target Release**: December 2030

**Major Template Features**:
- **Modern CSS Features**
  - CSS Grid layout system integration
  - CSS Container Queries support
  - CSS Cascade Layers implementation (layered style priority system)
  - Custom properties (CSS variables) UI
  - Modern filter and backdrop effects

- **Progressive Template Features**
  - Offline-capable template assets
  - Service worker template integration
  - App manifest generation
  - Install to home screen support
  - Template asset preloading strategies

- **Animation & Interaction**
  - Scroll-triggered animations
  - Hover effect library
  - Page transition effects
  - Micro-interactions for UI elements
  - Loading animation options

- **Advanced Responsive Features**
  - Container-based responsive design
  - Element visibility by viewport
  - Responsive navigation patterns library
  - Mobile-optimized interactions
  - Adaptive image loading

- **Template Accessibility Features**
  - Focus indicators customization
  - Reduced motion preferences support
  - High contrast mode automation
  - Keyboard navigation patterns
  - ARIA live regions for dynamic content

- **Content Presentation**
  - Advanced blockquote styles
  - Code snippet highlighting themes
  - Table styling variations
  - List styling options
  - Custom content block templates

- **Template Performance**
  - Resource hints (preconnect, prefetch)
  - Optimal asset delivery strategies
  - Image format optimization (AVIF support)
  - Font loading optimization
  - Template metrics dashboard

**Template Infrastructure**:
- Template pattern library
- Design token system
- Advanced document generation with live previews
- Automated template testing suite
- Template performance monitoring

---

#### v08.00.00 (Q4 2031) - Next-Generation Template Features
**Status**: Conceptual  
**Target Release**: December 2031

**Major Template Features**:
- **Advanced Layout Systems**
  - Subgrid support for complex layouts
  - Multi-column layout variations
  - Asymmetric grid systems
  - Dynamic layout switching
  - Layout constraint system

- **Enhanced Visual Customization**
  - Real-time style editor
  - Template style variations manager
  - Custom CSS injection with validation
  - Style inheritance and override system
  - Visual design tokens editor

- **Template Component Library**
  - Comprehensive UI component set
  - Reusable template blocks
  - Component variation system
  - Template snippet library
  - Pattern library integration

- **Advanced Typography System**
  - Variable font support
  - Advanced typographic scales
  - Font pairing recommendations
  - Fluid typography system
  - Custom font fallback chains

- **Template Integration Features**
  - Enhanced component overrides
  - Template hooks system
  - Event-based template modifications
  - Custom field rendering templates
  - Module position API enhancements

- **Responsive & Adaptive Design**
  - Advanced breakpoint management
  - Element-specific responsive controls
  - Adaptive images with art direction
  - Responsive typography system
  - Context-aware component rendering

- **Template Ecosystem**
  - Child template framework
  - Template derivative system
  - Community template marketplace
  - Template rating and review system
  - Professional template support network

- **Template Quality & Maintenance**
  - Automated accessibility testing
  - Template performance auditing
  - Code quality monitoring
  - Update notification system
  - Template health dashboard

**Template Infrastructure**:
- Template API for extensibility
- Template package manager
- Template development CLI tools
- Template migration utilities
- Comprehensive template documentation system

---

## Current Release (v03.08.00)

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
- **Cache-Based Minification**: Asset minification controlled by Joomla cache system
  - Cache enabled: Minified assets (`.min` suffix) created and used
  - Cache disabled: Non-minified assets used, minified files automatically removed
- **Dependency Management**: Automatic script/style loading
- **Installation Script**: Automated cleanup of deprecated files during updates

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

#### Document Generation System
**Status**: Planned  
**Description**: Automated documentation generation from template code and configuration  
**Potential Features**:
- Template documentation generator from inline code comments
- Automatic parameter reference documentation
- Style guide generation from CSS/SCSS files
- Module position documentation with visual layout diagrams
- Template override documentation
- Configuration guide generation
- API documentation for template helper classes
**Use Cases**:
- Maintain up-to-date documentation automatically
- Generate user-friendly configuration guides
- Create developer reference documentation
- Export documentation in multiple formats (HTML, PDF, Markdown)

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

**Template Layout Features**:
- Advanced responsive grid layouts
- Multiple column variations
- Custom module position system
- Layout preset library

**Template Styling Features**:
- Extended color palette management
- Custom font upload support
- Typography scale controls
- Visual style editor

---

## Development Priorities

### Immediate Focus (v03.x - 2026)
1. **Installation Automation** (v03.08.00): Complete installation script for automated cleanup (PR #65)
2. **Cache-Based Asset Minification** (v03.08.00): Finalize integration with Joomla cache system (PR #62)
3. **Document Generation System**: Implement automated documentation generation
4. **TODO Tracking System**: Implement separate file for issue tracking
5. **Soft Offline Mode**: Complete category-based offline access
6. **Security Updates**: Maintain Dependabot and CodeQL scans
7. **Documentation**: Keep docs synchronized with features
8. **Bug Fixes**: Address reported issues and edge cases

### v04.00.00 Priorities (2027) - Template Foundation
1. **WCAG 2.1 AA Compliance**: Full template accessibility audit and implementation
2. **Template Performance**: Critical CSS, lazy loading, WebP support
3. **Layout System**: Enhanced responsive grid and module positions
4. **Development Mode**: Enable comprehensive template developer tools

### v05.00.00 Priorities (2028) - Template Customization
1. **Layout Builder**: Template-based page layout system
2. **Styling System**: Extended color palettes and CSS variable management
3. **Template Components**: Enhanced header, footer, and menu variations
4. **Responsive Design**: Mobile-first navigation and layout improvements

### v06.00.00 Priorities (2029) - Template Extensions
1. **Template Marketplace**: Addon system and community extensions
2. **Module System**: Advanced module chrome and animation options
3. **Media Handling**: Background images, parallax, video backgrounds
4. **Template SEO**: Schema markup templates and meta tag controls

### v07.00.00+ Priorities (2030+) - Modern Standards
1. **Modern CSS**: Grid, Container Queries, Cascade Layers
2. **Progressive Template**: Offline-capable assets and PWA features
3. **Animation System**: Scroll-triggered effects and micro-interactions
4. **Template Performance**: Advanced optimization and monitoring

---

## Long-term Vision

### Mission Statement
Moko-Cassiopeia aims to be the **most developer-friendly, user-customizable, and standards-compliant Joomla template** while maintaining minimal core overrides for maximum upgrade compatibility.

### Core Principles
1. **Non-Invasive**: Minimal Cassiopeia overrides
2. **Standards-First**: MokoStandards compliance
3. **Accessibility**: WCAG 2.1 compliance
4. **Performance**: Fast, optimized delivery
5. **Developer Experience**: Clear docs, easy setup, powerful tools
6. **Template-Focused**: Pure template features without complex external dependencies

### 5-Year Strategic Roadmap (Template Features)

#### 2027 (v04.00.00) - Accessibility & Performance
- Achieve WCAG 2.1 AA compliance for all template elements
- Implement critical template performance optimizations
- Enhance template layout system with flexible grids
- Enable comprehensive development mode for template developers

#### 2028 (v05.00.00) - Layouts & Customization
- Launch template-based layout builder system
- Deploy extended styling and customization options
- Enhance template component variations (headers, footers, menus)
- Improve responsive design patterns for all devices

#### 2029 (v06.00.00) - Extensions & Enhancements
- Introduce template addon and extension system
- Launch template preset marketplace
- Deploy advanced module styling and animation features
- Implement comprehensive template SEO controls

#### 2030 (v07.00.00) - Modern Standards
- Adopt modern CSS standards (Grid, Container Queries, Cascade Layers)
- Implement progressive template features (PWA support)
- Deploy advanced animation and interaction system
- Enhance template performance monitoring and optimization

#### 2031 (v08.00.00) - Next-Generation Template
- Advanced layout systems with subgrid support
- Comprehensive template component library
- Enhanced visual customization tools
- Template ecosystem with child themes and derivatives

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
* Version: 03.08.00
* Status: Active
* Last Updated: 2026-01-28
* Classification: Public Open Source Documentation

## Revision History

| Date       | Change Summary                                        | Author          |
| ---------- | ----------------------------------------------------- | --------------- |
| 2026-01-27 | Initial version-specific roadmap generated from codebase scan. | GitHub Copilot |
| 2026-01-27 | Added 5-year future roadmap with annual major version releases (v04-v08). | GitHub Copilot |
| 2026-01-27 | Refocused roadmap to concentrate on template-oriented features only. | GitHub Copilot |
| 2026-01-28 | Updated roadmap based on open PRs #62 and #65 (v03.08.00). | GitHub Copilot |
| 2026-01-28 | Added document generation system as planned feature. | GitHub Copilot |
| 2026-01-28 | Corrected version number: PR #65 is v03.08.00, not v03.07.00. | GitHub Copilot |
