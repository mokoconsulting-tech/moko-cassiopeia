#!/usr/bin/env python3
"""
fix_paths.py

Normalizes invalid Windows-style backslash separators in repository *paths*.

What it does
- Uses `git ls-files` as the authoritative inventory of tracked paths.
- Detects any tracked path that contains a backslash (\\).
- Renames the path to a forward-slash (/) equivalent via `git mv`.
- Fails fast on collisions (when the normalized target path already exists).

What it does NOT do
- Does not rewrite file contents.
- Does not alter untracked files.

Intended usage
- Called by CI (GitHub Actions) and locally.
- Safe to run repeatedly (idempotent when no invalid paths exist).

Exit codes
- 0: Success, no invalid paths or all renames completed
- 1: Operational error (git failure, collision, or unexpected exception)
"""

from __future__ import annotations

import os
import subprocess
import sys


def run(cmd: list[str]) -> subprocess.CompletedProcess:
  return subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)


def ensure_repo_root() -> None:
  # In CI we usually start at the repo root, but this enforces determinism.
  workspace = os.environ.get("GITHUB_WORKSPACE")
  if workspace and os.path.isdir(workspace):
    os.chdir(workspace)


def require_git_repo() -> None:
  p = run(["git", "rev-parse", "--is-inside-work-tree"])
  if p.returncode != 0 or p.stdout.strip() != "true":
    print("Error: not inside a git work tree", file=sys.stderr)
    sys.exit(1)


def list_tracked_paths() -> list[str]:
  p = run(["git", "ls-files"])
  if p.returncode != 0:
    print("Error: git ls-files failed", file=sys.stderr)
    print(p.stderr, file=sys.stderr)
    sys.exit(1)
  return [line for line in p.stdout.splitlines() if line.strip()]


def path_exists(path: str) -> bool:
  # Use git to evaluate existence of a tracked path when possible.
  # For collision detection we use filesystem existence because the target may not be tracked yet.
  return os.path.exists(path)


def normalize_path(p: str) -> str:
  return p.replace("\\", "/")


def git_mv(old: str, new: str) -> None:
  p = run(["git", "mv", "-f", old, new])
  if p.returncode != 0:
    print(f"Error: git mv failed for {old} -> {new}", file=sys.stderr)
    print(p.stderr, file=sys.stderr)
    sys.exit(1)


def main() -> int:
  ensure_repo_root()
  require_git_repo()

  tracked = list_tracked_paths()
  bad = [p for p in tracked if "\\" in p]

  if not bad:
    print("No invalid backslash separators detected in tracked paths")
    return 0

  print(f"Detected {len(bad)} invalid tracked path(s). Normalizing.")

  # Sort longest-first to reduce rename issues in nested scenarios.
  bad.sort(key=len, reverse=True)

  for old in bad:
    new = normalize_path(old)

    if old == new:
      continue

    # Collision check: if target exists and is not the same logical file.
    if path_exists(new):
      print("Collision detected. Aborting.", file=sys.stderr)
      print(f"Source: {old}", file=sys.stderr)
      print(f"Target: {new}", file=sys.stderr)
      return 1

    # Ensure destination directory exists.
    dest_dir = os.path.dirname(new)
    if dest_dir:
      os.makedirs(dest_dir, exist_ok=True)

    git_mv(old, new)
    print(f"Renamed: {old} -> {new}")

  return 0


if __name__ == "__main__":
  sys.exit(main())
