# Generating SEO cards

Follow this file when a **page** needs the image that travels with its link — the picture a
crawler, a chat client or a social feed shows next to the title when somebody shares
`/dienstleistungen` or `/ki/llm`. Services and articles already have one, because their banner
doubles as `og:image`. Pages have nothing: every one of them currently falls back to
`images/seo/og-codebar.png` — no page YAML carries an `image:` at all, so nineteen pages in two
languages share one picture.

Reference implementation: `public/images/pages/start.index.svg` — the whole set is drawn and
wired; §6 lists it.

## 0. Read `illustration-services.md` first

An SEO card is the **same drawing language on a smaller canvas**. All of this is shared and is
not repeated here:

| Shared, defined in `illustration-services.md` | § |
|---|---|
| The idea grammar — input → act → output, and *draw the subject, not a document about it* | 1 |
| No words. None. No `<text>` element, ever | 4 |
| The palette — five values, and colour starts at the glow | 5 |
| The `<defs>` block, `w*` squiggle words | 6 |
| Stroke weights, banner column | 7 |
| Arrows, connectors and the tangle — two arrowheads, orthogonal lanes, nothing crossing | 8 |
| The parts catalogue | 9 |
| The act vocabulary, and how to test that an act is the right one | 10 |
| The quality gates and `scripts/check-illustrations.py` | 11 |

What this file adds is everything specific to a *page*: what the sentence is when the subject is
not a change and not an offering (§2), where the files go and what setting `image:` switches on
(§3), how small the drawing is actually seen (§4), which pages get one and which deliberately do
not (§5), the set (§6), the six acts this family added to the vocabulary (§7), and what the gate
checks that it does not check anywhere else (§8).

### The name is a trap

"Card" in this repo already means the 344×344 index thumbnail — `<slug>-card.svg`,
`illustration-news-card.md`. An **SEO card is not one of those.** It is a wide 1200×630 drawing
rendered to a PNG of exactly that size, and those two are the only files in this family.

**There is no `public/images/pages/<key>-card.svg`, ever.** Pages are never printed under one
another in a list, so there is no row to sit beside and nothing for a square to do. If you find
yourself drawing one, you are drawing for a component that does not exist.

## 1. The card travels alone

Every other drawing in this family is seen **with its page around it**. A news hero sits under
the `<h1>` and over the teaser; a service banner sits on the page it illustrates. Both can lean on
the words above them.

An SEO card is seen in a WhatsApp bubble, a Slack unfurl, a LinkedIn post — with the page title
beside it, the domain under it, and nothing else. Two consequences run through the whole file:

- **It has to be true on its own.** Nothing in the drawing may depend on a heading the viewer has
  not read.
- **It is never seen by anyone on the site.** No page renders `$page->image`; only
  `_seo.blade.php`, `SitemapBuilder` and `SchemaGraph` read it. So nothing on the website will
  ever tell you the drawing is wrong, missing or stretched. §9 is how you actually look at it.

## 2. The sentence, when the subject is a page

The grammar is `illustration-services.md` §1 — something unresolved, the act, something working.
What changes is where the left half comes from.

A service page describes an offering, and an article describes a change that happened. **A section
page describes neither: it is a promise.** So the left half of the sentence is not a worse version
of the product — it is **the reader's situation before they had us**:

> a laptop on the kitchen table at home → *moving in* → a desk in a room with a team in it
> customer documents leaving the building for a provider's rack → *protecting* → the same
> documents on a machine in our own basement
> a request handed down through three layers before it reaches whoever builds it → *meeting* →
> one table, with the person who writes the code at it

Write the sentence with the page's own promise in it, then check it against the page's `title:`
and `description:` in `database/files/pages/<key>.yaml`. Those two strings are the page's
elevator pitch, they were written carefully, and they are what the crawler prints **directly next
to the drawing**. If the sentence and the description are saying different things, one of them is
wrong, and it is usually the sentence.

**The drawing must not restate the description.** They are seen together, in one card. The
description already says "vier Bereiche, ein Weg"; the drawing's job is to show what four areas
and one path *look* like.

**And it must not repeat a drawing the page links to.** `/dienstleistungen` links to four service
banners, `/ki/llm` sits next to the `llm-gateway-open-source` hero, `/aktuelles` prints five news
heroes underneath itself. A page card that reuses one of those compositions makes the whole
section look like one repeated picture. The set gate (§8) cannot see this across families —
`illustration-services.md` §13 asks a person to, and here it is not optional.

## 3. Where the files go, and what `image:` switches on

```
public/images/pages/<key>.svg    1200×630   the drawing, authored at og:image size
public/images/pages/<key>.png    1200×630   rendered from it 1:1, never hand-edited — this is
                                            the file a crawler actually fetches
```

**Two files, one size, and that size is 1200×630.** The other families author at 1600×840 and
downscale by 0.75 into the PNG, because their banner is also displayed on the site at full width.
A page card is never displayed anywhere, so there is nothing to downscale for: it is drawn at the
exact pixel size `config/seo.php` declares, and the render is 1:1. No third file, no second
canvas, no card.

`<key>` is the page's `key:`, identical to the name of its YAML file — `about-us.index`,
`ai.llm.analytics.index`. Dots and all: one name, one lookup rule, no mapping table anywhere.

Wiring is one line, in the page's YAML — there is one file per page and it is not localised, so
unlike news and services there is no "German file only" rule to get wrong:

```yaml
# database/files/pages/<key>.yaml
image: images/pages/<key>.svg
```

```bash
php artisan pages:import
php artisan responsecache:clear
```

**The cache clear is not optional.** The meta tags live inside the cached HTML, so until the
response cache is cleared every crawler keeps getting the old `og:image` — including none at all.

`_seo.blade.php` hands the value to `App\Support\NewsImage::crawlable()`, which sees the `.svg`
and swaps it for the same-named `.png`. **That is why the PNG must exist and must sit next to the
SVG**: without it, `crawlable()` returns null and `og:image` silently falls back to
`images/seo/og-codebar.png` — the exact state we are trying to leave, and the page looks
completely fine while it happens.

Setting `image:` also switches on two things that are not `og:image`, both of which already
behave this way for news heroes and neither of which swaps in the PNG:

- `SitemapBuilder::addItem()` prints the value verbatim as an `<image:loc>` — so
  `images/pages/<key>.svg` appears in the sitemap as written.
- `SchemaGraph` emits it as `primaryImageOfPage` through `NewsImage::src()`, which resolves the
  **SVG**. Google does not accept SVG for image metadata, so that node is decorative today.

Neither is a reason not to draw the card, and neither is fixed by hand-editing the drawing.

**No alt text exists and none is wanted.** `_seo.blade.php` hard-codes
`og:image:alt` to the app name for every page, and there is no `hero_alt` on a page. The SVG's
`<title>` is the only description this drawing will ever carry — write it in German, describing
the promise, per `illustration-services.md` §4.

## 4. The canvas is easy; the size is not

| | news hero | service banner | **SEO card** |
|---|---|---|---|
| viewBox | `0 0 1600 840` | `0 0 1600 840` | **`0 0 1200 630`** |
| PNG | 1200×630, a 0.75 downscale | 1200×630, a 0.75 downscale | **1200×630, 1:1** |
| Cropped when displayed | 3:1 and 16:9 | never | **never** |
| Safe area | x 53–1547, y 153–687 | the canvas | **the canvas, less 18 units** |
| Card sibling | yes | yes | **none** |
| Displayed on the site | yes | yes | **no** |
| Seen at | 896 px | full width | **~500 px** |

1200×630 is what `summary_large_image`, Open Graph and LinkedIn all crop to, so nothing is cut.
This is the one canvas in the family with no crop rule: `illustration-news.md` §4 does not apply.

The fixed points, all of them derived from the 1600-unit banner at 0.75 and then rounded to
something a person can type:

```xml
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 630" width="1200" height="630" role="img">
<title>…</title>
<!-- wash, then a 150-wide dot field at each edge -->
<ellipse cx="600" cy="315" rx="92" ry="116" fill="url(#accent)" filter="url(#glow)" opacity="0.55"/>
<!-- input   x  70– 470, centred on y 315, at rotate(-4) -->
<!-- arrows  x 482– 534 and x 674– 726, both on y 315 -->
<rect x="546" y="261" width="108" height="108" rx="22" fill="#ffffff" stroke="#09090b" stroke-width="3"/>
<g class="act" transform="translate(570 285)">…</g>
<!-- output  x 740–1130, centred on y 315, upright -->
</svg>
```

The blur in `#glow` is `stdDeviation="32"`, not 42 — the filter is in user units and the canvas
shrank with it. Shadow offsets are `translate(12 12)`, not 16.

**Stroke weights stay in the banner column of `illustration-services.md` §7, unscaled.** In a
1200-unit canvas they come out a third heavier than on a banner, and that is deliberate: this
drawing is read at 500 px in a feed and a banner is read at full browser width. The same applies
to the `w*` squiggle words — 22 units per wave, unchanged, which makes them proportionally bigger
and therefore fewer per line.

What replaces it is the **size**. A link preview is roughly 500 px wide in a feed and smaller in
a chat list. That is 31 % of the scale a banner is drawn for, and it turns into arithmetic:

> Three stages across 500 px is **~160 px per stage**. A news card — the whole drawing, two
> stages, three surfaces, one flourish — renders at 168 px.

**So each stage of an SEO card gets about as much screen as an entire news card, and inherits its
discipline** (`illustration-news-card.md` §4): three surfaces at most per stage, nothing thinner
than 8 units at banner scale, one flourish per stage, two or three squiggle words per line and
never a paragraph. Stroke weights stay in the banner column of `illustration-services.md` §7 —
they are already right for 1600 units; it is the object count that has to come down.

The act chip is 124 units, which lands at ~39 px in a feed. That is exactly the size the act
glyphs are designed to survive at, and it is why the act stays: at that size it is the only
element carrying meaning reliably, so **choose it as carefully as §10 says and draw it clean.**

One soft rule the gate does not check: a few clients (compact Slack rows, some mail previews)
crop a preview to something near square. Keep the act chip and one recognisable object inside the
centre square, **x 285–915**. Anything outside that is worth having but must not be load-bearing.

## 5. Who gets one, and who does not

Fourteen pages get a card. Five do not, and that is a decision rather than a backlog:

| Not drawn | Why |
|---|---|
| `legal.imprint.index`, `legal.privacy.index`, `legal.terms.index` | Nobody shares an imprint into a chat. The default card is correct for them, and three legal drawings would be three drawings nobody sees. |
| `network.request.index` | `robots: noindex,nofollow`. A card for a page that must not be indexed is work aimed at nothing. |
| `media.index` | The page is about the codebar logo. `og-codebar.png` **is** the logo card — it is the one page where the fallback is the right answer, not a gap. |

Detail pages are not in this family at all. Services, products, technologies, news, open source
and network entries each carry their own `image` from their own source, and
`illustration-services.md` / `illustration-news.md` own those.

## 6. The set

Each row is one page's promise. The `key` is the filename. **Silhouettes differ before contents
do**, and the first object named in the manifest is the opening object — no two pages may share
one (§8, set gate).

| Page | Before — the reader's situation | Act | After — the promise, drawn |
|---|---|---|---|
| `start.index` | an idea that only exists as a sketch and a spoken sentence — a napkin drawing under a big speech bubble | `listen` | software in daily use: one panel somebody works in, with the sketch's shapes recognisable in it |
| `about-us.index` | a request relayed down a chain of boxes before it reaches whoever builds it | `meet` | one table, two people at it, the code panel open between them — no layer in between |
| `services.index` | four offerings lying apart and unaligned — a sketched screen, a code panel, a paper stack, a task board | `grid` | the same four as tiles threaded on one line from idea to operation |
| `products.index` | one project's panel, built once, for one customer, with its job number on it | `braces` | a released product: the same panel with version steps behind it and a usage row under it |
| `technologies.index` | a long shelf of tools, most of them untouched | `funnel` | three tools kept, each with years stacked behind it |
| `open-source.index` | a package we wrote, sitting inside our own repository and nowhere else | `fork` | the same package on a public repository, an install count under it, somebody else's branch off it |
| `ai.index` | customer documents on a lane out of the building to a provider's rack | `shield` | the same documents on a machine inside our own outline, counters running beside it |
| `ai.llm.index` | three rented model tiles with no machine under them | `host` | the three model categories on one laptop in a basement, a UPS beside it and a tunnel out |
| `ai.llm.analytics.index` | requests running past, nothing counting them | `measure` | a month column of token bars per model, with a total pill |
| `news.index` | a whiteboard at the end of a project day — everything learned, staying in the room | `bell` | a dated list of articles, and somebody being told |
| `jobs.index` | work handed over a wall to a department | `board` | one lane crossing several roles, from the customer conversation to the code, with a person card on it |
| `co-working.index` | a laptop on a kitchen table at home | `transfer` | a desk in a room with a team in it, a 250 Mbit/s line into it |
| `contact.index` | a note with a question on it and no address | `call` | a handset, a named contact person, and two address cards |
| `network.index` | partner names listed apart, nothing between them | `nodes` | partners on a shared spine around one hub, tier badges on two of them |

Each object in each row has to cite a phrase from that page's own copy — the YAML, and the
`lang/` strings the page's Blade template renders. That is Gate 1, and §8 is what makes it
runnable. Some are already sitting there and are worth using verbatim: "keine Zwischenebene",
"Vier Bereiche, ein Weg", "im täglichen Einsatz", "bewusst gewählt und über Jahre in der Tiefe
beherrscht", "geben etwas zurück", "Kundendaten verlassen unsere Infrastruktur nicht",
"im hauseigenen Bürokeller", "pro Monat und Modell", "Einblicke aus unserem Alltag",
"vom Kundengespräch bis zum Code", "250 Mbit/s Private Virtual Network",
"Deine Ansprechperson", "Gute Software entsteht nicht im Alleingang".

**Acts may repeat across families, never within one.** `grid` is `bausteine-styleguide`'s act and
`braces` is `individuelle-softwareentwicklung`'s; a page may use either, because a page card and
an article hero are never seen side by side. Two pages sharing an act is the failure the rule
exists for: paste three codebar links into one chat and three identical purple chips is exactly
the "list of one repeated drawing" that `illustration-services.md` §11 Gate 5 describes.

## 7. Six acts the vocabulary does not have yet

The thirteen acts in `illustration-services.md` §10 were written for **systems changing**. A page
card says what a company does for a reader, and six of those verbs do not exist yet. Each one
below passes the §10 test — it is a verb, the subject genuinely performs it, it reads as a
silhouette at 40 px, and none of them is an existing act under another name.

All six are in `ACTS` in `scripts/check-illustrations.py`. A seventh is added the same way, and
only under `illustration-services.md` §10's rules — it must be a verb, it must be legible at
40 px, and it must not already be in the list under another name.

| Act | Reads as | Use when the promise is |
|---|---|---|
| `listen` | a large bubble and a small answer | it starts with your problem, not with our product |
| `meet` | two people | the person who does the work is in the room |
| `call` | a handset | you reach a human directly, and no form is involved |
| `fork` | two nodes branching from one | it is given back, and someone else can take it further |
| `host` | a roof over a chip | it runs on our own hardware, in our own building |
| `measure` | a gauge | the change is that it is counted and shown |

```xml
<!-- listen — theirs is filled and large, ours is outlined and small -->
<path d="M8 4h26a6 6 0 0 1 6 6v16a6 6 0 0 1-6 6H20l-10 8v-8H8a6 6 0 0 1-6-6V10a6 6 0 0 1 6-6Z" fill="url(#accent)"/>
<path d="M32 28h20a6 6 0 0 1 6 6v12a6 6 0 0 1-6 6h-4l-8 6v-6h-8a6 6 0 0 1-6-6V34a6 6 0 0 1 6-6Z"
      stroke="url(#accent)" stroke-width="5" fill="none" stroke-linejoin="round"/>

<!-- meet -->
<g fill="url(#accent)"><circle cx="20" cy="16" r="9"/><path d="M4 52a16 16 0 0 1 32 0Z"/></g>
<g stroke="url(#accent)" stroke-width="5" fill="none" stroke-linejoin="round">
    <circle cx="43" cy="21" r="8"/><path d="M30 52a13 13 0 0 1 26 0Z"/>
</g>

<!-- call — a receiver, tilted to the angle a handset is always drawn at -->
<g transform="rotate(-32 30 30)">
    <path d="M16 4h10a5 5 0 0 1 5 5v7a5 5 0 0 1-5 5h-2a9 9 0 0 0-9 9v0a9 9 0 0 0 9 9h2a5 5 0 0 1 5 5v7a5 5 0 0 1-5 5H16a6 6 0 0 1-6-6V10a6 6 0 0 1 6-6Z"
          fill="url(#accent)"/>
</g>

<!-- fork — the trunk is one path with both shoulders, so it has area, see §5 of services -->
<g fill="url(#accent)"><circle cx="12" cy="12" r="8"/><circle cx="48" cy="12" r="8"/><circle cx="30" cy="50" r="8"/></g>
<path d="M12 20v6a10 10 0 0 0 10 10h16a10 10 0 0 0 10-10v-6M30 36v6"
      stroke="url(#accent)" stroke-width="5" fill="none" stroke-linecap="round"/>

<!-- host -->
<path d="M30 4 57 22v5H3v-5Z" fill="url(#accent)"/>
<rect x="17" y="33" width="26" height="22" rx="6" fill="url(#accent)"/>
<path d="M7 39h10M7 49h10M43 39h10M43 49h10"
      stroke="url(#accent)" stroke-width="5" stroke-linecap="round" fill="none"/>

<!-- measure -->
<path d="M4 46a26 26 0 0 1 52 0" stroke="url(#accent)" stroke-width="6" fill="none" stroke-linecap="round"/>
<path d="M30 46 45 27" stroke="url(#accent)" stroke-width="6" fill="none" stroke-linecap="round"/>
<circle cx="30" cy="46" r="7" fill="url(#accent)"/>
```

All six are drawn in the 60×60 box, use nothing but `url(#accent)`, and were rendered at 40 px
before being written down — each still reads there, which is the only reason any of them is in
the table. Re-check after any change to the geometry:

```bash
rsvg-convert -w 40 -h 40 /tmp/act.svg -o /tmp/act.png
```

Every one of them still goes inside `<g class="act" transform="translate(570 285)">` on the chip
at `x 546 y 261`, which is 108 units square with `rx 22` — the banner's 124 chip at 0.75, rounded. **That `class="act"` is required** — it is how the arrowhead gate knows a
triangle in a glyph is not a third stage arrow.

## 8. What the gate checks here

`scripts/check-illustrations.py pages` runs the whole of `illustration-services.md` §11 over this
directory. Five things in it are specific to the family and worth knowing before you fight one:

- **The corpus is not one file.** A page's copy lives in its YAML — the title and description a
  crawler prints next to the drawing — and in the `lang/` files, reached through `__()` keys this
  script does not resolve. So `load_sources()` reads `database/files/pages/<key>.yaml` plus
  `lang/de_CH.json`, `lang/en_CH.json` and both `components.php`, and checks quotes against the
  union. **Known weakness, stated in the code:** the lang files are site-wide, so the gate proves
  a quote is in codebar's own words, not that it is on *that page*. A person checks the page; the
  gate checks the wording. Quote what the page actually renders.
- **The canvas is `PAGE_VIEWBOX`** — `0 0 1200 630`. A page drawing on the 1600×840 banner canvas
  is a failure, not a variant.
- **The safe area is `PAGE_SAFE`** — `(18, 18, 1182, 612)`, measured the same way as a news crop
  even though nothing crops: it catches a shadow or a stack running off the right edge, which is
  what `technologies.index` did on its first pass.
- **The set rules run over all fourteen** — no two acts, no two opening objects. The opening
  object is the *first* key in the manifest's `objects`, so its order is load-bearing.
- **`pages/*-card.svg` fails outright**, with §0's reason. It is not a missing feature.

## 9. Rendering, and actually looking at it

```bash
scripts/render-news-og.sh public/images/pages/<key>.svg
php artisan pages:import
php artisan responsecache:clear
```

The script is named for news and takes explicit files; it renders at exactly 1200×630, which is
what `config/seo.php` declares in `og:image:width` / `og:image:height`. Do not add a second script
and do not change the size without changing that config.

Then look at it the way it is seen — at feed size, and next to the other pages, which is the only
test that matters:

```bash
for f in public/images/pages/*.svg; do
    rsvg-convert -w 500 -h 263 "$f" -o "/tmp/$(basename "$f" .svg)-feed.png"
done
```

And check the page actually serves it, because nothing on the site will:

```bash
curl -s https://<host>/dienstleistungen | grep -E 'og:image|twitter:image'
```

`og-codebar.png` in that output means the PNG is missing, the import did not run, or the response
cache is stale. In that order.

## 10. Before you commit

Run `scripts/check-illustrations.py pages`. It covers the palette, the canvas, the safe area,
the glow, the shadows, the arrowheads, the connectors, the file size, the PNG, the act
vocabulary, the set, and every object's provenance. What is left for a person is
`illustration-services.md` §13, plus:

- [ ] The sentence is the page's **promise**, and its left half is the reader's situation — not a
      worse version of what we sell.
- [ ] Held against the page's own `title:` and `description:`: the drawing shows what those words
      claim, and does not restate them.
- [ ] Held against everything the page links to — service banners, news heroes, the models page —
      it is not one of those compositions with new objects.
- [ ] Rendered at 500 px: all three stages still read, and the act is recognisable.
- [ ] The act chip and one anchor object sit inside x 285–915.
- [ ] Rendered next to the other page cards: different silhouettes, different opening objects,
      no two acts the same.
- [ ] The input half is black and white. Colour starts at the glow.
- [ ] `image:` set in `database/files/pages/<key>.yaml`, path relative to `public/`.
- [ ] The 1200×630 PNG exists next to the SVG, and no `-card.svg` does.
- [ ] `php artisan pages:import` **and** `php artisan responsecache:clear` run.
- [ ] `curl | grep og:image` on the real page shows `images/pages/<key>.png`.
