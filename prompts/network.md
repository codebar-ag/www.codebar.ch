# Generating network partner drawings

Follow this file when a partner on `/network` has no usable logo file and needs a flat
drawing instead. Reference implementations: `public/images/network/docuware.svg` (a
partner drawing) and `public/images/placeholders/network-company.svg` (the generic
fallback). Open one of them alongside this document.

These are a **different family** from the news heroes in `images-news.md` — much smaller,
no type, no gradients, and deliberately naive. Do not carry news-hero conventions over.

## 1. Where the files go

```
public/images/network/<key>.svg              ← drawing for one specific partner
public/images/placeholders/network-company.svg  ← the generic fallback, already exists
```

`<key>` must match the partner's `key` column exactly — `resources/views/app/network/index.blade.php`
resolves `images/network/{$network->key}.svg` by `file_exists()`, with no registration step
anywhere. Get the key wrong and the card silently falls back to the generic placeholder.

The cascade the view walks, in order:

1. `cover_url` on the model (a real uploaded logo) — wins if set,
2. `images/network/<key>.svg` — the drawing you are making,
3. `images/placeholders/network-company.svg` — generic, rendered at `opacity-70`.

So a drawing is only ever a stand-in for a missing logo. When the partner supplies real
artwork, `cover_url` takes over and the SVG can be deleted.

## 2. Canvas

```xml
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 96" role="img" aria-hidden="true">
    <!-- Flat drawing: filing cabinet with searched document — DMS/ECM -->
    <g>
```

160×96 user units, declared in the view as `width="160" height="96"` and capped at
`max-h-16` (64 px tall) on screen. `aria-hidden` is right: the drawing carries no
information the card's text does not already give, and the `<img>` has an empty `alt`.

Keep the one-line HTML comment naming the subject — it is how the next person tells the
files apart without rendering them.

Everything lives between **x 8–152** and **y 14–88**. There is no bleed and no
off-canvas geometry; at this size a clipped edge just looks like a bug.

## 3. Palette

Four values. Nothing else, and no gradients — flat fills only.

| Token   | Hex       | Used for                                          |
|---------|-----------|---------------------------------------------------|
| brand   | `#500472` | one accent per drawing, plus the shrubs           |
| mid     | `#d1d5db` | the primary mass — the biggest shape              |
| light   | `#e5e7eb` | secondary masses, ground line                     |
| white   | `#ffffff` | cut-outs: windows, handles, text lines            |

The brand colour is an **accent, not a fill**. One element carries it (a drawer, a roof
band, a magnifier, a door) at full strength or `opacity="0.65"`. If two things are purple,
one of them is wrong.

## 4. Composition

Five layers, in this order:

1. **Ground line** — `<rect x="8" y="86" width="144" height="2" rx="1" fill="#e5e7eb"/>`.
   Identical in every drawing; it is what makes the set feel like a set.
2. **Primary mass** — the subject, in `#d1d5db`, roughly 40–45 units wide and 60–70 tall,
   centred near x=80 and standing *on* the ground line (bottom edge at y=86).
3. **Secondary masses** — one or two supporting shapes in `#e5e7eb`, shorter than the
   primary, flanking it. Never taller, or the silhouette goes flat.
4. **Detail** — white cut-outs at `rx="1"`–`rx="2"`: windows on a 10-unit grid, text lines
   as `height="3" rx="1.5"` bars, handles as `width="8" height="4" rx="2"` pills. Suggest,
   do not describe.
5. **Shrub accents** — always exactly these two, closing the composition:

```xml
<circle cx="18" cy="82" r="5" fill="#500472" opacity="0.35"/>
<circle cx="146" cy="83" r="4" fill="#500472" opacity="0.25"/>
```

Corner radii: `rx="2"`–`rx="3"` on masses, `rx="1"`–`rx="2"` on details. Strokes only where
a shape is genuinely an outline (a magnifier ring) — `stroke-width="4"`, otherwise fill.

## 5. Choosing the subject

Draw what the partner *does*, in one object a person recognises at 64 px tall:

- DMS/ECM → filing cabinet with a document pulled out (`docuware.svg`)
- generic company → three buildings, tallest in the middle (`network-company.svg`)

Existing drawings to check before inventing another:
`baselhack` · `docuware` · `iway` · `odoo` · `pst` · `swiss-digital-services` ·
`swiss-laravel-association` · `swiss-made-software` · `wieland-business-solutions`.

Hard limits at this size: no text, no gradients, no shadows, no more than ~30 elements, and
**no third-party logos, wordmarks or trademarked shapes** — the whole point is that we do
not have the partner's mark.

## 6. No codebar logo here

Unlike the news heroes, these drawings carry **no codebar logo**. They stand in for
*another company's* logo inside that company's card — stamping our wordmark on it would
misattribute it. The 160×96 canvas has no room for a legible mark anyway.

## 7. No PNG

Network drawings are SVG only. They are decorative in-page images, never `og:image`
candidates, so nothing renders a raster copy. Do not run `scripts/render-news-og.sh`
against them.

## 8. Before you commit

- [ ] Filename equals the partner's `key` exactly (`Network::where('key', …)`).
- [ ] `viewBox="0 0 160 96"`, `role="img"`, `aria-hidden="true"`, subject named in a comment.
- [ ] Ground line and both shrub circles present, unmodified.
- [ ] Exactly one brand-coloured accent.
- [ ] No hex outside the table in §4; no gradients, no text, no partner trademark.
- [ ] Nothing outside x 8–152 / y 14–88.
- [ ] Checked at 64 px tall — the silhouette still reads.
- [ ] Partner card renders the drawing, not the generic placeholder (`cover_url` is null).
