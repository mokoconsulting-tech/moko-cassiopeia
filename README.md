<!--
=========================================================================
 Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program. If not, see https://www.gnu.org/licenses/ .
 =========================================================================
 FILE INFORMATION
 DEFGROUP: Joomla
 INGROUP: Moko-Cassiopeia
 PATH: README.md
 VERSION: 02.00
 BRIEF: Project readme for Moko-Cassiopeia, including features, setup, and usage
 =========================================================================
-->


# Moko-Cassiopeia v02.00 — README

> Joomla! site template by **Moko Consulting**
> License: **GPL-3.0-or-later**
> Compatibility: **Joomla 4.4+ / 5.x** (PHP 8.1+)

---

## Overview

Moko-Cassiopeia is a streamlined, Bootstrap-friendly Joomla template with a tokenized color system, Google Tag Manager / Analytics hooks, and performance-minded assets.

* **v02.00 (2025-08-30)** introduces **Dark Mode** with OS auto-detection **and** an optional **Dark Mode Toggle** (ID **25**) so users can manually switch themes; their preference persists.
* **v01.00** was the initial public release (FA6, BS5, TOC, GTM/GA hooks).

Public roadmap: **[https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap](https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap)**

---

## What’s New in v02.00

* **Dark Mode** with `prefers-color-scheme` auto-detect.
* **Dark Mode Toggle** (Template param **ID 25**) with positions **Header / Navbar / Footer**.

	* Persists choice to `localStorage` (key: `moko.theme`).
	* Keyboard- and screen-reader-friendly; focus ring uses theme tokens.
	* Admin option **“Show Theme Toggle”** (`On/Off`).
* CSS refactor to **variables**: light tokens in `:root`, dark overrides in `[data-theme="dark"]`.
* Lowered CSS specificity, `rem` units for scalable typography/spacing.
* Stabilized WebAsset registrations (LTR/RTL presets).

---

## Features

* **Dark Mode + Toggle**
	Auto-detect plus manual switch; persistent per user.

* **Bootstrap-friendly CSS**
	Low specificity, variable-driven utilities for buttons, alerts, typography, spacing.

* **GTM / GA Hooks**
	Clean injection points for Google Tag Manager and optional direct GA4 tag.

* **LTR / RTL Presets**
	Stable asset registration pattern for palette and template styles.

* **A11y & Performance**
	Clear focus styling and balanced contrast; minified bundles in production.

---

## Requirements

* Joomla **4.4+** / **5.x**
* PHP **8.1+**
* Modern evergreen browsers (graceful fallback if `prefers-color-scheme` isn’t available)

---

## Installation

1. **Install** via *Extensions → Manage → Install* (upload the template `.zip`).
2. **Set as default** in *System → Templates → Site Templates*.
3. **Clear caches**: *System → Clear Cache* and hard-reload your browser.

---

## Template Options (high-level)

**Theme**

* **Force Theme**: `Auto` (default) | `Light` | `Dark`
* **Show Theme Toggle** (ID **25**): `On` | `Off`
* **Toggle Position**: `Header` | `Navbar` | `Footer`
* **Default Theme** (when not using Auto): `Light`

**GTM / Analytics**

* **GTM Container ID** (e.g., `GTM-XXXXXXX`)
* **Analytics Tag ID** (optional GA4 if not using GTM)

**Performance**

* **Development Mode**

	* `Off` → `.min.css` / `.min.js` bundles
	* `On` → unminified sources for debugging

---

## Dark Mode — Tokens & Toggle

**Color tokens**

```css
:root {
	--color-bg: #ffffff;
	--color-surface: #f8f9fa;
	--color-text: #1d2125;
	--color-text-muted: #6c757d;
	--color-border: #dee2e6;

	--color-primary: #0d6efd;
	--color-primary-contrast: #ffffff;

	--color-link: var(--color-primary);
	--color-link-hover: #0b5ed7;

	--focus-ring: 0 0 0 .2rem rgba(13,110,253,.25);
}

[data-theme="dark"] {
	--color-bg: #0e1116;
	--color-surface: #151922;
	--color-text: #e7eaf0;
	--color-text-muted: #a4acb9;
	--color-border: #2a3240;

	--color-primary: #66b2ff;
	--color-primary-contrast: #0d1117;

	--color-link: var(--color-primary);
	--color-link-hover: #99ccff;

	--focus-ring: 0 0 0 .2rem rgba(102,178,255,.35);
}
```

**Programmatic switch (optional)**

```js
// Apply and persist a choice
document.documentElement.dataset.theme = 'dark'; // or 'light'
localStorage.setItem('moko.theme', 'dark');      // namespaced key
```

---

## CSS Architecture

* **`template.css`** = structure/layout and component scaffolding
* **No hard-coded hex** in core selectors; all colors reference tokens
* **Units**: `rem` (replacing `em`) for scalable typography/spacing
* **Low specificity** to play nicely with Bootstrap and content plugins

---

## GTM / Analytics Integration

* Enter **GTM Container ID** in Template Options to inject the GTM snippet.
* Optionally add a **GA4 Measurement ID** if not routing GA via GTM.
* Output uses Joomla rendering events to avoid duplication.

> Verify tags with DevTools / Tag Assistant.

---

## RTL / LTR Assets (WebAsset JSON)

Minimal pattern:

```json
{
	"$schema": "https://developer.joomla.org/schemas/json/schema_web_assets.json",
	"name": "template.moko-cassiopeia",
	"assets": [
		{ "name": "template.moko-cassiopeia.styles",  "type": "style", "uri": "templates/moko-cassiopeia/css/template.min.css" },
		{ "name": "template.moko-cassiopeia.palette", "type": "style", "uri": "templates/moko-cassiopeia/css/colors_standard.min.css" },
		{ "name": "template.moko-cassiopeia", "type": "preset", "dependencies": ["template.moko-cassiopeia.styles","template.moko-cassiopeia.palette"] }
	]
}
```

In `index.php`:

```php
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getWebAssetManager();
$wa->usePreset('template.moko-cassiopeia');
```

---

## Upgrade Notes

**1.0 → 2.0**

* Clear Joomla + browser caches.
* Convert any hard-coded colors in overrides to **tokens** (e.g., `var(--color-text-muted)`).
* Review spacing/typography where `rem` replaces `em`.
* Verify asset names if you referenced WebAsset handles directly.
* If you previously added a custom dark-mode toggle, remove it and enable **Show Theme Toggle** (ID **25**).

---

## Accessibility

* Improved contrast targets across light/dark.
* Visible, consistent focus indicators.
* Toggle is keyboard-navigable and labeled for assistive tech.

---

## Troubleshooting

* **Toggle not visible** → Ensure “Show Theme Toggle” is on and placed in a visible position.
* **Preference not persisting** → Check `localStorage` availability and console for JS errors.
* **Asset dependency warnings** → Confirm preset/asset names match your `joomla.asset.json`.

---

## Feature Rundown & Comparison

### Moko-Cassiopeia v01.00 — Initial public release

* **Font Awesome 6** integrated; **Bootstrap 5** helpers.
* **TOC utility** hooks for article table of contents.
* **GTM/GA hooks** with safe injection points.
* Minimal, upgrade-friendly overrides; variable-ready CSS.

### Moko-Cassiopeia v02.00 — Dark Mode + Toggle (ID 25)

* **Dark Mode** with OS auto-detect.
* **Optional Dark Mode Toggle** (ID 25) in Header / Navbar / Footer; persisted per user.
* **Tokenized palette** (`:root` + `[data-theme="dark"]`).
* **CSS refactor**: low specificity; `rem` units; Bootstrap-friendly utilities.
* Stabilized **Web Asset** registrations (LTR/RTL presets).

### Baseline: Cassiopeia (Joomla 4.4 / 5.x)

* Responsive, accessible core site template with Bootstrap-friendly markup.
* Template options for color preset, layout width, sticky header, and module menu layouts.
* Web Asset Manager integration (`joomla.asset.json`, `$this->getWebAssetManager()`).

---

## Roadmap

Public roadmap: **[https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap](https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap)**

---

## Changelog (1.0 → 2.0)

### 02.00 — 2025-08-30 — “Dark Mode”

**Added**

* Dark Mode with OS auto-detection (`prefers-color-scheme`).
* **Dark Mode Toggle** (param **ID 25**) with positions Header / Navbar / Footer; persists choice via `localStorage` (`moko.theme`); accessible markup and focus styling.
* Tokenized CSS palette with `[data-theme="dark"]` overrides.
* Admin override to force Light/Dark/Auto.
* Bootstrap-friendly utility hooks mapped to tokens.

**Changed**

* `template.css` now structure/layout only; colors via tokens.
* `em` → `rem`; reduced specificity; standardized focus indicators.

**Fixed**

* WebAsset registrations (LTR/RTL/preset deps) and dark-theme link/muted contrast.

**Removed / Deprecated**

* Hard-coded color declarations and legacy hex-based helper classes.

---

### 01.00 — Initial public release

* **FA6**, **BS5**, **TOC**, **GTM/GA** hooks.
