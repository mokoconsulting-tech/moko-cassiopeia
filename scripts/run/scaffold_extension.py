#!/usr/bin/env python3
"""
Create Joomla extension scaffolding.

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
along with this program (./LICENSE.md).

FILE INFORMATION
DEFGROUP: Moko-Cassiopeia.Scripts
INGROUP: Scripts.Run
REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
PATH: /scripts/run/scaffold_extension.py
VERSION: 01.00.00
BRIEF: Create scaffolding for different Joomla extension types
"""

import argparse
import sys
from datetime import datetime
from pathlib import Path
from typing import Dict

# Add lib directory to path
sys.path.insert(0, str(Path(__file__).parent.parent / "lib"))

try:
    import common
    import joomla_manifest
except ImportError:
    print("ERROR: Cannot import required libraries", file=sys.stderr)
    sys.exit(1)


# ============================================================================
# Templates for Extension Scaffolding
# ============================================================================

def get_component_structure(name: str, description: str, author: str) -> Dict[str, str]:
    """Get directory structure and files for a component."""
    safe_name = name.lower().replace(" ", "_")
    com_name = f"com_{safe_name}"
    
    manifest = f"""<?xml version="1.0" encoding="utf-8"?>
<extension type="component" version="4.0" method="upgrade">
    <name>{name}</name>
    <author>{author}</author>
    <creationDate>{datetime.now().strftime("%Y-%m-%d")}</creationDate>
    <copyright>Copyright (C) {datetime.now().year} {author}</copyright>
    <license>GPL-3.0-or-later</license>
    <authorEmail>hello@example.com</authorEmail>
    <authorUrl>https://example.com</authorUrl>
    <version>1.0.0</version>
    <description>{description}</description>

    <files folder="site">
        <folder>src</folder>
    </files>

    <administration>
        <menu>{name}</menu>
        <files folder="admin">
            <folder>services</folder>
            <folder>sql</folder>
            <folder>src</folder>
        </files>
    </administration>
</extension>
"""
    
    return {
        f"{com_name}.xml": manifest,
        "site/src/.gitkeep": "",
        "admin/services/provider.php": f"<?php\n// Service provider for {name}\n",
        "admin/sql/install.mysql.utf8.sql": "-- Installation SQL\n",
        "admin/sql/uninstall.mysql.utf8.sql": "-- Uninstallation SQL\n",
        "admin/src/.gitkeep": "",
    }


def get_module_structure(name: str, description: str, author: str, client: str = "site") -> Dict[str, str]:
    """Get directory structure and files for a module."""
    safe_name = name.lower().replace(" ", "_")
    mod_name = f"mod_{safe_name}"
    
    manifest = f"""<?xml version="1.0" encoding="utf-8"?>
<extension type="module" version="4.0" client="{client}" method="upgrade">
    <name>{name}</name>
    <author>{author}</author>
    <creationDate>{datetime.now().strftime("%Y-%m-%d")}</creationDate>
    <copyright>Copyright (C) {datetime.now().year} {author}</copyright>
    <license>GPL-3.0-or-later</license>
    <authorEmail>hello@example.com</authorEmail>
    <authorUrl>https://example.com</authorUrl>
    <version>1.0.0</version>
    <description>{description}</description>

    <files>
        <filename module="{mod_name}">{mod_name}.php</filename>
        <filename>{mod_name}.xml</filename>
        <folder>tmpl</folder>
    </files>
</extension>
"""
    
    module_php = f"""<?php
/**
 * @package     {name}
 * @copyright   Copyright (C) {datetime.now().year} {author}
 * @license     GPL-3.0-or-later
 */

defined('_JEXEC') or die;

// Module logic here
require JModuleHelper::getLayoutPath('mod_{safe_name}', $params->get('layout', 'default'));
"""
    
    default_tmpl = f"""<?php
/**
 * @package     {name}
 * @copyright   Copyright (C) {datetime.now().year} {author}
 * @license     GPL-3.0-or-later
 */

defined('_JEXEC') or die;
?>
<div class="{mod_name}">
    <p><?php echo JText::_('MOD_{safe_name.upper()}_DESCRIPTION'); ?></p>
</div>
"""
    
    return {
        f"{mod_name}.xml": manifest,
        f"{mod_name}.php": module_php,
        "tmpl/default.php": default_tmpl,
    }


def get_plugin_structure(name: str, description: str, author: str, group: str = "system") -> Dict[str, str]:
    """Get directory structure and files for a plugin."""
    safe_name = name.lower().replace(" ", "_")
    plg_name = f"{safe_name}"
    
    manifest = f"""<?xml version="1.0" encoding="utf-8"?>
<extension type="plugin" version="4.0" group="{group}" method="upgrade">
    <name>plg_{group}_{safe_name}</name>
    <author>{author}</author>
    <creationDate>{datetime.now().strftime("%Y-%m-%d")}</creationDate>
    <copyright>Copyright (C) {datetime.now().year} {author}</copyright>
    <license>GPL-3.0-or-later</license>
    <authorEmail>hello@example.com</authorEmail>
    <authorUrl>https://example.com</authorUrl>
    <version>1.0.0</version>
    <description>{description}</description>

    <files>
        <filename plugin="{plg_name}">{plg_name}.php</filename>
    </files>
</extension>
"""
    
    plugin_php = f"""<?php
/**
 * @package     {name}
 * @copyright   Copyright (C) {datetime.now().year} {author}
 * @license     GPL-3.0-or-later
 */

defined('_JEXEC') or die;

use Joomla\\CMS\\Plugin\\CMSPlugin;

class Plg{group.capitalize()}{plg_name.capitalize()} extends CMSPlugin
{{
    protected $autoloadLanguage = true;

    public function onContentPrepare($context, &$article, &$params, $limitstart = 0)
    {{
        // Plugin logic here
    }}
}}
"""
    
    return {
        f"plg_{group}_{safe_name}.xml": manifest,
        f"{plg_name}.php": plugin_php,
    }


def get_template_structure(name: str, description: str, author: str) -> Dict[str, str]:
    """Get directory structure and files for a template."""
    safe_name = name.lower().replace(" ", "_")
    
    manifest = f"""<?xml version="1.0" encoding="utf-8"?>
<extension type="template" version="4.0" client="site" method="upgrade">
    <name>{safe_name}</name>
    <creationDate>{datetime.now().strftime("%Y-%m-%d")}</creationDate>
    <author>{author}</author>
    <authorEmail>hello@example.com</authorEmail>
    <authorUrl>https://example.com</authorUrl>
    <copyright>Copyright (C) {datetime.now().year} {author}</copyright>
    <license>GPL-3.0-or-later</license>
    <version>1.0.0</version>
    <description>{description}</description>

    <files>
        <filename>index.php</filename>
        <filename>templateDetails.xml</filename>
        <folder>css</folder>
        <folder>js</folder>
        <folder>images</folder>
    </files>

    <positions>
        <position>header</position>
        <position>main</position>
        <position>footer</position>
    </positions>
</extension>
"""
    
    index_php = f"""<?php
/**
 * @package     {name}
 * @copyright   Copyright (C) {datetime.now().year} {author}
 * @license     GPL-3.0-or-later
 */

defined('_JEXEC') or die;

use Joomla\\CMS\\Factory;
use Joomla\\CMS\\HTML\\HTMLHelper;
use Joomla\\CMS\\Uri\\Uri;

$app = Factory::getApplication();
$wa  = $app->getDocument()->getWebAssetManager();

// Load template assets
$wa->useStyle('template.{safe_name}')->useScript('template.{safe_name}');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body>
    <header>
        <jdoc:include type="modules" name="header" style="html5" />
    </header>
    <main>
        <jdoc:include type="component" />
    </main>
    <footer>
        <jdoc:include type="modules" name="footer" style="html5" />
    </footer>
</body>
</html>
"""
    
    return {
        "templateDetails.xml": manifest,
        "index.php": index_php,
        "css/template.css": "/* Template styles */\n",
        "js/template.js": "// Template JavaScript\n",
        "images/.gitkeep": "",
    }


def get_package_structure(name: str, description: str, author: str) -> Dict[str, str]:
    """Get directory structure and files for a package."""
    safe_name = name.lower().replace(" ", "_")
    pkg_name = f"pkg_{safe_name}"
    
    manifest = f"""<?xml version="1.0" encoding="utf-8"?>
<extension type="package" version="4.0" method="upgrade">
    <name>{name}</name>
    <packagename>{safe_name}</packagename>
    <author>{author}</author>
    <creationDate>{datetime.now().strftime("%Y-%m-%d")}</creationDate>
    <copyright>Copyright (C) {datetime.now().year} {author}</copyright>
    <license>GPL-3.0-or-later</license>
    <authorEmail>hello@example.com</authorEmail>
    <authorUrl>https://example.com</authorUrl>
    <version>1.0.0</version>
    <description>{description}</description>

    <files folder="packages">
        <!-- Add extension packages here -->
    </files>
</extension>
"""
    
    return {
        f"{pkg_name}.xml": manifest,
        "packages/.gitkeep": "",
    }


# ============================================================================
# Scaffolding Functions
# ============================================================================

def create_extension(
    ext_type: str,
    name: str,
    description: str,
    author: str,
    output_dir: str = "src",
    **kwargs
) -> None:
    """
    Create extension scaffolding.
    
    Args:
        ext_type: Extension type (component, module, plugin, template, package)
        name: Extension name
        description: Extension description
        author: Author name
        output_dir: Output directory
        **kwargs: Additional type-specific options
    """
    output_path = Path(output_dir)
    
    # Get structure based on type
    if ext_type == "component":
        structure = get_component_structure(name, description, author)
    elif ext_type == "module":
        client = kwargs.get("client", "site")
        structure = get_module_structure(name, description, author, client)
    elif ext_type == "plugin":
        group = kwargs.get("group", "system")
        structure = get_plugin_structure(name, description, author, group)
    elif ext_type == "template":
        structure = get_template_structure(name, description, author)
    elif ext_type == "package":
        structure = get_package_structure(name, description, author)
    else:
        common.die(f"Unknown extension type: {ext_type}")
    
    # Create files
    common.log_section(f"Creating {ext_type}: {name}")
    
    for file_path, content in structure.items():
        full_path = output_path / file_path
        
        # Create parent directories
        full_path.parent.mkdir(parents=True, exist_ok=True)
        
        # Write file
        full_path.write_text(content, encoding="utf-8")
        common.log_success(f"Created: {file_path}")
    
    common.log_section("Scaffolding Complete")
    common.log_info(f"Extension files created in: {output_path}")
    common.log_info(f"Extension type: {ext_type}")
    common.log_info(f"Extension name: {name}")


# ============================================================================
# Command Line Interface
# ============================================================================

def main() -> None:
    """Main entry point."""
    parser = argparse.ArgumentParser(
        description="Create Joomla extension scaffolding",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Examples:
  # Create a component
  %(prog)s component MyComponent "My Component Description" "John Doe"
  
  # Create a module
  %(prog)s module MyModule "My Module Description" "John Doe" --client site
  
  # Create a plugin
  %(prog)s plugin MyPlugin "My Plugin Description" "John Doe" --group system
  
  # Create a template
  %(prog)s template mytheme "My Theme Description" "John Doe"
  
  # Create a package
  %(prog)s package mypackage "My Package Description" "John Doe"
"""
    )
    
    parser.add_argument(
        "type",
        choices=["component", "module", "plugin", "template", "package"],
        help="Extension type to create"
    )
    parser.add_argument("name", help="Extension name")
    parser.add_argument("description", help="Extension description")
    parser.add_argument("author", help="Author name")
    parser.add_argument(
        "-o", "--output",
        default="src",
        help="Output directory (default: src)"
    )
    parser.add_argument(
        "--client",
        choices=["site", "administrator"],
        default="site",
        help="Module client (site or administrator)"
    )
    parser.add_argument(
        "--group",
        default="system",
        help="Plugin group (system, content, user, etc.)"
    )
    
    args = parser.parse_args()
    
    try:
        create_extension(
            ext_type=args.type,
            name=args.name,
            description=args.description,
            author=args.author,
            output_dir=args.output,
            client=args.client,
            group=args.group
        )
    except Exception as e:
        common.log_error(f"Failed to create extension: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()
