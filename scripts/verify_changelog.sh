#!/bin/bash
set -e

echo "Running changelog verifier"

BRANCH="${GITHUB_REF_NAME}"

if [[ ! "$BRANCH" =~ ^version/ ]]; then
	echo "Not on a version branch. Skipping changelog verification."
	exit 0
fi

VERSION="${BRANCH#version/}"

if [ ! -f "CHANGELOG.md" ]; then
	echo "ERROR: CHANGELOG.md does not exist."
	exit 1
fi

if ! grep -q "$VERSION" CHANGELOG.md; then
	echo "ERROR: CHANGELOG.md missing entry for version $VERSION"
	exit 1
fi

echo "Changelog contains correct version section."
