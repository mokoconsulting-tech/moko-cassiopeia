<!-- Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 FILE: ./README.md
 VERSION: 03.06.00
 BRIEF: Documentation for Moko-Cassiopeia template
 -->

# Moko-Cassiopeia (VERSION: 03.06.00)

A modern, lightweight enhancement layer for Joomla's Cassiopeia
template.
Moko-Cassiopeia adds **Font Awesome 7**, **Bootstrap 5** helpers, an
automatic **Table of Contents (TOC)** utility, and optional **Moko
Expansions** including **Google Tag Manager** and **Google Analytics
(GA4)** hooks---all while keeping core template overrides minimal and
upgrade-friendly.

## Table of Contents

-   [Features](#features)
-   [Requirements](#requirements)
-   [Quick Start](#quick-start)
-   [Installation](#installation)
-   [Configuration](#configuration)
	-   [Global Params](#global-params)
	-   [Font Awesome 6](#font-awesome-6)
	-   [Bootstrap 5 Helpers](#bootstrap-5-helpers)
	-   [Table of Contents](#table-of-contents)
	-   [Dark Mode Toggle](#dark-mode-toggle)
	-   [Soft Offline Mode](#soft-offline-mode)
-   [Development](#development)
-   [Changelog](#changelog)
-   [Roadmap](#roadmap)

## Features

### Core Enhancements

-   Built on top of Joomla's default **Cassiopeia** template.
-   **Font Awesome 6** integration.
-   **Bootstrap 5** helpers (grid, utility classes).
-   **Automatic TOC** insertion for articles (activated via layout
	`toc-left` or `toc-right`).

### Added in 2.0

-   **Dark Mode Toggle**
	-   User-facing switch in the header.
	-   Persists preference with local storage.
	-   Admins can override default mode in template settings.
-   **Improved Template Params**
	-   Configure logo, GTM container ID, and dark mode defaults
		directly from template settings.

### New in 2.1.5 (In Development)

-   **Soft Offline Mode**
	-   Keeps articles in specific categories available when the site is
		offline.
	-   Example: legal or policy documents remain publicly viewable even
		during maintenance.
	-   Admin can configure which categories remain accessible.

## Requirements

-   Joomla **4.4.x** or **5.x**
-   PHP **8.0+**
-   MySQL/MariaDB compatible database

## Quick Start

1.  Install `moko-cassiopeia.zip` via Joomla's Template Installer.
2.  Set **Moko-Cassiopeia** as your default template.
3.  Configure template options under **System → Site Templates →
	Moko-Cassiopeia**.

## Installation

Upload and install through Joomla's extension manager.
If upgrading from a prior version, Joomla will safely overwrite files
--- no manual uninstall required.

## Configuration

### Global Params

-   **Logo**: Upload a custom site logo.
-   **Color Scheme**: Toggle light/dark defaults.
-   **Analytics/GTM**: Enable/disable optional expansions.

### Custom Color Palettes

Moko-Cassiopeia supports custom color schemes for both light and dark modes:

-   **Standard**: Default Joomla Cassiopeia colors
-   **Alternative**: Alternative color palette
-   **Custom**: Create your own custom colors by adding `colors_custom.css` files

To use custom colors:
1. Create `media/templates/site/moko-cassiopeia/css/colors/light/colors_custom.css` for light mode
2. Create `media/templates/site/moko-cassiopeia/css/colors/dark/colors_custom.css` for dark mode
3. Define your CSS variables in these files (see existing `colors_standard.css` for reference)
4. Select "Custom" in template settings under **Variables & Palettes**

### Font Awesome 7

-   Fully integrated into Joomla's asset manager.
-   No extra scripts required.

### Bootstrap 5 Helpers

-   Adds extended utility classes and responsive tweaks.

### Table of Contents

-   Select `toc-left` or `toc-right` in article **Options → Layout** to
	insert TOC automatically.

### Dark Mode Toggle

-   User-facing switch in the header.
-   Remembers preference via local storage.
-   Default behavior can be set in template settings.

### Soft Offline Mode

-   Introduced in **2.1.5**.
-   Allows articles in selected categories to remain viewable during
	offline/maintenance mode.
-   Useful for compliance, legal, or policy content.

## Development

For developers and contributors working on the moko-cassiopeia template:

### Quick Start for Developers

**New to the project?** See [Quick Start Guide](./docs/QUICK_START.md) for a 5-minute walkthrough.

### Development Resources

- **[Quick Start Guide](./docs/QUICK_START.md)** - Get up and running in 5 minutes
- **[Joomla Development Guide](./docs/JOOMLA_DEVELOPMENT.md)** - Testing, quality checks, and deployment
- **[Contributing Guide](./CONTRIBUTING.md)** - How to contribute

### Available Tools

- **Pre-commit Hooks**: Automatic validation before commits

## Changelog

See the [CHANGELOG.md](./CHANGELOG.md) for detailed version history.

---

## Metadata

* Maintainer: Moko Consulting Engineering
* Repository: [https://github.com/mokoconsulting-tech/moko-cassiopeia](https://github.com/mokoconsulting-tech/moko-cassiopeia)
* File: README.md
* Version: 3.0
* Classification: Public Open Source Standards

## Revision History

| Date       | Change Summary                                                      | Author                          |
| ---------- | ------------------------------------------------------------------- | ------------------------------- |
| 2026-01-05 | Initial publication of template documentation and feature overview. | Moko Consulting                 |
| 2026-01-05 | Fixed malformed markdown table formatting in revision history.     | Jonathan Miller (@jmiller-moko) |
