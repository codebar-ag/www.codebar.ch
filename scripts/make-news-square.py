#!/usr/bin/env python3
"""
Generates the square thumbnail that the news index shows next to a list row.

The hero (scripts/make-news-hero.py) is 16:9 and carries the article title, so cropping it
to the square slot cuts the words in half. This draws the same world without any type: wash,
rings, band, one motif — nothing that can be cut.

    scripts/make-news-square.py docuware-7-14 --motif mobile-app

No text means no locale, so one file serves both languages, and no PNG: the square is never
an og:image. See prompts/images-news-square.md for the spec and for how to add a motif.
"""

from __future__ import annotations

import argparse
import pathlib
import re

ROOT = pathlib.Path(__file__).resolve().parent.parent
OUT = ROOT / 'public/images/news/placeholders'

# ---------------------------------------------------------------- canvas & grid

S = 640                         # square canvas
PAD = 80                        # margin on all four sides
BOX = S - 2 * PAD               # 480 — the motif box, same size as the hero's

BRAND = '#500472'

# The background geometry is the hero's, scaled to this canvas: same ring centre relative to
# the corner, same radii ratio, same band angle. The dot field is the one exception — see
# DOT_STEP below.
RING_CX, RING_CY = 60, 626
RING_RADII = ((216, '0.10'), (160, '0.14'), (105, '0.18'), (52, '0.10'))

# The hero's dot spacing scaled down lands at ~3.7px in the 176px card slot, where the field
# turns into grey mush. Enlarged until the dots read as dots at card size.
DOT_STEP, DOT_R = 26, 3


# ---------------------------------------------------------------- svg fragments

def background() -> str:
    rings = '\n'.join(
        f'        <circle cx="{RING_CX}" cy="{RING_CY}" r="{r}" stroke-width="2" opacity="{o}"/>'
        for r, o in RING_RADII
    )

    return f"""    <rect width="{S}" height="{S}" fill="url(#wash)"/>

    <!-- dot field, bottom left, masked to a radial fade so its rectangle has no edge -->
    <rect x="-16" y="427" width="224" height="256" fill="url(#dots)" opacity="0.6"
          mask="url(#dot-mask)"/>

    <!-- concentric rings, anchored bottom-left so only arcs show -->
    <g fill="none" stroke="{BRAND}">
{rings}
    </g>

    <!-- diagonal band, passing behind the motif -->
    <rect x="360" y="-100" width="60" height="840" rx="30" fill="{BRAND}" opacity="0.05"
          transform="rotate(22 360 320)"/>"""


def arrow(x: float, y: float, opacity: str = '0.45') -> str:
    """A solid head pointing right, its tip at (x, y)."""
    return (f'        <path d="M{x} {y} l-16 -10 v20 z" fill="{BRAND}" opacity="{opacity}"/>')


# ---------------------------------------------------------------- motifs
#
# Each motif draws inside a 480x480 box at the origin, the same box the hero uses — but to a
# smaller detail budget, because this is seen at 176px: strokes 3, nothing thinner than 8,
# at most three surfaces. Add one by writing a function and registering it in MOTIFS.

def motif_invoice_analytics() -> str:
    """An invoice and, over it, the same numbers read as a chart."""
    bars = '\n'.join(
        f'            <rect x="{226 + i * 48}" y="{402 - h}" width="34" height="{h}" rx="8"'
        f' fill="{BRAND}" opacity="{o}"/>'
        for i, (h, o) in enumerate(((52, '0.22'), (84, '0.34'), (116, '0.50'), (148, '0.85')))
    )

    return f"""        <!-- the invoice -->
        <g>
            <rect x="46" y="52" width="268" height="352" rx="20" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.35" stroke-width="3"/>
            <rect x="82" y="96" width="120" height="14" rx="7" fill="{BRAND}" opacity="0.55"/>
            <rect x="82" y="142" width="196" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <rect x="82" y="172" width="196" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <rect x="82" y="202" width="148" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
        </g>

        <!-- the chart, laid over the document it came from -->
        <g>
            <rect x="196" y="238" width="238" height="196" rx="20" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.42" stroke-width="3"/>
{bars}
        </g>"""


def motif_workflow_browser() -> str:
    """A process being drawn where it now lives: in the browser."""
    return f"""        <!-- the browser frame -->
        <rect x="30" y="66" width="420" height="348" rx="22" fill="#ffffff"
              stroke="{BRAND}" stroke-opacity="0.35" stroke-width="3"/>
        <circle cx="66" cy="100" r="8" fill="{BRAND}" opacity="0.25"/>
        <circle cx="94" cy="100" r="8" fill="{BRAND}" opacity="0.25"/>
        <circle cx="122" cy="100" r="8" fill="{BRAND}" opacity="0.25"/>
        <rect x="30" y="128" width="420" height="3" fill="{BRAND}" opacity="0.14"/>

        <!-- three steps and the path between them -->
        <g>
            <path d="M196 191 H278" fill="none" stroke="{BRAND}" stroke-opacity="0.45"
                  stroke-width="4" stroke-linecap="round"/>
{arrow(288, 191)}
            <path d="M354 222 V262 H244 V292" fill="none" stroke="{BRAND}" stroke-opacity="0.45"
                  stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M244 302 l-10 -16 h20 z" fill="{BRAND}" opacity="0.45"/>
        </g>

        <g>
            <rect x="56" y="160" width="140" height="62" rx="18" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.30" stroke-width="3"/>
            <rect x="80" y="186" width="76" height="10" rx="5" fill="{BRAND}" opacity="0.30"/>

            <rect x="288" y="160" width="140" height="62" rx="18" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.30" stroke-width="3"/>
            <rect x="312" y="186" width="76" height="10" rx="5" fill="{BRAND}" opacity="0.30"/>

            <rect x="174" y="302" width="140" height="62" rx="18" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.45" stroke-width="3"/>
            <rect x="198" y="328" width="60" height="10" rx="5" fill="{BRAND}" opacity="0.55"/>
            <circle cx="288" cy="333" r="14" fill="{BRAND}" opacity="0.85"/>
            <path d="M281 333l5 5 9 -10" fill="none" stroke="#ffffff" stroke-width="4"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </g>"""


def motif_mobile_app() -> str:
    """Tasks arriving on the phone: a list, one done, and the push that brought it."""
    rows = []
    for i, y in enumerate((112, 192, 272)):
        front = i == 2
        rows.append(f"""            <rect x="166" y="{y}" width="148" height="64" rx="16" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="{'0.42' if front else '0.22'}" stroke-width="{3 if front else 2}"/>
            <rect x="188" y="{y + 27}" width="{62 if front else 54}" height="10" rx="5"
                  fill="{BRAND}" opacity="{'0.55' if front else '0.24'}"/>""")
        if front:
            rows.append(f"""            <circle cx="288" cy="{y + 32}" r="15" fill="{BRAND}" opacity="0.85"/>
            <path d="M281 {y + 32}l5 5 10 -11" fill="none" stroke="#ffffff" stroke-width="4"
                  stroke-linecap="round" stroke-linejoin="round"/>""")
        else:
            rows.append(f'            <circle cx="288" cy="{y + 32}" r="15" fill="{BRAND}"'
                        f' opacity="0.14"/>')

    body = '\n'.join(rows)

    return f"""        <!-- the push, as waves off the top corner -->
        <path d="M360 108 a52 52 0 0 1 0 84" fill="none" stroke="{BRAND}" stroke-opacity="0.45"
              stroke-width="7" stroke-linecap="round"/>
        <path d="M392 84 a92 92 0 0 1 0 132" fill="none" stroke="{BRAND}" stroke-opacity="0.20"
              stroke-width="7" stroke-linecap="round"/>

        <!-- the phone -->
        <g>
            <rect x="140" y="40" width="200" height="400" rx="34" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.42" stroke-width="3"/>
            <rect x="205" y="64" width="70" height="10" rx="5" fill="{BRAND}" opacity="0.25"/>
{body}
        </g>"""


def motif_editorial_blocks() -> str:
    """A page assembled from blocks: styleguide, redaction, anything about the site."""
    return f"""        <g>
            <rect x="44" y="44" width="392" height="392" rx="22" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.35" stroke-width="3"/>
            <circle cx="80" cy="78" r="8" fill="{BRAND}" opacity="0.25"/>
            <circle cx="108" cy="78" r="8" fill="{BRAND}" opacity="0.25"/>
            <circle cx="136" cy="78" r="8" fill="{BRAND}" opacity="0.25"/>
            <rect x="44" y="106" width="392" height="3" fill="{BRAND}" opacity="0.14"/>
            <rect x="80" y="140" width="320" height="104" rx="14" fill="{BRAND}" opacity="0.12"/>
            <rect x="80" y="272" width="190" height="14" rx="7" fill="{BRAND}" opacity="0.55"/>
            <rect x="80" y="308" width="320" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <rect x="80" y="336" width="320" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <rect x="80" y="364" width="230" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <circle cx="92" cy="404" r="13" fill="{BRAND}" opacity="0.50"/>
            <circle cx="130" cy="404" r="13" fill="{BRAND}" opacity="0.30"/>
            <circle cx="168" cy="404" r="13" fill="{BRAND}" opacity="0.15"/>
        </g>"""


def motif_queue_gateway() -> str:
    """Requests waiting their turn: a queue, the gateway working through it, one answer out."""
    waiting = '\n'.join(
        f"""            <rect x="8" y="{y}" width="118" height="54" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="{o}" stroke-width="3"/>
            <rect x="32" y="{y + 22}" width="{w}" height="10" rx="5" fill="{BRAND}" opacity="{o}"/>"""
        for y, o, w in ((112, '0.22', 58), (182, '0.30', 70), (252, '0.40', 48))
    )

    slots = '\n'.join(
        f'            <rect x="210" y="{y}" width="150" height="26" rx="13" fill="{BRAND}"'
        f' opacity="{o}"/>'
        for y, o in ((172, '0.12'), (214, '0.20'), (256, '0.30'))
    )

    return f"""        <!-- the queue: what is waiting, oldest at the front -->
        <g>
{waiting}
        </g>
        <path d="M138 236 H152" fill="none" stroke="{BRAND}" stroke-opacity="0.45"
              stroke-width="4" stroke-linecap="round"/>
{arrow(166, 236)}

        <!-- the gateway: it accepts everything and works through it in order. The filled
             row at the bottom is the one being answered right now. -->
        <g>
            <rect x="182" y="84" width="208" height="312" rx="22" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.42" stroke-width="3"/>
            <rect x="210" y="122" width="100" height="14" rx="7" fill="{BRAND}" opacity="0.55"/>
{slots}
            <rect x="210" y="310" width="150" height="26" rx="13" fill="{BRAND}" opacity="0.55"/>
        </g>

        <!-- and the answer, coming back out -->
        <path d="M398 236 H408" fill="none" stroke="{BRAND}" stroke-opacity="0.45"
              stroke-width="4" stroke-linecap="round"/>
{arrow(422, 236)}
        <g>
            <rect x="430" y="186" width="50" height="100" rx="14" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.45" stroke-width="3"/>
            <rect x="446" y="212" width="20" height="10" rx="5" fill="{BRAND}" opacity="0.55"/>
            <rect x="446" y="236" width="20" height="8" rx="4" fill="{BRAND}" opacity="0.20"/>
            <rect x="446" y="256" width="20" height="8" rx="4" fill="{BRAND}" opacity="0.20"/>
        </g>"""


def motif_documents() -> str:
    """The neutral fallback: a stack of documents, signed off."""
    return f"""        <g>
            <rect x="44" y="104" width="250" height="330" rx="16" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.18" stroke-width="3" opacity="0.7"/>
            <rect x="86" y="80" width="250" height="330" rx="16" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.26" stroke-width="3" opacity="0.85"/>
            <rect x="128" y="56" width="250" height="330" rx="16" fill="#ffffff"
                  stroke="{BRAND}" stroke-opacity="0.40" stroke-width="3"/>
            <rect x="160" y="100" width="130" height="12" rx="6" fill="{BRAND}" opacity="0.55"/>
            <rect x="160" y="140" width="186" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <rect x="160" y="170" width="186" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <rect x="160" y="200" width="142" height="10" rx="5" fill="{BRAND}" opacity="0.16"/>
            <circle cx="322" cy="312" r="30" fill="{BRAND}" opacity="0.85"/>
            <path d="M308 312l10 10 18 -20" fill="none" stroke="#ffffff" stroke-width="5"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </g>"""


MOTIFS = {
    'invoice-analytics': motif_invoice_analytics,
    'workflow-browser': motif_workflow_browser,
    'mobile-app': motif_mobile_app,
    'editorial-blocks': motif_editorial_blocks,
    'queue-gateway': motif_queue_gateway,
    'documents': motif_documents,
}


def motif_block(name: str) -> str:
    if name not in MOTIFS:
        raise SystemExit(f'unknown motif «{name}» — available: {", ".join(sorted(MOTIFS))}')

    half = BOX / 2
    return f"""    <!-- motif: {name} -->
    <g transform="translate({PAD} {PAD}) rotate(-4 {half:.0f} {half:.0f})">
{MOTIFS[name]()}
    </g>"""


# ---------------------------------------------------------------- assembly

def build(motif: str) -> str:
    return f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {S} {S}" width="{S}" height="{S}" role="img" aria-hidden="true">
    <!-- Generated by scripts/make-news-square.py — edit that, not this file. -->
    <defs>
        <linearGradient id="wash" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#ffffff"/>
            <stop offset="1" stop-color="#f4eef8"/>
        </linearGradient>

        <pattern id="dots" width="{DOT_STEP}" height="{DOT_STEP}" patternUnits="userSpaceOnUse">
            <circle cx="{DOT_R}" cy="{DOT_R}" r="{DOT_R}" fill="{BRAND}" opacity="0.14"/>
        </pattern>

        <radialGradient id="dot-fade" gradientUnits="userSpaceOnUse" cx="8" cy="668" r="200">
            <stop offset="0" stop-color="#ffffff"/>
            <stop offset="1" stop-color="#000000"/>
        </radialGradient>

        <mask id="dot-mask">
            <rect x="-16" y="427" width="224" height="256" fill="url(#dot-fade)"/>
        </mask>
    </defs>

{background()}

{motif_block(motif)}
</svg>
"""


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__,
                                     formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument('slug', help='file name stem, e.g. docuware-7-14 — the same stem the '
                                     'hero uses, without the locale')
    parser.add_argument('--motif', default='documents',
                        help=f'one of: {", ".join(sorted(MOTIFS))}')
    args = parser.parse_args()

    if not re.fullmatch(r'[a-z0-9]+(-[a-z0-9]+)*', args.slug):
        raise SystemExit(f'slug must be lower-kebab, got «{args.slug}»')

    OUT.mkdir(parents=True, exist_ok=True)
    svg = OUT / f'{args.slug}-square.svg'
    svg.write_text(build(args.motif))
    print(f'wrote {svg.relative_to(ROOT)}')

    print(f'\nfront matter:\n  thumb: images/news/placeholders/{args.slug}-square.svg')


if __name__ == '__main__':
    main()
