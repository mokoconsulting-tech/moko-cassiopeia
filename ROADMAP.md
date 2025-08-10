# Moko-Cassiopeia Template Roadmap

Copyright 2025 Moko Consulting.
This roadmap is part of the **Moko-Cassiopeia** Joomla template project.
All rights reserved. Redistribution permitted under the project’s license.

---

## Version 2: Core Features
- **Dark Mode Toggle**
  - [ ] Implement three-way front-end toggle: **Light / Dark / Follow Device (System)** for dynamic theme switching
  - [ ] Persist selection in local storage; respect admin default on first load for consistent user experience
  - [ ] When set to Follow Device, detect `prefers-color-scheme` and auto-switch on OS/theme changes without refresh
  - [ ] Accessible labels/ARIA: `Light mode`, `Dark mode`, `Follow device` for screen reader compatibility
  - [ ] Keyboard navigation (Tab/Arrow keys) and focus states for usability compliance
  - [ ] Apply **Override Policy**: **Force Site** (site-wide enforced), **Force User** (always use user preference), **Follow Device**, **Allow User Choice** (user preference unless overridden)
  - [ ] Fallback logic for browsers without `prefers-color-scheme` to ensure graceful degradation

- **Admin Panel Enhancements**
  - [ ] New settings for dark mode, including Follow Device and Override Policy, with tooltips for guidance
  - [ ] Preview changes before saving to allow safe experimentation
  - [ ] Preview link or module layout preview in admin panel for real-time visualization
  - [ ] Enable/disable add-ons individually for modular control
  - [ ] Basic optimization settings (asset minification, gzip) controlled by Development Mode toggle
  - [ ] Performance optimization tools (lazy loading, CSS/JS minification, cache-busting) controlled by Development Mode toggle
  - [ ] Ensure all CDN assets (Bootstrap, FontAwesome, libraries) are served locally for security and offline availability

- **Documentation & Support**
  - [ ] Update installation & configuration guide with new UI screenshots
  - [ ] Document three-way toggle behavior, override policy, and browser support
  - [ ] Add troubleshooting notes for common configuration conflicts

## Version 3: User Experience Enhancements
- **Admin Panel Enhancements**
  - [ ] Enable/disable add-ons individually with descriptive labels and dependency checks
  - [ ] Improved layout and categorization of settings for faster navigation
  - [ ] Intermediate optimization settings (image compression level, preloading resources)
  - [ ] Ensure all related assets are served locally instead of via CDN

- **Accessibility Features**
  - [ ] Adjustable font sizes with preview
  - [ ] High-contrast mode toggle
  - [ ] ARIA label improvements and WCAG compliance checks

- **Documentation & Support**
  - [ ] Update guides to include new admin panel features and accessibility tips
  - [ ] Provide accessibility best practices section
  - [ ] Add changelog entries with detailed feature notes

## Version 4: Advanced Functionality
- **Performance Optimization Tools**
  - [ ] Additional advanced optimization options (service worker caching, CDN asset routing with local fallback)
  - [ ] Admin toggle for enabling/disabling optimizations globally or per-page

- **Admin Panel Enhancements**
  - [ ] Enable/disable add-ons individually with version control for each
  - [ ] Performance optimization configuration sections with live metrics (load time, requests count)
  - [ ] Real-time status indicators for active optimizations with alerts for misconfigurations
  - [ ] Ensure all optimization scripts and resources are served locally for maximum security

- **Documentation & Support**
  - [ ] Update performance optimization instructions with before/after benchmarks
  - [ ] Add troubleshooting for optimization features, including compatibility notes with 3rd-party extensions
