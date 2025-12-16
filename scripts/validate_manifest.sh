#!/bin/bash
set -e

MANIFEST="src/mokowaasbrand.xml"

echo "Validating Joomla manifest: $MANIFEST"

if [ ! -f "$MANIFEST" ]; then
    echo "ERROR: Manifest not found: $MANIFEST"
    exit 1
fi

# Check XML syntax
if ! xmllint --noout "$MANIFEST"; then
    echo "ERROR: Manifest XML is not valid."
    exit 1
fi

# Required fields
REQUIRED_NODES=(
    "//extension"
    "//name"
    "//version"
    "//author"
    "//creationDate"
)

for NODE in "${REQUIRED_NODES[@]}"; do
    if ! xmllint --xpath "$NODE" "$MANIFEST" > /dev/null 2>&1; then
        echo "ERROR: Required manifest node missing: $NODE"
        exit 1
    fi
done

VERSION=$(xmllint --xpath "string(//version)" "$MANIFEST")

if [ -z "$VERSION" ]; then
    echo "ERROR: Version node is empty in manifest."
    exit 1
fi

echo "Manifest OK. Version: $VERSION"
