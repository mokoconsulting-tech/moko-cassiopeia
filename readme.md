# Moko-Cassiopeia

A modern, lightweight enhancement layer for Joomla’s Cassiopeia template. Moko-Cassiopeia adds **Font Awesome 6**, **Bootstrap 5** helpers, an automatic **Table of Contents (TOC)** utility, and optional **Moko Expansions** including **Google Tag Manager** and **Google Analytics (GA4)** hooks—all while keeping core template overrides minimal and upgrade‑friendly.

---

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Quick Start](#quick-start)
- [Installation](#installation)
- [Configuration](#configuration)
  - [Global Params](#global-params)
  - [Font Awesome 6](#font-awesome-6)
  - [Bootstrap 5 Helpers](#bootstrap-5-helpers)
  - [TOC Function](#toc-function)
  - [Moko Expansions](#moko-expansions)
    - [Google Tag Manager](#google-tag-manager)
    - [Google Analytics (GA4)](#google-analytics-ga4)
- [CSS Variables](#css-variables)
- [Usage Examples](#usage-examples)
- [Best Practices](#best-practices)
- [Development](#development)
- [Changelog](#changelog)
- [License](#license)

---

## Features

- **Font Awesome 6**: Solid, Regular, Brands (locally enqueued or CDN with Subresource Integrity).
- **Bootstrap 5**: Utility classes, grid, and components available to your layouts and modules.
- **Auto TOC**: Generate an in‑page Table of Contents from headings with a single data attribute.
- **Moko Expansions**:
  - **GTM**: Drop‑in dataLayer and container injection with a template param.
  - **GA4**: Native GA4 Measurement ID support (with or without GTM).
- **Production‑safe**: Assets loaded conditionally; no duplicate library loads if another extension already enqueues them.
- **Accessible by default**: TOC anchors and focus styles follow a11y guidelines.

## Requirements

- Joomla 4.4+ or Joomla 5+
- PHP 8.1+
- Cassiopeia as the active base template (Moko-Cassiopeia may be installed as a child/override set)

## Quick Start

1. Install the template package via Joomla Extension Manager.
2. In the Template Style settings, enable the features you want (FA6, BS5, TOC, GTM/GA).
3. (Optional) Add a TOC container to any article or module using the data attribute shown below.

## Installation

1. Go to 'System' → 'Install' → 'Extensions'.
2. Upload 'Moko-Cassiopeia.zip'.
3. After installation, go to 'System' → 'Site Templates' → 'Styles' and open 'Moko-Cassiopeia'.
4. Choose 'Default' to make it your active style (or assign per menu item).

## Configuration

Configuration lives in the Template Style parameters. Common params are listed below. Names may vary slightly depending on release.

### Global Params

- 'load\_bootstrap5' (bool): Enqueue Bootstrap 5 core CSS/JS if not provided by Joomla context.
- 'load\_fontawesome6' (bool): Enqueue Font Awesome 6 (solid, regular, brands).
- 'use\_cdn' (bool): Use CDN with SRI instead of local assets.
- 'minified' (bool): Prefer '.min' assets.
- 'defer\_js' (bool): Add 'defer' to template‑injected scripts where safe.

### Font Awesome 6

When enabled, the template registers FA6 and exposes the standard icon syntax:

```html
<i class='fa-solid fa-circle-check' aria-hidden='true'></i>
<span class='visually-hidden'>Success</span>
```

**Notes**

- Icons are decorative unless paired with text or 'aria-label'.
- Prefer the 'fa-solid', 'fa-regular', or 'fa-brands' families explicitly.

### Bootstrap 5 Helpers

If 'load\_bootstrap5' is enabled, grid and utilities are available:

```html
<div class='container'>
  <div class='row g-3'>
    <div class='col-12 col-md-6'>Left</div>
    <div class='col-12 col-md-6'>Right</div>
  </div>
</div>
```

You can also use helpers like 'ratio', 'visually-hidden', 'd-flex', and spacing utilities (e.g., 'mt-3', 'px-4').

### TOC Function

Moko-Cassiopeia ships a tiny script that scans within a container for headings (h2–h6) and builds a nested TOC with anchor links.

**Enable**: Turn on 'auto\_toc' in Template Style.

**Place a TOC container**:

```html
<nav class='toc' data-moko-toc='true' data-moko-toc-target='#article-body' aria-label='Table of contents'></nav>
```

**Mark your content region**:

```html
<article id='article-body'>
  <h2>Section A</h2>
  <p>...</p>
  <h3>Subsection A.1</h3>
  <p>...</p>
</article>
```

**Options via data attributes**

- 'data-moko-toc-target': CSS selector for the content area (default: 'main').
- 'data-moko-toc-levels': CSV or range string like '2-4' (default: '2-4').
- 'data-moko-toc-collapsible': 'true'|'false' to make nested lists collapsible.

**Styling**

A minimal stylesheet is included. Customize using the CSS variables below or add your own overrides.

### Moko Expansions

#### Google Tag Manager

Enable GTM by entering your container ID (e.g., 'GTM-XXXXXXX') in Template Style under 'Moko Expansions'. The template will inject the standard script and 'noscript' iframe per Google guidance.

**Data Layer**

You can push events from modules or overrides like so:

```html
<script>
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    'event': 'moko_event',
    'moko_category': 'ui',
    'moko_action': 'toc_opened',
    'moko_label': 'sidebar'
  });
</script>
```

#### Google Analytics (GA4)

Two options:

1. **Direct GA4**: Provide 'G-' Measurement ID (e.g., 'G-ABC123XYZ'). The template injects the GA4 base script.

2. **Via GTM**: Leave GA4 field empty and configure GA4 inside your GTM container.

```html
<script>
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({ 'event': 'page_view' });
</script>
```

> Tip: When both GTM and direct GA4 are set, the template prefers GTM to avoid duplicate pageviews.

## CSS Variables

Moko-Cassiopeia exposes custom properties for theme tuning. Example set:

```css
:root {
  --moko-cassiopeia-color-primary: #0b4008;
  --moko-cassiopeia-color-link: #0b4008;
  --moko-cassiopeia-color-hover: #000000;

  --moko-cassiopeia-header-background-image: linear-gradient(30deg, #fefcf9, var(--accent-color-primary));
  --moko-cassiopeia-header-background-position: auto;
  --moko-cassiopeia-header-background-attachment: fixed;
  --moko-cassiopeia-header-background-repeat: repeat;
  --moko-cassiopeia-header-background-size: auto;
}
```

> Apply these in a custom stylesheet or template options if provided. Use semantic variables where possible to maintain consistency.

## Usage Examples

### 1) FA6 Icon Buttons

```html
<a class='btn btn-primary d-inline-flex align-items-center' href='#'>
  <i class='fa-solid fa-bolt me-2' aria-hidden='true'></i>
  <span>Action</span>
</a>
```

### 2) Sticky Sidebar TOC

```html
<aside class='position-sticky top-0 pt-3'>
  <nav class='toc' data-moko-toc='true' data-moko-toc-target='#content'></nav>
</aside>
```

### 3) Module‑driven GA Event

```php
<?php
// In a custom module layout
$label = 'cta_hero';
?>
<button class='btn btn-outline-primary' onclick='window.dataLayer && window.dataLayer.push({"event": "cta_click", "label": "<?php echo $label; ?>"})'>
  Click me
</button>
```

> Note: We use single quotes in HTML where possible to keep consistency with PHP string style preferences.

## Best Practices

- **One source of truth** for analytics injection (prefer GTM, or direct GA4—not both).
- **Defer non‑critical JS** using the 'defer\_js' param when feasible.
- **Avoid duplicate libraries** if another extension already loads FA6/BS5.
- **Respect a11y**: Provide visible focus, 'visually-hidden' labels, and heading order for the TOC.
- **Cache smartly**: After enabling new features, clear Joomla cache and any CDN cache to propagate assets.

## Development

- Source structure follows Joomla template conventions:
  - '/css', '/js', '/images', '/html' (overrides), 'templateDetails.xml'
- Scripts are enqueued via the template's 'index.php' with conditional params.
- Build/compile steps (if using bundlers) are noted in 'package.json' (when applicable).

**Local overrides**

- Place site‑specific CSS in '/css/custom.css'.
- Use '/html' for component/module layout overrides as needed.

## Changelog

- 1.0.0: Initial public release with FA6, BS5, TOC, GTM/GA hooks.

## License

Distributed under the GNU General Public License v3. See 'LICENSE' for details.

