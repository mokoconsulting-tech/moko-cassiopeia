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

 You should have received a copy of the GNU General Public License
 along with this program. If not, see <https://www.gnu.org/licenses/>.

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia.Documentation
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 FILE: ./GOVERNANCE.md
 VERSION: 03.05.00
 BRIEF: Governance for Moko-Cassiopeia template
-->


# Governance Document Set

This document contains the canonical governance markdown files required for enterprise-grade open source project management within the Moko ecosystem. Each section represents an individual file.

---

## FILE: GOVERNANCE.md

# Governance

This document defines the governance framework for this repository. It establishes authority, decision-making processes, escalation paths, and accountability mechanisms.

### Governance Model

This repository operates under a maintainer-led governance model.

Final authority resides with the designated Maintainers, who are responsible for technical direction, compliance, and release approval.

### Roles and Responsibilities

**Maintainers**
- Approve releases and version tags
- Enforce coding, documentation, and licensing standards
- Resolve disputes and merge conflicts
- Ensure audit and compliance readiness

**Contributors**
- Submit changes via pull requests
- Adhere to all defined standards and workflows
- Respond to review feedback in a timely manner

### Decision Making

Decisions are made through documented pull requests and issues.
All material decisions must be traceable via Git history.

### Amendments

Changes to governance require Maintainer approval and must be recorded in the CHANGELOG.

---

## FILE: CODE_OF_CONDUCT.md

# Code of Conduct

This project adheres to a professional, inclusive, and respectful code of conduct.

### Expected Behavior

- Professional and respectful communication
- Constructive feedback
- Focus on technical merit and documented standards

### Unacceptable Behavior

- Harassment or discrimination
- Hostile or abusive language
- Disruptive behavior in issues or pull requests

### Enforcement

Maintainers are responsible for enforcement.
Violations may result in warnings, suspension, or removal.

---

## FILE: CONTRIBUTING.md

# Contributing

This document defines the contribution workflow and compliance requirements.

### Contribution Requirements

- All changes must be submitted via pull request
- All CI checks must pass
- SPDX headers and FILE INFORMATION blocks are mandatory where applicable
- Documentation changes must include Metadata and Revision History sections

### Commit Standards

Commits must be atomic, descriptive, and traceable to an issue or change request.

### Review Process

- Maintainer review is required
- CI validation is mandatory
- Approval is required before merge

---

## FILE: SECURITY.md

# Security Policy

This document defines the security posture and reporting process.

### Supported Versions

Only the latest released version and active development branches are supported.

### Reporting Vulnerabilities

Security issues must be reported privately to the Maintainers.
Public disclosure prior to resolution is prohibited.

### Response Process

- Acknowledge receipt within a reasonable timeframe
- Assess severity and impact
- Issue patches or mitigations as required

---

## FILE: COMPLIANCE.md

# Compliance

This repository is designed to support audit and compliance requirements.

### Licensing

All code must comply with GPL-3.0-or-later licensing requirements.
SPDX identifiers are mandatory.

### Documentation Compliance

- Mandatory Metadata sections
- Mandatory Revision History sections
- Version traceability across manifests, changelogs, and releases

### CI Enforcement

Automated workflows enforce:
- Path consistency
- Formatting rules
- Manifest validation
- Changelog governance

---

## FILE: RISK_REGISTER.md

# Risk Register

This document tracks identified risks and mitigation strategies.

### Risk Categories

- Technical debt
- Security vulnerabilities
- Compliance drift
- Dependency instability

### Management

Risks are reviewed during release cycles.
Mitigations must be documented and traceable.

---

## FILE: CHANGE_MANAGEMENT.md

# Change Management

This document defines how changes are introduced, reviewed, and released.

### Change Types

- Patch
- Minor
- Major

### Process

- Documented pull request
- CI validation
- Version bump and changelog update
- Maintainer approval

### Traceability

All changes must be traceable through Git history and release artifacts.

---

## FILE: GOVERNANCE_INDEX.md

# Governance Index

This file serves as the authoritative index of governance artifacts.

### Governance Documents

- GOVERNANCE.md
- CODE_OF_CONDUCT.md
- CONTRIBUTING.md
- SECURITY.md
- COMPLIANCE.md
- RISK_REGISTER.md
- CHANGE_MANAGEMENT.md

---

## Metadata

- DEFGROUP: MokoStandards
- INGROUP: Governance
- REPO: https://github.com/mokoconsulting-tech
- JURISDICTION: Tennessee, United States
- LICENSE: GPL-3.0-or-later

---

## Revision History

| Version | Date       | Description                     |
|--------:|------------|---------------------------------|
| 01.00.00 | 2025-12-18 | Initial governance document set |
