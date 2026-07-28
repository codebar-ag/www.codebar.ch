# Refactor / Branch-Consolidation TODOs

**Origin:** gap analysis ahead of retiring the side branches in favour of `feature-updates` → `production`.

**Scope (updated 2026-07-28):** the original pass only compared `feature-updates` against `main` and `feature-refactor` (PR #48). This pass extends it to **all three local branches** — `feature-design`, `feature-gki`, `feature-refactor` — plus `main`. Two of them had never been assessed, and one of those (`feature-gki`) turned out to hold a finished, unmerged page.

**Standing decision:** gaps are **documented, not ported.** Nothing from these branches is being merged right now. The branches themselves are left in place — not deleted, not tagged — so nothing is lost while this is under review.

Status legend: ✅ done / closed · 🟡 partially done, decision needed · 🔴 open · ⛔ rejected, do not port

---

## Branch survey

| Branch | Tip | Merge-base with `feature-updates` | Unique commits | Verdict |
|---|---|---|---|---|
| `feature-gki` | `1e4dad8` (2026-07-23) | `cd2e0c7` — only 7 behind HEAD | 1 | **Real, unported, cleanly cherry-pickable** |
| `feature-design` | `a3a032e` (2026-05-03) | `cf8f413` — ~64 behind | 1 snapshot | ⛔ Almost entirely superseded; parts would actively break HEAD |
| `feature-refactor` | `f08b3e7` (2026-02-20) | `8d3872e` — ~66 behind | 9 | Mostly already ported or superseded; small hygiene remainder |
| `main` | `478ed3d` (2026-06-30) | — | 52 | Unchanged from the original analysis; still an open architecture question |

`feature-refactor` also exists on `origin`. `feature-design` and `feature-gki` are **local-only** — deleting them would lose them.

---

## Corrections to the earlier version of this document

The previous pass was written 2026-07-22. `feature-updates` has moved since, and three items it listed as open are in fact already closed. Recorded here so nobody redoes finished work.

### ✅ Terms & Conditions (AGB) — done, was listed as 🔴 High
The earlier note said Terms still redirects home with an empty `terms.md`. That is no longer true.
- `app/Http/Controllers/Legal/TermsIndexController.php` renders a real view — no redirect.
- `resources/views/app/legal/terms/index.blade.php` (133 lines) renders all 9 sections plus every subsection (1.1–1.9, 2.1–2.9, 3.1–3.3, 4.1–4.3, 5–9).
- Content lives as ~65 `Terms *` keys in **both** `lang/de_CH.json` and `lang/en_CH.json` — the German AGB was ported into the translation layer, not as a markdown file.
- All six review points from `feature-refactor`'s `terms_feedback.md` are applied: general scope in 1.1, 30-day offer validity + written confirmation in 1.2, explicit no-right-of-withdrawal in 1.3, fixed 01.01–31.12 term with no auto-renewal in 1.5, prices excl. VAT in 1.6, 14-day payment terms in 1.7. That feedback file has served its purpose and does not need porting.
- HEAD additionally has `Terms language body` (German version prevails) and `Terms last updated date` = 23.07.2026, which `feature-refactor` never had.
- `database/files/legal/{de_CH,en_CH}/terms.md` are 0-byte on HEAD, as are `imprint.md` / `privacy.md`. The flat-markdown approach was **abandoned**, not left unfinished — the empty files are leftovers, not a gap.

### ✅ Jobs page — done, was listed as 🔴 High
- `app/Http/Controllers/Jobs/JobsIndexController.php` returns the view — no redirect. It is byte-identical to `feature-design`'s version; HEAD arrived there independently.
- `resources/views/app/jobs/index.blade.php` is fully translation-key driven: page header, `Jobs intro`, `Jobs training heading/body`, `Jobs open positions heading`, a `Jobs no open positions` panel, and an `@if(false)`-gated spontaneous-application block ready to switch on.
- **`feature-design`'s jobs page is worse and must not be ported** — three invented placeholder roles ("Senior Engineer", "Product Designer", "Project Lead") in hardcoded, untranslated English with `mailto:` apply links. Fabricated content, not real openings.

### ✅ Services partnerships block — superseded, not lost
The partner-logo partial `resources/views/app/services/_parials/partnerships.blade.php` (note the `_parials` typo) exists only on `feature-design` now. It was deleted in `dec0e5d` — **the same commit that introduced the whole Network feature**, which replaces it properly: `Network` / `NetworkUser` models, `NetworkCategoryEnum`, a real index page at `network.index`, and 9 partner SVGs in `public/images/network/` including `docuware.svg` with the Silver Partner tier badge rendered from `$network->tier_label`. Nothing to recover.

### ✅ `SyncRepositoriesCommand` — already on HEAD, and better
`app/Console/Commands/SyncRepositoriesCommand.php` is present with `config/services.php` `github.token` and `.env.example` `GITHUB_TOKEN=`. HEAD's version improves on `feature-refactor`'s with a typed `normalizeRepo()`, PHPDoc array shapes and an `is_string($token)` guard. The open questions from the earlier pass still stand (see 🟡 below), but the port itself is done.

---

## `feature-gki` — a finished page that was never merged

**Commit:** `1e4dad8` "Add GKI generative AI consulting page (dormant)" — 8 files, +196/−8, authored 2026-07-23.

This is the most significant find of this pass. It **supersedes and closes** the "GKI consulting service copy" item that this document previously carried against `feature-refactor`: the same three-tier offering, already rewritten against HEAD's own component kit.

### What the page is

A single consulting landing page for codebar's generative-AI offering (GKI = *generative künstliche Intelligenz*), presenting three service tiers as cards in a 3-column grid. German is the source copy; English is a full translation.

**Intro:** "Our three GKI offerings support companies step by step: from strategic assessment through a working prototype in a matter of days to technical integration into existing systems."

| Tier | Teaser | Contents | Closing / audience |
|---|---|---|---|
| **GKI Strategy** | Strategic positioning of AI in your organisation. | AI readiness assessment · identification of prioritised use cases · business case & value-creation logic · governance and compliance framework · roadmap (6–12 months) | *Audience:* ideal for executive boards, innovation leads and universities |
| **GKI Sprint** | From problem to working prototype in 2–5 days. | Use-case refinement · prompt architecture · MVP development (e.g. internal copilot, knowledge agent, automation solution) · user testing · scaling decision | *Closing:* "No PowerPoint. Only working systems." |
| **GKI Build** | Technical integration into existing systems. | API integration · CRM / ERP integration · internal knowledge GPTs · automation pipelines · documentation & operating concept | *Closing:* "We build solutions that run in production — not demo environments." |

The page closes with a single call-to-action button linking to `contact.index`.

### What it ships

- `app/Http/Controllers/Gki/GkiIndexController.php` — tiers as a typed private array (`name`, `teaser`, `features[]`, `closing`, `audience`), SEO via the existing `PageAction`. No new model, no migration, no seeder data beyond SEO.
- `resources/views/app/gki/index.blade.php` — built entirely on HEAD's kit: `x-layout.page-header`, `x-layout.section`, `x-layout.grid :cols="3"`, `x-ui.panel`, `x-ui.prose`, `x-ui.button`. Verified: every prop it uses exists on HEAD's components today.
- `routes/web.php` — `gki-consulting` (EN) and `gki-beratung` (DE), both named `gki.index`.
- `database/seeders/PagesTableSeeder.php` — SEO title/description rows for both locales, `robots: index,follow`.
- `lang/de_CH.json` / `lang/en_CH.json` — 26 keys each.
- `tests/Feature/Controllers/RouteStatusTest.php` — 2 cases asserting 200 OK.

### Dormant by design

Routes are registered but **not linked in navigation**, the same deliberate pattern as `technologies.index` and `co-working.index`. Activating it later means adding the nav link — the page itself is ready.

### Relationship to `feature-refactor`

`feature-refactor` carried the same three-tier content as `app/Data/GkiServiceData.php` plus per-tier **detail pages** at `/ai/{slug}` (`AiShowController`). Those detail pages were dropped in the `feature-gki` rewrite. Reinstating them is not a port — the detail-page bodies would need new copy written, and it would have to coexist with HEAD's `/ai` route, which is now a completely different LLM-usage-statistics hub (`AiIndexController`, `LlmUsageStatsAction`, `ai.llm.*` sub-routes), not a services listing.

### Merge cost, if it is wanted later

`git cherry-pick 1e4dad8`. The controller and the view apply clean (both files are untouched on HEAD since the fork). Expect **additive** conflicts, all mechanical, in `routes/web.php`, both lang JSONs and `PagesTableSeeder.php`. Two conflicts need judgement:
- Its `tests/Feature/HttpErrorPagesTest.php` hunk is **already on HEAD** — skip it.
- Take **HEAD's** `RouteStatusTest` `disabled-routes` dataset, which is newer (it has `about-us.index` and `co-working.index` that the branch version lacks). Add only the two `gki.index` entries to the `routes` dataset.

---

## ⛔ `feature-design` — do not merge

A single snapshot commit ("pre-migration state before laravel-start promotion", 2026-05-03) taken from a fork point that predates essentially all of HEAD's work — Network, the AI/LLM hub, co-working, custom error pages, the icon set, the card kit, the seeder rework, `well-known` routes, the sitemap builder. The true two-dot diff against HEAD is ~460 files, +5149/−14094. It is an archive, not a candidate.

Three findings worth recording, because each looks harmless and is not:

### Its `ui/*` kit collides destructively with HEAD's
`feature-design` and HEAD independently built component kits that share names but not contracts.
- **`ui/button`** drops the `type` prop and hardcodes `type="button"`. Dropping it in would **silently stop three form submits**: `app/network/manage.blade.php` (Save changes), `app/network/request.blade.php` (Request link), `app/ai/llm/analytics.blade.php` (filter form, which passes `type="submit"`). No error — the buttons just stop working.
- **`ui/button` and `ui/link`** make `$label` required. Every slot-only call site raises `Undefined variable $label`: `components/docuware-showme.blade.php`, `errors/partials/_error-page.blade.php`, `app/network/pages/baselhack.blade.php`, `app/ai/index.blade.php`, `app/ai/llm/index.blade.php`, `app/ai/llm/analytics.blade.php`.
- **`ui/link`** has no `download` prop (breaks `components/card/download-card.blade.php`) and drops `rel="noopener noreferrer"` on `target="_blank"`.
- **`ui/badge`** depends on a `.brand-pill` CSS class that does not exist on HEAD, and forces a redundant `title` equal to the visible text — an a11y regression.

### Its `resources/css/app.css` has no `@theme` block
HEAD is on Tailwind v4 with a `@theme` block defining `--color-brand`, `--color-brand-strong`, `--color-surface`, `--color-border`, `--color-border-soft`, `--color-muted`, `--radius-pill`, `--radius-panel`. Those generate `bg-brand`, `text-muted`, `border-border`, `rounded-pill`, `rounded-panel` — used site-wide. `feature-design`'s file defines a parallel `:root` variable set (`--brand-color`, `--surface-feature`, `--border-subtle`, `.hero-gradient`, `.surface-card`, `.editorial-divider`, …) with **zero overlap**, and no `@theme` at all. Overwriting the file turns every one of those utility classes into a no-op — a site-wide visual collapse. It also drops HEAD's `.legal-prose` list styling and contains a bogus `@safelist` directive that is not valid Tailwind v4.

### Its `resources/js/app.js` removes `Alpine.plugin(focus)`
That breaks `x-trap.inert.noscroll` in `_navigation_mobile.blade.php`. It also deletes HEAD's `autoSubmit`, `combobox` (a full a11y combobox with roving `aria-activedescendant`) and `tabs`, breaking `components/form/combobox.blade.php` and the AI analytics page. Its only addition is a richer `navigation` component — whose mobile drawer has `role="dialog"` but *no* focus trap and *no* scroll lock, i.e. an a11y regression against HEAD.

### Everything else
Nav and footer depend on the `Configuration` model (deleted on HEAD) and hardcode `info@paperflakes.ch`; the paperflakes/codebar brand partials were removed on purpose when the site went single-brand; `config/auth.php` was intentionally deleted on HEAD (Laravel 12 slim config); `config/responsecache.php` is the **older** spatie schema with `enabled => false`, so porting it would silently disable response caching; the `config/{health,nova,permission}.php` and `database/factories/*` "changes" are already byte-identical on HEAD.

### 🟡 The one idea worth keeping — a styleguide page
`app/Http/Controllers/Styleguide/StyleguideIndexController.php` + `resources/views/app/styleguide/index.blade.php` + a route. HEAD has **no visual index of its own component kit** — `grep -ri styleguide` over HEAD returns nothing. That is a genuine gap.

Treat it as a **fresh write, not a port** — the view is ~90% `feature-design` kit. The controller is 3 lines but passes `'page' => null`, which HEAD's `AppLayout` does not expect. Mapping if someone builds it:

| `feature-design` tag | HEAD equivalent |
|---|---|
| `x-blocks.editorial-hero` | `x-layout.page-header` (`teaser` → `intro`, drop `eyebrow`) |
| `x-ui.section` | `x-layout.section` |
| `x-ui.headline level="h1\|h2\|h3"` | `x-h1` / `x-h2` / `x-h3` (all take `:title`) |
| `x-ui.grid columns="3"` | `x-layout.grid :cols="3"` |
| `x-ui.card` | `x-ui.panel` |
| `x-ui.text` | `x-ui.prose` or raw `<p>` |
| `x-ui.feature-card` | `x-card.item-card` / `x-feature-block` |
| `x-ui.cta` | `x-band.cta-band` |
| `x-list-card` | `x-card.item-card` + `x-data.tag-list` |
| `x-blocks.meta-strip` | `x-data.meta-badges` (prop shape differs) |

No HEAD equivalent exists for `x-ui.eyebrow`, `x-ui.stack`, the `ghost` button variant or the `outline` badge variant — each is either a small new component or a dropped feature.

---

## `feature-refactor` — remainder

Closed by this pass (evidence above or on HEAD): AGB content, opening-hours widget, `SyncRepositoriesCommand`, Privacy/Terms un-redirect, Paperflakes seeder removal, `PageAction`/`ViewDataAction` changes, `Configuration` removal, nav component extraction, imprint address (HEAD also fixed the "Haupstrasse" typo), footer rewrite, `intro.blade.php`, `tests/Pest.php`.

Genuinely still missing — **all deliberately not done this pass**:

| Item | Where | Note |
|---|---|---|
| 🔴 `OpenSoruceShowController` typo | `app/Http/Controllers/OpenSource/OpenSoruceShowController.php` + `routes/web.php:26,64,107` | Misspelled class name, 3 references. `feature-refactor` already fixed it. Cheap hygiene. |
| 🟡 Company social links | `config/site.php` on the branch: `linkedin.com/company/codebarag`, `github.com/orgs/codebar-ag` | `x-icon.linkedin` / `x-icon.github` exist on HEAD but are used **only** on person cards (`card/person-card.blade.php`, `card/network-user-card.blade.php`). The company itself has no social links anywhere; the footer has no social row. |
| 🟢 Empty `routes/console.php` | still wired in `bootstrap/app.php` | Dead file. `feature-refactor` deleted both. |
| 🟢 `.gitignore` | missing `/laravel-start/` | The only entry HEAD lacks — everything else on `feature-design`'s list is already present. |
| 🟡 `ContactSectionEnum` split | `app/Enums/ContactSectionEnum.php` | Branch splits `EMPLOYEES` into Software Engineering / Digital Transformation / Scanning. Only worth it if `about-us.index` is re-enabled (it is currently in `disabled-routes`); needs coordinated edits to `ViewDataAction::contacts()`, `about-us/index.blade.php`, `database/seeders/data/contacts.csv` and lang keys. **The branch's own constant has a typo: `SOFTWARE_ENGINERING`.** |
| 🟢 `repositorySearch` Alpine data + open-source GitHub stat badges | `resources/js/app.js`, `open-source/show.blade.php` | Dead while `OpenSourceIndexController` redirects to start and the open-source routes sit in `disabled-routes`. Only relevant if that listing is re-enabled. |
| ⛔ `OpenSource` → `GithubRepository` rename | model + table + route param | **Rejected as churn.** HEAD already has every GitHub column (`github_name`, `stars`, `forks`, `primary_language`, `version`) folded into the original create-table migration. |
| 🟢 `vite` version | `package.json:20` pins `^6.3.6` | `node_modules` actually resolves **6.4.3**, so the manifest is behind reality. Dependabot PR #41 bumped it to `^6.4.1` on `feature-refactor` only. |
| 🟢 2 extra news entries | `NewsTableSeeder` | DocuWare 7.13 and "Hello World". Low value — news routes are disabled and HEAD's seeder has a different signature. |

---

## Still open — unchanged

### 🔴 High priority
- **`main`'s architecture rewrite** — flat-file `content/` system + `MarkdownContentService`, ~10 deleted legacy models, a larger UI kit, CSP/security hardening, custom error pages, `packages/coding-guidelines` + `AGENTS.md`/`boost.json`. Still an open question about whether that direction is live intent or an abandoned experiment. Needs an answer from whoever ran that migration on `main` before any further reconciliation.

### 🟡 Medium / low priority
- **`SyncRepositoriesCommand` title/teaser policy.** The command is ported but **deliberately not scheduled**: it overwrites `title`/`teaser` with the raw GitHub API `description` on every run, which would clobber the hand-written German/English marketing copy in `OpenSourceTableSeeder`. Decision needed: sync stats only (stars/forks/downloads) and keep hand-written teasers, or accept GitHub descriptions as the source of truth.
- **`OpenSourceTableSeeder` is broken and left commented out** in `DatabaseSeeder`. The structural bug was fixed (parameter renamed to `sharedSlug`, missing `Str::slug()` added), but all 16 call sites still pass `link:` / `downloads:` / `version:` that the method does not accept, and several `sharedSlug` values are corrupted auto-scraped strings (e.g. `'packagist-v1220-downloads-212k-laravel,-docuware,...'`). Recommendation: do not repair by hand — use `sync:repositories` as the data source once the question above is settled.
- **Opening hours need business confirmation.** The Contact page ships Mon–Sat with Sunday closed, drafted on `feature-refactor` and never independently verified. Contact is a live, linked page, so this ships with the branch.
- **Design-system reconciliation** — `feature-updates`, `main` and `feature-design` each built an incompatible `ui/*` kit. No action unless `main`'s direction is decided. See the `feature-design` section for why its kit specifically cannot be merged.
- **Custom error pages and CSP hardening from `main`** — real, isolated wins, portable independently of the content-model question.
- **Pages built but not surfaced.** `technologies.index`, `open-source.index`, `co-working.index`, `about-us.index` and (if merged) `gki.index` are all routed but unlinked. Presumably intentional pending real data, but worth an explicit decision as a group rather than case by case.
- **Pre-existing `add_`/`update_` migrations.** This project deploys via `migrate:fresh`, so the convention is to fold schema changes into the original create-table migration. Two files still break that: `2026_06_30_135300_update_codebar_seo_images.php` (a one-off data backfill — folding does not really apply) and `2026_07_14_120000_add_content_sections_to_products_table.php` (a genuine schema addition to `products`). Neither was touched.

---

## Decisions log

| Date | Decision |
|---|---|
| 2026-07-28 | **AGB §9 aligned EN → DE.** The two locales diverged: DE named no court, EN said "Basel-Landschaft or the registered office". English now matches German, consistent with the `Terms language body` clause stating the German version prevails. `lang/en_CH.json` — the only code change in this pass. |
| 2026-07-28 | **`feature-gki` documented, not merged.** The page is finished and cherry-pickable; merging is a separate, deliberate decision. |
| 2026-07-28 | **`feature-design` rejected.** Its `ui/*` kit, `app.css` and `app.js` would break HEAD in the specific ways listed above. Only the styleguide-page *idea* is retained, as a fresh write. |
| 2026-07-28 | **Branches left in place.** No deletions, no archive tags. `feature-design` and `feature-gki` are local-only and would be lost if deleted. |
| earlier | **Sitemap: `main`'s static `GenerateSitemapCommand` rejected.** The site runs on Laravel Cloud, where writing a static file on a schedule does not fit the platform model. HEAD's dynamic `SitemapController` / `SitemapBuilder` is the intended approach. |
| earlier | **Migration convention: fold into the original.** No `add_`/`update_` migration files — this project always runs `migrate:fresh`, so schema changes go straight into the create-table migration. Verified end-to-end with `php artisan migrate:fresh --seed`. |

---

## Full original gap tables

The file-by-file gap analysis from the first pass, preserved for reference. Note that several rows are now **out of date** — see "Corrections" above.

<details>
<summary>Click to expand full original analysis</summary>

### Branch relationship (at time of the original analysis)

| Comparison | Commits only in left | Commits only in right |
|---|---|---|
| `main` ↔ `feature-updates` | 52 (incl. the big `20ff73c` rewrite) | 68 |
| `main` ↔ `feature-refactor` | forked earlier (`bc47f78`), mostly superseded | 34 |
| `feature-updates` ↔ `production` | 0 | 20 (production behind, PR #76 open to catch it up) |

### Gap table: `main` → `feature-updates` (master)

| Area | On `main` | On master | Gap severity |
|---|---|---|---|
| Legal pages enabled | Terms/Jobs render real views | ~~Both redirect home~~ | ✅ Closed — both render on HEAD now |
| Content architecture | Flat-file `content/*.md` + `MarkdownContentService` | DB-seeded via Eloquent | 🔴 High |
| Legacy model layer | `Configuration/Contact/News/OpenSource/Page/Product/ProductModule/Reference/Role/Service/Technology/User` all deleted | All present, in use | 🔴 High |
| Co-working feature | Full page w/ gallery, map, pricing | ✅ migrated (simplified, inactive) | ✅ |
| `config/site.php` | Rich config w/ office data + intro copy | Same content hardcoded in blade | 🟢 Low |
| UI component kit | Much larger `ui/*` + `blocks/*` | Own smaller, independent kit | 🟡 Low–Med |
| Custom error pages | Full 401/403/404/419/429/500/503 set | Laravel defaults | 🟠 Medium |
| Security/CSP hardening | `SecurityPolicyBasic`, `CspAllowlist` | Basic `csp.php` only | 🟠 Medium |
| Sitemap generation | `GenerateSitemapCommand` (static) | Dynamic `SitemapController` | ✅ Closed — dynamic approach is intended on Laravel Cloud |
| `packages/coding-guidelines` | Full Boost-style skills package + `AGENTS.md` | Doesn't exist | 🟢 Low (dev tooling) |
| Observability config | Nightwatch, history, pillars, team, content | Doesn't exist | 🟡 Low–Med |
| Honeypot view | Published vendor view | Config only | 🟢 Low |

### Gap table: `feature-refactor` (PR #48) → `feature-updates` (master)

| Area | On `feature-refactor` | On master | Gap severity |
|---|---|---|---|
| Terms & Conditions content | Full AGB draft + review feedback | ✅ ported into `lang/*.json`, all feedback applied | ✅ |
| Opening-hours widget | Structured hours + live indicator | ✅ migrated | ✅ |
| GKI consulting service copy | Full marketing copy, 3 tiers | ✅ superseded by `feature-gki` (see above) | ✅ |
| `SyncRepositoriesCommand` | GitHub API auto-sync | ✅ ported (not scheduled) | 🟡 policy decision open |
| Everything else (nav extraction, Matrix well-known, model rename, `config/site.php` v1) | — | Already equivalent/superseded | ✅ No gap |

### Gap table: `feature-design` → `feature-updates` (added this pass)

| Area | On `feature-design` | On master | Verdict |
|---|---|---|---|
| `ui/*` component kit | 15 components, own contracts | 7 components, different contracts | ⛔ Collides — breaks 3 form submits + ~10 slot-only call sites |
| `blocks/*` component kit | 7 editorial blocks | `layout/*` + `card/*` + `band/*` | ⛔ Superseded |
| `resources/css/app.css` | `:root` vars, no `@theme` | Tailwind v4 `@theme` tokens | ⛔ Overwriting collapses site-wide styling |
| `resources/js/app.js` | Richer nav, no focus plugin | `autoSubmit` + `combobox` + `tabs` + focus | ⛔ Removes `Alpine.plugin(focus)`, breaks `x-trap` |
| Styleguide page | Controller + view + route | Doesn't exist | 🟡 Genuine gap — but fresh write, ~90% rewrite |
| Jobs page content | 3 invented English placeholder roles | Translation-key driven, no-openings panel | ⛔ HEAD is better |
| Services partnerships partial | 80-line inline SVG block | Replaced by the Network feature | ✅ Superseded |
| Nav / footer | `$configuration`-driven, paperflakes email | Static, single-brand, focus-trapped | ⛔ Cannot render on HEAD |
| `config/responsecache.php` | Old spatie schema, `enabled => false` | Current spatie v8 schema | ⛔ Would disable response caching |
| `config/auth.php` | Present | Intentionally deleted | ⛔ |
| `.gitignore` | adds `/laravel-start/` | missing that one line | 🟢 Trivial |

### Gap table: `feature-gki` → `feature-updates` (added this pass)

| Area | On `feature-gki` | On master | Verdict |
|---|---|---|---|
| GKI consulting page (3 tiers) | Controller + view + routes + SEO + 26 lang keys ×2 + tests | Doesn't exist | 🟡 Real gap — finished, dormant, cherry-pickable as `1e4dad8` |
| `HttpErrorPagesTest` Pest imports | Fixed | ✅ Already fixed | ✅ No gap |
| `RouteStatusTest` disabled-routes | Older list | Newer (has `about-us`, `co-working`) | ✅ HEAD is ahead |

</details>
