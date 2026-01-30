<!--
 Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 FILE: docs/CSS_VARIABLES.md
 VERSION: 03.06.03
 BRIEF: Complete CSS variable reference for MokoCassiopeia template
-->

# CSS Variables Reference - MokoCassiopeia

This document provides a complete reference of all CSS variables available in the MokoCassiopeia template for customization.

## Table of Contents

- [Using Custom Color Palettes](#using-custom-color-palettes)
- [Primary Brand Colors](#primary-brand-colors)
- [Link Colors](#link-colors)
- [Navigation Colors](#navigation-colors)
- [Header Background](#header-background)
- [Container Backgrounds](#container-backgrounds)
- [Bootstrap Color Palette](#bootstrap-color-palette)
- [Body & Typography](#body--typography)
- [Additional Theme Colors](#additional-theme-colors)
- [Borders & Shadows](#borders--shadows)
- [Form Colors](#form-colors)

---

## Using Custom Color Palettes

To create custom color schemes:

1. **Copy template files** from `./templates/` directory:
   - `colors_custom_light.css` → `media/templates/site/mokocassiopeia/css/colors/light/colors_custom.css`
   - `colors_custom_dark.css` → `media/templates/site/mokocassiopeia/css/colors/dark/colors_custom.css`

2. **Edit the variables** in the copied files to match your brand

3. **Enable in Joomla**:
   - Navigate to: System → Site Templates → MokoCassiopeia
   - Under "Theme" tab, set palette to "Custom"
   - Save changes

4. **Note**: Custom files are gitignored and won't be committed to the repository

---

## Primary Brand Colors

### `--color-primary`
- **Description**: Main brand color used throughout the template
- **Light Mode Default**: `#112855`
- **Dark Mode Default**: `#112855`
- **Usage**: Headers, primary buttons, brand elements

### `--accent-color-primary`
- **Description**: Primary accent color for interactive elements
- **Light Mode Default**: `#3f8ff0`
- **Dark Mode Default**: `#3f8ff0`
- **Usage**: Hover states, focus indicators, call-to-action elements

### `--accent-color-secondary`
- **Description**: Secondary accent color
- **Light Mode Default**: `#3f8ff0`
- **Dark Mode Default**: `#6fb3ff`
- **Usage**: Secondary highlights, alternative styling

---

## Link Colors

### `--color-link`
- **Description**: Default color for hyperlinks
- **Light Mode Default**: `#224FAA`
- **Dark Mode Default**: `white`
- **Usage**: All text links in content

### `--color-hover`
- **Description**: Color when hovering over links and interactive elements
- **Light Mode Default**: `var(--accent-color-primary)`
- **Dark Mode Default**: `gray`
- **Usage**: Hover states for links and buttons

### `--color-active`
- **Description**: Color for active/selected links
- **Light Mode Default**: (not set)
- **Dark Mode Default**: `var(--mainmenu-nav-link-color)`
- **Usage**: Active navigation items, selected states

### `--link-color`
- **Description**: Bootstrap-compatible link color variable
- **Light Mode Default**: `#224faa`
- **Dark Mode Default**: `#8ab4f8`
- **Usage**: Alternative link color variable for Bootstrap compatibility

### `--link-hover-color`
- **Description**: Bootstrap-compatible hover color
- **Light Mode Default**: `#424077`
- **Dark Mode Default**: `#c3d6ff`
- **Usage**: Link hover state for Bootstrap components

---

## Navigation Colors

### `--mainmenu-nav-link-color`
- **Description**: Text color for main navigation menu
- **Light Mode Default**: `white`
- **Dark Mode Default**: `#fff`
- **Usage**: Navigation menu text

### `--nav-text-color`
- **Description**: General navigation text color
- **Light Mode Default**: `white`
- **Dark Mode Default**: `gray`
- **Usage**: Navigation elements

### `--nav-bg-color`
- **Description**: Background color for navigation bars
- **Light Mode Default**: `var(--color-link)`
- **Dark Mode Default**: `var(--color-primary)`
- **Usage**: Navigation background

---

## Header Background

### `--header-background-image`
- **Description**: Background image URL for header
- **Default**: `url('../../../../../../media/templates/site/mokocassiopeia/images/bg.svg')`
- **Usage**: Header section background

### `--header-background-attachment`
- **Description**: CSS background-attachment property
- **Default**: `fixed`
- **Options**: `scroll`, `fixed`, `local`

### `--header-background-repeat`
- **Description**: CSS background-repeat property
- **Default**: `repeat`
- **Options**: `repeat`, `repeat-x`, `repeat-y`, `no-repeat`

### `--header-background-size`
- **Description**: CSS background-size property
- **Default**: `auto`
- **Options**: `auto`, `cover`, `contain`, specific sizes

---

## Container Backgrounds

Each container section has the following customizable properties:

### Container Sections
- `below-topbar` - Below top navigation bar
- `top-a` - Top section A
- `top-b` - Top section B
- `toc` - Table of Contents sidebar
- `sidebar` - Sidebar area
- `bottom-a` - Bottom section A
- `bottom-b` - Bottom section B

### Available Properties per Container
Replace `{section}` with the container name:

- `--container-{section}-bg-image` - Background image URL
- `--container-{section}-bg-color` - Background color
- `--container-{section}-bg-position` - Background position
- `--container-{section}-bg-attachment` - Attachment (scroll/fixed)
- `--container-{section}-bg-repeat` - Repeat pattern
- `--container-{section}-bg-size` - Background size
- `--container-{section}-border` - Border styling
- `--container-{section}-border-radius` - Border radius

### Special TOC Variables

#### `--container-toc-bg`
- **Description**: Background color for TOC container
- **Light Mode Default**: `var(--mainmenu-nav-link-color)`
- **Dark Mode Default**: (empty, transparent)

#### `--container-toc-color`
- **Description**: Text color for TOC
- **Light Mode Default**: `var(--color-primary)`
- **Dark Mode Default**: `#dbe3ff`

---

## Bootstrap Color Palette

### Contextual Colors

#### `--primary`
- **Light Mode**: `#010156`
- **Dark Mode**: `#010156`
- **Usage**: Primary theme color

#### `--secondary`
- **Light Mode**: `#6d757e`
- **Dark Mode**: `#48525d`
- **Usage**: Secondary elements

#### `--success`
- **Light Mode**: `#448344`
- **Dark Mode**: `#4aa664`
- **Usage**: Success messages, positive actions

#### `--info`
- **Light Mode**: `#30638d`
- **Dark Mode**: `#4f7aa0`
- **Usage**: Informational messages

#### `--warning`
- **Light Mode**: `#ad6200`
- **Dark Mode**: `#c77a00`
- **Usage**: Warning messages

#### `--danger`
- **Light Mode**: `#a51f18`
- **Dark Mode**: `#c23a31`
- **Usage**: Error messages, destructive actions

#### `--light`
- **Light Mode**: `#f9fafb`
- **Dark Mode**: `#1b2027`
- **Usage**: Light backgrounds

#### `--dark`
- **Light Mode**: `#353b41`
- **Dark Mode**: `#0f1318`
- **Usage**: Dark backgrounds

### Standard Colors

Available in both themes:
- `--blue`, `--indigo`, `--purple`, `--pink`
- `--red`, `--orange`, `--yellow`, `--green`
- `--teal`, `--cyan`
- `--black`, `--white`

### Gray Scale

Available in 9 shades: `--gray-100` through `--gray-900`

Light mode ranges from very light (`#f9fafb`) to very dark (`#22262a`).
Dark mode ranges are inverted for better contrast on dark backgrounds.

---

## Body & Typography

### `--body-font-family`
- **Description**: Default font stack for body text
- **Default**: `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif`
- **Usage**: All body text

### `--body-font-size`
- **Description**: Base font size
- **Default**: `1rem` (typically 16px)
- **Usage**: Base typography size

### `--body-font-weight`
- **Description**: Default font weight
- **Default**: `400`
- **Usage**: Body text weight

### `--body-line-height`
- **Description**: Line height for body text
- **Default**: `1.5`
- **Usage**: Text line spacing

### `--body-color`
- **Description**: Main text color
- **Light Mode Default**: `#22262a`
- **Dark Mode Default**: `#e6ebf1`
- **Usage**: Body text color

### `--body-bg`
- **Description**: Main background color
- **Light Mode Default**: `#fff`
- **Dark Mode Default**: `#0e1318`
- **Usage**: Page background

### `--heading-color`
- **Description**: Color for headings (h1-h6)
- **Light Mode Default**: `inherit`
- **Dark Mode Default**: `#f1f5f9`
- **Usage**: Heading text

---

## Additional Theme Colors

### `--muted-color`
- **Default**: `#6d757e`
- **Usage**: Muted/secondary text

### `--code-color`
- **Light Mode**: `#e93f8e`
- **Dark Mode**: `#ff7abd`
- **Usage**: Inline code elements

### `--highlight-bg`
- **Light Mode**: `#fbeea8`
- **Dark Mode**: `#ffe28a1a`
- **Usage**: Text highlighting, mark elements

### `--emphasis-color`
- **Light Mode**: `#000`
- **Dark Mode**: `#fff`
- **Usage**: Emphasized text

### `--secondary-bg`
- **Light Mode**: `#eaedf0`
- **Dark Mode**: `#151b22`
- **Usage**: Secondary backgrounds, alternate rows

### `--tertiary-bg`
- **Light Mode**: `#f9fafb`
- **Dark Mode**: `#10151b`
- **Usage**: Tertiary backgrounds, subtle contrast

---

## Borders & Shadows

### Border Variables

#### `--border`
- **Default**: `5px`
- **Usage**: Border width shorthand

#### `--border-width`
- **Default**: `1px`
- **Usage**: Standard border width

#### `--border-style`
- **Default**: `solid`
- **Usage**: Border style

#### `--border-color`
- **Light Mode**: `#dfe3e7`
- **Dark Mode**: `#2b323b`
- **Usage**: Standard border color

#### `--border-color-translucent`
- **Light Mode**: `#0000002d`
- **Dark Mode**: `#ffffff26`
- **Usage**: Semi-transparent borders

### Border Radius

- `--border-radius`: `.25rem` (default)
- `--border-radius-sm`: `.2rem` (small)
- `--border-radius-lg`: `.3rem` (large)
- `--border-radius-xl`: `.3rem` (extra large)
- `--border-radius-xxl`: `2rem` (2x extra large)
- `--border-radius-pill`: `50rem` (pill-shaped)

### Box Shadows

#### `--box-shadow`
- **Default**: `0 .5rem 1rem rgba(0,0,0,.15)`
- **Usage**: Standard drop shadow

#### `--box-shadow-sm`
- **Default**: `0 .125rem .25rem rgba(0,0,0,.075)`
- **Usage**: Small/subtle shadow

#### `--box-shadow-lg`
- **Default**: `0 1rem 3rem rgba(0,0,0,.175)`
- **Usage**: Large/prominent shadow

#### `--box-shadow-inset`
- **Default**: `inset 0 1px 2px rgba(0,0,0,.075)`
- **Usage**: Inset/inner shadow

---

## Form Colors

### Focus Ring

#### `--focus-ring-width`
- **Default**: `.25rem`
- **Usage**: Width of focus indicators

#### `--focus-ring-opacity`
- **Light Mode**: `.25`
- **Dark Mode**: `.6`
- **Usage**: Opacity of focus ring

#### `--focus-ring-color`
- **Light Mode**: `rgba(1,1,86,.25)`
- **Dark Mode**: `rgba(84,114,255,.4)`
- **Usage**: Color of focus indicators

### Validation Colors

#### Valid State
- `--form-valid-color`: Success green
  - Light: `#448344`
  - Dark: `#78d694`
- `--form-valid-border-color`: Matching border color

#### Invalid State
- `--form-invalid-color`: Error red
  - Light: `#a51f18`
  - Dark: `#ff8e86`
- `--form-invalid-border-color`: Matching border color

---

## Usage Examples

### Example 1: Custom Brand Colors

```css
:root[data-bs-theme="light"] {
  --color-primary: #1e40af;
  --accent-color-primary: #3b82f6;
  --color-link: #2563eb;
  --color-hover: #1d4ed8;
}
```

### Example 2: Custom Container Background

```css
:root[data-bs-theme="light"] {
  --container-top-a-bg-color: #f3f4f6;
  --container-top-a-bg-image: url('/images/pattern.svg');
  --container-top-a-bg-repeat: repeat;
  --container-top-a-border-radius: 8px;
}
```

### Example 3: Custom Typography

```css
:root[data-bs-theme="light"] {
  --body-font-family: 'Inter', sans-serif;
  --body-font-size: 1.125rem;
  --heading-color: #1f2937;
}
```

---

## Tips for Customization

1. **Start with templates**: Use the provided template files as a starting point
2. **Test both themes**: Ensure your colors work well in both light and dark modes
3. **Use CSS variables**: Reference other variables with `var()` for consistency
4. **Check contrast**: Ensure text remains readable against backgrounds
5. **Use fallbacks**: Provide fallback values in `var()` functions
6. **Test responsively**: Verify colors work on all screen sizes
7. **Document changes**: Keep notes on custom color choices

---

## See Also

- [README](../README.md) - Main documentation
- [Quick Start Guide](./QUICK_START.md) - Getting started
- [Development Guide](./JOOMLA_DEVELOPMENT.md) - Developer resources
- Template files in `/templates/` directory

---

**Version**: 03.06.03  
**Last Updated**: 2026-01-30  
**Maintainer**: Moko Consulting
