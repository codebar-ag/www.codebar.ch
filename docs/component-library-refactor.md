# Component Library Refactor Concept

Goal: one Blade component library under `resources/views/components/`, reused across the entire site — no page-level ad-hoc markup for patterns that exist as components, consistent mobile/desktop behavior, and accessibility fixed at the component level so every usage inherits it.

Based on a full audit of `resources/views/app/**`, `layouts/**`, `components/**`, `resources/css/app.css` and `resources/js/app.js` (July 2026, branch `feature-updates`).

---

## 1. Current state (short)

**What already works well — keep it:**
- Headings are centralized (`x-h1`, `x-h2`, `x-h3`, `x-h1-teaser`) and used almost everywhere (20/31/3/5 usages).
- One global container: `max-w-4xl mx-auto px-4 sm:px-6 lg:px-8` in `layouts/app.blade.php:31` — no page defines its own width.
- Images: `srcset`/`sizes`/`loading="lazy"` in `list-image-card`, all `<img>` have `alt`.
- `prefers-reduced-motion` is globally handled (`app.css:72-80`).
- Single `<h1>` per page, `lang` attribute set.

**The problems, in one sentence each:**
1. Duplicated components: `cta-band` ≈ `docuware-showme` (copy-pasted band+blob markup), `list-card` ≈ `product-card` (same card, diverging details).
2. Inline duplication in pages: external-link SVG pasted 4+ times, address+badge block 3×, tag loops reimplemented 4×, hand-rolled button in `analytics.blade.php:27` that drifts from `x-button`.
3. Inconsistent visual language: 3 pill stylings, radius mixes `rounded-md/lg/xl` with no rule, `gray` + `zinc` families mixed, 3 row-grid treatments with different borders/paddings.
4. Brand color accessed 4 different ways (`bg-brand`, `bg-(--brand)`, two inline `style="--brand: …"` variants).
5. Responsive drift: identical 2-col grids collapse at `sm`, `md` or `lg` depending on the page; nav collapses at `lg` while content collapses at `sm/md`.
6. A11y gaps concentrated in a few components: combobox not keyboard-operable, nav toggle missing `aria-expanded`, `<main>` wraps nav+footer, no skip link, small touch targets on badge links and the menu button.

Because the site already routes most UI through components, **fixing the components fixes most pages for free** — that's the core leverage of this refactor.

---

## 2. Target architecture

One library, four layers. Everything a page renders comes from layers 2–4; pages themselves contain only composition, text and loops.

```
resources/css/app.css        Layer 0: design tokens (@theme)
resources/views/components/
├── ui/                      Layer 1: primitives (no domain knowledge)
│   ├── heading.blade.php        h1/h2/h3 via level prop (or keep h1/h2/h3 as thin wrappers)
│   ├── link.blade.php           today's a.blade.php
│   ├── button.blade.php         variants: primary | outline
│   ├── badge.blade.php          static pill (span)
│   ├── badge-link.blade.php     tappable pill (a), ≥44px target
│   ├── panel.blade.php          surface chrome: bg-gray-50 border rounded-xl (used by stat-card, ai-llm card, media card)
│   ├── row.blade.php            bordered grid row (replaces archive-row/infra-row/model-row chrome)
│   └── prose.blade.php          one prose wrapper (merges content.blade.php + legal/prose)
├── icon/                    Layer 1b: every SVG as a component
│   ├── external-link.blade.php  chevron.blade.php  close.blade.php  arrow-right.blade.php
│   ├── linkedin.blade.php  github.blade.php  email.blade.php  website.blade.php
│   └── logo/docuware.blade.php  logo/codebar.blade.php …
├── layout/                  Layer 2: page scaffolding
│   ├── section.blade.php        vertical rhythm (see §5)
│   ├── section-header.blade.php h2 + intro paragraph pair
│   ├── grid.blade.php           the ONE responsive grid (cols=2|3, standard ladder, see §5)
│   └── list.blade.php           divided vertical stack (unchanged)
├── card/                    Layer 3: composed patterns
│   ├── item-card.blade.php      merges list-card + product-card (variant: list | tile)
│   ├── person-card.blade.php    today's list-image-card
│   ├── stat-card.blade.php      moves out of llm-analytics/
│   └── download-card.blade.php  extracted from media/index (logo + png/svg links)
├── band/                    Layer 3: marketing bands
│   ├── cta-band.blade.php       ONE band base (blob + title + body + actions slot)
│   └── (docuware-showme becomes a call site of cta-band with 2 buttons)
├── data/                    Layer 3: data display
│   ├── table.blade.php          real <table> with caption/scope baked in
│   ├── tag-list.blade.php       the tag loop, one implementation
│   └── meta-badges.blade.php    published/updated/author row (news/show)
├── form/                    Layer 3
│   └── combobox.blade.php       rebuilt with full ARIA pattern (see §6)
└── (page-specific one-offs stay flat: what-we-do, intro — see §7)
```

Naming/convention rules (enforce in review):
- **Props for classes:** always kebab-case `class-attributes` at call sites (today camel/kebab is mixed). Better: drop the custom prop and use Blade's native `$attributes->merge([...])` so callers just write `class="…"`.
- **No inline SVG in pages or composed components** — always `<x-icon.*>`. Icons default to `aria-hidden="true"`; a `label` prop switches to `role="img"` + `<title>`.
- **No raw pill/button/panel markup in pages.** If a page needs a styled box, it uses `x-ui.panel`; a styled link uses `x-ui.button` or `x-ui.badge-link`.
- **demo/** stays out of the library (it's a prototype sandbox with zero shared components) — mine it for ideas, don't migrate it.

---

## 3. Design tokens (Layer 0)

Current `@theme` has only `--color-brand`, `--color-brand-strong`, and an unused `--color-brand-navy`. Extend it with a small semantic layer so components stop hardcoding grays and radii:

```css
@theme {
  --color-brand:        #500472;
  --color-brand-strong: #3a0354;
  /* semantic */
  --color-surface:      var(--color-gray-50);   /* panel bg */
  --color-border:       var(--color-gray-200);  /* panel/row borders */
  --color-border-soft:  var(--color-gray-100);  /* divide lines */
  --color-text:         var(--color-gray-800);
  --color-text-muted:   var(--color-gray-600);  /* min for secondary text */
  --radius-pill:  var(--radius-md);   /* badges, buttons */
  --radius-panel: var(--radius-xl);   /* cards, panels */
}
```

Decisions this encodes:
- **One gray family.** Replace `text-zinc-950` (only in `x-h3`) with the gray scale. Delete `zinc` usage.
- **Two radii, not four.** `rounded-md` for interactive pills/buttons, `rounded-xl` for surfaces. The `rounded-lg` outliers (noscript button, combobox, archive-table) move to one of the two.
- **`text-gray-500` is the floor for small text; `text-gray-400` never for text** (contrast: gray-400 ≈ 2.8:1 fails AA — currently the combobox placeholder).
- **One brand-color mechanism:** components use theme utilities (`bg-brand`, `text-brand`, `focus:ring-brand`). The *only* legitimate CSS-var override is the dynamic partner color in the DocuWare band (`style="--color-brand: {{ $configuration?->company_primary_color }}"` on the band root — Tailwind v4 utilities read the var, so the override cascades). Remove all other `bg-(--brand)` / `style="--brand: …"` variants.
- **Delete dead weight:** unused `--color-brand-navy`, unused `bg-brand-gradient`, and the `animate-[lava_…]` class whose `@keyframes lava` doesn't exist anywhere (define it or — simpler — remove the class from `icon-docuware-arrow`, `partnerships`, footer labels).
- **Remove the `@safelist`** for heading classes once `intro.blade.php` stops regex-injecting classes into markdown (see §7).

---

## 4. Consolidation map (existing → target)

| Today | Action |
|---|---|
| `cta-band` + `docuware-showme` | One `band/cta-band` with `title`, `body`, and an `actions` slot. `docuware-showme` becomes a thin wrapper (it keeps its config lookup + dynamic brand color) or is inlined into `products/show`. |
| `list-card` + `product-card` | One `card/item-card` (`variant="list|tile"`). Resolve the diffs deliberately: tags **always visible** on mobile (product-card behavior wins — hiding content on mobile was inconsistent), one hover treatment, title becomes a real `<h3>` (a11y §6). |
| `badge` / `a-badge` | Keep both (span vs link is a real distinction) but share the pill class string via one partial/`@props` include; `badge-link` gets `py-2 px-3 min-h-11` tap target on mobile (`sm:py-1 sm:px-2` if the compact look must stay on desktop). |
| `button` + hand-rolled noscript button (`analytics.blade.php:27-31`) | Replace the inline copy with `<x-ui.button>`; add a `type="submit"` render mode (`<button>` vs `<a>` depending on `href` prop). |
| `ai-llm/card` + `llm-analytics/stat-card` chrome + media logo card | All sit on `ui/panel` (surface + border + radius from tokens). |
| `archive-row` / `infra-row` / `model-row` | Keep as domain components but all use `ui/row` for border+padding chrome (today: 3 different border shades/paddings). `archive-table` becomes a real `<table>` (a11y §6). |
| `content.blade.php` + `legal/prose` | One `ui/prose` with a `variant="default|legal"` — two prose systems is one too many. |
| Inline external-link SVG ×4, chevron/close in combobox, arrow in `list-card`, social icons in `list-image-card`, 75-line DocuWare SVG in `partnerships.blade.php` | Extract to `icon/` components. Fix the misspelled `_parials` folder → `_partials` while touching it. |
| Address + map/Zefix badge block (contact ×2, imprint ×1) | New `card/address-card` (`name`, `lines`, `link-href`, `link-label`). |
| Tag loops (×4) + news meta badges | `data/tag-list` + `data/meta-badges`. |
| Stat grids (`ai/index:10`, `analytics:37`) | `layout/grid` with `cols=2|3`. |
| Analytics `<table>` (~30 lines inline) | `data/table` (or at minimum extract to a partial with `scope`/`caption` fixed). |
| `h1-teaser` duplicating h2's class string | Reference the shared heading scale (or accept the duplication but add a comment-free single source: one `@props`-level class map). |

---

## 5. Mobile & desktop standardization

**One breakpoint ladder.** Standard for content grids: `grid-cols-1 sm:grid-cols-2` (2-col) and `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3` (3-col — never skip from 1→3). This becomes the *default of `layout/grid`* and call sites stop overriding breakpoints. Fixes the current spread:
- `contact:18`, `ai/index:10` collapse at `sm` ✓ (already standard)
- `media:11`, `products/show:16` (`md`) and `about-us:36`, `list-grid` default (`lg`) → move to `sm`
- `products/show:30` (`1→3` at `md`) → `sm:grid-cols-2 lg:grid-cols-3`
- `analytics:7` (3 filter columns at `sm` inside `max-w-4xl` — cramped) → 2 at `sm`, 3 at `lg`

**Nav breakpoint `lg` → `md`.** Content is multi-column from 640–768px but the nav shows the mobile "Menu" until 1024px. Change `hidden lg:flex` / `lg:hidden` in `_navigation*.blade.php` to `md:` so nav and content collapse together. (Verify the desktop link row fits at 768px; if not, reduce link font from `text-2xl`.)

**Touch targets ≥ 44px:**
- Menu toggle (`_navigation.blade.php:30`): add `p-2 -m-2` (bigger hit area, unchanged layout).
- `badge-link`: see consolidation map above.

**Spacing rhythm.** `x-section` = `mt-6 mb-2` breaks when nested (`services/index.blade.php:5-7` double-margins today). Rule: **sections don't nest**; rhythm comes from one direction only (`mt-*` or a parent `space-y-*`, not both). Concretely: layout slot wrapper gets `space-y-8` (or keep `my-8` + section `mt-6`), sections drop `mb-2`, and the nested section in services/index is flattened.

**Small fixes:** `_footer/labels.blade.php:1` — `sm:justify-start` is dead while the container is `flex-col` until `md`; align both to `md:`.

**Dark mode:** currently absent (zero `dark:` classes). Not in scope for this refactor, but the token layer (§3) is the prerequisite — once components read `--color-surface`/`--color-text`, dark mode becomes a token-swap instead of a 24-component sweep. Decide separately.

---

## 6. Accessibility — fixed at component/layout level

Ordered by severity; file references from the audit.

**High:**
1. **Landmarks** (`layouts/app.blade.php:30-44`): `<main>` currently wraps nav+footer. Restructure to `<header>` (nav) → `<main id="main">` ($slot only) → `<footer>` as siblings. Add a skip link as first focusable element: `<a href="#main" class="sr-only focus:not-sr-only …">`.
2. **Combobox** (`llm-analytics/filter-combobox` + `app.js:13-49`): mouse-only today — no keyboard nav, no ARIA roles. Rebuild `form/combobox` with the ARIA combobox pattern: `role="combobox"`, `aria-expanded`, `aria-controls`, `aria-activedescendant` on the input; `role="listbox"`/`role="option"` on the list; Arrow/Enter/Escape/Home/End keydown handling in the Alpine component. (Pragmatic alternative if effort must be minimal: native `<select>` with the same styling.)
3. **Mobile nav toggle** (`_navigation.blade.php:30-41`): add `x-bind:aria-expanded="open"`, `aria-controls="mobile-menu"`, `id="mobile-menu"` on the panel; remove `focus:outline-none` or replace with `focus-visible:ring-2 ring-brand`.

**Medium (mostly one-line fixes that land inside the new components):**
4. `ui/link` and `item-card`: auto-emit `rel="noopener noreferrer"` whenever `target="_blank"` (component-level guarantee; today ~8 call sites miss it).
5. `data/table`: `scope="col"` on `<th>`, `<caption class="sr-only">` — analytics table gets this for free.
6. `archive-table` → real `<table>` semantics (currently div-grid "table").
7. `item-card` title: `<div class="font-semibold text-xl">` → `<h3>` (cards become navigable by heading).
8. Logo-only links (`partnerships`, footer labels): `aria-label` on the `<a>`, `aria-hidden="true"` on the SVG — solved once by the `icon/` component defaults.
9. Combobox clear button: `aria-label="×"` → translated "Clear"; placeholder color `gray-400` → `gray-500` (contrast).
10. `_seo.blade.php:14`: emit a fallback `<title>{{ config('app.name') }}</title>` when `$page` is empty.

**Low:** `aria-hidden="true"` on decorative glyphs (`↗`, `←`, nav `|` separators) — the `|` separators ideally become CSS borders/pseudo-elements instead of DOM text.

**Verification:** after Phase 2 (below), run Lighthouse a11y + axe on: start, products/show (band), contact (address cards), ai/llm/analytics (combobox+table), news/show. Keyboard-walk the nav and combobox manually.

---

## 7. Special cases

- **`intro.blade.php`** regex-injects heading classes into rendered markdown (fragile; forces the CSS `@safelist`). Replace with either (a) prose styling: wrap output in `ui/prose` and style `h2/p` via `prose-*` modifiers, or (b) move the intro copy from markdown to a lang file + components like `what-we-do` does. Option (a) is less churn.
- **`what-we-do.blade.php`** is fine as a page-specific composition — it already uses `x-h2`/`x-h3`.
- **`icon-docuware-arrow`** has 0 usages — delete it, or move under `icon/logo/` if the demo flows will need it.
- **demo/** (22 layout variants): out of scope, keep as sandbox.

---

## 8. Migration plan

Each phase ships independently and leaves the site consistent. Do not start a big-bang rename.

**Phase 1 — Tokens & primitives (no visual change intended)**
Add the `@theme` semantic tokens; create `ui/` + `icon/` components; unify brand-color access; delete dead CSS (`brand-navy`, `bg-brand-gradient`, `lava`); fix `zinc→gray`, radius outliers, `class-attributes` casing. *Risk: low. Review via visual diff of all pages.*

**Phase 2 — Layout & a11y structure**
Landmarks + skip link in `app.blade.php`; nav toggle ARIA + focus ring; nav breakpoint `lg→md`; section rhythm rule (un-nest services/index); footer labels breakpoint fix; `rel` auto-emit in `ui/link`. *Risk: medium (layout template touches every page) — this is the phase to browser-test mobile & desktop.*

**Phase 3 — Consolidation**
`item-card` (merge list/product card), `cta-band` unification, `panel`/`row` adoption in ai-llm + llm-analytics, `prose` merge, `grid` with standard ladder replacing `list-grid` + inline grids, extract `address-card`, `tag-list`, `meta-badges`, `download-card`, `data/table`. Migrate call sites page by page (order: products → about-us/media → ai/llm → contact/legal → news/start). Delete the superseded components at the end of the phase.

**Phase 4 — Interactive & polish**
Rebuild the combobox with full ARIA + keyboard support; touch-target sizes (badge-link, menu button); remaining low-severity a11y (decorative glyphs); Lighthouse/axe pass and fixes.

**Definition of done**
- `grep -r '<svg' resources/views/app` → 0 hits (all icons are components).
- No `grid grid-cols` in page views (grids come from `layout/grid`).
- One pill/button/panel implementation each; `rounded-lg`, `zinc-`, `bg-(--brand)`, `style="--brand"` (except DocuWare band) → 0 hits.
- axe: no critical/serious issues on the five key pages.
- Every component in `components/` has ≥1 usage (no dead components).
