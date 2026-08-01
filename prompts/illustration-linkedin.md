# The LinkedIn post image

Follow this file when an article is going out as a **LinkedIn post** and needs a picture that works
without the page around it.

Reference implementation: `public/images/social/linkedin/llm-gateway-open-source.svg`. Open it
alongside this document.

## 0. Read the other two first

| Shared, defined elsewhere | Where |
|---|---|
| The idea grammar, the palette, the `<defs>`, stroke weights, arrows and connectors, the parts catalogue, the act vocabulary | `illustration-services.md` §1, §5–§10 |
| How to find the sentence when the subject is an article, and reading both locale files | `illustration-news.md` §1–§2 |

This file adds the canvas, the title block, and the one rule it breaks.

## 1. What this is, and what it is not

**A link share already has a picture.** Paste a codebar URL into LinkedIn and it pulls `og:image` —
the hero's 1200×630 PNG, via `App\Support\NewsImage::ogImage()`. That path is done and needs nothing
from this file.

This file is for the other thing: an image **uploaded to the post**. The ratio is the same 1.91:1,
but an unfurl prints the headline in text under the thumbnail and an upload does not — so this one
carries the headline itself. It also travels alone: reshared, screenshotted, seen with the post text
collapsed behind "…mehr". That is the whole difference, and it is why this is a family and not a
copy of the hero.

**One asset per post, and the post text does the rest.** No teaser, no URL, no call to action, no
date, no tag pills on the picture. LinkedIn puts all of that directly under it in selectable text;
printing it into a PNG makes it unsearchable and costs the headline a size.

## 2. The canvas

1200×627, `viewBox="0 0 1200 627"`. Margin is **70** on every side. Two zones, and the boundary
between them moves with the headline:

| Zone | Where | Holds |
|---|---|---|
| Motif band | x 70–1130, y 40–305 | the drawing, re-composed per §4 |
| Title block | x 70–1130, y 340–580 | accent rule, headline, logo |

No crop to design around — LinkedIn shows a 1.91:1 upload whole. What it does do is scale it to
about **552 px wide** on desktop, so one unit renders at 0.46 px. The 1600-wide hero on the site
gets 0.475. Stroke weights therefore transfer from the hero almost unchanged; §4 has the table.

The dot field runs in the **margins only** — two 70-wide columns at x 0 and x 1130, full height,
`opacity 0.1`. On this canvas there is no room for the hero's 300-wide columns without putting dots
behind the drawing, which `illustration-services.md` §9 forbids.

## 3. The rule this family breaks

`illustration-services.md` §4 forbids `<text>` outright. Here it is the point, so the cost that rule
was written to avoid comes back:

**The font has to travel inside the file.** `rsvg-convert` cannot fetch a webfont and Poppins is not
a system font, so a bare `font-family: 'Poppins'` silently renders as Helvetica in the PNG. Embed
the woff2 as base64 in a `<style>` block:

```xml
<style>
    @font-face {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 600;
        src: url(data:font/woff2;base64,…) format('woff2');
    }
    .title { font: 600 60px 'Poppins', ui-sans-serif, system-ui, sans-serif; letter-spacing: -0.5px; fill: #09090b; }
</style>
```

The source is `resources/fonts/poppins/poppins-600-normal-latin.woff2` — 8 KB, so the payload is
about 11 KB of base64 and no subsetting is needed. One weight, 600, one class. (`scripts/make-news-hero.py`
still points at `public/fonts/poppins/`, which no longer exists. That script is v1 and is stale;
take the path from here.)

**German only.** The headline is language-bound, so strictly this file is per locale — but we only
ever post in German, so only the German one gets drawn, and there is no locale suffix on the name.
Fall back to the English title only where an article has no German one. If that ever changes, the
second file is `<key>-en.svg` and nothing else about this document moves.

The palette holds otherwise: ink for the headline, the accent gradient on the rule and inside the
drawing, `#500472` nowhere — with the tag pills gone, this family has no use for it.

## 4. The motif is re-composed, not cropped

Take the article's approved hero and rebuild it into the band. **It is the same sentence with the
same objects and the same act** — a LinkedIn asset is never a place to invent a second idea for one
article — but the hero has 486 units of height for its drawing and this band has 265.

The story still runs **left → right**, exactly as the hero does:

```
x   70–500    before   the unresolved thing
x  524–576    arrow    one arrowhead
x  596–714    act      the chip, on the glow
x  730–782    arrow    the second arrowhead
x  790–1124   after    the working system
```

Two arrowheads, still — §8 of `illustration-services.md` applies unchanged, and so does the ban on
crossing connectors. All three stages share one optical axis; in the reference that is y 168.

**Drop one object rather than shrink three.** The gateway hero carries a queue panel, a model chip
*and* a database cylinder; this one keeps the first two and lets the panel say what the cylinder
said. Three surfaces in the output half is the ceiling. If everything feels essential, the hero is
doing too much and the LinkedIn asset is the wrong place to discover that.

Strokes, against the hero:

| | hero | here |
|---|---|---|
| Outer surfaces | 3 | 3.2 |
| Inner detail, small glyphs | 2.5 | 2.6 |
| Connectors (`opacity 0.55`) | 3.5 | 3.2 |
| Arrow shaft | 4 | 4.2 |
| Squiggle, heading / body / secondary | 5.5 / 4.5 / 3.5 | 4.5 / 4 / 2.4 |

Scaled groups need the division done by hand: `<g transform="scale(0.86)">` with
`stroke-width="2.9"` renders at 2.5, not 2.9.

## 5. The title block

**Bottom-anchored, left-aligned.** The last baseline is fixed and the block grows *upward* — a
two-line headline and a three-line headline end on the same line, and the motif band gives up the
difference. Anchoring the top instead makes every article's picture end somewhere else, which is
exactly what a feed makes visible.

| Element | Position | Notes |
|---|---|---|
| Accent rule | x 70, y 340, 100×7, `rx 3.5` | `url(#accent)`, the block's hook |
| Headline | x 70, baselines 566 / 492 / 418 | 60 px, leading 74, column **850** wide |
| Logo | x 940, y 525, 190×40.35 | `codebar-logo-colored.svg`, inlined as a nested `<svg>` |

The logo sits bottom-right, its centre on the last line's x-height. The headline column stops at
x 920 so the two never meet — that is what makes 850 the column and not the full 1060.

Two rungs, and the first one that fits wins:

| Lines | Size / leading | Baselines | Band ends | Rule at |
|---|---|---|---|---|
| up to 3 | 60 / 74 | 566, 492, 418 | 305 | 340 |
| 4 | 52 / 64 | 566, 502, 438, 374 | 270 | 300 |

**Five lines is not a rung.** A headline that needs one is too long for a feed — shorten it for the
post, or drop the subtitle half of a colon headline.

SVG does not wrap text, so the lines are wrapped by hand into `<tspan>`s and every one carries its
own `x="70"`. Measure rather than guess:

```python
from fontTools.ttLib import TTFont
f = TTFont('resources/fonts/poppins/poppins-600-normal-latin.woff2')
cmap, hmtx, upem = f.getBestCmap(), f['hmtx'], f['head'].unitsPerEm
width = lambda s, px, ls=0.0: sum(hmtx[cmap[ord(c)]][0] / upem * px
                                  for c in s if ord(c) in cmap) + ls * max(len(s) - 1, 0)
```

Break where the sentence breaks — after a colon, before a verb — not wherever 850 units run out.
«Lokale LLMs betreiben: / begrenzte Ressourcen / orchestrieren» reads; the same three lines broken
mechanically do not.

## 6. Where files go

```
public/images/social/linkedin/<key>.svg    1200×627   authored
public/images/social/linkedin/<key>.png    1200×627   rendered, never hand-edited
```

`<key>` is the article's `key:` — the same string in every locale, and the name the hero and the card
already use.

**Not in `public/images/news/`.** `scripts/render-news-og.sh` with no arguments globs that directory
and re-renders everything in it at 1200×630. Three units of stretch would not be visible, but the
resulting PNG would sit next to the hero's own and there would be two candidates for one `og:image`.
The separate directory is the guard.

Nothing in the app reads these files. There is no front matter to wire and no import to run: the
asset is uploaded to LinkedIn by hand, and the repository is where it is kept so the next post can
start from it.

## 7. Rendering

```bash
rsvg-convert -w 1200 -h 627 \
  public/images/social/linkedin/<key>.svg \
  -o public/images/social/linkedin/<key>.png
```

Not `render-news-og.sh` — that script is hard-wired to 1200×**630** because `config/seo.php` declares
those numbers for `og:image`, and it is right to be. Upload the **PNG**; LinkedIn does not accept SVG.

Then look at it at feed size before you believe it:

```bash
rsvg-convert -w 552 -h 288 <file>.svg -o /tmp/check.png
```

## 8. The gate does not cover this family

`scripts/check-illustrations.py` would fail every file here on Gate 4 — `<text>` exists, the canvas
is not 1600×840 — and it does not look in this directory. That is deliberate, and it moves the
burden onto the hero: **only draw a LinkedIn asset for an article whose hero already passes.** The
provenance, the act and the set were checked there; this file inherits them and must not add an
object the hero does not have.

## 9. Before you post

- [ ] Derived from a hero that passes `scripts/check-illustrations.py news`, same objects, same act.
- [ ] The story runs left → right on one axis, exactly two arrowheads, no crossing connectors.
- [ ] At most three surfaces in the output half; something from the hero was dropped, not shrunk.
- [ ] Headline bottom-anchored: last baseline on 566, lines stacked upward, wrapped at the sense.
- [ ] Headline column stops at x 920; the logo is bottom-right and nothing reaches into it.
- [ ] Nothing in the picture the post text should be carrying — no teaser, no URL, no tags, no date.
- [ ] Poppins 600 embedded as base64 — check the **PNG**, not the SVG: Helvetica in the render means
      the `@font-face` did not resolve.
- [ ] Rendered at 552 px: the headline reads, the motif still tells its story.
- [ ] German, unless the article has no German title.
- [ ] The PNG is 1200×627 and sits next to the SVG, and neither is in `public/images/news/`.
