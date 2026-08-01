# Generating news cards

Follow this file for the small square drawing the news index puts next to a list row. It is the
companion to `illustration-news.md`, which covers the 1600×840 hero, and both sit downstream of
`illustration-services.md`, which defines the drawing language. Read those two first; this one is
only what changes when the canvas is 344 units wide.

Not this: an **SEO card** (`illustration-seo-card.md`) is a page's `og:image` and is a wide
1200×630 drawing despite the name. The two are different files for different canvases, and
`public/images/pages/` never contains a `-card.svg`.

Reference implementation: `public/images/news/docuware-7-13-is-here-card.svg`, next to its hero.

## 1. The card is not a crop of the hero

It is a second drawing of the same sentence, with one stage removed and everything roughly twice
as thick in proportion.

A hero is a three-stage narrative across 1494 usable units. The index row renders the card at
**168 px**. Scaled down, three stages and an act chip land at about 50 px each and stop being
shapes — they become grey texture with a purple smudge in the middle. So the card keeps the two
ends of the sentence and drops the middle:

> **hero** loose blocks → *composing* → a page assembled from them
> **card** loose blocks → a page assembled from them

The act survives as a bare symbol on the wash between them where it genuinely helps, and is
otherwise left out entirely — the before/after pair already carries the change. **There is no act
chip on a card**: a 124-unit white square framing a symbol costs a third of the canvas to say
nothing.

The services family made the same split for the same reason; `illustration-services.md` §3 has
the long version.

## 2. The canvas

| | hero | card |
|---|---|---|
| viewBox | `0 0 1600 840` | `-10 -10 344 344` |
| Draw inside | x 53–1547, y 153–687 (the crop) | **0–320 only** |
| Rendered at | 896 px wide, cropped two ways | **168 px**, uncropped |
| Story | left → right, three stages | top → bottom, two stages |
| Arrow | horizontal | **vertical** |
| Arrowheads | exactly two | **exactly one** |
| Glow | exactly one | none |
| Act | a symbol in a chip on the glow | bare on the wash, or absent |
| PNG | yes, 1200×630 | **never** |

**The 10 units of bleed on every side are not decoration.** Offset shadows sit up to 12 units
below and right of their shape, so a composition filling 0–320 gets its bottom shadows sliced at
the canvas edge. The margin also keeps the drawing off the headline beside it in the row.

The card is displayed at its natural ratio with `width="344" height="344"` hard-coded in
`resources/views/components/illustration-row.blade.php` — 1:1, identical to this canvas, so the
row reserves exactly the right space and nothing shifts. If the card ever stops being square,
they have to change with it.

Unlike the hero, the card is **never cropped**. The whole 0–320 box is always visible.

It is also **not in the row**. From `xl` up it floats in the outer margin the 60rem frame leaves,
alternating side down the list and tilting toward the reader on hover. That arrangement is not
written here: `x-illustration-row` owns it, and `/dienstleistungen` renders the same component,
so the two pages cannot drift apart on size, offset or rhythm. Below `xl` there is no margin to
break into and the row is text alone. The 168 px render width and the `xl:pr-14` / `xl:pl-18` on
the text block are derived from each other: the drawing sits 128 px past the text column — 96 px
past the page frame, a lg gutter further out — so it reaches `168 − 128 = 40` px back in and the
padding clears that. Change the width and both numbers move with it — and the services page moves
too.

## 3. No PNG, and no alt text

The card is never an `og:image` — that is the hero's job, and `NewsImage::ogImage()` only ever
looks beside the hero. A `<slug>-card.png` in this directory is a mistake, and the way it gets
made is passing the card to `scripts/render-news-og.sh`, which forces 1200×630 and turns a square
into soup. The script refuses any `-card.svg` or `-square.svg` and says so; the gate fails a card
that has a PNG beside it. If you catch yourself routing around either, stop.

The index renders the card with `alt=""` and `aria-hidden`: it repeats the headline sitting
directly beside it, so to a screen reader it is decoration. There is no `thumb_alt`, and if you
find yourself wanting one, the drawing is carrying information the teaser should be carrying.

Wiring is one line in the **German** file, per `illustration-news.md` §3:

```yaml
thumb: images/news/<slug>-card.svg
```

Without it the row falls back to nothing — `news/card.blade.php` deliberately does not fall back
to the hero, because a 1600×840 band floating beside the frame at a tilt is not a smaller version
of a drawing, it is a different thing.

## 4. Weights, and the detail budget

Stroke weights are `illustration-services.md` §7, card column — 2.4 on outer surfaces, 1.6 on
inner detail, 2.2 on body squiggles, 2 on secondary ones and on connectors. Squiggle words are
the `s*` paths from the shared `<defs>`, 11 units per wave, 6 units of gap between words. The
`w*` paths have no business here.

The card is a fifth the width of the hero, so its lines are proportionally three times heavier.
That is what keeps it readable, and it is also what limits how much can be in it:

- **Three surfaces at most**, and four is already wrong. The hero's four-tile output column is a
  smear here; two tiles and an implied third is the same idea and survives.
- **Nothing thinner than 8 units**, no shape smaller than ~14 units across.
- **One flourish.** A check, a wave, a branch — one, not three.
- **Vary the flourish across the set.** Five cards ending in the same filled accent check circle
  is five cards that look identical in a list, which is the exact failure the whole file is
  avoiding.
- Two or three squiggle words per line, never a full paragraph. At 168 px a `s3` word is 6 px
  wide; a line of six of them is a grey bar.
- **The connector floor is 40 units.** Below that a line is object detail and needs
  `data-detail="…"`; above it, it is running between two objects and needs `class="connector"`.
  `illustration-services.md` §8 has the rules; they apply here unchanged, at card scale.

Check it at size before believing any of it:

```bash
rsvg-convert -w 168 -h 168 public/images/news/<slug>-card.svg -o /tmp/check.png
```

Then look at it next to its neighbours, which is the only test that matters:

```bash
for f in public/images/news/*-card.svg public/images/services/*-card.svg; do
    rsvg-convert -w 168 -h 168 "$f" -o "/tmp/$(basename "$f" .svg).png"
done
```

## 5. The set as it stands

Each card is the two ends of its hero's sentence. Silhouettes differ before contents do — that is
what makes them tell each other apart at thumbnail size.

| Slug | Top — before | Bottom — after | Flourish |
|---|---|---|---|
| `docuware-7-12-is-here` | an envelope with a machine file coming out of it | a table over a small bar chart | rising bars |
| `docuware-7-13-is-here` | a desktop window with title-bar controls | a browser with a two-step chain | a branch |
| `docuware-7-14-is-here` | an inbox tray with cards piled in it | a phone with grouped tasks | push waves |
| `bausteine-styleguide` | three loose blocks, unaligned | a page with a colour row | the colour row |
| `llm-gateway-open-source` | three callers on lanes into one laptop | a queue feeding one chip | a progress bar |

## 6. Before you commit

Run `scripts/check-illustrations.py news`. It covers the viewBox and the 0–320 bounds, the
palette, the absence of a glow and of a PNG, the single arrowhead, the connectors, the file size
and the provenance of every object. What is left for a person:

- [ ] Two stages, one vertical arrow, no glow, no act chip.
- [ ] Three surfaces at most, one flourish, and the flourish is not shared with another card.
- [ ] Rendered at 168 px: every surface still reads and the flourish is recognisable.
- [ ] Rendered next to the other cards — news *and* services, they share a component and a page
      rhythm: distinguishable at a glance, different silhouettes.
- [ ] The top half is black and white. Colour starts below the arrow.
- [ ] `thumb:` set in the **German** file, path relative to `public/`.
- [ ] `php artisan news:import` run, so the column is filled.
- [ ] `git status` shows no orphaned v1 square left behind by the switch.
