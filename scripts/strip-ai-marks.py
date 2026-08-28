#!/usr/bin/env python3
"""Strip invisible Unicode from staged files.

    scripts/strip-ai-marks.py               fix staged files, restage them (pre-commit)
    scripts/strip-ai-marks.py --check       report only, exit 1 on findings
    scripts/strip-ai-marks.py --all         scan every tracked file
    scripts/strip-ai-marks.py PATH ...      scan/fix explicit paths
    scripts/strip-ai-marks.py --fix-spaces  additionally fold NBSP & co. to U+0020

Removed characters are invisible and never load-bearing here: zero-width space,
bidi controls, soft hyphen, word joiner, BOM, Unicode tag characters.

ZWJ/ZWNJ (U+200C/U+200D) and variation selectors are kept - they glue emoji
sequences together. Exotic spaces are reported but kept unless --fix-spaces,
because NBSP is legitimate German typography.
"""

import argparse
import os
import subprocess
import sys
import unicodedata
from collections import Counter

INVISIBLE_RANGES = (
    (0x00AD, 0x00AD),
    (0x034F, 0x034F),
    (0x061C, 0x061C),
    (0x115F, 0x1160),
    (0x17B4, 0x17B5),
    (0x180E, 0x180E),
    (0x200B, 0x200B),
    (0x200E, 0x200F),
    (0x202A, 0x202E),
    (0x2060, 0x2064),
    (0x2066, 0x206F),
    (0x3164, 0x3164),
    (0xFEFF, 0xFEFF),
    (0xFFA0, 0xFFA0),
    (0xFFF9, 0xFFFB),
    (0x1D173, 0x1D17A),
    (0xE0000, 0xE007F),
)

EXOTIC_SPACES = frozenset(
    [0x00A0, 0x1680, 0x202F, 0x205F, 0x3000] + list(range(0x2000, 0x200B))
)

SKIP_DIRS = (
    "vendor/",
    "node_modules/",
    "public/build/",
    "public/vendor/",
    "storage/",
    "tests/lighthouse/reports/",
    "resources/fonts/",
)

SKIP_SUFFIXES = (
    ".lock",
    ".min.js",
    ".min.css",
    ".map",
    ".woff",
    ".woff2",
    ".ttf",
    ".otf",
    ".eot",
    ".png",
    ".jpg",
    ".jpeg",
    ".gif",
    ".webp",
    ".avif",
    ".ico",
    ".pdf",
    ".zip",
    ".gz",
    ".sqlite",
    ".mo",
)

MAX_BYTES = 2 * 1024 * 1024


def is_invisible(codepoint):
    return any(lo <= codepoint <= hi for lo, hi in INVISIBLE_RANGES)


def describe(codepoint):
    try:
        name = unicodedata.name(chr(codepoint))
    except ValueError:
        name = "UNNAMED"
    return f"U+{codepoint:04X} {name}"


def should_skip(path):
    normalised = path.replace(os.sep, "/")
    if any(normalised.startswith(prefix) for prefix in SKIP_DIRS):
        return True
    return normalised.lower().endswith(SKIP_SUFFIXES)


def decode(data):
    if b"\x00" in data[:8000] or len(data) > MAX_BYTES:
        return None
    try:
        return data.decode("utf-8")
    except UnicodeDecodeError:
        return None


def scan(text):
    invisible = Counter()
    spaces = Counter()
    for char in text:
        codepoint = ord(char)
        if is_invisible(codepoint):
            invisible[codepoint] += 1
        elif codepoint in EXOTIC_SPACES:
            spaces[codepoint] += 1
    return invisible, spaces


def clean(text, fix_spaces):
    out = []
    for char in text:
        codepoint = ord(char)
        if is_invisible(codepoint):
            continue
        if fix_spaces and codepoint in EXOTIC_SPACES:
            out.append(" ")
            continue
        out.append(char)
    return "".join(out)


def git(*args, stdin=None):
    return subprocess.run(
        ["git", *args], input=stdin, stdout=subprocess.PIPE, check=True
    ).stdout


def staged_paths():
    raw = git("diff", "--cached", "--name-only", "--diff-filter=ACMR", "-z")
    return [p for p in raw.decode("utf-8", "replace").split("\0") if p]


def tracked_paths():
    raw = git("ls-files", "-z")
    return [p for p in raw.decode("utf-8", "replace").split("\0") if p]


def staged_blob(path):
    result = subprocess.run(
        ["git", "show", f":{path}"], stdout=subprocess.PIPE, stderr=subprocess.DEVNULL
    )
    return result.stdout if result.returncode == 0 else None


def restage(path, data):
    listing = git("ls-files", "-s", "--", path).decode().split()
    if not listing:
        return False
    mode = listing[0]
    sha = git("hash-object", "-w", "--stdin", stdin=data).decode().strip()
    git("update-index", "--cacheinfo", f"{mode},{sha},{path}")
    return True


def report(path, invisible, spaces, action):
    print(f"  {path}")
    for codepoint, count in sorted(invisible.items()):
        print(f"      {action:<8} {describe(codepoint)} x{count}")
    for codepoint, count in sorted(spaces.items()):
        print(f"      {'kept':<8} {describe(codepoint)} x{count}")


def run_staged(args):
    findings = 0
    fixed = []
    warned = []

    for path in staged_paths():
        if should_skip(path):
            continue
        data = staged_blob(path)
        if data is None:
            continue
        text = decode(data)
        if text is None:
            continue

        invisible, spaces = scan(text)
        if not invisible and not (spaces and args.fix_spaces):
            if spaces:
                warned.append((path, spaces))
            continue

        findings += 1
        if args.check:
            report(path, invisible, spaces, "found")
            continue

        cleaned = clean(text, args.fix_spaces).encode("utf-8")
        if not restage(path, cleaned):
            continue
        report(path, invisible, spaces if args.fix_spaces else Counter(), "removed")
        fixed.append(path)

        if os.path.exists(path):
            with open(path, "rb") as handle:
                on_disk = handle.read()
            if on_disk == data:
                with open(path, "wb") as handle:
                    handle.write(cleaned)
            else:
                print("      note     worktree differs from index, left untouched")
        if spaces and not args.fix_spaces:
            warned.append((path, spaces))

    if findings and not args.check:
        print(f"\nstrip-ai-marks: cleaned {len(fixed)} file(s) and restaged them.")
    if warned:
        print("\nstrip-ai-marks: exotic spaces kept (use --fix-spaces to fold them):")
        for path, spaces in warned:
            summary = ", ".join(
                f"{describe(cp)} x{n}" for cp, n in sorted(spaces.items())
            )
            print(f"  {path}: {summary}")

    if args.check and findings:
        print(f"\nstrip-ai-marks: {findings} file(s) contain invisible characters.")
        return 1
    return 0


def run_paths(paths, args):
    findings = 0
    for path in paths:
        if should_skip(path) or not os.path.isfile(path):
            continue
        with open(path, "rb") as handle:
            data = handle.read()
        text = decode(data)
        if text is None:
            continue

        invisible, spaces = scan(text)
        if not invisible and not (spaces and args.fix_spaces):
            continue

        findings += 1
        if args.check:
            report(path, invisible, spaces, "found")
            continue

        with open(path, "wb") as handle:
            handle.write(clean(text, args.fix_spaces).encode("utf-8"))
        report(path, invisible, spaces if args.fix_spaces else Counter(), "removed")

    if findings:
        state = "contain invisible characters" if args.check else "cleaned"
        print(f"\nstrip-ai-marks: {findings} file(s) {state}.")
        return 1 if args.check else 0
    print("strip-ai-marks: clean.")
    return 0


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("paths", nargs="*")
    parser.add_argument("--check", action="store_true")
    parser.add_argument("--all", action="store_true")
    parser.add_argument("--fix-spaces", action="store_true")
    args = parser.parse_args()

    if args.all:
        return run_paths(tracked_paths(), args)
    if args.paths:
        return run_paths(args.paths, args)
    return run_staged(args)


if __name__ == "__main__":
    sys.exit(main())
