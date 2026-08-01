# Generating news illustrations

Follow this file when a news article needs a hero and no real photograph or screenshot exists.

Reference implementation: `public/images/news/docuware-7-13-is-here.svg` (hero) and
`public/images/news/docuware-7-13-is-here-card.svg` (card). Open both alongside this document.

## 0. Read `illustration-services.md` first

The news hero is the **same drawing language** as a service illustration on a different canvas.
All of this is shared and is not repeated here:

| Shared, defined in `illustration-services.md` | § |
|---|---|
| The idea grammar — input → act → output, and *draw the subject, not a document about it* | 1 |
| No words. None. No `<text>` element, ever | 4 |
| The palette — five values, and colour starts at the glow | 5 |
| The `<defs>` block, `w*` / `s*` squiggle words | 6 |
| Stroke weights | 7 |
| Arrows, connectors and the tangle — two arrowheads, orthogonal lanes, nothing crossing | 8 |
| The parts catalogue | 9 |
| The act vocabulary, and how to test that an act is the right one | 10 |
| The quality gates and `scripts/check-illustrations.py` | 11 |

What this file adds is everything specific to an *article* rather than an offering: what the idea
has to be when the subject is a release (§1), how to read the source (§2), where the files go
(§3), and the one hard constraint the services canvas does not have — **the news hero is
displayed cropped** (§4).

A fourth family sits beside these: `illustration-seo-card.md` covers the `og:image` of a *page* —
drawn at 1200×630 rather than 1600×840, no crop, no card, and never displayed on the site itself. A page card must not reuse
a hero's composition either; `/aktuelles` prints five of them directly under its own card.

Two older families still exist and are not this. `images-news.md` and `images-news-square.md`
describe **v1**: the article title baked in as Poppins, `scripts/make-news-hero.py`, five files
per article, one hero per locale. Those placeholders are still on disk under
`public/images/news/placeholders/` and no article front matter points at them any more; do not
mix their motifs into a v2 drawing, and do not port a v2 composition back into the script.

## 1. The idea, when the subject is an article

The grammar is `illustration-services.md` §1 — something unresolved on the left, something
working on the right, the act between them. What changes is how you find the sentence.

**An article already says what it is, in words, twice.** The `<h1>` sits directly above the hero
and the teaser sits directly above that; in the index the headline sits beside the card. A hero
that re-states the headline is decoration. The hero's job is the thing the headline cannot do in
eight words: show **what was true before and what is true after**.

So the sentence is always a *change*:

> the workflow designer was an installed application → *it moved into the browser* → a designer that runs in a tab
> agents, processes and people all called one machine until it ran out of memory → *queueing* → a gateway that stores every request and feeds the model one at a time
> content blocks lie around loose → *composing* → one page assembled from them, in order

**Pick one change, not all four.** A release note has four sections; a hero has one sentence.
Choose the change a reader would notice on the Monday after upgrading — the new app on the phone,
not the third bullet under "Sicherheit und Konfiguration". Everything else in the release is what
the article is for.

**A drawing is per article, not per topic.** Three DocuWare releases are all tagged `DMS/ECM` and
the index prints them directly under one another; three variations on a document stack make the
list look broken. Read what the release actually changed. The set gate in
`illustration-services.md` §11 enforces the two cheapest halves of this — no shared act, no
shared opening object — but it cannot tell you that two different objects are boring in the same
way.

And per `illustration-services.md` §1: a news hero must not reuse a service composition either.
`dms-ecm-consulting` already owns "paper → magnifier → workflow chain". A DocuWare release cannot
have it.

## 2. The source is both locale files

An article exists twice:

```
database/files/news/de_CH/<date>-<key>.md
database/files/news/en_CH/<date>-<key>.md
```

Read **both** before drawing. They are not translations of each other line for line — the English
file is written, not converted, and it regularly names the change more plainly than the German
one does, or vice versa. Since the drawing carries no words it has to be true in both languages
at once, and the fastest way to find the sentence is often the phrasing that survived into both.

This is also the corpus the provenance gate checks against: an object may cite a phrase from
either file, and the check resolves against the union of the two. `illustration-services.md` §11
has the format. A news entry additionally requires:

- both locale files to exist, and
- `hero_alt` to differ between them. Identical `hero_alt` in `de_CH` and `en_CH` means the German
  was pasted into the English file, and it fails.

## 3. Where files go

```
public/images/news/<slug>.svg        1600×840   hero    → the article page and og:image
public/images/news/<slug>.png        1200×630   rendered from the hero, never hand-edited
public/images/news/<slug>-card.svg    344×344   card    → the index row → illustration-news-card.md
```

Three files per article, **no locale suffix** — that is the whole dividend of
`illustration-services.md` §4.

`<slug>` is the article's `key:`, not its `slug:`. `slug:` is localised (`docuware-7-14-ist-da`
vs `docuware-7-14-is-here`) and would give one article two names; `key:` is the same string in
every locale, which is exactly the property a locale-free file needs.

**Not in `public/images/news/placeholders/`.** That directory belongs to v1, and
`scripts/render-news-og.sh` with no arguments globs it — a v2 hero dropped in there gets
re-rendered by an unrelated invocation, and a v2 *card* dropped in there gets stretched to
1200×630 and written next to itself as a PNG. Keep the two generations in separate directories.

Wire it into the front matter:

```yaml
# database/files/news/de_CH/<date>-<key>.md
hero: images/news/<slug>.svg
hero_alt: Der Workflow Designer zieht aus einer installierten Anwendung in den Browser
thumb: images/news/<slug>-card.svg
```

**No colon in an unquoted `hero_alt:`.** Symfony's YAML parser rejects
`hero_alt: Illustration: …` with "A colon cannot be used in an unquoted mapping value" and
`news:import` refuses the whole file. Prefixing alt text with "Illustration:" is the obvious way
to walk into this and it is redundant anyway — a screen reader already announces the element as
an image. Write the sentence plainly, or quote the string if a colon is genuinely needed.

`ImportNewsCommand::store()` reads `hero:` and `thumb:` from **`$primary`, which is the de_CH
document only** — the English file's values are parsed and thrown away. Set them in German.
Mirroring them into `en_CH` is harmless and the existing articles do it, but nothing reads them.

`hero_alt:` **is** per locale and you still have to write it, in that language. The drawing
carries no words, but on the article page it is content sitting under a caption slot, not
decoration — unlike the card, which the index renders `alt=""` on purpose. Describe the change
the drawing shows, not the shapes: "Gleichzeitige Anfragen laufen neu über eine Warteschlange",
not "Zylinder und Pfeile".

`App\Support\NewsImage::ogImage()` swaps the `.svg` for the same-named `.png` when emitting
`og:image`. **That is why the PNG must exist and sit next to the SVG**; without it `og:image`
falls back to `images/seo/og-codebar.png`.

## 4. The crop — the one thing services does not have to think about

A service banner is only ever the whole 1600×840. A news hero is not. Two components render it,
and both crop:

```blade
{{-- app/news/show.blade.php — the article hero --}}
class="w-full aspect-[3/1] object-cover"

{{-- components/news/lead.blade.php — the index lead --}}
class="mb-6 hidden aspect-[16/9] w-full object-cover sm:block lg:aspect-[3/1]"
```

Against a 1600×840 source:

| Where | Ratio | Keeps | Throws away |
|---|---|---|---|
| `og:image` (1200×630) | 1.905 | **everything** — 1600×840 is exactly 1200:630 | nothing |
| Article hero, every width | 3 : 1 | y 153 → 687 | 153 units off the top **and** the bottom |
| Index lead, `sm`–`lg` | 16 : 9 | x 53 → 1547 | 53 off each side |
| Index lead, below `sm` | — | nothing, the image is `hidden` | — |

Intersect them:

```
        0    53                                              1547  1600
      0 ┌─────┬────────────────────────────────────────────────┬─────┐
        │     │            ▲ 153 cut on every article          │     │
    153 ├ ─ ─ ┼────────────────────────────────────────────────┼ ─ ─ ┤
        │dots │                                                │dots │
        │shdw │             the story lives here               │shdw │
        │wash │                 1494 × 534                     │wash │
    687 ├ ─ ─ ┼────────────────────────────────────────────────┼ ─ ─ ┤
        │     │            ▼ 153 cut on every article          │     │
    840 └─────┴────────────────────────────────────────────────┴─────┘
              ▲ cut on a tablet lead          cut on a tablet lead ▲
```

**Everything that carries meaning lives inside x 53–1547, y 153–687.** The gate allows
x 58–1542, y 158–682 — a few units in, for the round join on a 3-wide stroke.

Older drafts of this file documented a `4/3` phone crop and a safe area of x 240–1360. There is
no `4/3` any more: below `sm` the lead image is hidden outright rather than squeezed, so the
horizontal budget is nearly three times what it was. The vertical budget is unchanged and is the
one that actually bites.

That leaves a 2.8:1 band. Two consequences:

- **The composition is wider than it is tall, always.** A service banner can run an output column
  from y 120 to y 660; a hero cannot. Objects here are ~500 tall at most, and the three stages sit
  side by side rather than stacking.
- **The glow may spill.** It is a 42-unit blur behind the arrow; it is meant to bleed past the
  safe area and does not need to be inside it. The measurement below thresholds it out.

Check it with a number, not an eyeball — `scripts/check-illustrations.py` does it for you on
every run. It renders at 2× and finds the bounding box of everything darker than 43 % grey, which
walks straight past the 240-grey dot field and the glow and finds only real ink:

```
FAIL public/images/news/<slug>.svg
       crop: ink spans [246, 140, 1352, 682], safe area is [58, 158, 1542, 682] — a crop would cut it
```

`rsvg-convert` scales rather than crops, so rendering the file and looking at it cannot show you
what the browser does. Trust the number.

## 5. The set as it stands

| Slug | Left — before | Act | Right — after |
|---|---|---|---|
| `docuware-7-12-is-here` | an e-invoice arriving as a machine file — angle brackets and token bars, nothing a person reads | `funnel` | a table with the positions in rows, and the same numbers again as a chart |
| `docuware-7-13-is-here` | the workflow designer as an installed desktop window, next to an install glyph | `window` | a browser running the designer: steps wired by connectors, one exception branch |
| `docuware-7-14-is-here` | an inbox tray with approvals piled in it, untouched, a clock beside it | `bell` | a phone with tasks grouped by process, one approved, push waves off the corner |
| `bausteine-styleguide` | content blocks lying around loose, each a different shape, none aligned | `grid` | one page assembled from them in order, with a colour row |
| `llm-gateway-open-source` | four callers on orthogonal lanes into one laptop whose memory is already full | `queue` | a gateway that persists the queue and feeds the model one at a time, forkable |

Check a sixth against these before drawing it. Different silhouettes: only 7.12 uses a chart,
only 7.13 uses a browser, only 7.14 uses a phone, only the styleguide uses a page, only the
gateway uses a laptop and a cylinder. Note also that no two *inputs* repeat — a file, a window, a
tray, loose blocks, a set of lanes — which matters more than the outputs, because the input is
the left half and the left half is what a reader sees first. If a new one is "a rounded rectangle
with squiggles in it", it is not finished.

## 6. Rendering and import

```bash
scripts/render-news-og.sh public/images/news/<slug>.svg
php artisan news:import
```

Bare, `render-news-og.sh` re-renders every hero of both generations —
`public/images/news/*.svg` and `public/images/news/placeholders/*.svg` — and skips anything
ending in `-card.svg` or `-square.svg` with a message. That guard exists because a square forced
into 1200×630 comes out as stretched soup, and the resulting `<slug>-card.png` sitting next to
`<slug>-card.svg` is exactly the file `NewsImage::ogImage()` would then hand a social crawler. Do
not work around it.

## 7. Before you commit

Run `scripts/check-illustrations.py news`. It covers the canvas, the palette, the glow, the
shadows, the arrowheads, the connectors, the crop measurement, the file size, the PNG, the act
vocabulary, the set, and every object's provenance against both locale files. What is left for a
person is `illustration-services.md` §13, plus:

- [ ] The drawing shows the *change*, not the headline restated.
- [ ] The change is the one a reader notices on the Monday after upgrading.
- [ ] Held against the other articles in the index: different silhouettes, different objects.
- [ ] Held against `public/images/services/`: it is not a service composition with new labels.
- [ ] `hero:` and `thumb:` set in the **German** file; `hero_alt:` written in **both**, each in
      its own language, describing the change.
- [ ] `php artisan news:import` run.
- [ ] `git status` shows no orphaned v1 placeholder left behind by the switch.
