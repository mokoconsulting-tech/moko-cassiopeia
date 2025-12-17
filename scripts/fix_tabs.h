#!/usr/bin/env python3
"""
fix_tabs.py

Replaces all tab characters (\t) in tracked text files with two spaces.

Behavior
- Operates only on Git-tracked files.
- Skips binary files automatically via Git attributes.
- Modifies files in place.
- Intended for CI and local formatting enforcement.

Exit codes
- 0: Success, no errors
- 1: One or more files failed processing
"""

import subprocess
import sys
from pathlib import Path

REPLACEMENT = "  "  # two spaces


def get_tracked_files():
  try:
    result = subprocess.run(
      ["git", "ls-files"],
      check=True,
      stdout=subprocess.PIPE,
      stderr=subprocess.PIPE,
      text=True,
    )
    return [Path(p) for p in result.stdout.splitlines() if p.strip()]
  except subprocess.CalledProcessError as e:
    print("Error: unable to list git-tracked files", file=sys.stderr)
    print(e.stderr, file=sys.stderr)
    sys.exit(1)


def is_binary(path: Path) -> bool:
  try:
    with path.open("rb") as f:
      chunk = f.read(1024)
      return b"\0" in chunk
  except Exception:
    return True


def process_file(path: Path) -> bool:
  try:
    if is_binary(path):
      return True

    content = path.read_text(encoding="utf-8", errors="strict")

    if "\t" not in content:
      return True

    updated = content.replace("\t", REPLACEMENT)
    path.write_text(updated, encoding="utf-8")
    print(f"Normalized tabs: {path}")
    return True
  except UnicodeDecodeError:
    # Non-UTF8 text file, skip safely
    return True
  except Exception as e:
    print(f"Failed processing {path}: {e}", file=sys.stderr)
    return False


def main() -> int:
  failures = False

  for file_path in get_tracked_files():
    if not process_file(file_path):
      failures = True

  return 1 if failures else 0


if __name__ == "__main__":
  sys.exit(main())
