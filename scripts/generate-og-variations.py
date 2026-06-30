#!/usr/bin/env python3
"""Generate 25 UI/UX-led OG image variations with fixed inverted logo."""

from __future__ import annotations

import argparse
import subprocess
from pathlib import Path

W, H = 1200, 630
ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / "public/images/seo/variations"

LOGO_PATH = (
    "M42.025 115.575C17.7 115.575 0.9 98.075 0.9 73.75C0.9 49.425 17.7 32.1 42.025 32.1C56.2 32.1 "
    "67.75 38.4 74.75 48.375L59.175 60.45C56.375 57.125 51.475 52.75 42.55 52.75C30.475 52.75 22.25 "
    "61.675 22.25 73.75C22.25 85.825 30.475 94.75 42.55 94.75C51.475 94.75 56.2 91.075 59.175 87.4L74.75 "
    "99.125C67.75 109.275 56.2 115.575 42.025 115.575ZM121.74 115.75C97.4152 115.75 80.2652 97.725 "
    "80.2652 73.75C80.2652 49.425 97.4152 31.925 121.74 31.925C146.24 31.925 163.215 49.425 163.215 "
    "73.75C163.215 97.725 146.24 115.75 121.74 115.75ZM121.74 94.75C133.815 94.75 141.69 85.825 141.69 "
    "73.75C141.69 61.675 133.815 52.75 121.74 52.75C109.84 52.75 101.79 61.675 101.79 73.75C101.79 "
    "85.825 109.84 94.75 121.74 94.75ZM210.256 115.4C188.206 115.4 172.106 97.725 172.106 73.75C172.106 "
    "49.425 188.206 32.275 210.256 32.275C220.406 32.275 227.931 35.95 233.356 42.075V0.249994H255.231V114H233.356V105.6C227.931 "
    "111.725 220.406 115.4 210.256 115.4ZM213.406 94.75C225.481 94.75 233.356 85.825 233.356 73.75C233.356 "
    "61.675 225.481 52.75 213.406 52.75C201.331 52.75 193.281 61.675 193.281 73.75C193.281 85.825 201.331 "
    "94.75 213.406 94.75ZM308.144 115.75C283.469 115.75 267.194 97.725 267.194 73.75C267.194 49.775 282.944 "
    "31.925 307.269 31.925C331.244 31.925 345.944 49.775 345.944 73.75V79.7H288.194C290.294 90.55 298.169 "
    "97.025 308.144 97.025C318.819 97.025 324.419 92.125 327.219 88.45L342.094 99.3C335.094 109.45 322.844 "
    "115.75 308.144 115.75ZM288.544 65.875H325.469C323.544 56.425 317.594 49.25 307.269 49.25C297.294 49.25 "
    "290.644 55.725 288.544 65.875ZM402.883 115.4C392.733 115.4 385.208 111.725 379.783 105.6V114H357.908V0.249994H379.783V42.075C385.208 "
    "35.95 392.733 32.275 402.883 32.275C424.933 32.275 441.033 49.425 441.033 73.75C441.033 97.725 424.933 115.4 "
    "402.883 115.4ZM399.733 94.75C411.808 94.75 419.858 85.825 419.858 73.75C419.858 61.675 411.808 52.75 399.733 "
    "52.75C387.658 52.75 379.783 61.675 379.783 73.75C379.783 85.825 387.658 94.75 399.733 94.75ZM488.171 115.4C466.121 "
    "115.4 450.021 97.725 450.021 73.75C450.021 49.425 466.121 32.275 488.171 32.275C498.321 32.275 505.846 35.95 511.271 "
    "42.075V33.5H533.146V114H511.271V105.6C505.846 111.725 498.321 115.4 488.171 115.4ZM491.321 94.75C503.396 94.75 511.271 "
    "85.825 511.271 73.75C511.271 61.675 503.396 52.75 491.321 52.75C479.246 52.75 471.196 61.675 471.196 73.75C471.196 "
    "85.825 479.246 94.75 491.321 94.75ZM548.084 114V33.5H569.959V48.025C576.784 37 587.634 32.45 598.309 32.275V52.75C590.434 "
    "52.925 569.959 54.15 569.959 77.075V114H548.084Z"
)


def logo_block(cx: int = 600, cy: int = 315, scale: float = 1.28, bar_fill: str = "#09090b") -> str:
    return f"""
    <g transform="translate({cx} {cy}) scale({scale}) translate(-299.5 58)">
        <g transform="translate(299.5 58) skewX(-3) translate(-299.5 -58)">
            <rect x="-28" y="12" width="655" height="96" rx="6" fill="{bar_fill}"/>
            <rect x="-23.96" y="16.24" width="647.92" height="83.52" fill="url(#codebar-logo-inverted-bg)" style="mix-blend-mode: screen"/>
        </g>
        <path fill="#ffffff" d="{LOGO_PATH}"/>
    </g>"""


BASE_DEFS = """
        <linearGradient id="brand-strip" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="1200" y2="0">
            <stop offset="0%" stop-color="#C026D3"/>
            <stop offset="50%" stop-color="#500472"/>
            <stop offset="100%" stop-color="#2563EB"/>
        </linearGradient>
        <linearGradient id="brand-strip-soft" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="1200" y2="0">
            <stop offset="0%" stop-color="#C026D3" stop-opacity="0.35"/>
            <stop offset="50%" stop-color="#500472" stop-opacity="0.35"/>
            <stop offset="100%" stop-color="#2563EB" stop-opacity="0.35"/>
        </linearGradient>
        <linearGradient id="codebar-logo-inverted-bg" gradientUnits="userSpaceOnUse" x1="-24" y1="0" x2="635" y2="0">
            <stop offset="0%" stop-color="rgb(192,38,211)" stop-opacity="0.45"/>
            <stop offset="50%" stop-color="rgb(80,4,114)" stop-opacity="0.45"/>
            <stop offset="100%" stop-color="rgb(37,99,235)" stop-opacity="0.40"/>
        </linearGradient>
        <filter id="blur-soft" x="-30%" y="-30%" width="160%" height="160%">
            <feGaussianBlur stdDeviation="36"/>
        </filter>
        <filter id="blur-heavy" x="-40%" y="-40%" width="180%" height="180%">
            <feGaussianBlur stdDeviation="56"/>
        </filter>
        <filter id="paper-noise" x="0" y="0" width="100%" height="100%">
            <feTurbulence type="fractalNoise" baseFrequency="0.75" numOctaves="4" seed="4" result="noise"/>
            <feColorMatrix in="noise" type="matrix"
                values="0 0 0 0 0.5
                        0 0 0 0 0.5
                        0 0 0 0 0.5
                        0 0 0 0.04 0"/>
        </filter>
        <filter id="card-shadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="0" dy="8" stdDeviation="16" flood-color="#500472" flood-opacity="0.08"/>
            <feDropShadow dx="0" dy="2" stdDeviation="4" flood-color="#09090b" flood-opacity="0.06"/>
        </filter>
        <clipPath id="top-slice"><rect x="0" y="0" width="1200" height="210"/></clipPath>
"""

# (slug, title, extra_defs, background_layers, logo_override or None)
RECIPES: list[tuple[str, str, str, str, str | None]] = [
    (
        "v01-mega-strip",
        "Giant skewed stripe",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <g transform="translate(600 315) skewX(-3)">
        <rect x="-520" y="-90" width="1040" height="180" fill="url(#brand-strip-soft)" opacity="0.85"/>
    </g>""",
        None,
    ),
    (
        "v02-strip-stack",
        "Strip rhythm",
        "",
        """
    <rect width="1200" height="630" fill="#FAFAFA"/>
    <g transform="translate(600 320) skewX(-3)">
        <rect x="-500" y="-120" width="1000" height="18" fill="url(#brand-strip)" opacity="0.12"/>
        <rect x="-500" y="-80" width="1000" height="18" fill="url(#brand-strip)" opacity="0.18"/>
        <rect x="-500" y="-40" width="1000" height="18" fill="url(#brand-strip)" opacity="0.24"/>
        <rect x="-500" y="0" width="1000" height="18" fill="url(#brand-strip)" opacity="0.30"/>
        <rect x="-500" y="40" width="1000" height="18" fill="url(#brand-strip)" opacity="0.24"/>
        <rect x="-500" y="80" width="1000" height="18" fill="url(#brand-strip)" opacity="0.18"/>
        <rect x="-500" y="120" width="1000" height="18" fill="url(#brand-strip)" opacity="0.12"/>
    </g>""",
        None,
    ),
    (
        "v03-strip-slice",
        "Stripe crop",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <g clip-path="url(#top-slice)">
        <g transform="translate(600 380) skewX(-3)">
            <rect x="-560" y="-160" width="1120" height="320" fill="url(#brand-strip-soft)"/>
        </g>
    </g>""",
        None,
    ),
    (
        "v04-gradient-edge",
        "Edge bleed",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <rect x="0" y="0" width="480" height="630" fill="url(#brand-strip-soft)"/>
    <rect x="420" y="0" width="60" height="630" fill="url(#edge-fade)"/>""",
        None,
    ),
    (
        "v05-dual-strip-cross",
        "Crossing strips",
        "",
        """
    <rect width="1200" height="630" fill="#F9FAFB"/>
    <g transform="translate(600 315) rotate(-12)"><rect x="-600" y="-40" width="1200" height="80" fill="url(#brand-strip)" opacity="0.14"/></g>
    <g transform="translate(600 315) rotate(18)"><rect x="-600" y="-40" width="1200" height="80" fill="url(#brand-strip)" opacity="0.12"/></g>""",
        None,
    ),
    (
        "v06-swiss-grid",
        "Visible grid",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <g stroke="#E5E7EB" stroke-width="1">
        {"".join(f'<line x1="{x}" y1="0" x2="{x}" y2="630"/>' for x in range(0, 1201, 100))}
        {"".join(f'<line x1="0" y1="{y}" x2="1200" y2="{y}"/>' for y in range(0, 631, 90))}
    </g>""",
        None,
    ),
    (
        "v07-golden-rules",
        "Typographic rules",
        "",
        """
    <rect width="1200" height="630" fill="#FEFEFE"/>
    <line x1="100" y1="210" x2="1100" y2="210" stroke="#D1D5DB" stroke-width="1"/>
    <line x1="100" y1="315" x2="1100" y2="315" stroke="#500472" stroke-width="1" opacity="0.35"/>
    <line x1="100" y1="420" x2="1100" y2="420" stroke="#D1D5DB" stroke-width="1"/>""",
        None,
    ),
    (
        "v08-pipe-rhythm",
        "Pipe dividers",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <g fill="#E5E7EB" font-family="ui-sans-serif, system-ui, sans-serif" font-size="280" font-weight="300" opacity="0.55">
        <text x="180" y="400">|</text>
        <text x="420" y="400">|</text>
        <text x="660" y="400">|</text>
        <text x="900" y="400">|</text>
    </g>""",
        None,
    ),
    (
        "v09-margin-note",
        "Asymmetric margin",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <rect x="0" y="0" width="420" height="630" fill="url(#brand-strip-soft)"/>""",
        logo_block(cx=700, cy=315),
    ),
    (
        "v10-index-card",
        "List-card float",
        "",
        """
    <rect width="1200" height="630" fill="#F9FAFB"/>
    <g filter="url(#card-shadow)">
        <rect x="260" y="195" width="680" height="240" rx="12" fill="#FFFFFF" stroke="#9CA3AF" stroke-opacity="0.2" stroke-width="1"/>
    </g>""",
        None,
    ),
    (
        "v11-midnight-bar",
        "Full dark canvas",
        "",
        """<rect width="1200" height="630" fill="#09090b"/>""",
        logo_block(bar_fill="#09090b"),
    ),
    (
        "v12-navy-swiss",
        "Brand navy field",
        "",
        """<rect width="1200" height="630" fill="#152044"/>""",
        logo_block(bar_fill="#152044"),
    ),
    (
        "v13-spotlight",
        "Stage light",
        "",
        """
    <rect width="1200" height="630" fill="#111111"/>
    <ellipse cx="600" cy="315" rx="520" ry="200" fill="#FFFFFF" opacity="0.95"/>
    <rect width="1200" height="630" fill="url(#spot-vignette)"/>""",
        None,
    ),
    (
        "v14-inverted-world",
        "Negative space bar",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <rect x="0" y="235" width="1200" height="160" fill="#09090b"/>""",
        logo_block(bar_fill="#09090b"),
    ),
    (
        "v15-split-tone",
        "Half dark half light",
        "",
        """
    <rect x="0" y="0" width="600" height="630" fill="#FFFFFF"/>
    <rect x="600" y="0" width="600" height="630" fill="#09090b"/>""",
        logo_block(bar_fill="#09090b"),
    ),
    (
        "v16-paper-grain",
        "Printed paper",
        "",
        """
    <rect width="1200" height="630" fill="#FEFEFE"/>
    <rect width="1200" height="630" filter="url(#paper-noise)"/>""",
        None,
    ),
    (
        "v17-dither-fade",
        "Ordered dither",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <rect width="1200" height="630" fill="url(#dither-pattern)"/>""",
        None,
    ),
    (
        "v18-frosted-glass",
        "Frosted panel",
        "",
        """
    <rect width="1200" height="630" fill="#F3F4F6"/>
    <circle cx="200" cy="150" r="180" fill="#E29DEB" opacity="0.35" filter="url(#blur-heavy)"/>
    <circle cx="1000" cy="480" r="220" fill="#A8C0F6" opacity="0.4" filter="url(#blur-heavy)"/>
    <rect x="180" y="130" width="840" height="370" rx="20" fill="#FFFFFF" opacity="0.72" filter="url(#blur-soft)"/>
    <rect x="180" y="130" width="840" height="370" rx="20" fill="#FFFFFF" opacity="0.45"/>""",
        None,
    ),
    (
        "v19-mesh-aurora",
        "Mesh gradient",
        "",
        """
    <rect width="1200" height="630" fill="url(#mesh-bg)"/>""",
        None,
    ),
    (
        "v20-concrete-minimal",
        "Brutalist Swiss",
        "",
        """
    <rect width="1200" height="630" fill="#F3F4F6"/>
    <line x1="350" y1="395" x2="850" y2="395" stroke="#500472" stroke-width="2" opacity="0.7"/>""",
        None,
    ),
    (
        "v21-listen-wave",
        "Single waveform",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <path d="M0 340 C 200 180, 400 480, 600 315 S 1000 120, 1200 300"
          stroke="url(#brand-strip)" stroke-width="3" fill="none" opacity="0.45"/>
    <path d="M0 360 C 200 520, 400 220, 600 385 S 1000 580, 1200 400"
          stroke="#A8C0F6" stroke-width="2" fill="none" opacity="0.35"/>""",
        None,
    ),
    (
        "v22-ripple",
        "Concentric ripples",
        "",
        """
    <rect width="1200" height="630" fill="#FAFAFA"/>
    <circle cx="600" cy="315" r="120" fill="none" stroke="#E29DEB" stroke-width="2" opacity="0.35"/>
    <circle cx="600" cy="315" r="200" fill="none" stroke="#C084FC" stroke-width="1.5" opacity="0.28"/>
    <circle cx="600" cy="315" r="290" fill="none" stroke="#A8C0F6" stroke-width="1" opacity="0.22"/>
    <circle cx="600" cy="315" r="380" fill="none" stroke="#2563EB" stroke-width="1" opacity="0.14"/>""",
        None,
    ),
    (
        "v23-orbit-dot",
        "Satellite dot",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <circle cx="600" cy="315" r="220" fill="none" stroke="#E5E7EB" stroke-width="1" stroke-dasharray="6 10"/>
    <circle cx="820" cy="195" r="14" fill="#500472"/>""",
        None,
    ),
    (
        "v24-portal-ring",
        "Gradient ring",
        "",
        """
    <rect width="1200" height="630" fill="#FFFFFF"/>
    <circle cx="600" cy="315" r="250" fill="none" stroke="url(#brand-strip)" stroke-width="6" opacity="0.55"/>
    <circle cx="600" cy="315" r="230" fill="none" stroke="#FFFFFF" stroke-width="3"/>""",
        None,
    ),
    (
        "v25-horizon-sun",
        "Rising arc",
        "",
        """
    <rect width="1200" height="630" fill="url(#sky-wash)"/>
    <path d="M-100 630 A 700 700 0 0 1 1300 630 Z" fill="url(#brand-strip-soft)" opacity="0.75"/>""",
        None,
    ),
]

EXTRA_DEFS = """
        <linearGradient id="edge-fade" gradientUnits="userSpaceOnUse" x1="420" y1="0" x2="480" y2="0">
            <stop offset="0%" stop-color="#500472" stop-opacity="0.35"/>
            <stop offset="100%" stop-color="#500472" stop-opacity="0"/>
        </linearGradient>
        <radialGradient id="spot-vignette" cx="600" cy="315" r="680" gradientUnits="userSpaceOnUse">
            <stop offset="50%" stop-color="#000000" stop-opacity="0"/>
            <stop offset="100%" stop-color="#000000" stop-opacity="0.55"/>
        </radialGradient>
        <pattern id="dither-pattern" width="8" height="8" patternUnits="userSpaceOnUse">
            <rect width="8" height="8" fill="#FFFFFF"/>
            <circle cx="2" cy="2" r="1.2" fill="#E29DEB" opacity="0.35"/>
            <circle cx="6" cy="6" r="1.2" fill="#A8C0F6" opacity="0.35"/>
        </pattern>
        <radialGradient id="mesh-a" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(200 100) scale(500)">
            <stop offset="0%" stop-color="#E29DEB" stop-opacity="0.35"/>
            <stop offset="100%" stop-color="#E29DEB" stop-opacity="0"/>
        </radialGradient>
        <radialGradient id="mesh-b" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(600 400) scale(450)">
            <stop offset="0%" stop-color="#500472" stop-opacity="0.2"/>
            <stop offset="100%" stop-color="#500472" stop-opacity="0"/>
        </radialGradient>
        <radialGradient id="mesh-c" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(1050 520) scale(520)">
            <stop offset="0%" stop-color="#A8C0F6" stop-opacity="0.4"/>
            <stop offset="100%" stop-color="#A8C0F6" stop-opacity="0"/>
        </radialGradient>
        <linearGradient id="mesh-bg" x1="0" y1="0" x2="1200" y2="630" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#FDF8FE"/>
            <stop offset="100%" stop-color="#F0F4FE"/>
        </linearGradient>
        <linearGradient id="sky-wash" x1="600" y1="0" x2="600" y2="630" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#FDF8FE"/>
            <stop offset="70%" stop-color="#F5F7FE"/>
            <stop offset="100%" stop-color="#EEF3FE"/>
        </linearGradient>
"""


def build_svg(extra_defs: str, body: str, logo: str) -> str:
    return f"""<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="{W}" height="{H}" viewBox="0 0 {W} {H}" fill="none">
    <defs>
{BASE_DEFS}
{EXTRA_DEFS}
{extra_defs}
    </defs>
{body}
{logo}
</svg>
"""


def export_png(svg_path: Path, png_path: Path) -> None:
    subprocess.run(
        [
            "magick",
            "-background",
            "none",
            "-density",
            "200",
            str(svg_path),
            "-resize",
            f"{W}x{H}!",
            str(png_path),
        ],
        check=True,
        capture_output=True,
    )


def write_index(recipes: list[tuple[str, str, str, str, str | None]]) -> None:
    cells = []
    for slug, title, *_ in recipes:
        cells.append(f"""
        <a class="cell" href="og-codebar-{slug}.png" target="_blank">
            <img src="og-codebar-{slug}.png" alt="{title}" loading="lazy"/>
            <span class="label">{slug.replace('v', 'V').replace('-', ' ')}</span>
            <span class="desc">{title}</span>
        </a>""")

    html = f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>codebar OG variations</title>
    <style>
        * {{ box-sizing: border-box; }}
        body {{ font-family: ui-sans-serif, system-ui, sans-serif; background: #f3f4f6; margin: 0; padding: 2rem; color: #111; }}
        h1 {{ font-size: 1.5rem; margin: 0 0 .5rem; }}
        p {{ color: #4b5563; margin: 0 0 2rem; max-width: 60ch; }}
        .grid {{ display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }}
        .cell {{ display: block; background: #fff; border-radius: 12px; overflow: hidden; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,.08); transition: transform .15s; }}
        .cell:hover {{ transform: translateY(-2px); box-shadow: 0 8px 24px rgba(80,4,114,.12); }}
        .cell img {{ width: 100%; aspect-ratio: 1200/630; object-fit: cover; display: block; }}
        .label {{ display: block; padding: .75rem 1rem 0; font-weight: 600; font-size: .85rem; text-transform: capitalize; }}
        .desc {{ display: block; padding: .25rem 1rem 1rem; font-size: .8rem; color: #6b7280; }}
        code {{ background: #e5e7eb; padding: .1rem .35rem; border-radius: 4px; font-size: .85em; }}
    </style>
</head>
<body>
    <h1>codebar OG image variations</h1>
    <p>25 UI/UX concepts with the same inverted logo. Click to open full size, then tell us your pick (e.g. <code>v11-midnight-bar</code>) to set as the live SEO image.</p>
    <div class="grid">{''.join(cells)}
    </div>
</body>
</html>
"""
    (OUT / "index.html").write_text(html, encoding="utf-8")


def main() -> None:
    parser = argparse.ArgumentParser(description="Generate codebar OG variations")
    parser.add_argument("--export", action="store_true", help="Export PNGs via ImageMagick")
    args = parser.parse_args()

    OUT.mkdir(parents=True, exist_ok=True)

    # v19 mesh uses layered rects
    mesh_layers = """
    <rect width="1200" height="630" fill="url(#mesh-bg)"/>
    <rect width="1200" height="630" fill="url(#mesh-a)"/>
    <rect width="1200" height="630" fill="url(#mesh-b)"/>
    <rect width="1200" height="630" fill="url(#mesh-c)"/>"""

    for slug, title, extra_defs, body, logo_override in RECIPES:
        if slug == "v19-mesh-aurora":
            body = mesh_layers

        logo = logo_override if logo_override else logo_block()
        svg = build_svg(extra_defs, body, logo)
        svg_path = OUT / f"og-codebar-{slug}.svg"
        svg_path.write_text(svg, encoding="utf-8")
        print(f"Wrote {svg_path.name} — {title}")

        if args.export:
            png_path = OUT / f"og-codebar-{slug}.png"
            export_png(svg_path, png_path)
            print(f"  → {png_path.name}")

    write_index(RECIPES)
    print("Wrote index.html")


if __name__ == "__main__":
    main()
