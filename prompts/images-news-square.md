# Generating news square thumbnails (v1)

> **Superseded by `illustration-news-card.md`.** This file stays because
> `scripts/make-news-square.py` and the existing squares still work; use it to regenerate one,
> not to make a new article's.

Follow this file whenever an article needs the small square picture the news index shows next
to a list row. It is the companion to `prompts/images-news.md`, which covers the 16:9 hero.

The square is a **separate file, not a crop**. The hero carries the article title, so the
square slot cuts it in half — «DocuWare 7.13 ist da» arrives as «cuWare 7.13 ist d». Rather
than shrink the hero's type until it survives a centre crop, the square drops the words
entirely and shows only the motif. That has two consequences worth knowing up front:

- **One file per article, not one per locale.** No text means nothing to translate.
- **No PNG.** The square is never an `og:image`; that stays the hero's job. SVG only.

## 1. Making one

```bash
scripts/make-news-square.py docuware-7-14 --motif mobile-app
```

Writes `public/images/news/placeholders/docuware-7-14-square.svg` and prints the front-matter
line to paste. No fonts, no rendering step, no dependencies — the file is a couple of KB.

- **slug** — the *same stem the hero uses*, without the locale: `docuware-7-14-de.svg` and
  `docuware-7-14-en.svg` are joined by `docuware-7-14-square.svg`. An article's three files
  then sit together in a directory listing.
- **`--motif`** — see §3. This is the whole decision.

Then wire it into the front matter of **both** language files, next to `hero:`:

```yaml
thumb: images/news/placeholders/docuware-7-14-square.svg
```

Both files carry it even though only the German one is read, exactly as `hero:` does — a
front matter that differs between locales for no visible reason is a trap for the next person.

There is no `thumb_alt`. The index renders the square with `alt=""`: it repeats the headline
sitting right beside it, so to a screen reader it is decoration. If you ever find yourself
wanting alt text here, the picture is carrying information the teaser should be carrying.

Without a `thumb:` the row falls back to the hero and the slot reverts to 4:3, so nothing
breaks — the article just shows a cropped hero, which is the state this whole file exists to
get rid of.

## 2. The layout

640×640, with one job: survive being displayed at **176 px**. That is the real size in the
index row, and every decision below follows from it.

| Element     | Position                        | Notes                                    |
|-------------|---------------------------------|------------------------------------------|
| Motif       | 80, 80 → 560, 560               | the only thing that changes per article   |
| Rings       | centre (60, 626), r 216/160/105/52 | bottom-left, only arcs show            |
| Dot field   | bottom-left, behind the rings   | step 26 — see below                       |
| Band        | x 360, rotated 22°              | passes behind the motif                   |

**No title, no tag row, no logo.** The codebar logo is on the hero and would be an
unreadable smudge at 176 px; the topic already sits in the row as a chip.

The background is the hero's, scaled to this canvas: same ring centre relative to the corner,
same radii ratio, same band angle, same wash. **It is identical on every square**, and it is
not a place to tell articles apart — change the motif instead.

One deliberate deviation: the **dot field is enlarged**, step 26 rather than the hero's 34
scaled down to 14. At card size the correctly-scaled spacing lands around 3.7 px, where the
field stops reading as dots and turns into grey mush.

The motif box is 480×480 at the origin — the same box the hero uses, tilted the same
`rotate(-4)`. A hero motif can be dropped into a square unchanged, but usually should not:
see the detail budget below.

## 3. Motifs

Pick with `--motif`. Defined at the bottom of `scripts/make-news-square.py`:

| Name                | Reads as                                                     | Use for                          |
|---------------------|--------------------------------------------------------------|----------------------------------|
| `invoice-analytics` | an invoice with a bar chart laid over it — the same numbers, read a second way | e-invoicing, IDP, reporting, analytics |
| `workflow-browser`  | a browser window with three steps wired together, the last one approved | workflow, configuration, anything that moved into the browser |
| `mobile-app`        | a phone with a task list, one done, push waves off the corner | mobile, notifications, tasks     |
| `editorial-blocks`  | a page assembled from blocks, with a colour row               | Styleguide, Redaktion, the site itself |
| `queue-gateway`     | requests queueing into a panel that works through them, one answer coming back out | queues, gateways, batch processing |
| `documents`         | a stack of documents, signed off                              | the neutral fallback             |

**A motif is per article, not per topic.** Five DocuWare releases all tagged DMS/ECM must not
share one picture: the index puts them directly under one another, and five identical squares
make the list look broken. Read what the release actually changed and pick — or write — the
motif that says it. If two articles genuinely tell the same story, they are probably one
article.

To add one, write a function that draws inside the **480×480 box at the origin** and register
it in `MOTIFS`. The rules from `prompts/images-news.md` §4 all still hold — white surfaces,
brand strokes, suggested content rather than literal, no gradients, no text, no third-party
logos — with a tighter budget on top, because 480 units land on 176 px:

- **Three surfaces at most.** The hero's `dms-ecm` stacks four documents and still reads at
  1600 wide; here it would be a grey smear.
- **`stroke-width="3"` on the large shapes**, not 2, and nothing thinner than 8 units.
- **`rx` 14–22.**
- **One idea, one flourish.** A check, an arrow, a wave — one of them, not three.
- **Vary the flourish across the set.** Every motif ending in the same filled check circle
  defeats the point of drawing five of them.
- Check it at 176 px before you believe it:
  `rsvg-convert -w 176 -h 176 <file>.svg -o /tmp/check.png`

## 4. Before you commit

- [ ] One square per article, file name `<hero-stem>-square.svg`, **no** `-de` / `-en`.
- [ ] No text anywhere in the file — that is what makes it locale-free.
- [ ] No PNG next to it, and `og:image` still points at the hero's PNG.
- [ ] The motif is not shared with another article in the index.
- [ ] Rendered at 176 px: every surface still reads, the flourish is recognisable.
- [ ] Seen next to its neighbours in the list: distinguishable at a glance.
- [ ] `thumb:` set in **both** locale files, path relative to `public/`.
- [ ] `php artisan news:import` run, so the column is filled.
- [ ] `git status` shows no orphaned square left behind by a rename.
