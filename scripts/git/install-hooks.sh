#!/usr/bin/env bash
# Install Git hooks for Moko Cassiopeia
# Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
#
# SPDX-License-Identifier: GPL-3.0-or-later
#
# FILE INFORMATION
# DEFGROUP: Moko-Cassiopeia.Scripts
# INGROUP: Scripts.Git
# REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
# FILE: ./scripts/git/install-hooks.sh
# VERSION: 01.00.00
# BRIEF: Install Git hooks for local development

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"

echo "Installing Git hooks..."
echo ""

# Create .git/hooks directory if it doesn't exist
mkdir -p "${REPO_ROOT}/.git/hooks"

# Install pre-commit hook
PRE_COMMIT_HOOK="${REPO_ROOT}/.git/hooks/pre-commit"
cat > "${PRE_COMMIT_HOOK}" <<'EOF'
#!/usr/bin/env bash
# Pre-commit hook - installed by scripts/git/install-hooks.sh

SCRIPT_DIR="$(git rev-parse --show-toplevel)/scripts/git"

if [ -f "${SCRIPT_DIR}/pre-commit.sh" ]; then
    exec "${SCRIPT_DIR}/pre-commit.sh" "$@"
else
    echo "Error: pre-commit.sh not found in ${SCRIPT_DIR}"
    exit 1
fi
EOF

chmod +x "${PRE_COMMIT_HOOK}"

echo "✓ Installed pre-commit hook"
echo ""
echo "The pre-commit hook will run automatically before each commit."
echo ""
echo "Options:"
echo "  - Skip hook: git commit --no-verify"
echo "  - Quick mode: ./scripts/git/pre-commit.sh --quick"
echo "  - Skip quality checks: ./scripts/git/pre-commit.sh --skip-quality"
echo ""
echo "To uninstall hooks:"
echo "  rm .git/hooks/pre-commit"
echo ""
echo "Done!"
