#!/usr/bin/env python3
"""Quality gates for public/images/{news,services}.

The drawing language is prompts/illustration-services.md. This file is the part of it a
machine can decide, and the rationale for every gate is in that document under "Quality
gates".

Usage:
    scripts/check-illustrations.py                 all drawings
    scripts/check-illustrations.py news            one family
    scripts/check-illustrations.py public/images/news/llm-gateway-open-source.svg
    scripts/check-illustrations.py --list-acts     the act vocabulary
    scripts/check-illustrations.py --json          machine-readable report
"""

from __future__ import annotations

import json
import re
import shutil
import subprocess
import sys
from dataclasses import dataclass, field
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
MANIFEST = ROOT / "public/images/illustrations.json"

PALETTE = {"#09090b", "#500472", "#c026d3", "#2563eb", "#ffffff", "#f4eef8"}

BANNER_VIEWBOX = "0 0 1600 840"
CARD_VIEWBOX = "-10 -10 344 344"

NEWS_SAFE = (58, 158, 1542, 682)
SERVICE_SAFE = (24, 24, 1576, 816)

MAX_SVG_BYTES = 12288
OG_SIZE = (1200, 630)
CONNECTOR_FLOOR = {"banner": 150, "card": 40}
MIN_OBJECTS = 3
STAGE_ARROWS = {"banner": 2, "card": 1}
ARROWHEAD_RE = re.compile(r"[lL]\s*-?[\d.]+\s*,?\s*-?[\d.]+\s*[vVhH]\s*-?[\d.]+\s*Z")

ACTS = {
    "magnifier": "find — something is searched and located",
    "braces": "build — it is written as code",
    "nodes": "integrate — separate things are wired together",
    "cursor": "click — it becomes something you can operate",
    "schedule": "recur — it now runs on its own, repeatedly",
    "funnel": "extract — unstructured material becomes fields",
    "window": "move to the browser — an installed thing runs in a tab",
    "transfer": "relocate — it moved to another org, tenant or surface",
    "grid": "compose — a whole is assembled from parts",
    "queue": "queue — order and waiting are the point",
    "shield": "protect — the change is that it is now guarded",
    "bell": "notify — the change is that someone now gets told",
    "board": "plan — work becomes visible and assignable",
}

ABSTRACT_WORDS = {
    "dot", "node", "hub", "blob", "shape", "abstract", "generic", "circle", "square",
    "rectangle", "rect", "starburst", "spark", "burst", "cloud", "thing", "object",
    "widget", "graphic", "symbol", "swirl", "gradient", "decoration", "accent",
}

SOURCES = {
    "news": [ROOT / "database/files/news/de_CH", ROOT / "database/files/news/en_CH"],
    "services": [
        ROOT / "database/files/services/de_CH",
        ROOT / "database/files/services/en_CH",
    ],
}


@dataclass
class Report:
    path: Path
    failures: list[str] = field(default_factory=list)
    notes: list[str] = field(default_factory=list)
    meta: dict = field(default_factory=dict)

    def fail(self, gate: str, detail: str) -> None:
        self.failures.append(f"{gate}: {detail}")

    def note(self, detail: str) -> None:
        self.notes.append(detail)

    @property
    def ok(self) -> bool:
        return not self.failures


def normalise(text: str) -> str:
    """Fold a quote and its source to one shape before comparing."""
    text = text.lower()
    text = re.sub(r"[*_`\[\]]", "", text)
    for src, dst in (
        ("’", "'"), ("‘", "'"), ("„", '"'), ("“", '"'), ("”", '"'),
        ("«", '"'), ("»", '"'), ("–", "-"), ("—", "-"), ("‑", "-"),
        (" ", " "), (" ", " "),
    ):
        text = text.replace(src, dst)
    return re.sub(r"\s+", " ", text).strip()


def load_manifest() -> dict:
    if not MANIFEST.exists():
        return {}
    return json.loads(MANIFEST.read_text(encoding="utf-8"))


def load_sources(family: str, slug: str) -> dict[Path, str]:
    """Every localised markdown file for one subject, normalised, keyed by path."""
    found: dict[Path, str] = {}
    for directory in SOURCES[family]:
        if not directory.is_dir():
            continue
        for candidate in sorted(directory.glob("*.md")):
            if candidate.stem == slug or candidate.stem.endswith("-" + slug):
                found[candidate] = normalise(candidate.read_text(encoding="utf-8"))
    return found


def hero_alts(slug: str) -> list[str]:
    alts = []
    for directory in SOURCES["news"]:
        for candidate in sorted(directory.glob("*.md")):
            if not candidate.stem.endswith("-" + slug):
                continue
            match = re.search(
                r"^hero_alt:\s*(.+)$", candidate.read_text(encoding="utf-8"), re.M
            )
            if match:
                alts.append(match.group(1).strip())
    return alts


CURVE_CMDS = set("cCsSqQtTaA")
PATH_CMD_RE = re.compile(r"([MmLlHhVvCcSsQqTtAaZz])([^MmLlHhVvCcSsQqTtAaZz]*)")
NUM_RE = re.compile(r"-?\d*\.?\d+(?:e-?\d+)?")
ARITY = {"M": 2, "L": 2, "T": 2, "H": 1, "V": 1, "C": 6, "S": 4, "Q": 4, "A": 7}


def path_commands(d: str) -> list[tuple[str, list[float]]]:
    return [
        (cmd, [float(n) for n in NUM_RE.findall(args)])
        for cmd, args in PATH_CMD_RE.findall(d)
    ]


def has_curve(d: str) -> bool:
    return any(cmd in CURVE_CMDS for cmd, _ in path_commands(d))


def bbox(d: str) -> tuple[float, float, float, float]:
    """A box around a path, counting curve control points as if they were on the curve."""
    xs: list[float] = []
    ys: list[float] = []
    x = y = 0.0
    start = (0.0, 0.0)
    for cmd, nums in path_commands(d):
        upper, rel = cmd.upper(), cmd.islower()
        if upper == "Z":
            x, y = start
            continue
        step = ARITY[upper]
        for i in range(0, len(nums) - step + 1, step):
            chunk = nums[i : i + step]
            if upper == "H":
                x = x + chunk[0] if rel else chunk[0]
            elif upper == "V":
                y = y + chunk[0] if rel else chunk[0]
            elif upper == "A":
                ex, ey = chunk[5], chunk[6]
                x, y = (x + ex, y + ey) if rel else (ex, ey)
            else:
                px = py = 0.0
                for j in range(0, step, 2):
                    px, py = chunk[j], chunk[j + 1]
                    px, py = (x + px, y + py) if rel else (px, py)
                    xs.append(px)
                    ys.append(py)
                x, y = px, py
            if upper == "M" and i == 0:
                start = (x, y)
            xs.append(x)
            ys.append(y)
    if not xs:
        return (0.0, 0.0, 0.0, 0.0)
    return (min(xs), min(ys), max(xs), max(ys))


def polyline(d: str) -> list[tuple[float, float]]:
    pts: list[tuple[float, float]] = []
    x = y = 0.0
    start = (0.0, 0.0)
    for cmd, nums in path_commands(d):
        upper, rel = cmd.upper(), cmd.islower()
        if upper == "Z":
            if pts:
                pts.append(start)
                x, y = start
            continue
        if upper in ("M", "L"):
            for i in range(0, len(nums) - 1, 2):
                nx, ny = nums[i], nums[i + 1]
                x, y = (x + nx, y + ny) if rel else (nx, ny)
                if upper == "M" and i == 0:
                    start = (x, y)
                pts.append((x, y))
        elif upper == "H":
            for nx in nums:
                x = x + nx if rel else nx
                pts.append((x, y))
        elif upper == "V":
            for ny in nums:
                y = y + ny if rel else ny
                pts.append((x, y))
    return pts


def subpaths(d: str) -> list[list[tuple[float, float]]]:
    out: list[list[tuple[float, float]]] = []
    current: list[tuple[float, float]] = []
    for chunk in re.split(r"(?=[Mm])", d):
        if not chunk.strip():
            continue
        current = polyline(chunk if chunk[0] in "Mm" else "M0 0" + chunk)
        if len(current) > 1:
            out.append(current)
    return out


def segments(pts: list[tuple[float, float]]) -> list[tuple]:
    return [(pts[i], pts[i + 1]) for i in range(len(pts) - 1)]


def _orient(a, b, c) -> float:
    return (b[0] - a[0]) * (c[1] - a[1]) - (b[1] - a[1]) * (c[0] - a[0])


def crosses(s1, s2) -> bool:
    """True only where two lines pass through each other.

    A T-junction — one lane ending on another, four lanes meeting a trunk — leaves at least
    one orientation at zero, and is a join rather than a tangle.
    """
    (a, b), (c, d) = s1, s2
    d1, d2 = _orient(c, d, a), _orient(c, d, b)
    d3, d4 = _orient(a, b, c), _orient(a, b, d)
    if min(abs(d1), abs(d2), abs(d3), abs(d4)) < 1e-6:
        return False
    return ((d1 > 0) != (d2 > 0)) and ((d3 > 0) != (d4 > 0))


ATTR_RE = re.compile(r'([\w:-]+)\s*=\s*"([^"]*)"')
DEFS_RE = re.compile(r"<defs>.*?</defs>", re.S)
TAG_RE = re.compile(r"<(/?)([a-zA-Z][\w:-]*)([^>]*?)(/?)>", re.S)

SHAPE_TAGS = {"path", "line", "polyline", "polygon"}
INHERITED = ("fill", "stroke", "class", "data-detail", "data-curve")


def attrs_of(chunk: str) -> dict[str, str]:
    return dict(ATTR_RE.findall(chunk))


def shapes(body: str):
    """Every shape element with the attributes it actually paints with.

    fill and class are inheritable in SVG, and the tangle this checker exists to catch was
    written as bare paths inside a group that carried fill="none" for all of them.
    """
    stack: list[dict[str, str]] = [{}]
    for closing, tag, chunk, selfclose in TAG_RE.findall(body):
        if closing:
            if tag == "g" and len(stack) > 1:
                stack.pop()
            continue
        attrs = attrs_of(chunk)
        if tag == "g":
            merged = dict(stack[-1])
            for key in INHERITED:
                if key in attrs:
                    merged[key] = attrs[key]
            if not selfclose:
                stack.append(merged)
            continue
        if tag not in SHAPE_TAGS:
            continue
        effective = dict(stack[-1])
        effective.update(attrs)
        yield tag, effective


def snip(d: str, width: int = 52) -> str:
    d = re.sub(r"\s+", " ", d).strip()
    return d if len(d) <= width else d[: width - 1] + "…"


def check_svg(path: Path, manifest: dict) -> Report:
    report = Report(path=path)
    svg = path.read_text(encoding="utf-8")
    body = DEFS_RE.sub("", svg)
    is_card = path.stem.endswith("-card")
    family = "news" if "/news/" in path.as_posix() else "services"
    slug = path.stem[: -len("-card")] if is_card else path.stem
    kind = "card" if is_card else "banner"
    report.meta.update(family=family, slug=slug, kind=kind)

    check_brief(report, manifest, family, slug, is_card)
    check_words(report, body)
    check_palette(report, svg)
    check_canvas(report, svg, is_card)
    check_glow(report, body, is_card)
    check_craft(report, svg, body)
    check_arrowheads(report, body, kind)
    check_connectors(report, body, kind)
    check_weight(report, path)
    check_png(report, path, is_card)
    if not is_card:
        check_safe_area(report, path, NEWS_SAFE if family == "news" else SERVICE_SAFE)
    return report


def check_brief(report, manifest, family, slug, is_card) -> None:
    key = f"{family}/{slug}"
    brief = manifest.get(key)
    if not brief:
        report.fail("brief", f"no '{key}' in public/images/illustrations.json")
        return

    sentence = brief.get("sentence", "")
    if sentence.count("→") < 2:
        report.fail("brief", "sentence needs both arrows — before → act → after")

    act = brief.get("act", "")
    report.meta["act"] = act
    if act not in ACTS:
        report.fail("act", f"'{act}' is not in the vocabulary — {', '.join(sorted(ACTS))}")

    objects = brief.get("objects", {})
    report.meta["objects"] = list(objects)
    if len(objects) < MIN_OBJECTS:
        report.fail("brief", f"{len(objects)} objects named, want {MIN_OBJECTS} or more")

    for name in objects:
        words = re.findall(r"[a-z]+", name.lower())
        head = words[-1] if words else ""
        if head in ABSTRACT_WORDS:
            report.fail(
                "abstract",
                f"'{name}' is a '{head}' — name the thing a reader would name, "
                "not the geometry it is drawn with",
            )

    sources = load_sources(family, slug)
    if not sources:
        report.fail("source", f"no source markdown for '{slug}'")
        return
    report.meta["sources"] = [p.name for p in sources]

    if family == "news" and len(sources) < 2:
        report.fail("source", "only one locale file — news is authored de_CH + en_CH")

    haystack = "\n".join(sources.values())
    cited = [("act", brief.get("actSource", ""))] + list(objects.items())
    for name, quote in cited:
        if not quote:
            report.fail("context", f"'{name}' cites no source phrase")
        elif normalise(quote) not in haystack:
            report.fail(
                "context",
                f'\'{name}\' cites "{snip(quote, 60)}" — not in '
                f"{', '.join(p.name for p in sources)}",
            )

    if family == "news" and not is_card:
        alts = hero_alts(slug)
        if len(alts) == 2 and normalise(alts[0]) == normalise(alts[1]):
            report.fail("alt", "hero_alt is identical in de_CH and en_CH")
        if len(alts) < 2:
            report.fail("alt", "hero_alt missing in one locale")


def check_words(report, body) -> None:
    for token in ("<text", "<tspan", "font-family", "font-size"):
        if token in body:
            report.fail("no-text", f"contains {token} — rsvg renders it as Helvetica")


def check_palette(report, svg) -> None:
    for value in {h.lower() for h in re.findall(r"#[0-9A-Fa-f]{3,8}\b", svg)}:
        expanded = "#" + "".join(c * 2 for c in value[1:]) if len(value) == 4 else value
        if expanded not in PALETTE:
            report.fail("palette", f"{value} is not one of the five values")


def check_canvas(report, svg, is_card) -> None:
    match = re.search(r'viewBox="([^"]+)"', svg)
    viewbox = match.group(1).strip() if match else ""
    want = CARD_VIEWBOX if is_card else BANNER_VIEWBOX
    if viewbox != want:
        report.fail("canvas", f'viewBox="{viewbox}" should be "{want}"')


def check_glow(report, body, is_card) -> None:
    glows = body.count("url(#glow)")
    if is_card and glows:
        report.fail("glow", f"{glows} on a card — a card has no transformation point")
    if not is_card and glows != 1:
        report.fail("glow", f"{glows} on a banner, want exactly 1")


def check_craft(report, svg, body) -> None:
    if "feDropShadow" in svg:
        report.fail("craft", "feDropShadow — shadows are offset copies at opacity 0.3")
    for filter_id in set(re.findall(r'filter="url\(#([^)]+)\)"', body)):
        if filter_id != "glow":
            report.fail("craft", f"filter #{filter_id} — the blur is reserved for the glow")
    if 'opacity="0.3"' not in body:
        report.fail("craft", "no offset shadow — every surface sits on one")
    if not re.search(r'<use\s+href="#[ws]\d', body):
        report.fail("craft", "no squiggle — body copy is hand-drawn, never a plain bar")
    if "stroke-linejoin" not in body:
        report.fail("craft", 'no stroke-linejoin="round" — corners are drawn, not mitred')


def check_arrowheads(report, body, kind) -> None:
    """An arrowhead says one stage became the next. Nothing else in a drawing may wear one."""
    heads = 0
    for _tag, attrs in shapes(body):
        if "act" in attrs.get("class", "").split():
            continue
        heads += len(ARROWHEAD_RE.findall(attrs.get("d", "")))
    report.meta["arrowheads"] = heads
    want = STAGE_ARROWS[kind]
    if heads != want:
        report.fail(
            "arrow",
            f"{heads} arrowheads outside the act glyph, want exactly {want} — a step in a "
            "chain, a lane, a branch and a link are connectors and carry no head",
        )


def check_connectors(report, body, kind) -> None:
    floor = CONNECTOR_FLOOR[kind]
    connectors: list[tuple[list, str]] = []

    for _tag, attrs in shapes(body):
        d = attrs.get("d", "")
        if not d or attrs.get("fill", "none") != "none":
            continue
        classes = set(attrs.get("class", "").split())
        if not classes & {"connector", "arrow"}:
            if "data-detail" in attrs:
                continue
            x0, y0, x1, y1 = bbox(d)
            span = max(x1 - x0, y1 - y0)
            if span < floor:
                continue
            report.fail(
                "connector",
                f"undeclared line spanning {span:.0f} units — mark it "
                'class="connector"/"arrow" if it runs between objects, '
                f'or data-detail="…" if it belongs to one. [{snip(d)}]',
            )
            continue
        if has_curve(d):
            if "data-curve" in attrs:
                report.note(f"curved by design: {attrs['data-curve']}")
            else:
                report.fail(
                    "connector",
                    "a connector is curved — connectors are orthogonal polylines. "
                    f'Use data-curve="…" only if the curve is the subject. [{snip(d)}]',
                )
            continue
        for pts in subpaths(d):
            connectors.append((pts, d))

    report.meta["connectors"] = len(connectors)
    for i in range(len(connectors)):
        for j in range(i + 1, len(connectors)):
            if connectors[i][1] == connectors[j][1]:
                continue
            hit = any(
                crosses(s1, s2)
                for s1 in segments(connectors[i][0])
                for s2 in segments(connectors[j][0])
            )
            if hit:
                report.fail(
                    "connector",
                    "two connectors cross — route them around each other or drop one. "
                    f"[{snip(connectors[i][1])}] x [{snip(connectors[j][1])}]",
                )


def check_weight(report, path) -> None:
    size = path.stat().st_size
    if size > MAX_SVG_BYTES:
        report.fail("size", f"{size} bytes, budget is {MAX_SVG_BYTES}")


def png_size(path: Path) -> tuple[int, int] | None:
    data = path.read_bytes()[:33]
    if len(data) < 33 or data[12:16] != b"IHDR":
        return None
    return int.from_bytes(data[16:20], "big"), int.from_bytes(data[20:24], "big")


def check_png(report, path, is_card) -> None:
    png = path.with_suffix(".png")
    if is_card:
        if png.exists():
            report.fail("png", "a card is never an og:image — delete the PNG")
        return
    if not png.exists():
        report.fail("png", "missing — og:image falls back to og-codebar.png")
        return
    size = png_size(png)
    if size and size != OG_SIZE:
        report.fail("png", f"{size[0]}x{size[1]}, must be {OG_SIZE[0]}x{OG_SIZE[1]}")


_HAVE_RASTER = shutil.which("rsvg-convert") and shutil.which("magick")


def check_safe_area(report, path: Path, safe: tuple[int, int, int, int]) -> None:
    """Where the first real ink sits, in SVG units, measured rather than eyeballed."""
    if not _HAVE_RASTER:
        report.note("safe area not measured — needs rsvg-convert and magick")
        return
    scale = 2
    out = Path("/tmp") / f"illu-{path.stem}.png"
    try:
        subprocess.run(
            ["rsvg-convert", "-w", str(1600 * scale), "-h", str(840 * scale),
             str(path), "-o", str(out)],
            check=True, capture_output=True,
        )
        result = subprocess.run(
            ["magick", str(out), "-colorspace", "Gray", "-threshold", "43%",
             "-negate", "-format", "%@", "info:"],
            check=True, capture_output=True, text=True,
        )
    except subprocess.CalledProcessError as exc:
        report.note(f"safe area not measured — {exc}")
        return
    finally:
        out.unlink(missing_ok=True)

    match = re.match(r"(\d+)x(\d+)\+(\d+)\+(\d+)", result.stdout.strip())
    if not match:
        report.note("safe area not measured — no ink found")
        return
    w, h, x, y = (int(g) for g in match.groups())
    box = (x / scale, y / scale, (x + w) / scale, (y + h) / scale)
    report.meta["ink"] = [round(v) for v in box]
    left, top, right, bottom = safe
    slack = 1.0
    if (box[0] < left - slack or box[1] < top - slack
            or box[2] > right + slack or box[3] > bottom + slack):
        report.fail(
            "crop",
            f"ink spans {[round(v) for v in box]}, safe area is {list(safe)} — "
            "a crop would cut it",
        )


def check_set(reports: list[Report]) -> list[str]:
    """Held against each other: the index prints these directly under one another."""
    failures = []
    for family in ("news", "services"):
        banners = [
            r for r in reports
            if r.meta.get("family") == family and r.meta.get("kind") == "banner"
        ]
        for key, label in (("act", "act"), ("primary", "opening object")):
            seen: dict[str, str] = {}
            for report in banners:
                if key == "act":
                    value = report.meta.get("act", "")
                else:
                    objects = report.meta.get("objects") or []
                    value = objects[0].lower() if objects else ""
                if not value:
                    continue
                if value in seen:
                    failures.append(
                        f"{family}: '{value}' is the {label} of both {seen[value]} "
                        f"and {report.meta['slug']} — the two rows read as one drawing"
                    )
                seen[value] = report.meta["slug"]
    return failures


def collect(args: list[str]) -> list[Path]:
    if not args:
        args = ["news", "services"]
    paths: list[Path] = []
    for arg in args:
        if arg in ("news", "services"):
            paths += sorted((ROOT / "public/images" / arg).glob("*.svg"))
        else:
            paths.append(Path(arg).resolve())
    return paths


def main() -> int:
    flags = {a for a in sys.argv[1:] if a.startswith("--")}
    argv = [a for a in sys.argv[1:] if not a.startswith("--")]

    if "--list-acts" in flags:
        for name, meaning in sorted(ACTS.items()):
            print(f"  {name:<11} {meaning}")
        return 0

    paths = collect(argv)
    if not paths:
        print("no illustrations found", file=sys.stderr)
        return 1

    manifest = load_manifest()
    reports = [check_svg(p, manifest) for p in paths]
    set_failures = check_set(reports)
    bad = sum(1 for r in reports if not r.ok)

    if "--json" in flags:
        print(json.dumps({
            "files": [
                {
                    "path": str(r.path.relative_to(ROOT)),
                    "ok": r.ok,
                    "failures": r.failures,
                    "notes": r.notes,
                    "meta": r.meta,
                }
                for r in reports
            ],
            "set": set_failures,
        }, indent=2, ensure_ascii=False))
        return 1 if bad or set_failures else 0

    for report in reports:
        name = report.path.relative_to(ROOT)
        if report.ok:
            extra = f"  ({'; '.join(report.notes)})" if report.notes else ""
            print(f"\033[32mPASS\033[0m {name}{extra}")
        else:
            print(f"\033[31mFAIL\033[0m {name}")
            for failure in report.failures:
                print(f"       {failure}")
            for note in report.notes:
                print(f"       note: {note}")

    if set_failures:
        print("\n\033[31mFAIL\033[0m the set as a whole")
        for failure in set_failures:
            print(f"       {failure}")

    print(f"\n{len(reports) - bad}/{len(reports)} files pass"
          + (f", {len(set_failures)} set-level failures" if set_failures else ""))
    return 1 if bad or set_failures else 0


if __name__ == "__main__":
    sys.exit(main())
