#!/usr/bin/env python3
"""
Generates a news hero placeholder (SVG + og:image PNG) from a title and its tags.

The point of doing this in code rather than by hand is that the text block must start at
exactly the same place on every hero, whatever the title's length. Poppins is measured with
fontTools, the title is wrapped to the column and the type size steps down if it needs to,
but the accent rule, the tag row and the first baseline never move.

    scripts/make-news-hero.py docuware-7-14 \
        --title "DocuWare 7.14 ist da" \
        --tags DMS/ECM \
        --motif dms-ecm

See prompts/images-news.md for the layout spec and for how to add a motif.
"""

from __future__ import annotations

import argparse
import base64
import pathlib
import re
import subprocess
import sys

from fontTools.ttLib import TTFont

ROOT = pathlib.Path(__file__).resolve().parent.parent
FONT = ROOT / 'public/fonts/poppins/poppins-600-normal-latin.woff2'
LOGO = ROOT / 'public/images/logos/codebar-logo-colored.svg'
OUT = ROOT / 'public/images/news/placeholders'

# ---------------------------------------------------------------- canvas & grid

W, H = 1600, 900

LEFT = 80                       # left margin — where every text block begins
COL_W = 900                     # type column width, x 80 → 980

RULE_Y = 200                    # accent rule, the fixed top anchor of the block
TAG_TOP = 246                   # tag row
TAG_H = 50
TAG_GAP = 16
TAG_PAD = 26
TAG_SIZE = 24
TAG_LS = 2

TITLE_TOP = 390                 # first baseline — never moves
# (font size, line height, max lines) — first rung whose wrap fits is used. The line counts
# are what keeps the longest title clear of the logo at the bottom.
TITLE_STEPS = [(76, 92, 3), (64, 78, 4), (56, 68, 4)]
TITLE_LS = -1.5

MOTIF_X, MOTIF_Y, MOTIF_BOX = 1040, 180, 480

LOGO_W = 264
LOGO_H = LOGO_W * 120 / 565
LOGO_X = W - LEFT - LOGO_W
# The PNG export squashes y by 0.7 against x's 0.75, so the bottom margin is scaled up to
# land visually equal to the left/right one.
LOGO_Y = H - LEFT * 1.0714 - LOGO_H

BRAND = '#500472'
BRAND_STRONG = '#3a0354'

# One fixed centre and one fixed set of radii for every hero, in every language. The
# background is not a place to vary things: only the words and the motif change.
RING_CX, RING_CY = 150, 880
RING_RADII = ((540, '0.10'), (400, '0.14'), (262, '0.18'), (130, '0.10'))


# ---------------------------------------------------------------- text metrics

class Metrics:
    def __init__(self, path: pathlib.Path):
        font = TTFont(path)
        self.cmap = font.getBestCmap()
        self.hmtx = font['hmtx']
        self.upem = font['head'].unitsPerEm

    def width(self, text: str, size: float, letter_spacing: float = 0.0) -> float:
        units = 0
        for ch in text:
            glyph = self.cmap.get(ord(ch))
            units += self.hmtx[glyph][0] if glyph else 0
        ink = units / self.upem * size
        return ink + letter_spacing * max(len(text) - 1, 0)


def wrap(metrics: Metrics, text: str, size: float, max_width: float) -> list[str]:
    lines: list[str] = []
    current = ''

    for word in text.split():
        candidate = f'{current} {word}'.strip()
        if current and metrics.width(candidate, size, TITLE_LS) > max_width:
            lines.append(current)
            current = word
        else:
            current = candidate

    if current:
        lines.append(current)

    return lines


def fit_title(metrics: Metrics, title: str) -> tuple[list[str], int, int]:
    for size, line_height, max_lines in TITLE_STEPS:
        lines = wrap(metrics, title, size, COL_W)
        if len(lines) <= max_lines:
            return lines, size, line_height

    size, line_height, max_lines = TITLE_STEPS[-1]
    lines = wrap(metrics, title, size, COL_W)
    print(f'  ! title needs {len(lines)} lines, {max_lines} is the limit — shorten it',
          file=sys.stderr)
    return lines, size, line_height


# ---------------------------------------------------------------- svg fragments

def escape(text: str) -> str:
    return text.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')


def font_face() -> str:
    payload = base64.b64encode(FONT.read_bytes()).decode()
    return f"""            @font-face {{
                font-family: 'Poppins';
                font-style: normal;
                font-weight: 600;
                src: url(data:font/woff2;base64,{payload}) format('woff2');
            }}"""


def background() -> str:
    rings = '\n'.join(
        f'        <circle cx="{RING_CX}" cy="{RING_CY}" r="{r}" stroke-width="2" opacity="{o}"/>'
        for r, o in RING_RADII
    )

    return f"""    <rect width="{W}" height="{H}" fill="url(#wash)"/>

    <!-- dot field, bottom left. Masked to a radial fade: the block now sits in open space,
         where the bare rectangle's edge would read as a seam. -->
    <rect x="-40" y="600" width="560" height="360" fill="url(#dots)" opacity="0.6"
          mask="url(#dot-mask)"/>

    <!-- concentric rings, anchored bottom-left so only arcs show -->
    <g fill="none" stroke="{BRAND}">
{rings}
    </g>

    <!-- diagonal band, passing between the type column and the motif -->
    <rect x="900" y="-40" width="150" height="1200" rx="75" fill="{BRAND}"
          opacity="0.05" transform="rotate(22 900 450)"/>"""


def tag_row(metrics: Metrics, tags: list[str]) -> str:
    if not tags:
        return ''

    parts = ['    <!-- tag row -->', '    <g>']
    x = LEFT
    baseline = TAG_TOP + TAG_H / 2 + TAG_SIZE * 0.35

    for tag in tags:
        label = tag.upper()
        text_w = metrics.width(label, TAG_SIZE, TAG_LS)
        pill_w = text_w + 2 * TAG_PAD

        if x + pill_w > LEFT + COL_W:
            print(f'  ! tag row wider than the column, dropping «{tag}» and the rest',
                  file=sys.stderr)
            break

        parts.append(
            f'        <rect x="{x:.0f}" y="{TAG_TOP}" width="{pill_w:.0f}" height="{TAG_H}"'
            f' rx="{TAG_H / 2:.0f}" fill="{BRAND}" opacity="0.10"/>'
        )
        parts.append(
            f'        <text class="tag" x="{x + TAG_PAD:.0f}" y="{baseline:.0f}">'
            f'{escape(label)}</text>'
        )
        x += pill_w + TAG_GAP

    parts.append('    </g>')
    return '\n'.join(parts)


def title_block(lines: list[str], size: int, line_height: int) -> str:
    spans = '\n'.join(
        f'        <tspan x="{LEFT}" y="{TITLE_TOP + i * line_height}">{escape(line)}</tspan>'
        for i, line in enumerate(lines)
    )
    return f"""    <!-- title: wrapped here because SVG does not wrap text itself -->
    <text class="title">
{spans}
    </text>"""


def logo() -> str:
    inner = LOGO.read_text().split('>', 2)[2].rsplit('</svg>', 1)[0].strip()
    inner = '        ' + inner.replace('\n    ', '\n        ')
    return f"""    <!-- codebar logo, bottom-right: public/images/logos/codebar-logo-colored.svg,
         inlined 1:1 in a nested <svg> that keeps its own viewBox — placed and sized only -->
    <svg x="{LOGO_X:.0f}" y="{LOGO_Y:.2f}" width="{LOGO_W}" height="{LOGO_H:.2f}" viewBox="0 0 565 120">
{inner}
    </svg>"""


# ---------------------------------------------------------------- motifs
#
# Each motif draws inside a 480x480 box at the origin. Add one by writing a function and
# registering it in MOTIFS; nothing else in the layout needs to change.

def motif_dms_ecm() -> str:
    """A queue of documents, each carrying the stage it has reached."""
    return f"""        <!-- the queue, seen edge-on. Opacity rises down the stack, so the eye
             reads it as a progression rather than as three identical rows. -->
        <g>
            <rect x="96" y="34" width="288" height="72" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.18" stroke-width="2"/>
            <rect x="124" y="62" width="86" height="9" rx="4.5" fill="{BRAND}" opacity="0.20"/>
            <rect x="316" y="56" width="44" height="20" rx="10" fill="{BRAND}" opacity="0.22"/>

            <rect x="96" y="118" width="288" height="72" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.24" stroke-width="2"/>
            <rect x="124" y="146" width="106" height="9" rx="4.5" fill="{BRAND}" opacity="0.24"/>
            <rect x="316" y="140" width="44" height="20" rx="10" fill="{BRAND}" opacity="0.34"/>

            <rect x="96" y="202" width="288" height="72" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.30" stroke-width="2"/>
            <rect x="124" y="230" width="70" height="9" rx="4.5" fill="{BRAND}" opacity="0.30"/>
            <rect x="316" y="224" width="44" height="20" rx="10" fill="{BRAND}" opacity="0.5"/>
        </g>

        <!-- the one at the front of the queue: fully drawn, indexed, approved -->
        <g>
            <rect x="76" y="292" width="328" height="150" rx="18" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.42" stroke-width="3"/>
            <rect x="68" y="332" width="14" height="70" rx="7" fill="{BRAND}" opacity="0.55"/>
            <rect x="110" y="322" width="120" height="12" rx="6" fill="{BRAND}" opacity="0.55"/>
            <rect x="110" y="356" width="176" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="110" y="380" width="176" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="110" y="404" width="128" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <circle cx="350" cy="392" r="28" fill="{BRAND}" opacity="0.85"/>
            <path d="M338 392l8 9 15-17" fill="none" stroke="#ffffff" stroke-width="5"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </g>"""


def motif_archive() -> str:
    """Storage rather than processing: documents standing in an open drawer."""
    return f"""        <!-- documents standing in the drawer, front one pulled up and indexed -->
        <g>
            <rect x="96" y="118" width="176" height="230" rx="12" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.16" stroke-width="2" opacity="0.75"
                  transform="rotate(-9 184 233)"/>
            <rect x="214" y="112" width="176" height="236" rx="12" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.20" stroke-width="2" opacity="0.85"
                  transform="rotate(8 302 230)"/>
            <rect x="146" y="58" width="204" height="290" rx="16" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.35" stroke-width="2"/>
            <!-- index tab: the "filed under" of a DMS -->
            <rect x="138" y="100" width="14" height="68" rx="7" fill="{BRAND}" opacity="0.55"/>
            <rect x="176" y="98" width="108" height="12" rx="6" fill="{BRAND}" opacity="0.55"/>
            <rect x="176" y="134" width="146" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="176" y="160" width="146" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="176" y="186" width="114" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="176" y="212" width="146" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <!-- retrieval -->
            <circle cx="298" cy="258" r="26" fill="#ffffff"/>
            <circle cx="298" cy="258" r="26" fill="none" stroke="{BRAND}" stroke-width="5"
                    opacity="0.7"/>
            <rect x="316" y="276" width="34" height="8" rx="4" fill="{BRAND}" opacity="0.7"
                  transform="rotate(45 316 276)"/>
        </g>

        <!-- the slot the documents disappear into, then the drawer front over it -->
        <rect x="82" y="330" width="316" height="22" rx="11" fill="{BRAND}" opacity="0.22"/>
        <g>
            <rect x="66" y="348" width="348" height="94" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.30" stroke-width="2"/>
            <rect x="210" y="388" width="60" height="12" rx="6" fill="{BRAND}" opacity="0.45"/>
        </g>"""


def motif_editorial() -> str:
    """A page made of blocks: styleguide, redaction, anything about the site itself."""
    return f"""        <g>
            <rect x="70" y="70" width="340" height="340" rx="18" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.35" stroke-width="2"/>
            <circle cx="102" cy="94" r="7" fill="{BRAND}" opacity="0.25"/>
            <circle cx="126" cy="94" r="7" fill="{BRAND}" opacity="0.25"/>
            <circle cx="150" cy="94" r="7" fill="{BRAND}" opacity="0.25"/>
            <rect x="70" y="116" width="340" height="2" fill="{BRAND}" opacity="0.14"/>
            <rect x="100" y="146" width="280" height="92" rx="10" fill="{BRAND}" opacity="0.12"/>
            <rect x="100" y="262" width="170" height="12" rx="6" fill="{BRAND}" opacity="0.55"/>
            <rect x="100" y="294" width="280" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="100" y="320" width="280" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="100" y="346" width="212" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <circle cx="112" cy="386" r="12" fill="{BRAND}" opacity="0.50"/>
            <circle cx="146" cy="386" r="12" fill="{BRAND}" opacity="0.30"/>
            <circle cx="180" cy="386" r="12" fill="{BRAND}" opacity="0.15"/>
        </g>"""


def motif_documents() -> str:
    """The neutral fallback: a stack of documents, signed off."""
    return f"""        <g>
            <rect x="70" y="110" width="250" height="330" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.16" stroke-width="2" opacity="0.7"/>
            <rect x="102" y="90" width="250" height="330" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.22" stroke-width="2" opacity="0.85"/>
            <rect x="134" y="70" width="250" height="330" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.35" stroke-width="2"/>
            <rect x="164" y="110" width="120" height="10" rx="5" fill="{BRAND}" opacity="0.55"/>
            <rect x="164" y="142" width="190" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="164" y="168" width="190" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="164" y="194" width="150" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <rect x="164" y="230" width="190" height="8" rx="4" fill="{BRAND}" opacity="0.16"/>
            <circle cx="334" cy="325" r="26" fill="{BRAND}" opacity="0.12"/>
            <path d="M322 325l9 9 17-18" fill="none" stroke="{BRAND}" stroke-width="4"
                  stroke-linecap="round" stroke-linejoin="round" opacity="0.7"/>
        </g>"""


MOTIFS = {
    'dms-ecm': motif_dms_ecm,
    'archive': motif_archive,
    'editorial': motif_editorial,
    'documents': motif_documents,
}


def motif_block(name: str) -> str:
    if name not in MOTIFS:
        raise SystemExit(f'unknown motif «{name}» — available: {", ".join(sorted(MOTIFS))}')

    half = MOTIF_BOX / 2
    return f"""    <!-- motif: {name} -->
    <g transform="translate({MOTIF_X} {MOTIF_Y}) rotate(-4 {half:.0f} {half:.0f})">
{MOTIFS[name]()}
    </g>"""


# ---------------------------------------------------------------- assembly

def build(title: str, tags: list[str], motif: str) -> str:
    metrics = Metrics(FONT)
    lines, size, line_height = fit_title(metrics, title)

    return f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" width="{W}" height="{H}" role="img" aria-hidden="true">
    <title>{escape(title)}</title>
    <!-- Generated by scripts/make-news-hero.py — edit that, not this file. -->
    <defs>
        <style>
{font_face()}
            .tag {{ font: 600 {TAG_SIZE}px 'Poppins', ui-sans-serif, system-ui, sans-serif; letter-spacing: {TAG_LS}px; fill: {BRAND}; }}
            .title {{ font: 600 {size}px 'Poppins', ui-sans-serif, system-ui, sans-serif; letter-spacing: {TITLE_LS}px; fill: {BRAND_STRONG}; }}
        </style>

        <linearGradient id="wash" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#ffffff"/>
            <stop offset="1" stop-color="#f4eef8"/>
        </linearGradient>

        <pattern id="dots" width="34" height="34" patternUnits="userSpaceOnUse">
            <circle cx="3" cy="3" r="3" fill="{BRAND}" opacity="0.14"/>
        </pattern>

        <radialGradient id="dot-fade" gradientUnits="userSpaceOnUse" cx="20" cy="940" r="470">
            <stop offset="0" stop-color="#ffffff"/>
            <stop offset="1" stop-color="#000000"/>
        </radialGradient>

        <mask id="dot-mask">
            <rect x="-40" y="600" width="560" height="360" fill="url(#dot-fade)"/>
        </mask>
    </defs>

{background()}

{motif_block(motif)}

    <!-- accent rule: the fixed anchor the whole text block hangs from -->
    <rect x="{LEFT}" y="{RULE_Y}" width="132" height="8" rx="4" fill="{BRAND}"/>

{tag_row(metrics, tags)}

{title_block(lines, size, line_height)}

{logo()}
</svg>
"""


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__,
                                     formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument('slug', help='file name stem, e.g. docuware-7-14')
    parser.add_argument('--title', required=True, help='the article title, verbatim')
    parser.add_argument('--tags', nargs='*', default=[], help='one or more tags')
    parser.add_argument('--motif', default='documents',
                        help=f'one of: {", ".join(sorted(MOTIFS))}')
    parser.add_argument('--locale', help='de, en, … — appended to the file name. The hero '
                                         'carries the title, so each locale needs its own.')
    parser.add_argument('--no-png', action='store_true', help='skip the og:image render')
    args = parser.parse_args()

    if not re.fullmatch(r'[a-z0-9]+(-[a-z0-9]+)*', args.slug):
        raise SystemExit(f'slug must be lower-kebab, got «{args.slug}»')

    name = f'{args.slug}-{args.locale}' if args.locale else args.slug

    OUT.mkdir(parents=True, exist_ok=True)
    svg = OUT / f'{name}.svg'
    svg.write_text(build(args.title, args.tags, args.motif))
    print(f'wrote {svg.relative_to(ROOT)}')

    if not args.no_png:
        subprocess.run([str(ROOT / 'scripts/render-news-og.sh'), str(svg)], check=True)

    print(f'\nfront matter:\n  hero: images/news/placeholders/{name}.svg'
          f'\n  hero_alt: <describe the graphic in this locale\'s language>')


if __name__ == '__main__':
    main()
