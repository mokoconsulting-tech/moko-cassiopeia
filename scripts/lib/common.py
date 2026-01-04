#!/usr/bin/env python3
"""
Common utilities for Moko-Cassiopeia scripts.

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
DEFGROUP: Script.Library
INGROUP: Common
REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
PATH: /scripts/lib/common.py
VERSION: 01.00.00
BRIEF: Unified shared Python utilities for all CI and local scripts
"""

import json
import os
import shutil
import sys
from pathlib import Path
from typing import Any, Dict, List, Optional, Union
import subprocess
import traceback


# ============================================================================
# Environment and Detection
# ============================================================================

def is_ci() -> bool:
    """Check if running in CI environment."""
    return os.environ.get("CI", "").lower() == "true"


def require_cmd(command: str) -> None:
    """
    Ensure a required command is available.
    
    Args:
        command: Command name to check
        
    Raises:
        SystemExit: If command is not found
    """
    if not shutil.which(command):
        log_error(f"Required command not found: {command}")
        sys.exit(1)


# ============================================================================
# Logging
# ============================================================================

class Colors:
    """ANSI color codes for terminal output."""
    RED = '\033[0;31m'
    GREEN = '\033[0;32m'
    YELLOW = '\033[1;33m'
    BLUE = '\033[0;34m'
    CYAN = '\033[0;36m'
    BOLD = '\033[1m'
    NC = '\033[0m'  # No Color
    
    @classmethod
    def enabled(cls) -> bool:
        """Check if colors should be enabled."""
        return sys.stdout.isatty() and os.environ.get("NO_COLOR") is None


def log_info(message: str) -> None:
    """Log informational message."""
    print(f"INFO: {message}")


def log_warn(message: str) -> None:
    """Log warning message."""
    color = Colors.YELLOW if Colors.enabled() else ""
    nc = Colors.NC if Colors.enabled() else ""
    print(f"{color}WARN: {message}{nc}", file=sys.stderr)


def log_error(message: str) -> None:
    """Log error message."""
    color = Colors.RED if Colors.enabled() else ""
    nc = Colors.NC if Colors.enabled() else ""
    print(f"{color}ERROR: {message}{nc}", file=sys.stderr)


def log_success(message: str) -> None:
    """Log success message."""
    color = Colors.GREEN if Colors.enabled() else ""
    nc = Colors.NC if Colors.enabled() else ""
    print(f"{color}✓ {message}{nc}")


def log_step(message: str) -> None:
    """Log a step in a process."""
    color = Colors.CYAN if Colors.enabled() else ""
    nc = Colors.NC if Colors.enabled() else ""
    print(f"{color}➜ {message}{nc}")


def log_section(title: str) -> None:
    """Log a section header."""
    print()
    print("=" * 60)
    print(title)
    print("=" * 60)


def log_kv(key: str, value: str) -> None:
    """Log a key-value pair."""
    print(f"  {key}: {value}")


def die(message: str, exit_code: int = 1) -> None:
    """
    Log error and exit.
    
    Args:
        message: Error message
        exit_code: Exit code (default: 1)
    """
    log_error(message)
    
    if os.environ.get("VERBOSE_ERRORS", "true").lower() == "true":
        print("", file=sys.stderr)
        print("Stack trace:", file=sys.stderr)
        traceback.print_stack(file=sys.stderr)
        print("", file=sys.stderr)
        print("Environment:", file=sys.stderr)
        print(f"  PWD: {os.getcwd()}", file=sys.stderr)
        print(f"  USER: {os.environ.get('USER', 'unknown')}", file=sys.stderr)
        print(f"  PYTHON: {sys.version}", file=sys.stderr)
        print(f"  CI: {is_ci()}", file=sys.stderr)
        print("", file=sys.stderr)
    
    sys.exit(exit_code)


# ============================================================================
# Validation Helpers
# ============================================================================

def assert_file_exists(path: Union[str, Path]) -> None:
    """
    Assert that a file exists.
    
    Args:
        path: Path to file
        
    Raises:
        SystemExit: If file doesn't exist
    """
    if not Path(path).is_file():
        die(f"Required file missing: {path}")


def assert_dir_exists(path: Union[str, Path]) -> None:
    """
    Assert that a directory exists.
    
    Args:
        path: Path to directory
        
    Raises:
        SystemExit: If directory doesn't exist
    """
    if not Path(path).is_dir():
        die(f"Required directory missing: {path}")


def assert_not_empty(value: Any, name: str) -> None:
    """
    Assert that a value is not empty.
    
    Args:
        value: Value to check
        name: Name of the value for error message
        
    Raises:
        SystemExit: If value is empty
    """
    if not value:
        die(f"Required value is empty: {name}")


# ============================================================================
# JSON Utilities
# ============================================================================

def json_escape(text: str) -> str:
    """
    Escape text for JSON.
    
    Args:
        text: Text to escape
        
    Returns:
        Escaped text
    """
    return json.dumps(text)[1:-1]  # Remove surrounding quotes


def json_output(data: Dict[str, Any], pretty: bool = False) -> None:
    """
    Output data as JSON.
    
    Args:
        data: Dictionary to output
        pretty: Whether to pretty-print
    """
    if pretty:
        print(json.dumps(data, indent=2, sort_keys=True))
    else:
        print(json.dumps(data, separators=(',', ':')))


# ============================================================================
# Path Utilities
# ============================================================================

def script_root() -> Path:
    """
    Get the root scripts directory.
    
    Returns:
        Path to scripts directory
    """
    return Path(__file__).parent.parent


def repo_root() -> Path:
    """
    Get the repository root directory.
    
    Returns:
        Path to repository root
    """
    return script_root().parent


def normalize_path(path: Union[str, Path]) -> str:
    """
    Normalize a path (resolve, absolute, forward slashes).
    
    Args:
        path: Path to normalize
        
    Returns:
        Normalized path string
    """
    return str(Path(path).resolve()).replace("\\", "/")


# ============================================================================
# File Operations
# ============================================================================

def read_file(path: Union[str, Path], encoding: str = "utf-8") -> str:
    """
    Read a file.
    
    Args:
        path: Path to file
        encoding: File encoding
        
    Returns:
        File contents
    """
    assert_file_exists(path)
    return Path(path).read_text(encoding=encoding)


def write_file(path: Union[str, Path], content: str, encoding: str = "utf-8") -> None:
    """
    Write a file.
    
    Args:
        path: Path to file
        content: Content to write
        encoding: File encoding
    """
    Path(path).write_text(content, encoding=encoding)


def ensure_dir(path: Union[str, Path]) -> None:
    """
    Ensure a directory exists.
    
    Args:
        path: Path to directory
    """
    Path(path).mkdir(parents=True, exist_ok=True)


# ============================================================================
# Command Execution
# ============================================================================

def run_command(
    cmd: List[str],
    capture_output: bool = True,
    check: bool = True,
    cwd: Optional[Union[str, Path]] = None,
    env: Optional[Dict[str, str]] = None
) -> subprocess.CompletedProcess:
    """
    Run a command.
    
    Args:
        cmd: Command and arguments
        capture_output: Whether to capture stdout/stderr
        check: Whether to raise on non-zero exit
        cwd: Working directory
        env: Environment variables
        
    Returns:
        CompletedProcess instance
    """
    return subprocess.run(
        cmd,
        capture_output=capture_output,
        text=True,
        check=check,
        cwd=cwd,
        env=env
    )


def run_shell(
    script: str,
    capture_output: bool = True,
    check: bool = True,
    cwd: Optional[Union[str, Path]] = None
) -> subprocess.CompletedProcess:
    """
    Run a shell script.
    
    Args:
        script: Shell script
        capture_output: Whether to capture stdout/stderr
        check: Whether to raise on non-zero exit
        cwd: Working directory
        
    Returns:
        CompletedProcess instance
    """
    return subprocess.run(
        script,
        shell=True,
        capture_output=capture_output,
        text=True,
        check=check,
        cwd=cwd
    )


# ============================================================================
# Git Utilities
# ============================================================================

def git_root() -> Path:
    """
    Get git repository root.
    
    Returns:
        Path to git root
    """
    result = run_command(
        ["git", "rev-parse", "--show-toplevel"],
        capture_output=True,
        check=True
    )
    return Path(result.stdout.strip())


def git_status(porcelain: bool = True) -> str:
    """
    Get git status.
    
    Args:
        porcelain: Use porcelain format
        
    Returns:
        Git status output
    """
    cmd = ["git", "status"]
    if porcelain:
        cmd.append("--porcelain")
    
    result = run_command(cmd, capture_output=True, check=True)
    return result.stdout


def git_branch() -> str:
    """
    Get current git branch.
    
    Returns:
        Branch name
    """
    result = run_command(
        ["git", "rev-parse", "--abbrev-ref", "HEAD"],
        capture_output=True,
        check=True
    )
    return result.stdout.strip()


# ============================================================================
# Main Entry Point (for testing)
# ============================================================================

def main() -> None:
    """Test the common utilities."""
    log_section("Testing Common Utilities")
    
    log_info("This is an info message")
    log_warn("This is a warning message")
    log_success("This is a success message")
    log_step("This is a step message")
    
    log_section("Environment")
    log_kv("CI", str(is_ci()))
    log_kv("Script Root", str(script_root()))
    log_kv("Repo Root", str(repo_root()))
    log_kv("Git Root", str(git_root()))
    log_kv("Git Branch", git_branch())
    
    log_section("Tests Passed")


if __name__ == "__main__":
    main()
