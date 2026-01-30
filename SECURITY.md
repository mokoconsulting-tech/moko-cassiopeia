<!--
 Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template
 INGROUP: MokoCassiopeia.Governance
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 FILE: SECURITY.md
 VERSION: 03.06.02
 BRIEF: Security policy and vulnerability reporting process for MokoCassiopeia.
 PATH: /SECURITY.md
 NOTE: This policy is process oriented and does not replace secure engineering practices.
-->

## Security Policy

This document defines how MokoCassiopeia handles vulnerability intake, triage, remediation, and disclosure. The objective is to reduce risk, protect downstream users, and preserve operational continuity with a verifiable audit trail.

## Scope

This policy applies to:

* Repository source code, workflows, scripts, and build artifacts.
* Release packaging (ZIP outputs) generated from the repository.
* Configuration and metadata used for distribution (for example manifests and update metadata).

Out of scope:

* Vulnerabilities in upstream Joomla core, third party extensions, or external infrastructure not controlled by this repository.
* Issues that require physical access to a host, compromised administrator credentials, or a compromised hosting provider, unless the repository materially increases impact.

## Supported Versions

Security fixes are prioritized for:

* The latest released version.
* The current development line when it is actively used for release engineering.

Backports may be provided based on impact, deployment footprint, and engineering capacity.

## Reporting a Vulnerability

Use one of the following channels:

* GitHub Security Advisories (preferred): use the repository security tab to submit a private report.
* Email: send details to `hello@mokoconsulting.tech` with subject `SECURITY: MokoCassiopeia vulnerability report`.

Do not file a public GitHub issue for suspected security vulnerabilities.

### What to include

Provide enough detail to reproduce and triage:

* A clear description of the vulnerability and expected impact.
* A minimal proof of concept or reproduction steps.
* Affected versions, configuration assumptions, and environment details.
* Any proposed mitigation or patch.
* Your preferred contact details for follow up.

## Triage and Response Targets

The project operates with response targets aligned to practical delivery realities:

* **Acknowledgement:** within 3 business days.
* **Initial triage:** within 10 business days.
* **Fix plan:** communicated once severity is confirmed.

These targets are not guarantees. Complex issues, supply chain considerations, and coordination with upstream vendors may extend timelines.

## Severity Assessment

Issues are triaged based on business impact and technical exploitability, including:

* Remote exploitability and required privileges.
* Data confidentiality, integrity, and availability impact.
* Likelihood of exploitation in typical Joomla deployments.
* Exposure surface (public endpoints, administrator area, installation flows, and update mechanisms).

When appropriate, industry standard scoring such as CVSS may be used for internal prioritization.

## Coordinated Disclosure

The project follows coordinated vulnerability disclosure:

* Reports are treated as confidential until remediation is available.
* A public advisory may be published once a fix is released.
* A reasonable embargo period is expected to enable patch distribution.

If you believe disclosure is time sensitive due to active exploitation, include that assessment and any supporting indicators.

## Security Updates and Advisories

Security updates are distributed through:

* GitHub releases for the repository.
* GitHub Security Advisories when applicable.

Advisories may include:

* Affected versions and fixed versions.
* Mitigations and workarounds when a fix is not immediately available.
* Upgrade guidance.

## Dependencies and Supply Chain Controls

The project aims to manage supply chain risk through:

* Pinning and review of workflow dependencies where feasible.
* Minimizing privileged GitHub token permissions.
* Validating build inputs prior to packaging releases.

If you identify a supply chain issue (for example compromised action, dependency confusion, or malicious upstream artifact), report it as a vulnerability.

## Secure Development and CI Expectations

Security posture is reinforced through operational controls:

* CI validation for packaging inputs and manifest integrity.
* Consistent path normalization and whitespace hygiene checks where required for release correctness.
* Least privilege for GitHub Actions permissions.

### Template Security Features

**Custom Head Content Injection**

The template provides Custom Head Code fields (`custom_head_start` and `custom_head_end`) that allow administrators to inject custom HTML, CSS, and JavaScript code. This is an intentional feature for:

* Adding analytics scripts (Google Analytics, Google Tag Manager)
* Custom meta tags
* Third-party integrations
* Custom styling

**Security Considerations:**

* These fields use `filter="raw"` to allow HTML/JS injection
* **Access is restricted to Joomla administrators only** via template configuration
* This is not an XSS vulnerability as it requires administrator privileges
* Administrators should only add trusted code from verified sources
* Regular security audits should review custom head content

This policy does not guarantee that all vulnerabilities will be prevented. It defines how risk is managed when issues are discovered.

## Safe Harbor

The project supports good faith security research. When you:

* Avoid privacy violations, data destruction, and service disruption.
* Limit testing to systems you own or have explicit permission to test.
* Provide a reasonable window for coordinated disclosure.

Then the project will treat your report as a constructive security contribution.

Jurisdiction note: this repository is managed from Tennessee, USA. This note is informational only and does not constitute legal advice.

## Public Communications

Only maintainers will publish security advisories or public statements for confirmed vulnerabilities. Public communication will focus on actionable remediation and operational risk reduction.

## Acknowledgements

If you want credit, include the name or handle to list in an advisory. If you prefer anonymity, state that explicitly.

---

## Metadata

* **Document:** SECURITY.md
* **Repository:** [https://github.com/mokoconsulting-tech/MokoCassiopeia](https://github.com/mokoconsulting-tech/MokoCassiopeia)
* **Path:** /SECURITY.md
* **Owner:** Moko Consulting
* **Version:** 03.06.00
* **Status:** Active
* **Effective Date:** 2025-12-18
* **Last Reviewed:** 2025-12-18

## Revision History

| Date       | Change Summary                                                                                   | Author          |
| ---------- | ------------------------------------------------------------------------------------------------ | --------------- |
| 2026-01-30 | Added Template Security Features section documenting custom head content injection controls.    | Copilot Agent   |
| 2025-12-18 | Initial publication of security policy, intake channels, triage targets, and disclosure process. | Moko Consulting |
