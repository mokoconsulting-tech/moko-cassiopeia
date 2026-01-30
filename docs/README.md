<!--
 Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 FILE: docs/README.md
 VERSION: 03.06.02
 BRIEF: Documentation index for MokoCassiopeia template
 PATH: /docs/README.md
-->

# MokoCassiopeia Documentation

This directory contains comprehensive documentation for the MokoCassiopeia Joomla template.

## Documentation Overview

### Developer Documentation

* **[Quick Start Guide](QUICK_START.md)** - Get up and running in 5 minutes
  * Development environment setup
  * Essential commands and workflows
  * First-time contributor guide

* **[Workflow Guide](WORKFLOW_GUIDE.md)** - Complete workflow reference
  * Git branching strategy
  * Development workflow
  * Release process
  * Pull request guidelines

* **[Joomla Development Guide](JOOMLA_DEVELOPMENT.md)** - Joomla-specific development
  * Testing with Codeception
  * PHP quality checks (PHPStan, PHPCS)
  * Joomla extension packaging
  * Multi-version testing

* **[Roadmap](ROADMAP.md)** - Version-specific roadmap
  * Current features (v03.06.00)
  * Feature evolution timeline
  * Planned enhancements
  * Development priorities

### User Documentation

For end-user documentation, installation instructions, and feature guides, see the main [README.md](../README.md) in the repository root.

## Project Structure

```
moko-cassiopeia/
├── docs/                    # Documentation (you are here)
│   ├── README.md           # This file - documentation index
│   ├── QUICK_START.md      # Quick start guide for developers
│   ├── WORKFLOW_GUIDE.md   # Development workflow guide
│   ├── JOOMLA_DEVELOPMENT.md # Joomla-specific development guide
│   └── ROADMAP.md          # Version-specific roadmap
├── src/                     # Template source code
│   ├── templates/          # Joomla template files
│   └── media/              # Assets (CSS, JS, images)
├── tests/                   # Automated tests
└── .github/                # GitHub configuration and workflows
```

## Contributing

Before contributing, please read:

1. **[CONTRIBUTING.md](../CONTRIBUTING.md)** - Contribution guidelines and standards
2. **[CODE_OF_CONDUCT.md](../CODE_OF_CONDUCT.md)** - Community standards and expectations
3. **[SECURITY.md](../SECURITY.md)** - Security policy and reporting procedures

## Standards Compliance

This project adheres to [MokoStandards](https://github.com/mokoconsulting-tech/MokoStandards) for:

* Coding standards and formatting
* Documentation requirements
* Git workflow and branching
* CI/CD pipeline configuration
* Security scanning and dependency management

## Additional Resources

* **Repository**: [https://github.com/mokoconsulting-tech/MokoCassiopeia](https://github.com/mokoconsulting-tech/MokoCassiopeia)
* **Issue Tracker**: [GitHub Issues](https://github.com/mokoconsulting-tech/MokoCassiopeia/issues)
* **Changelog**: [CHANGELOG.md](../CHANGELOG.md)
* **License**: [GPL-3.0-or-later](../LICENSE)

## Support

* **Email**: hello@mokoconsulting.tech
* **Website**: https://mokoconsulting.tech/support/joomla-cms/moko-cassiopeia-roadmap

---

## Metadata

* Document: docs/README.md
* Repository: [https://github.com/mokoconsulting-tech/MokoCassiopeia](https://github.com/mokoconsulting-tech/MokoCassiopeia)
* Path: /docs/README.md
* Owner: Moko Consulting
* Version: 03.06.02
* Status: Active
* Effective Date: 2026-01-09

## Revision History

| Date       | Change Summary                                        | Author          |
| ---------- | ----------------------------------------------------- | --------------- |
| 2026-01-09 | Initial documentation index created for MokoStandards compliance. | GitHub Copilot |
| 2026-01-27 | Updated with roadmap link and version to 03.05.01.   | GitHub Copilot  |
