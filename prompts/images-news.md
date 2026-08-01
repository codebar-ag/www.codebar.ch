# Generating news hero placeholders (v1)

> **Superseded by `illustration-news.md`.** New articles get a wordless, hand-authored hero in
> the illustration family — three files instead of five, no locale suffix, no embedded font.
> This file stays because `scripts/make-news-hero.py` and the placeholders under
> `public/images/news/placeholders/` still exist and still work; use it to regenerate an existing
> v1 hero, not to make a new article's.

Follow this file whenever a news article needs a hero image and no real photograph or
screenshot exists. The output is a **pair** of files per article — one SVG for the page,
one PNG for social crawlers.

You do not hand-write these. `scripts/make-news-hero.py` lays out the type, because the
whole point of the design is that the text block starts at exactly the same place on every
hero regardless of how long the title is — and SVG has no text wrapping, so that only stays
true if something measures the font. Your job is the wording, the tags and the motif.

## 1. Making one

```bash
scripts/make-news-hero.py docuware-7-14 \
    --locale de \
    --title "DocuWare 7.14 ist da" \
    --tags DMS/ECM \
    --motif dms-ecm
```

Writes `public/images/news/placeholders/docuware-7-14-de.svg` and the matching `.png`, then
prints the front-matter lines to paste.

- **slug** — lower-kebab, describes the *subject* (`docuware-7-14`), not the article.
- **`--locale`** — appended to the file name. **Every localised article needs its own
  hero**, because the title is baked into the graphic: `DocuWare 7.14 ist da` and
  `DocuWare 7.14 is here` cannot share one file. Omit `--locale` only for a hero with no
  language in it.
- **`--title`** — the article's `title:` verbatim. Do not shorten it to make it fit; the
  script steps the type size down instead.
- **`--tags`** — the article's `tags:`, in order, space-separated. Quote any that contain
  spaces. Zero tags is allowed; the row just disappears and nothing else moves.
- **`--motif`** — see §4.

Then wire it into the article's front matter, path relative to `public/`:

```yaml
hero: images/news/placeholders/docuware-7-14-de.svg
hero_alt: Platzhaltergrafik zum DocuWare-Release 7.14
```

`hero_alt` is real alt text and belongs in that locale's language — the script cannot write
it for you. `App\Support\NewsImage` resolves the `images/…` prefix as a local path, and
`NewsImage::ogImage()` swaps `.svg` for `.png` when emitting `og:image`.

An article needs a second graphic on top of this one: the square the news index puts next to
a list row. It is not a crop of the hero — the crop would cut the title in half — but its own
file, without any type. See `prompts/images-news-square.md`.

## 2. The layout

1600×900, and it hangs off one fixed anchor on the left at x=80:

| Element     | Position                        | Notes                                    |
|-------------|---------------------------------|------------------------------------------|
| Accent rule | x 80, y 200, 132×8              | fixed — the top of the block, always      |
| Tag row     | x 80, y 246, pills 50 tall      | grows rightwards; absent if no tags       |
| Title       | x 80, first baseline y 390      | wraps within a 900-wide column            |
| Motif       | 1040, 180 → 1520, 660           | the one thing that changes per topic      |
| Logo        | 1256, 758 → 1520, 814           | codebar logo, bottom-right                |

**Nothing above the title moves.** A one-line title and a four-line title both start at
y=390; the long one simply runs further down. That is the property the whole design exists
to protect, so do not "balance" a short title by nudging it lower.

The title auto-fits down a ladder of `(size, line-height, max lines)`:
`(76, 92, 3) → (64, 78, 4) → (56, 68, 4)`. The first rung whose wrap fits is used, and the
last rung's line count is what keeps the longest title clear of the logo. A title that
still overflows prints a warning — that is a signal to shorten the *article* title, not to
edit the layout.

Margins are 80 left and right. The bottom is 80 × 1.0714, because the PNG export in §5
compresses the vertical axis and that factor makes the margins land visually equal.

**The background is fixed and identical on every hero**, in every language: the rings are
concentric at (150, 880) — bottom-left, so only arcs show — with radii 540 / 400 / 262 / 130,
the dot field is bottom-left behind them, and the band sits at x=900. None of it varies per
article or per locale. Two heroes differ only in their words and their motif; if you find
yourself wanting to vary the background to tell articles apart, change the motif instead.

## 3. Palette and type

Only these values. No new hues, ever.

| Token        | Hex       | Used for                                          |
|--------------|-----------|---------------------------------------------------|
| brand        | `#500472` | rule, tag pills and text, every motif stroke/fill  |
| brand-strong | `#3a0354` | the title                                          |
| wash start   | `#ffffff` | background gradient stop 0                         |
| wash end     | `#f4eef8` | background gradient stop 1                         |
| paper        | `#ffffff` | motif surfaces (cards, documents, panels)          |

The codebar logo is the one exception: it brings its own ink and its own magenta→blue
gradient strip. Those arrive with the asset and are never recoloured to match.

Depth comes from `opacity` on brand, never from another colour: `0.05` (band) · `0.10–0.22`
(rings, tag pills, slots) · `0.16` (motif body lines) · `0.35–0.55` (motif accents) · `1.0`
(type).

One typeface, Poppins 600, embedded as base64 in the SVG. That is not decoration:
`rsvg-convert` cannot fetch a webfont and Poppins is not a system font, so without embedding
the PNG silently falls back to Helvetica. The script handles it.

## 4. Motifs — the only thing that changes per topic

Pick with `--motif`. Current set, all defined at the bottom of `scripts/make-news-hero.py`:

| Name        | Reads as                                              | Use for                    |
|-------------|-------------------------------------------------------|----------------------------|
| `dms-ecm`   | a queue of stacked documents seen edge-on, each with the stage it has reached as a pill, the front one fully drawn, indexed and approved — the stack *is* the workflow | DMS/ECM, DocuWare, workflow, automation |
| `archive`   | documents standing in an open drawer, one pulled up and indexed, magnifier over it — storage rather than processing | archiving, retention, migration |
| `editorial` | a page built from blocks, with a colour row            | Styleguide, Redaktion, anything about the site |
| `documents` | a plain stack of documents, signed off                 | the neutral fallback        |

To add one, write a function that draws inside a **480×480 box at the origin** and register
it in `MOTIFS`. Nothing else in the layout changes. The rules that keep a new motif in the
family:

- White surfaces with a `#500472` stroke at `stroke-opacity` 0.16–0.35, `stroke-width="2"`,
  `rx="14"`–`rx="16"` on the large shapes.
- Content inside a surface is suggested, never literal: rounded bars (`rx` = half the
  height) at `opacity="0.16"` for body text, one bar at `0.55` for a heading.
- Depth by stacking two or three offset copies at rising opacity (0.75 / 0.85 / 1.0), and
  by putting a darker slot *behind* an element so things read as going into it — not by
  shadows or blur.
- At most one flourish that carries meaning — a magnifier, a check, an arrow.
- No gradients inside a motif, no text, no third-party logos or trademarks.
- It must still read at ~300px wide, which is the size in the article card where most
  people will see it.

The whole motif group is tilted `rotate(-4)` about the box centre; leave that to the script.

## 5. Rendering

The script renders the PNG itself via `scripts/render-news-og.sh` (pass `--no-png` to skip).
To re-render every hero after changing the layout:

```bash
scripts/render-news-og.sh
```

Requires `rsvg-convert` (`brew install librsvg`) and `fontTools` (`pip install fonttools`).
The PNG is **1200×630**, which is what `config/seo.php` declares in `og:image:width` /
`og:image:height` for every page. That squashes the 16:9 artwork vertically by 6.7 %; it is
an accepted trade-off and the layout is built around it. Do not change the export size
unless `config/seo.php` changes with it.

## 6. Before you commit

- [ ] One hero per locale, and the file name ends in the right `-de` / `-en`.
- [ ] Rings bottom-left at (150, 880) — the same on this hero as on every other one.
- [ ] Title in the graphic matches the article's `title:` exactly, in that locale.
- [ ] Tags in the graphic match `tags:`, same order.
- [ ] The script printed no warning about line count or tag-row width.
- [ ] SVG and PNG both exist, same basename, PNG is 1200×630.
- [ ] Open the PNG: the title is Poppins, not Helvetica.
- [ ] `hero:` and `hero_alt:` set in **every** locale file, alt text in that language.
- [ ] Viewed at card size (~300px): the motif still reads and the title is legible.
- [ ] `git status` shows no orphaned placeholder left behind by a rename.
