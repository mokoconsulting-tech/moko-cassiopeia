<?php
/**
 * verify_changelog.php
 *
 * Verifies that CHANGELOG.md is compliant with basic release governance rules.
 *
 * Enforced rules
 * - CHANGELOG.md must exist at repository root.
 * - File must not contain unresolved placeholders such as "UNRELEASED" or "TBD".
 * - Headings must follow semantic version format: ## [NN.NN.NN]
 * - The highest version must appear first.
 *
 * Intended usage
 * - CI validation step (read-only).
 * - Does not modify files.
 *
 * Exit codes
 * - 0: Changelog is valid
 * - 1: Validation failure
 */

$changelog = 'CHANGELOG.md';

if (!file_exists($changelog)) {
    fwrite(STDERR, "ERROR: CHANGELOG.md not found at repository root\n");
    exit(1);
}

$content = file_get_contents($changelog);
if ($content === false) {
    fwrite(STDERR, "ERROR: Unable to read CHANGELOG.md\n");
    exit(1);
}

$errors = [];

// Rule: no unresolved placeholders
$placeholders = ['UNRELEASED', 'TBD', 'TO DO', 'TODO'];
foreach ($placeholders as $token) {
    if (stripos($content, $token) !== false) {
        $errors[] = "Unresolved placeholder detected: {$token}";
    }
}

// Rule: extract version headings
preg_match_all('/^## \[([0-9]+\.[0-9]+\.[0-9]+)\]/m', $content, $matches);
$versions = $matches[1] ?? [];

if (empty($versions)) {
    $errors[] = 'No version headings found (expected format: ## [NN.NN.NN])';
} else {
    // Rule: highest version first
    $sorted = $versions;
    usort($sorted, 'version_compare');
    $sorted = array_reverse($sorted);

    if ($versions !== $sorted) {
        $errors[] = 'Versions are not ordered from newest to oldest';
    }
}

if (!empty($errors)) {
    fwrite(STDERR, "CHANGELOG.md validation failed:\n");
    foreach ($errors as $err) {
        fwrite(STDERR, " - {$err}\n");
    }
    exit(1);
}

echo "CHANGELOG.md validation passed\n";
exit(0);
