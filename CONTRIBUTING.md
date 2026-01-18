<!--
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

 You should have received a copy of the GNU General Public License (./LICENSE).

 # FILE INFORMATION
 DEFGROUP: Joomla.Template
 INGROUP: Moko-Cassiopeia.Governance
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 FILE: CONTRIBUTING.md
 VERSION: 03.05.00
 BRIEF: Contribution guidelines for the Moko-Cassiopeia project.
 PATH: /CONTRIBUTING.md
 NOTE: This document defines contribution workflow, standards, and governance alignment.
-->

## Contributing

This document defines how to contribute to the Moko-Cassiopeia project. The goal is to ensure changes are reviewable, auditable, and aligned with project governance and release processes.

## Scope

These guidelines apply to all contributions, including:

* Source code changes
* Documentation updates
* Bug reports and enhancement proposals

## Prerequisites

Contributors are expected to:

* Have a working understanding of Joomla template structure.
* Be familiar with Git and GitHub pull request workflows.
* Review repository governance documents prior to submitting changes.
* Set up the development environment using the provided tools.

### Quick Setup

For first-time contributors:

```bash
# Clone the repository
git clone https://github.com/mokoconsulting-tech/moko-cassiopeia.git
cd moko-cassiopeia

# Run development setup
make dev-setup
```

See [docs/QUICK_START.md](./docs/QUICK_START.md) for detailed setup instructions.

## Development Tools

The repository provides several tools to streamline development:

* **Makefile**: Common development tasks (`make help` to see all commands)
* **Pre-commit Hooks**: Automatic local validation before commits
* **VS Code Tasks**: Pre-configured tasks for common operations

Run `make validate-required` before submitting PRs to catch common issues early.

## Contribution Workflow

1. Fork the repository.
2. Create a branch from the active development branch.
3. Make focused, minimal changes that address a single concern.
4. Submit a pull request with a clear description of intent and impact.

Direct commits to protected branches are not permitted.

## Branching and Versioning

* Development work occurs on designated development branches.
* Releases are produced from versioned branches following repository standards.
* Contributors should not bump version numbers unless explicitly requested.

## Coding and Formatting Standards

All contributions must:

* Follow Joomla coding standards where applicable.
* Conform to Moko Consulting repository standards for headers, metadata, and file structure.
* Avoid introducing tabs, inconsistent path separators, or non portable assumptions.

Automated checks may reject changes that do not meet these requirements.

## Documentation Standards

Documentation changes must:

* Include required metadata and revision history sections.
* Avoid embedding version numbers in revision history tables.
* Preserve existing structure unless a structural change is explicitly proposed.

## Commit Messages

Commit messages should:

* Be concise and descriptive.
* Focus on what changed and why.
* Avoid referencing internal issue trackers unless required.

## Reporting Issues

Bug reports and enhancement requests should be filed as GitHub issues and include:

* Clear reproduction steps or use cases.
* Expected versus actual behavior.
* Relevant environment details.

Security related issues must follow the process defined in SECURITY.md and must not be reported publicly.

## Review Process

All pull requests are subject to review. Review criteria include:

* Technical correctness
* Alignment with project goals
* Maintainability and clarity
* Risk introduced to release and update processes

Maintainers may request changes prior to approval.

## License

By contributing, you agree that your contributions will be licensed under GPL-3.0-or-later, consistent with the rest of the project.

## Code of Conduct

Participation in this project is governed by the Code of Conduct. Unacceptable behavior may result in contribution restrictions.

---

## Metadata

* **Document:** CONTRIBUTING.md
* **Repository:** [https://github.com/mokoconsulting-tech/moko-cassiopeia](https://github.com/mokoconsulting-tech/moko-cassiopeia)
* **Path:** /CONTRIBUTING.md
* **Owner:** Moko Consulting
* **Version:** 03.05.00
* **Status:** Active
* **Effective Date:** 2025-12-18
* **Last Reviewed:** 2025-12-18

## Revision History

| Date       | Change Summary                                                            | Author          |
| ---------- | ------------------------------------------------------------------------- | --------------- |
| 2025-12-18 | Initial publication of contribution guidelines and workflow expectations. | Moko Consulting |
