<!-- Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 This program is free software; you can redistribute it and/or modify  it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or 
    (at your option) any later version.

 This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the  GNU General Public License for more details. 

 You should have received a copy of the GNU General Public License along with this program. If not, see https://www.gnu.org/licenses/ .

 # FILE INFORMATION
 DEFGROUP: Joomla
 INGROUP: Template
 FILE: ./README.md
 VERSION: 03.00 RC
 BRIEF: Documentation for Moko-Cassiopeia template
 PATH: /templates/moko-cassiopeia/
 NOTE: Includes Dark Mode and Soft Offline Mode
 -->

# Moko-Cassiopeia (v03.00 RC)

A modern, lightweight enhancement layer for Joomla's Cassiopeia
template.
Moko-Cassiopeia adds **Font Awesome 6**, **Bootstrap 5** helpers, an
automatic **Table of Contents (TOC)** utility, and optional **Moko
Expansions** including **Google Tag Manager** and **Google Analytics
(GA4)** hooks---all while keeping core template overrides minimal and
upgrade-friendly.

## Table of Contents

-   [Features](#features)\
-   [Requirements](#requirements)\
-   [Quick Start](#quick-start)\
-   [Installation](#installation)\
-   [Configuration](#configuration)
	-   [Global Params](#global-params)\
	-   [Font Awesome 6](#font-awesome-6)\
	-   [Bootstrap 5 Helpers](#bootstrap-5-helpers)\
	-   [Table of Contents](#table-of-contents)\
	-   [Dark Mode Toggle](#dark-mode-toggle)\
	-   [Soft Offline Mode](#soft-offline-mode)\
-   [Changelog](#changelog)\
-   [Roadmap](#roadmap)

## Features

### Core Enhancements

-   Built on top of Joomla's default **Cassiopeia** template.\
-   **Font Awesome 6** integration.\
-   **Bootstrap 5** helpers (grid, utility classes).\
-   **Automatic TOC** insertion for articles (activated via layout
	`toc-left` or `toc-right`).

### Added in 2.0

-   **Dark Mode Toggle**
	-   User-facing switch in the header.\
	-   Persists preference with local storage.\
	-   Admins can override default mode in template settings.
-   **Improved Template Params**
	-   Configure logo, GTM container ID, and dark mode defaults
		directly from template settings.

### New in 2.1.5 (In Development)

-   **Soft Offline Mode**
	-   Keeps articles in specific categories available when the site is
		offline.\
	-   Example: legal or policy documents remain publicly viewable even
		during maintenance.\
	-   Admin can configure which categories remain accessible.

## Requirements

-   Joomla **4.4.x** or **5.x**\
-   PHP **8.0+**\
-   MySQL/MariaDB compatible database

## Quick Start

1.  Install `moko-cassiopeia.zip` via Joomla's Template Installer.\
2.  Set **Moko-Cassiopeia** as your default template.\
3.  Configure template options under **System → Site Templates →
	Moko-Cassiopeia**.

## Installation

Upload and install through Joomla's extension manager.\
If upgrading from a prior version, Joomla will safely overwrite files
--- no manual uninstall required.

## Configuration

### Global Params

-   **Logo**: Upload a custom site logo.\
-   **Color Scheme**: Toggle light/dark defaults.\
-   **Analytics/GTM**: Enable/disable optional expansions.

### Font Awesome 6

-   Fully integrated into Joomla's asset manager.\
-   No extra scripts required.

### Bootstrap 5 Helpers

-   Adds extended utility classes and responsive tweaks.

### Table of Contents

-   Select `toc-left` or `toc-right` in article **Options → Layout** to
	insert TOC automatically.

### Dark Mode Toggle

-   User-facing switch in the header.\
-   Remembers preference via local storage.\
-   Default behavior can be set in template settings.

### Soft Offline Mode

-   Introduced in **2.1.5**.\
-   Allows articles in selected categories to remain viewable during
	offline/maintenance mode.\
-   Useful for compliance, legal, or policy content.

## Changelog

See the [CHANGELOG.md](./CHANGELOG.md) for detailed version history.

## Roadmap

-   **2.2 (Planned)**
	-   Lazy load media for performance.\
	-   Expanded template overrides for more layout control.
-   **Future Considerations**
	-   Multi-tenancy support.\
	-   Visual layout builder for drag-and-drop template positions.
