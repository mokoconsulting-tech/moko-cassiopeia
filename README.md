<!-- Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 FILE: ./README.md
 VERSION: 03.06.03
 BRIEF: Documentation for MokoCassiopeia template
 -->

# README - MokoCassiopeia (VERSION: 03.06.03)

**A Modern, Lightweight Joomla Template Based on Cassiopeia**

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Joomla](https://img.shields.io/badge/Joomla-4.4.x%20%7C%205.x-blue.svg)](https://www.joomla.org)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://www.php.net)

MokoCassiopeia is a modern, lightweight enhancement layer built on top of Joomla's Cassiopeia template. It adds **Font Awesome 7**, **Bootstrap 5** helpers, an automatic **Table of Contents (TOC)** utility, advanced **Dark Mode** theming, and optional integrations for **Google Tag Manager** and **Google Analytics (GA4)**—all while maintaining minimal core template overrides for maximum upgrade compatibility.

---

## 📑 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Configuration](#️-configuration)
- [Theme System](#-theme-system)
- [Development](#-development)
- [Documentation](#-documentation)
- [Changelog](#-changelog)
- [Support](#-support)
- [Contributing](#-contributing)
- [Included Libraries](#-included-libraries)
- [License](#-license)

---

## ✨ Features

### Core Enhancements

- **Built on Cassiopeia**: Extends Joomla's default template with minimal overrides
- **Font Awesome 7**: Fully integrated into Joomla's asset manager with 2,000+ icons
- **Bootstrap 5**: Extended utility classes and responsive grid system
- **No Template Overrides**: Clean installation that inherits all Cassiopeia defaults
- **Upgrade-Friendly**: Minimal modifications ensure smooth Joomla updates

### Advanced Theming

- **Dark Mode Support**: Built-in light/dark mode toggle with system preference detection
- **Color Palettes**: Standard, Alternative, and Custom color schemes
- **Theme Persistence**: User preferences saved via localStorage
- **Theme Control Options**: Switch, radio buttons, or hidden controls
- **Auto Dark Mode**: Optional automatic dark mode based on time/system settings
- **Meta Tags**: Automatic color-scheme and theme-color meta tags

### Developer Features

- **Custom Code Injection**: Add custom HTML to `<head>` start/end
- **Drawer Sidebars**: Configurable left/right drawer positions with custom icons
- **Font Options**: Local and web fonts (Roboto, Fira Sans, Noto Sans)
- **Sticky Header**: Optional sticky navigation
- **Back to Top**: Floating back-to-top button

### Analytics & Tracking

- **Google Tag Manager**: Optional GTM integration with container ID configuration
- **Google Analytics**: Optional GA4 integration with measurement ID
- **Privacy-Friendly**: All tracking features are optional and easily disabled

### Content Features

- **Table of Contents**: Automatic TOC generation for long articles
  - Placement options: `toc-left` or `toc-right` layouts
  - Automatic heading extraction and navigation
  - Responsive sidebar positioning

---

## 📋 Requirements

- **Joomla**: 4.4.x or 5.x
- **PHP**: 8.0 or higher
- **Database**: MySQL 5.7+ / MariaDB 10.2+ / PostgreSQL 11+
- **Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)

---

## 📦 Installation

### Via Joomla Extension Manager

1. Download the latest `mokocassiopeia-{version}.zip` from [Releases](https://github.com/mokoconsulting-tech/MokoCassiopeia/releases)
2. In Joomla admin, navigate to **System → Install → Extensions**
3. Upload the ZIP file and click **Upload & Install**
4. Navigate to **System → Site Templates**
5. Set **MokoCassiopeia** as your default template

### Via Git (Development)

```bash
git clone https://github.com/mokoconsulting-tech/MokoCassiopeia.git
cd MokoCassiopeia
```

See [Development Guide](./docs/JOOMLA_DEVELOPMENT.md) for development setup.

---

## 🚀 Quick Start

### 1. Install the Template

Install `mokocassiopeia.zip` via Joomla's Extension Manager.

### 2. Set as Default

Navigate to **System → Site Templates** and set **MokoCassiopeia** as default.

### 3. Configure Template Options

Go to **System → Site Templates → MokoCassiopeia** to configure:

- **Branding**: Upload logo, set site title/description
- **Theme**: Configure color schemes and dark mode
- **Layout**: Set container type (static/fluid), sticky header
- **Analytics**: Add GTM/GA4 tracking codes (optional)
- **Custom Code**: Inject custom HTML/CSS/JS

### 4. Test Dark Mode

The template includes a dark mode toggle. Test it by:
- Using the floating theme toggle button (bottom-right by default)
- Checking theme persistence across page loads
- Verifying system preference detection

---

## ⚙️ Configuration

### Global Parameters

Access template configuration via **System → Site Templates → MokoCassiopeia**.

#### Theme Tab

**General Settings:**
- **Theme Enabled**: Enable/disable theme system
- **Theme Control Type**: Switch (Light↔Dark), Radios (Light/Dark/System), or None
- **Default Choice**: System, Light, or Dark
- **Auto Dark Mode**: Automatic dark mode based on time
- **Meta Tags**: Enable color-scheme and theme-color meta tags
- **Bridge Bootstrap ARIA**: Sync theme with Bootstrap's data-bs-theme

**Variables & Palettes:**
- **Light Mode Palette**: Standard, Alternative, or Custom
- **Dark Mode Palette**: Standard, Alternative, or Custom

**Typography:**
- **Font Scheme**: Local (Roboto) or Web fonts (Fira Sans, Roboto+Noto Sans)

**Branding & Icons:**
- **Brand**: Enable/disable site branding
- **Logo File**: Upload custom logo (no default logo included)
- **Site Title**: Custom site title
- **Site Description**: Tagline/description
- **Font Awesome Kit**: Optional FA Pro kit code

**Header & Navigation:**
- **Sticky Header**: Enable fixed header on scroll
- **Back to Top**: Enable floating back-to-top button

**Theme Toggle UI:**
- **FAB Enabled**: Enable floating action button toggle
- **FAB Position**: Bottom-right, Bottom-left, Top-right, or Top-left

#### Advanced Tab

- **Layout**: Static or Fluid container

#### Google Tab

- **Google Tag Manager**: Enable and configure GTM container ID
- **Google Analytics**: Enable and configure GA4 measurement ID

#### Custom Code Tab

- **Custom Head Start**: HTML injected at start of `<head>`
- **Custom Head End**: HTML injected at end of `<head>`

#### Drawers Tab

- **Left Drawer Icon**: Font Awesome icon class (e.g., `fa-solid fa-chevron-right`)
- **Right Drawer Icon**: Font Awesome icon class (e.g., `fa-solid fa-chevron-left`)

### Custom Color Palettes

MokoCassiopeia supports custom color schemes:

1. **Copy template files** from `/templates/` directory:
   - `colors_custom_light.css` → `media/templates/site/mokocassiopeia/css/colors/light/colors_custom.css`
   - `colors_custom_dark.css` → `media/templates/site/mokocassiopeia/css/colors/dark/colors_custom.css`
2. **Customize** the CSS variables to match your brand colors
3. **Enable in Joomla**: System → Site Templates → MokoCassiopeia → Theme tab → Set palette to "Custom"
4. **Save** and view your site with custom colors

**Note:** Custom color files are excluded from version control (`.gitignore`) to prevent fork-specific customizations from being committed.

**Quick Example:**

```css
:root[data-bs-theme="light"] {
  --color-primary: #1e40af;
  --color-link: #2563eb;
  --color-hover: #1d4ed8;
  --body-color: #1f2937;
  --body-bg: #ffffff;
}
```

**Complete Reference:** See [CSS Variables Documentation](./docs/CSS_VARIABLES.md) for all available variables and detailed usage examples.

### Table of Contents

Enable automatic TOC for articles:

1. Edit an article in Joomla admin
2. Navigate to **Options → Layout**
3. Select **toc-left** or **toc-right**
4. Save the article

The TOC will automatically generate from article headings (H2, H3, etc.) and appear as a sidebar.

---

## 🎨 Theme System

### Dark Mode Features

- **Automatic Detection**: Respects user's system preferences
- **Manual Toggle**: Floating button or radio controls
- **Persistence**: Saves preference in localStorage
- **Smooth Transitions**: Animated theme switching
- **Comprehensive Support**: All components themed for dark mode

### Theme Control Types

1. **Switch**: Simple light/dark toggle button
2. **Radios**: Three options - Light, Dark, System
3. **None**: No visible control (respects system only)

### Meta Tags

When enabled, the template adds:

```html
<meta name="color-scheme" content="light dark">
<meta name="theme-color" content="#1e3a8a" media="(prefers-color-scheme: dark)">
<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
```

---

## 🛠 Development

### For Contributors

**New to the project?** See [Quick Start Guide](./docs/QUICK_START.md) for a 5-minute setup.

### Development Resources

- **[Quick Start Guide](./docs/QUICK_START.md)** - Setup and first steps
- **[Joomla Development Guide](./docs/JOOMLA_DEVELOPMENT.md)** - Testing, quality checks, deployment
- **[Workflow Guide](./docs/WORKFLOW_GUIDE.md)** - Git workflow and branching
- **[Contributing Guide](./CONTRIBUTING.md)** - Contribution guidelines
- **[Roadmap](./docs/ROADMAP.md)** - Feature roadmap and planning

### Development Tools

- **Pre-commit Hooks**: Automatic validation before commits
- **PHP CodeSniffer**: Code style validation (Joomla standards)
- **PHPStan**: Static analysis for PHP code
- **Codeception**: Testing framework

### Quick Development Setup

```bash
# Clone repository
git clone https://github.com/mokoconsulting-tech/MokoCassiopeia.git
cd MokoCassiopeia

# Install development dependencies (if using Composer)
composer install --dev

# Run code quality checks
make validate  # or manual commands
```

### Building Template Package

See [Joomla Development Guide](./docs/JOOMLA_DEVELOPMENT.md) for packaging instructions.

---

## 📚 Documentation

### User Documentation

- **[README](./README.md)** - This file (overview and features)
- **[CHANGELOG](./CHANGELOG.md)** - Version history and changes
- **[Roadmap](./docs/ROADMAP.md)** - Planned features and timeline

### Developer Documentation

- **[Quick Start](./docs/QUICK_START.md)** - 5-minute developer setup
- **[Development Guide](./docs/JOOMLA_DEVELOPMENT.md)** - Comprehensive development guide
- **[Workflow Guide](./docs/WORKFLOW_GUIDE.md)** - Git workflow and processes
- **[CSS Variables Reference](./docs/CSS_VARIABLES.md)** - Complete CSS customization guide
- **[Documentation Index](./docs/README.md)** - All documentation links

### Customization Resources

- **[Template Files](./templates/)** - Ready-to-use color palette templates
  - `colors_custom_light.css` - Light mode template
  - `colors_custom_dark.css` - Dark mode template

### Governance

- **[Contributing](./CONTRIBUTING.md)** - How to contribute
- **[Code of Conduct](./CODE_OF_CONDUCT.md)** - Community standards
- **[Governance](./GOVERNANCE.md)** - Project governance model
- **[Security Policy](./SECURITY.md)** - Security reporting and procedures

---

## 📖 Changelog

See the [CHANGELOG.md](./CHANGELOG.md) for detailed version history.

### Recent Releases

- **[03.06.03]** (2026-01-30) - README updates and TOC color variable improvements
- **[03.06.02]** (2026-01-30) - Complete rebrand to MokoCassiopeia, removed all overrides
- **[03.06.00]** (2026-01-28) - Version standardization
- **[03.05.01]** (2026-01-09) - Security and compliance improvements
- **[02.00.00]** (2025-08-30) - Dark mode toggle and improved theming

---

## 💬 Support

### Getting Help

- **Documentation**: Check this README and [docs folder](./docs/)
- **Issues**: Report bugs via [GitHub Issues](https://github.com/mokoconsulting-tech/MokoCassiopeia/issues)
- **Discussions**: Ask questions in [GitHub Discussions](https://github.com/mokoconsulting-tech/MokoCassiopeia/discussions)
- **Roadmap**: View planned features in [Roadmap](https://mokoconsulting.tech/support/joomla-cms/mokocassiopeia-roadmap)

### Reporting Bugs

Please include:
- Joomla version
- PHP version
- Template version
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)

### Security Issues

**Do not** report security vulnerabilities via public issues. See [SECURITY.md](./SECURITY.md) for reporting procedures.

---

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](./CONTRIBUTING.md) for guidelines.

### How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run quality checks
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Development Workflow

See [Workflow Guide](./docs/WORKFLOW_GUIDE.md) for detailed Git workflow.

---

## 📦 Included Libraries

MokoCassiopeia includes the following third-party libraries to provide enhanced functionality:

### Bootstrap TOC

- **Version**: 1.0.1
- **Author**: Aidan Feldman
- **License**: MIT License
- **Source**: [GitHub Repository](https://github.com/afeld/bootstrap-toc)
- **Release**: [v1.0.1 Release](https://github.com/afeld/bootstrap-toc/releases/tag/v1.0.1)
- **Purpose**: Automatically generates a table of contents from article headings with scrollspy support
- **Location**: `src/media/vendor/bootstrap-toc/`
- **Integration**: Registered in `joomla.asset.json` as `vendor.bootstrap-toc` (CSS) and `vendor.bootstrap-toc.js` (JavaScript)
- **Usage**: Activated when using `toc-left` or `toc-right` article layouts
- **Features**:
  - Automatic TOC generation from H1-H6 headings
  - Hierarchical nested navigation
  - Active state highlighting with scrollspy
  - Responsive design (collapses on mobile)
  - Smooth scrolling to sections
  - Automatic unique ID generation for headings
- **Customizations**: CSS adapted to use Cassiopeia CSS variables for theme compatibility

### Font Awesome 7 Free

- **Version**: 7.0 (Free)
- **License**: Font Awesome Free License
- **Source**: [Font Awesome](https://fontawesome.com)
- **Purpose**: Provides 2,000+ vector icons for interface elements
- **Location**: `src/media/vendor/fa7free/`
- **Integration**: Fully integrated into Joomla's asset manager
- **Styles Available**: Solid, Regular, Brands

### Bootstrap 5

- **Version**: 5.x (via Joomla)
- **License**: MIT License
- **Source**: [Bootstrap](https://getbootstrap.com)
- **Purpose**: Provides responsive grid system and utility classes
- **Integration**: Inherited from Joomla's Cassiopeia template, extended with additional helpers
- **Components Used**: Grid, utilities, modal, dropdown, collapse, offcanvas, tooltip, popover, scrollspy

### Integration Method

All third-party libraries are:
- ✅ Properly licensed and attributed
- ✅ Registered in Joomla's Web Asset Manager (`joomla.asset.json`)
- ✅ Loaded on-demand to optimize performance
- ✅ Versioned and documented for maintenance
- ✅ Compatible with Joomla 4.4.x and 5.x

---

## 📄 License

This project is licensed under the **GNU General Public License v3.0** - see the [LICENSE](./LICENSE) file for details.

### Third-Party Licenses

- **Joomla! CMS**: GPL-2.0-or-later
- **Cassiopeia Template**: GPL-2.0-or-later (Joomla Project)
- **Font Awesome 7 Free**: Font Awesome Free License
- **Bootstrap 5**: MIT License
- **Bootstrap TOC**: MIT License (A. Feld)

All third-party libraries and assets remain the property of their respective authors and are credited in source files.

---

## 🔗 Links

- **Repository**: [GitHub](https://github.com/mokoconsulting-tech/MokoCassiopeia)
- **Issue Tracker**: [GitHub Issues](https://github.com/mokoconsulting-tech/MokoCassiopeia/issues)
- **Discussions**: [GitHub Discussions](https://github.com/mokoconsulting-tech/MokoCassiopeia/discussions)
- **Roadmap**: [Full Roadmap](https://mokoconsulting.tech/support/joomla-cms/mokocassiopeia-roadmap)
- **Moko Consulting**: [Website](https://mokoconsulting.tech)

---

## 📊 Metadata

- **Maintainer**: Moko Consulting Engineering
- **Author**: Jonathan Miller (@jmiller-moko)
- **Repository**: https://github.com/mokoconsulting-tech/MokoCassiopeia
- **License**: GPL-3.0-or-later
- **Classification**: Public Open Source Standards

## 📝 Revision History

| Date       | Version  | Change Summary                                                            | Author                          |
| ---------- | -------- | ------------------------------------------------------------------------- | ------------------------------- |
| 2026-01-30 | 03.06.03 | Updated README title, fixed custom color variables instructions, improved TOC color scheme integration | Copilot Agent                   |
| 2026-01-30 | 03.06.02 | Regenerated README with comprehensive documentation and updated structure | Copilot Agent                   |
| 2026-01-30 | 03.06.02 | Complete rebrand to MokoCassiopeia, removed overrides                     | Copilot Agent                   |
| 2026-01-05 | 03.00.00 | Initial publication of template documentation and feature overview        | Moko Consulting                 |
| 2026-01-05 | 03.00.00 | Fixed malformed markdown table formatting in revision history             | Jonathan Miller (@jmiller-moko) |

---

**Made with ❤️ by [Moko Consulting](https://mokoconsulting.tech)**
