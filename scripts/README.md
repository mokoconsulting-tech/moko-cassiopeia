# Scripts Documentation

All automation scripts for the moko-cassiopeia project are written in Python for cross-platform compatibility and support both Joomla and Dolibarr extensions.

## Quick Reference

```bash
# Run all validations
make validate
python3 scripts/run/validate_all.py

# Run specific validations
python3 scripts/validate/manifest.py
python3 scripts/validate/xml_wellformed.py

# Create distribution package (auto-detects Joomla or Dolibarr)
make package
python3 scripts/release/package_extension.py dist
```

## Platform Support

All scripts automatically detect and support:
- **Joomla Extensions**: Templates, Components, Modules, Plugins, Packages
- **Dolibarr Modules**: Automatic detection and packaging

## Available Scripts

### Validation Scripts (`scripts/validate/`)
- `manifest.py` - Validate extension manifests (Joomla/Dolibarr)
- `xml_wellformed.py` - Validate XML syntax
- `workflows.py` - Validate GitHub Actions workflows
- `tabs.py` - Check for tab characters in YAML
- `no_secrets.py` - Scan for secrets/credentials
- `paths.py` - Check for Windows-style paths
- `php_syntax.py` - Validate PHP syntax

### Release Scripts (`scripts/release/`)
- `package_extension.py` - Create distributable ZIP packages

### Run Scripts (`scripts/run/`)
- `validate_all.py` - Run all validation scripts
- `scaffold_extension.py` - Create new extension scaffolding

### Library Scripts (`scripts/lib/`)
- `common.py` - Common utilities
- `joomla_manifest.py` - Joomla manifest parsing
- `dolibarr_manifest.py` - Dolibarr module parsing
- `extension_utils.py` - Unified extension detection

## Requirements

- Python 3.6+
- Git
- PHP (for PHP syntax validation)

## Migration from Shell Scripts

All shell scripts have been converted to Python. Use Python equivalents:

```bash
# Old (removed)                  # New
./scripts/validate/manifest.sh  → python3 scripts/validate/manifest.py
./scripts/release/package.sh    → python3 scripts/release/package_extension.py
```

For detailed documentation, see individual script help:
```bash
python3 scripts/validate/manifest.py --help
python3 scripts/release/package_extension.py --help
```

## License

GPL-3.0-or-later - See [LICENSE](../LICENSE)
