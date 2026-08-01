# The illustration language

Follow this file when a service, product, article or landing section needs a drawing. Send an
idea in one sentence — "loose paper gets scanned and becomes a searchable archive" — and this
document is the rest of the brief.

Reference implementation: `public/images/services/dms-ecm-consulting.svg` (banner) and
`public/images/services/dms-ecm-consulting-card.svg` (card). Open both alongside this document.

This file, `illustration-news.md` and `illustration-news-card.md` describe **one drawing
language on three canvases**. Everything below — the idea grammar, the palette, the `<defs>`,
the parts catalogue, the act vocabulary, the gates — is shared, and the two news files only add
what is specific to an article. Read this one first.

Two older families still exist and are not this: the generated news placeholders in
`images-news.md` / `images-news-square.md` (v1 — a title in Poppins, `make-news-hero.py`, one
file per locale) and the partner drawings in `network.md`. A drawing in this family must not
reuse a `make-news-hero.py` motif, even for the same topic.

## 1. The idea comes first

Every illustration in this family is one sentence with a middle: **something unresolved on the
left, something working on the right, and the act that turns one into the other between them.**
That is the whole grammar. Before drawing anything, write the sentence:

> loose paper → *indexing* → a process that runs itself
> a sketched screenflow → *clicking* → a prototype on desktop and mobile
> three systems that don't talk → *code* → one bus they all sit on
> a task list and a stopwatch kept by hand → *wiring* → a board with the hours booked on it

If the idea has no middle, it does not belong in this family — it is an icon, and `network.md`
covers those.

### Draw the subject, not a document about the subject

**There is no default container.** Nothing needs to sit inside a sheet of paper, a card or a
browser window, and nothing needs an outer frame holding the composition together. Objects float
on the wash and the composition is held by alignment and the arrow alone.

This is the rule that is easiest to break and does the most damage when broken: the first pass at
this family wrapped all four services in an A4 page, and four completely different offerings came
out looking like the same drawing. A paper sheet belongs in the DMS illustration because that
service is *about* paper. It has no business in the ERP one.

Pick objects from the subject's own copy, and let them be their own shape:

| The copy says | Draw | Not |
|---|---|---|
| Odoo, Projektmanagement, Zeiterfassung | a task list, a stopwatch, a kanban board, hours on a card | a document |
| Portale, Schnittstellen, Automatisierungen | a database cylinder, ports, a bus, a code panel, a recurring-job glyph | a document |
| Mockups, klickbare Prototypen, UX | linked wireframe screens, a browser, a phone, a cursor | a document |
| Vom Papier zum papierlosen Büro, Workflows | a paper stack, a magnifier, a workflow chain with a branch | *here* a document is right |

**Vary the silhouette between illustrations in a set.** Four rounded rectangles in a row is a
failure even when the contents differ — a cylinder, a circle, a phone, a laptop, a tilted board
are what make two drawings tell each other apart at thumbnail size. This is checked: see §11.

## 2. Where files go

```
public/images/services/<slug>.svg        1600×840   banner  → og:image and the page hero
public/images/services/<slug>.png        1200×630   rendered from the banner, never hand-edited
public/images/services/<slug>-card.svg    344×344   card    → the index row thumbnail
```

`<slug>` must match the service's `key`/`slug` exactly. The banner is wired in front matter, the
card is found by convention:

```yaml
# database/files/services/de_CH/<slug>.md — German only, the importer reads shared
# metadata from de_CH and ignores it in en_CH
image: images/services/<slug>.svg
```

`resources/views/app/services/index.blade.php` resolves `images/services/<slug>-card.svg` with
`file_exists()` and shows nothing if it is missing. There is no registration step anywhere.

`resources/views/layouts/_partials/_seo.blade.php` sees the `.svg` in `image:` and swaps it for
the same-named `.png` via `App\Support\NewsImage::ogImage()`. **That is why the PNG must exist
and must sit next to the SVG**: without it, `og:image` falls back to `images/seo/og-codebar.png`.

Every drawing also needs an entry in `public/images/illustrations.json` — that is §11, and it is
not optional.

## 3. Two canvases, one language

| | banner | card |
|---|---|---|
| viewBox | `0 0 1600 840` | `-10 -10 344 344` |
| Draw inside | the whole canvas | **0–320 only** |
| Rendered at | full width, and 1200×630 as a social card | **168 px**, and only from `xl` up |
| Story | left → right, three stages | top → bottom, two stages |
| Arrow | horizontal, one | vertical, one |
| Glow | exactly one | none |
| Act | in a chip on the glow | bare, no chip — a card has no room to frame it |

**168 px is the one number.** `illustration-row.blade.php` renders the card at `w-42`, and the
`width`/`height` attributes on that `<img>` are `344`/`344` so the row reserves the right space
before the SVG loads. Earlier drafts of this file quoted 112, 128 and 144 in three different
places; all three were wrong, and a stale ratio in that component is a layout shift on every page
load. If the card ever stops being square, those attributes change with it.

**The card viewBox has 10 units of bleed on every side and you draw inside 0–320 regardless.**
That margin is not decoration: offset shadows sit up to 12 units below and right of their shape,
so a composition that fills 0–320 has its bottom shadows sliced off at the canvas edge. The bleed
also keeps the drawing from touching the text beside it in the row.

`illustration-row` is shared with the news lists — the same size, offset and rhythm on
`/dienstleistungen` and on `/aktuelles`, because they used to be set in two files and drifted
apart. Two numbers in it are derived from the drawing's rendered width: it is 168 px and sits
128 px past the text column, so it reaches 40 px back in and `xl:pr-14` / `xl:pl-18` clear
exactly that. Resizing the drawing without moving those two leaves the text either colliding with
it or floating a long way off — on both pages at once.

The card earns its place by using the empty outer margin the 60rem frame leaves on a wide screen.
Below `xl` there is no such margin, and every attempt to fit the drawing inside the text column
cost more in reading width than the drawing returned. Hence: `xl` and up, or not at all. A card
is still worth drawing for a subject that will only ever be read on a phone, because the
**banner** is what `og:image` uses and that is unaffected.

1600×840 is exactly 1200:630, so the PNG is a clean 0.75 downscale with no distortion. Both
canvases in this family are authored at that ratio.

The card is **not a crop of the banner**. A three-stage narrative is illegible at 168 px, so the
card tells the same story with one stage removed and everything ~2× thicker in proportion.

The card sits **after** the text in the row, both on screen and in the DOM: the copy starts at the
page's own left margin so all rows share one text edge, and a decorative image that repeats the
title has no business coming first for a screen reader.

## 4. No words. None.

Body copy is a hand-drawn squiggle. Code and JSON are coloured token bars. Labels are shapes
inside a coloured pill. There is **no `<text>` element in this family at all**, and that is
load-bearing twice over:

- `rsvg-convert` cannot fetch a webfont and Poppins is not a system font, so any real type
  silently renders as Helvetica in the PNG. A wordless drawing sidesteps it and keeps each file
  under 10 KB.
- **One file serves both locales.** v1 news heroes needed a `-de` and an `-en` because the title
  was baked in. Nothing here is language-bound, so there is no locale suffix — ever.

`<title>` is not `<text>`: it renders no glyphs, it is what a screen reader announces, and every
drawing in this family has one. Write it in German, describing the change.

Squiggle rules: ragged right edge, never justified; word lengths mixed 1–4 waves within a line;
line length varies between lines; the last line of a block is short. A block of identical-length
lines reads as a barcode, not as text.

## 5. Palette — the whole of it

```
ink        #09090b   every stroke, every squiggle        (the codebar logo's ink)
brand      #500472   metadata chips, secondary accents
accent     #C026D3 → #2563EB   the logo gradient, at 135°
paper      #ffffff   every surface
wash       #ffffff → #f4eef8   background
```

No fourth hue, ever. Depth comes from `opacity` on ink and brand: `0.10–0.12` (dot field, row
dividers) · `0.18–0.20` (chip fills) · `0.3` (shadows) · `0.45` (connectors, secondary
squiggles) · `0.6` (chip squiggles) · `1.0` (everything structural).

The reference images this family is modelled on use `#37D7FA → #FF8DF2 → #FF8705`. **Those
colours are not ours** and must not appear. The construction is borrowed; the palette is
codebar's.

Colour is also how the story is told: the left half of a banner is black and white —
unstructured material has no colour. The accent only appears at the moment of transformation and
in the structured result. Do not colour the input.

### `url(#accent)` needs the element to have area

`#accent` is a `linearGradient` with the default `gradientUnits="objectBoundingBox"`, so it is
resolved against the bounding box of **the element being painted** — not its parent `<g>`, and
not the path's stroke. An element whose box is zero in either axis has no gradient to resolve,
and librsvg drops the paint entirely: the shape silently does not render.

The way this bites is a single straight line:

```xml
<g stroke="url(#accent)" stroke-width="5">
    <path d="M2 24h56"/>          <!-- INVISIBLE — bbox is 56×0 -->
    <path d="M2 24h56M2 40h56"/>  <!-- fine — bbox is 56×16 -->
    <path d="M70 15l40 30"/>      <!-- fine — diagonal -->
</g>
```

A purely horizontal or purely vertical path in its own element is invisible in the accent. Rects,
circles and diagonals are always safe; so is a path that contains two parallel segments, because
together they have area. When you want one accent rule, merge it into a sibling subpath — an
`M…` command appended to a path that already has area costs nothing and fixes it. The `window`
and `queue` glyphs in §9 are written as single merged paths for exactly this reason; do not
"tidy" them back apart.

Nothing about this applies to `#09090b` or `#500472`, which are flat colours. It is a gradient
problem only, and it is silent — you will not see a warning, you will see a missing line.

## 6. The shared `<defs>`

Paste this whole block. `w*` is banner scale (one wave = 22 units), `s*` is card scale (11
units); a banner needs only `w*`, a card only `s*`. Add longer words by repeating the alternating
`s7.5 6 11 0` / `s7.5-6 11 0` pair.

```xml
<defs>
    <linearGradient id="accent" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0" stop-color="#C026D3"/>
        <stop offset="1" stop-color="#2563EB"/>
    </linearGradient>
    <linearGradient id="wash" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0" stop-color="#ffffff"/>
        <stop offset="1" stop-color="#f4eef8"/>
    </linearGradient>
    <filter id="glow" x="-100%" y="-100%" width="300%" height="300%">
        <feGaussianBlur stdDeviation="42"/>
    </filter>
    <pattern id="dots" width="18" height="18" patternUnits="userSpaceOnUse">
        <circle cx="2" cy="2" r="1.8" fill="#09090b"/>
    </pattern>

    <path id="w1" d="M0 0c3.5-6 7.5-6 11 0s7.5 6 11 0"/>
    <path id="w2" d="M0 0c3.5-6 7.5-6 11 0s7.5 6 11 0s7.5-6 11 0s7.5 6 11 0"/>
    <path id="w3" d="M0 0c3.5-6 7.5-6 11 0s7.5 6 11 0s7.5-6 11 0s7.5 6 11 0s7.5-6 11 0s7.5 6 11 0"/>
    <path id="w4" d="M0 0c3.5-6 7.5-6 11 0s7.5 6 11 0s7.5-6 11 0s7.5 6 11 0s7.5-6 11 0s7.5 6 11 0s7.5-6 11 0s7.5 6 11 0"/>

    <path id="s1" d="M0 0c1.75-3 3.75-3 5.5 0s3.75 3 5.5 0"/>
    <path id="s2" d="M0 0c1.75-3 3.75-3 5.5 0s3.75 3 5.5 0s3.75-3 5.5 0s3.75 3 5.5 0"/>
    <path id="s3" d="M0 0c1.75-3 3.75-3 5.5 0s3.75 3 5.5 0s3.75-3 5.5 0s3.75 3 5.5 0s3.75-3 5.5 0s3.75 3 5.5 0"/>
</defs>
```

Word widths: `w1` 22 · `w2` 44 · `w3` 66 · `w4` 88 — and half those for `s*`. Leave a 14-unit gap
between words on a banner, 6 on a card, so `<use href="#w3" x="44">` is followed by `x="124"`.

## 7. Stroke weights

A stroke is a percentage of the canvas, not a number. The card is a fifth the width of the
banner, so its lines are proportionally three times heavier — that is what keeps it readable at
168 px.

| | banner | card |
|---|---|---|
| Outer surfaces | 3 | 2.4 |
| Inner detail, small glyphs | 2.5 | 1.6 |
| Row dividers (at `opacity 0.12`) | 2 | — |
| Connectors (at `opacity 0.45`) | 3 | 2 |
| Arrow shaft | 4 | 2.2 |
| Squiggle, heading | 5.5 | — |
| Squiggle, body | 4.5 | 2.2 |
| Squiggle, secondary (at `opacity 0.45`) | 3.5 | 2 |

Everything is `stroke-linecap="round"` on squiggles and `stroke-linejoin="round"` on surfaces.
`rx` is 18–26 on large surfaces, 6–9 on small glyphs, and half the height on a pill.

## 8. Arrows, connectors and the tangle

This section exists because two drawings shipped that a reader could not follow, and both
failures were about lines rather than objects.

**An arrowhead means one stage became the next. Nothing else in a drawing may wear one.** A
banner has exactly two arrowheads — before → act, act → after. A card has exactly one. That is
checked; see §11.

The failure it prevents: `dms-ecm-consulting-card.svg` used to draw the stage arrow into a
workflow chain and then draw the chain's own step-to-step links with the *same* arrow. Four
arrowheads down one column, all identical, and the before/after split the whole composition
rests on simply disappeared. A step in a chain is not a transformation. It is a connector.

**Connectors are orthogonal polylines.** Horizontal and vertical segments only, `stroke-width`
per §7 at `opacity 0.45`, no arrowhead. Use one for a relationship that is not a transformation:
a workflow branch, an exception lane, a screen flow, a request routed somewhere.

**A stacked chain needs no links at all.** Tiles stacked in a column with an even gap already
read as a sequence — that is what stacking means. Drawing a connector between each of them is
three extra lines saying what the spacing has already said, and at 168 px they merge with the
stage arrow above into one column of marks. `dms-ecm-consulting` went through both wrong answers
before this one: arrowheads between the tiles, then thin connectors between the tiles, then
nothing. Nothing is right.

Link only what the stacking cannot show — the branch off to the side, the exception lane, the one
step that loops back. A single connector leaving the column is legible precisely because it is
the only one.

**No two connectors may cross.** `llm-gateway-open-source.svg` used to draw four callers reaching
one point with four curved lines that crossed each other twice on the way. It read as a knot. The
fix is not neater curves, it is a different construction: run each lane straight out to a shared
spine and let the spine carry them in. Lanes meeting a spine is a T-junction, which is a join and
not a crossing. If two connectors genuinely have to reach past each other, the composition is
wrong — move an object instead.

**A curve is only allowed where the curve is the subject** — push waves leaving a phone, the fill
line on a cylinder. Mark those `data-curve="…"` and the gate will let them through and list them
for review. A curve used as routing is never right.

**Every line has to declare what it is.** A line running between two objects carries
`class="connector"` or `class="arrow"`; a line inside one object carries `data-detail="table
rules"`, `data-detail="chip pins"`, `data-detail="laptop hinge"`. There is no geometric test that
tells those apart — a cylinder's fill line and a lane joining two tiles are the same arc — so the
drawing says which, and an undeclared line long enough to matter is itself a gate failure. You
cannot get past the check by not labelling.

**A lane diagram does not tilt.** The input side normally sits at `rotate(-4)` (§9, Tilt), but a
tilted orthogonal lane is a contradiction and looks like a mistake. Where the input is a set of
objects wired to something, leave the whole group upright.

## 9. Parts catalogue

Every part below is optional. Take what the idea needs and leave the rest out; a drawing that
uses all of them is a drawing with no subject. The only parts every banner has are the arrow, the
glow and the act.

### Structure

**Shadow — never blurred.** The same path again, filled ink at `0.3`, drawn *behind*, offset ~4 %
of the shape's width. Never `feDropShadow` on a flat surface; the blur is reserved for the glow.

```xml
<path d="…" fill="#09090b" stroke="none" opacity="0.3" transform="translate(16 16)"/>
<path d="…" fill="#ffffff"/>
```

**Arrow** — strictly horizontal on a banner, vertical on a card. Ink, shaft `stroke-width 4`,
head a filled triangle `l-20-12v24Z` at the tip (`l-9-14h18Z` on a card). No curves, no dashes.
One per gap between stages, and §8 says how many gaps there are.

**Connector** — §8. Right-angled ink polyline, `opacity 0.45`, no head. Optionally a filled `r 6`
dot at a free end.

**Bus** — the accent version of a connector, `stroke-width 4`: parallel lines running into a
shared spine with a solid `r 16` hub node on it. Reads as "these are wired together now".

**Glow** — the accent gradient, heavily blurred, behind the arrow at the transformation point.
Exactly one per banner and none on a card; it is the only soft element in the family.

```xml
<ellipse cx="780" cy="428" rx="118" ry="146" fill="url(#accent)" filter="url(#glow)" opacity="0.55"/>
```

**Act chip** — on a banner, a 124×124 `rx 26` white square on the glow at `x 706 y 358`, holding
one symbol from §10 in the accent. Stroke the symbol where it is a line drawing and fill it where
the shape is solid — a hollow cursor reads as nothing. One symbol, no more. **On a card the chip
is dropped** and the symbol sits bare on the wash, or is left out entirely.

**Tilt** — the input side sits at `rotate(-4)` about its own centre. The output never tilts: that
asymmetry is what makes one side read as loose material and the other as a system. Exception in
§8: a lane diagram stays upright.

**Dot field** — `url(#dots)` at `opacity 0.1` in a 300-wide column at each edge. Never behind the
focal object.

### Objects — pick by subject, not by habit

**Sheet of paper** — for when the subject really is paper. A rounded rect with the top-right
corner cut, plus the fold as a small white triangle on the same stroke. 380×520 on a banner:

```xml
<path d="M18 0h282l80 76v426a18 18 0 0 1-18 18H18A18 18 0 0 1 0 502V18A18 18 0 0 1 18 0Z" fill="#ffffff"/>
<path d="M300 0l80 76h-64a16 16 0 0 1-16-16Z" fill="#ffffff"/>
```

**Stack** — two or three plain rounded rects offset ~17 units up and right behind the front
object, all white, all the same stroke. Never more than four; beyond that it reads as noise.

**Screen** — a rounded rect with a nav bar, a placeholder well crossed by two diagonals at
`opacity 0.3`, and two lines of squiggle. Several joined by connectors is a screen flow.

**Browser** — a screen plus a chrome bar closed by a full-width rule, three `r 8` ink dots at
`0.25`, and an address pill in ink at `0.08`. Pair it with a **phone** — same construction,
`rx 26`, a notch pill at the top — overlapping its bottom-right corner when the point is "works
on both".

**Laptop** — a lid and a splayed deck, joined by a hinge rule. The shape that says *one specific
machine*, as opposed to a browser (a surface) or a panel (an application):

```xml
<path id="lid"  d="M12 0h116a12 12 0 0 1 12 12v84H0V12A12 12 0 0 1 12 0Z"/>
<path id="deck" d="M0 0h156l8 18a6 6 0 0 1-6 8H-2a6 6 0 0 1-6-8Z"/>
```

**Database cylinder** — the shape that instantly is not a document:

```xml
<path d="M130 200v110a100 26 0 0 0 200 0V200" fill="#ffffff"/>
<ellipse cx="230" cy="200" rx="100" ry="26" fill="#ffffff"/>
<path d="M130 244a100 26 0 0 0 200 0" data-detail="cylinder fill line" opacity="0.4"/>
```

**Port** — a short ink line at `opacity 0.5` ending in a hollow `r 10` circle. On the input side
it dangles unconnected; on the output side it joins the bus.

**Spreadsheet** — a rounded rect with a header rule, then evenly spaced ink rules at `0.4` both
ways and short ink bars at `0.3` in some cells but not all. Empty cells are what make it read as
manual.

**Task list** — a panel of `rx 5` checkboxes with a squiggle beside each, one of them ticked.
Cheaper and more specific than a spreadsheet when the subject is work rather than data.

**Stopwatch / clock** — a circle with a `rx 6` crown, four tick marks at `0.4`, and two hands at
different lengths. The single fastest way to say "Zeiterfassung".

**Kanban board** — a panel whose content is three `#500472 0.05` columns, each with a squiggle
header and one to three white `rx 9` task cards. Give one card an accent duration bar and a small
accent clock to say that hours are booked against the work, not tracked beside it.

**Workflow chain** — three or four `rx 16` step tiles stacked in a column with an even gap and
**nothing between them** (§8). One step carries an accent check (approved), one an accent
circular arrow (automatic). A chain without a branch is a list; the one connector you draw goes
sideways, to an exception tile, and it reads because it is the only line in the object.

**Archive drawer** — a `rx 6` rect split by a rule with a small `rx 3` handle pill in each half.

**Inbox tray** — the container shape, for "this piled up and nobody dealt with it". It is harder
than it looks: a rounded U, thick posts with a rail, and a back panel with a lip were all tried
on `docuware-7-14-is-here` and all three read as bars stacked on a bench. What works is a
**trapezoid with a separate front wall** — a shape wider at the top than the bottom, filled at a
low ink opacity so it sits behind, with the front wall drawn as its own band *in front of* the
pile. The pile is three or four plain white `rx 12` cards, each offset ~16 units up and sideways
so the stack leans, rising well above the tray's opening. The front wall crossing the cards is
the whole trick — without it the cards read as sitting on the tray rather than in it.

**Panel** — where a subject genuinely is an application: a large white `rx 18`–`20` rounded rect
with a header strip closed by a full-width rule and a 34×34 gradient square in it. An accent ring
6 units outside, `stroke-width 3`, marks it as the finished thing. **Use it for the output at
most, never as a wrapper around the whole composition.**

**Code panel** — a panel with a gutter rule and, beside it, short rounded bars in `#C026D3`,
`#2563EB` and ink at `0.55`, indented in blocks. It must read as syntax highlighting without
being text.

**Metadata chip** — a `rx`-half-height pill, `#500472` at `0.12` or the accent at `0.18`, holding
a `w2` squiggle in brand at `0.6`. Two or three per column, not one per row — a chip on every row
reads as a table, not as metadata.

**Result badge** — a 300×66 `rx 33` pill filled with the accent, straddling the output's top
edge, with a white `rx 11` icon square at its left and white squiggles beside it. The most
saturated element in the family: at most one per banner, never on a card, and only where the
output genuinely is a *result* rather than a running system.

## 10. The act vocabulary

The act is a **verb the subject performs**, and it is the single most load-bearing choice in the
drawing — it sits dead centre, in the only saturated colour, and a reader looks at it first.
`docuware-7-14-is-here` shipped with `transfer`, an arrow leaving a box, for a release whose
change was that a phone now tells you when an approval arrives. It read as "open in new window".
The act was `bell` all along.

The test, before drawing: **write the act as a sentence with the subject in it.** "DocuWare 7.14
*notifies* you" is true and quotable. "DocuWare 7.14 *relocates* you" is not. If the sentence is
awkward, the act is wrong — not the wording.

Each symbol is drawn in a 60×60 box. On a banner the chip sits at `x 706 y 358` and the box is
centred in it with `<g class="act" transform="translate(738 390)">`. **That `class="act"` is
required** — it is how the arrowhead gate knows a triangle inside the `queue` or `funnel` glyph
is part of the symbol rather than a third stage arrow. One symbol per drawing, no more.

| Act | Reads as | Use when the change is |
|---|---|---|
| `magnifier` | a lens | something unfindable becomes searchable |
| `braces` | `{ }` | it is written as code |
| `nodes` | a joined graph | separate things are wired together |
| `cursor` | a pointer | it becomes something you can operate |
| `schedule` | a circular double arrow | it now runs on its own, repeatedly |
| `funnel` | wide in, narrow out | unstructured material is extracted into fields |
| `window` | a browser chrome | a thing that was installed now runs in a tab |
| `transfer` | an arrow leaving a box | it moved house — another org, tenant or surface |
| `grid` | three blocks placed, one still being set | a whole is composed from parts |
| `queue` | a list, one item leaving | order and waiting are the point |
| `shield` | a check inside a shield | it is now protected |
| `bell` | a notification | someone now gets told |
| `board` | columns with cards | work becomes visible and assignable |

```xml
<!-- funnel -->
<path d="M4 6h52L34 32v20l-8 6V32Z" fill="url(#accent)"/>

<!-- window — frame and chrome rule are ONE path on purpose, see §5 -->
<path d="M10 8h40a8 8 0 0 1 8 8v28a8 8 0 0 1-8 8H10a8 8 0 0 1-8-8V16a8 8 0 0 1 8-8ZM2 24h56"
      stroke="url(#accent)" stroke-width="5" fill="none" stroke-linejoin="round"/>
<g fill="url(#accent)"><circle cx="12" cy="16" r="3"/><circle cx="23" cy="16" r="3"/><circle cx="34" cy="16" r="3"/></g>

<!-- transfer -->
<g stroke="url(#accent)" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path d="M30 10H12a6 6 0 0 0-6 6v30a6 6 0 0 0 6 6h30a6 6 0 0 0 6-6V28"/>
    <path d="M32 26L54 4"/><path d="M38 4h16v16"/>
</g>

<!-- grid -->
<g fill="url(#accent)">
    <rect x="4" y="4" width="22" height="22" rx="5"/><rect x="34" y="4" width="22" height="22" rx="5"/>
    <rect x="4" y="34" width="22" height="22" rx="5"/>
</g>
<rect x="34" y="34" width="22" height="22" rx="5" stroke="url(#accent)" stroke-width="5" fill="none"/>

<!-- queue — the connector is merged into the bars path on purpose, see §5 -->
<path d="M4 12h28M4 30h28M4 48h28M40 30h10"
      stroke="url(#accent)" stroke-width="6" stroke-linecap="round" fill="none"/>
<path d="M58 30l-12-7v14Z" fill="url(#accent)"/>

<!-- shield -->
<path d="M30 3l24 9v18c0 15-10 24-24 28C16 54 6 45 6 30V12Z" fill="url(#accent)"/>
<path d="M21 30l6 7 13-15" stroke="#ffffff" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>

<!-- bell -->
<path d="M30 6a16 16 0 0 1 16 16v13l6 8H8l6-8V22A16 16 0 0 1 30 6Z" fill="url(#accent)"/>
<path d="M23 47a7 7 0 0 0 14 0Z" fill="url(#accent)"/>

<!-- board -->
<g stroke="url(#accent)" stroke-width="5" fill="none" stroke-linejoin="round">
    <path d="M4 6h52v48H4ZM22 6v48M40 6v48"/>
</g>
<g fill="url(#accent)"><rect x="8" y="14" width="10" height="12" rx="3"/><rect x="26" y="14" width="10" height="20" rx="3"/></g>
```

Adding a fourteenth: it must be a **verb**, it must be legible as a silhouette at 40 px, and it
must not already be in the list under another name. `bell`, `shield` and `board` are as close to
nouns as this family goes, and each earns it because the change it names genuinely is "you get
told" / "it is protected" / "the work is on a board". Add it to `ACTS` in
`scripts/check-illustrations.py` in the same commit, or the gate rejects it.

**No two drawings in the same family share an act.** That is checked, and it is the cheapest
possible guard against three DocuWare releases becoming three variations on one picture.

## 11. Quality gates

Three things kept going wrong that no checklist caught, because a checklist is read by whoever
already believes the drawing is finished: the objects had nothing to do with the copy, the lines
tangled, and the act named a verb the subject never performs. So they are gates now.

```bash
scripts/check-illustrations.py                 # everything
scripts/check-illustrations.py news            # one family
scripts/check-illustrations.py public/images/services/open-source-erp.svg
scripts/check-illustrations.py --list-acts
```

### Gate 1 — the drawing is about its subject

Every drawing has an entry in `public/images/illustrations.json`, and every object in it cites a
phrase from the subject's own copy:

```json
"services/open-source-erp": {
  "sentence": "a task list and a stopwatch kept beside each other by hand → wiring them together → one board where the work is planned and the hours are booked against it",
  "act": "nodes",
  "actSource": "Als Odoo-Partner bieten wir dir Schritt für Schritt an, was sich bei uns bewährt",
  "objects": {
    "task list": "die Einführung von Projektmanagement und Zeiterfassung",
    "stopwatch": "Projektmanagement und Zeiterfassung",
    "kanban board": "Odoo setzen wir seit Kurzem selbst als ERP ein",
    "booked hours": "ohne Lizenz-Lock-in"
  }
}
```

Each quote has to appear **verbatim** in the source markdown — for a service that is
`database/files/services/{de_CH,en_CH}/<slug>.md`, for an article both locale files. Markdown
emphasis, curly quotes, en dashes and line wrapping are folded before comparing; wording is not.
If you cannot find a phrase for an object, the object does not belong in the drawing. That rule
is what removed the spreadsheet from `open-source-erp`: nothing on that page has ever mentioned
one.

Minimum three objects, and the sentence needs both arrows.

### Gate 2 — it is concrete

Object names are checked against a list of words that name geometry instead of a thing — `dot`,
`node`, `hub`, `blob`, `shape`, `circle`, `starburst`, `spark`, `cloud`, `widget`, `symbol`,
`gradient`, `accent`. If the honest name for what you drew is "a dot with a starburst", the
drawing is decoration. Name what a reader would name, then check the drawing actually shows that.

### Gate 3 — the lines can be followed

- exactly two arrowheads on a banner, one on a card, outside the `class="act"` glyph
- no connector contains a curve command, unless it carries `data-curve="…"`
- no two connectors cross
- every stroked line over 150 units (40 on a card) carries `class`, or `data-detail`

### Gate 4 — the invariants

No `<text>`; only the five palette values; the right viewBox; exactly one glow on a banner and
none on a card; no `feDropShadow` and no filter but `#glow`; at least one offset shadow; at least
one squiggle; `stroke-linejoin="round"` present; under 12 KB; the PNG exists at exactly 1200×630
beside a banner and does not exist beside a card; and, for a news hero, no ink outside the crop
(measured, see `illustration-news.md` §3).

### Gate 5 — the set

Held against each other: no two banners in a family share an act, and no two open with the same
object. The index prints these directly under one another, so the thing a reader actually sees is
the set.

### What the gates cannot do

They check that every **declared** object is in the copy. They cannot see an object you drew and
did not declare. They check that lines do not cross; they cannot tell you a composition is dull.
So one human pass stays, and it is short:

- Look at the banner at 760 px and the card at 168 px, next to their neighbours.
- Name every shape out loud. Anything you cannot name in one noun comes out.
- Read the sentence in the manifest, then look at the drawing. If you had to explain a shape to
  make the sentence true, the shape is wrong.
- **Professional, but still a sketch.** Hand-drawn squiggles, flat offset shadows, round joins,
  one soft element in the whole drawing and it is the glow. Nothing beveled, nothing with a
  second gradient, no drop shadow filter, no icon lifted from a set. If it looks like stock
  vector art, something in this list was skipped.

## 12. Rendering

```bash
scripts/render-news-og.sh public/images/services/<slug>.svg
```

That script is named for news but takes explicit files and renders each at exactly 1200×630,
which is what `config/seo.php` declares in `og:image:width` / `og:image:height` for every page.
Do not add a second script and do not change the size without changing that config.

**Never pass a `-card.svg` to it.** Cards are square; forced into 1200×630 they come out stretched
into unrecognisable soup, and the wrong PNG next to the wrong SVG is exactly what
`NewsImage::ogImage()` would then serve to Twitter. The script refuses, and so does the gate.

After a new or changed banner:

```bash
php artisan services:import
```

## 13. Before you commit

Run `scripts/check-illustrations.py` — it covers the palette, the canvas, the glow, the shadows,
the arrowheads, the connectors, the file size, the PNG, the crop, the act vocabulary, the set,
and every object's provenance. What is left for a person:

- [ ] The sentence has a middle, and the drawing shows the middle.
- [ ] **No wrapper.** Nothing exists only to contain the rest of it.
- [ ] Held against the rest of the set: different silhouettes, different objects. If two of them
      are "a rounded rectangle with squiggles in it", one of them is wrong.
- [ ] Every shape can be named in one noun, and the manifest names all of them.
- [ ] The act, written as a sentence with the subject in it, is true.
- [ ] The input half is black and white. Colour starts at the glow.
- [ ] Rendered at 168 px, the card still tells its story.
- [ ] Still a sketch, not stock vector art — §11, Gate 5.
- [ ] `image:` set in the **German** file only, pointing at the `.svg`.
