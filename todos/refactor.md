# Refactor / Branch-Consolidation TODOs

**Origin:** gap analysis comparing `feature-updates` (master/current) against `main` and `feature-refactor` (PR #48), done ahead of retiring `feature-refactor` / `feature-design` in favor of `feature-updates` → `production`.

Status legend: ✅ done this session · 🟡 partially done / decision needed · 🔴 open

---

## Done this session

### ✅ Co-working page — migrated, left inactive (same pattern as Technologies)
- `app/Http/Controllers/CoWorking/CoWorkingIndexController.php` — new controller, no dependency on `main`'s flat-file `content/` system (that's a separate architecture question, see below). Content is inlined using master's existing `PageAction`/component conventions.
- `resources/views/app/co-working/index.blade.php` — built with master's existing design system (`x-layout.*`, `x-card.address-card`, `x-feature-block`, `x-ui.*`) rather than porting `main`'s bigger, dependency-heavy version (Leaflet map, Alpine lightbox gallery, `x-blocks.pricing-card` — none of which exist on master, and `main`'s own gallery images were never actually added, just a placeholder README).
- Routes added in `routes/web.php` (`co-working` under both locale groups), named `co-working.index` — **registered but not linked in nav**, matching how `technologies.index` is currently dormant (link commented out in `_navigation_desktop.blade.php`).
- SEO metadata added to `PagesTableSeeder::enCH()`/`deCH()` under the existing "pages not yet shipped to production" section, alongside `technologies.index`.
- Content ported from `main`'s `content/co-working/{de_CH,en_CH}/page.md` (location: Langegasse 39, Oberwil — same building as the existing Oberwil branch office on the Contact page; services/amenities list; pricing CHF 750/month; rental conditions). Full interactive gallery/map was intentionally simplified, not ported 1:1 — see note above.
- **To activate later:** uncomment the nav link (same place Technologies' would go) once the page is ready to ship, and consider whether a real photo gallery is wanted before launch.

### ✅ Opening-hours widget — migrated into the Contact page
- `resources/views/components/opening-hours.blade.php` — ported from `feature-refactor`, adapted to master's design tokens (`brand` color instead of hardcoded blue).
- `ContactIndexController` now builds and passes `openingHours` (Mon–Sat 08:00–18:00, Sun closed — as drafted in `feature-refactor`).
- Wired into `resources/views/app/contact/index.blade.php`, right above the existing "Locations" section.
- Added day-name + widget copy translations to `lang/en_CH.json` / `lang/de_CH.json` (Monday–Sunday, "Opening hours", "Currently open/closed", "Closed", `:open to :close`).
- **Live immediately** — Contact is an active, linked page, so this ships as soon as this branch does. Worth a quick confirmation from whoever owns the business info that Mon–Sat 08:00–18:00 is still accurate (it was a `feature-refactor` draft, never independently verified against real hours).

### ✅ Sitemap — decision made, item closed
- Master already generates `sitemap.xml` dynamically via `SitemapController`/`SitemapBuilder` on each request. `main`'s static `GenerateSitemapCommand` approach is **explicitly rejected** — the site runs on Laravel Cloud, where writing a static file to disk on a schedule doesn't fit the platform model the way a dynamic route does. No code change needed; this gap is closed as "intentionally not adopting `main`'s approach," not as "ported."

### 🟡 Open-source repo sync (`SyncRepositoriesCommand`) — schema aligned, command ported, one new bug found and fixed, one bigger issue surfaced
- **Schema aligned:** `github_name`, `stars`, `forks`, `primary_language` added directly to the original `database/migrations/2025_06_24_063710_create_open_sources_table.php` (per explicit instruction: this project always deploys via `migrate:fresh`, no persisted migration history to preserve across environments — so no separate add-column migration, just clean up the original).
- **Command ported:** `app/Console/Commands/SyncRepositoriesCommand.php`, adapted to master's existing `OpenSource` model (master never did the `OpenSource` → `GithubRepository` rename from `feature-refactor`, so this targets the model that actually exists).
- **Config wired:** `config/services.php` gets a `github.token` entry, `.env.example` gets `GITHUB_TOKEN=`.
- **Not scheduled automatically** — deliberately. This command overwrites `title`/`teaser` with the raw GitHub API `description` field on every run. The existing hand-curated `OpenSourceTableSeeder` has real German/English marketing copy per package (e.g. "Nahtlose Integration von Zendesk-Supportfunktionen...") that a scheduled sync would silently clobber with terser English GitHub descriptions. **Needs a decision**: keep hand-written teasers and only sync stats (stars/forks/downloads), or accept GitHub's descriptions as the source of truth. I did not make that call silently.
- **Bigger discovery while checking "seeders still working":** `OpenSourceTableSeeder` is currently **commented out** in `database/seeders/DatabaseSeeder.php` — and it turns out that's not a style choice, it's broken:
  - Its private `seed()` method took `string $identifier`, but every one of its 16 call sites passes a named `sharedSlug:` argument — an immediate fatal error.
  - It matched rows on a non-existent `identifier` column (the `open_sources` table has no such column) instead of `slug`, and referenced an undefined `$slug` variable.
  - **Fixed** the structural bug (renamed the parameter to `sharedSlug` and added the missing `Str::slug()` call, matching the working pattern in `ProductsTableSeeder`/`ServicesTableSeeder`).
  - **Not fixed, and not recommended to fix by hand:** every one of the 16 call sites also passes `link:`, `downloads:`, `version:` that the method still doesn't accept — and several of the `sharedSlug` values are corrupted, auto-scraped garbage (e.g. `'packagist-v1220-downloads-212k-laravel,-docuware,-codebar-ag,-docuware,...'` instead of a real package slug). This isn't a typo, it's bad underlying data. I left `OpenSourceTableSeeder` commented out in `DatabaseSeeder` rather than patch ~300 lines of corrupted seed data.
  - **Recommendation:** don't repair this seeder — use `sync:repositories` as the actual data source for the open-source listing going forward, since it pulls clean names/slugs straight from the GitHub API. Decide the title/teaser question above, then this item is fully closeable.
- Also worth noting: like Technologies and now Co-Working, the **Open Source page itself is not linked in nav or footer either** — it's in the same "built, routed, but not surfaced" bucket, which is presumably intentional pending real data.

**Migration note (updated):** initially added this as a separate `add_github_columns_to_open_sources_table` migration out of caution (editing an already-run migration is normally unsafe). Corrected per direct instruction — this project doesn't preserve migration history across deploys, it always runs `migrate:fresh`, so the convention here is to fold schema changes straight into the original create-table migration and keep the migrations folder clean rather than accumulate `add_`/`update_` files. Folded the 4 columns into `2025_06_24_063710_create_open_sources_table.php` and deleted the separate migration. Verified with a full local `php artisan migrate:fresh --seed` — runs clean end-to-end.

**Note:** two other pre-existing `add_`/`update_` style migrations are still in the list (`2026_06_30_135300_update_codebar_seo_images.php` — a one-off data backfill, not a schema change, so folding doesn't really apply — and `2026_07_14_120000_add_content_sections_to_products_table.php`, a genuine schema addition to `products`). I didn't touch either since they're not mine and weren't part of this ask — flagging in case the same fold-into-original cleanup is wanted there too.

---

## Still open (unchanged from the original gap analysis — see below for full detail)

### 🔴 High priority
- **Terms & Conditions (AGB) content** — still redirect-home + empty `terms.md` on master. The 188-line German AGB draft + unresolved legal review notes (`terms_feedback.md`) still exist **only** on `feature-refactor`. Not touched this session — this is a legal/business call, not a mechanical port.
- **Jobs page content** — still redirect-home on master. `feature-design` only flips the redirect off; no real listing copy exists anywhere yet.
- **`main`'s architecture rewrite** (flat-file `content/` system, ~10 deleted legacy models, bigger UI kit, CSP/security hardening, custom error pages, `packages/coding-guidelines` + `AGENTS.md`/`boost.json`) — still an open, much bigger question about whether that direction is live intent or an abandoned experiment. Needs an answer from whoever ran that migration on `main` before any further reconciliation work.

### 🟡 Medium / low priority
- GKI consulting service copy (Strategy/Sprint/Build) from `feature-refactor` — confirm with business side whether it's still wanted; no equivalent exists on master.
- Design-system reconciliation — master, `main`, and `feature-design` each built their own incompatible `ui/*` component kit. No action needed unless/until `main`'s direction is decided.
- Custom error pages, CSP/security hardening from `main` — real, isolated wins, portable independent of the bigger content-model question.

---

## Full original gap tables

The complete file-by-file gap analysis (main ↔ master, `feature-refactor` ↔ master) is preserved below for reference.

<details>
<summary>Click to expand full original analysis</summary>

### Branch relationship (at time of analysis)

| Comparison | Commits only in left | Commits only in right |
|---|---|---|
| `main` ↔ `feature-updates` | 52 (incl. the big `20ff73c` rewrite) | 68 |
| `main` ↔ `feature-refactor` | forked earlier (`bc47f78`), mostly superseded | 34 |
| `feature-updates` ↔ `production` | 0 | 20 (production behind, PR #76 open to catch it up) |

### Gap table: `main` → `feature-updates` (master)

| Area | On `main` | On master | Gap severity |
|---|---|---|---|
| Legal pages enabled | Terms/Jobs render real views | Both redirect home | 🔴 High |
| Content architecture | Flat-file `content/*.md` + `MarkdownContentService` | DB-seeded via Eloquent | 🔴 High |
| Legacy model layer | `Configuration/Contact/News/OpenSource/Page/Product/ProductModule/Reference/Role/Service/Technology/User` all deleted | All present, in use | 🔴 High |
| Co-working feature | Full page w/ gallery, map, pricing | ✅ migrated (simplified, inactive) this session | 🟠→✅ |
| `config/site.php` | Rich config w/ office data + intro copy | Same content hardcoded in blade | 🟢 Low |
| UI component kit | Much larger `ui/*` + `blocks/*` | Own smaller, independent kit | 🟡 Low–Med |
| Custom error pages | Full 401/403/404/419/429/500/503 set | Laravel defaults | 🟠 Medium |
| Security/CSP hardening | `SecurityPolicyBasic`, `CspAllowlist` | Basic `csp.php` only | 🟠 Medium |
| Sitemap generation | `GenerateSitemapCommand` (static) | Dynamic `SitemapController` | ✅ Closed — dynamic approach is the intended one on Laravel Cloud |
| `packages/coding-guidelines` | Full Boost-style skills package + `AGENTS.md` | Doesn't exist | 🟢 Low (dev tooling) |
| Observability config | Nightwatch, history, pillars, team, content | Doesn't exist | 🟡 Low–Med |
| Honeypot view | Published vendor view | Config only | 🟢 Low |

### Gap table: `feature-refactor` (PR #48) → `feature-updates` (master)

| Area | On `feature-refactor` | On master | Gap severity |
|---|---|---|---|
| Terms & Conditions content | Full AGB draft + review feedback | Empty, disabled | 🔴 High |
| Opening-hours widget | Structured hours + live indicator | ✅ migrated this session | 🟠→✅ |
| GKI consulting service copy | Full marketing copy, 3 tiers | No equivalent | 🟡 Low–Med |
| `SyncRepositoriesCommand` | GitHub API auto-sync | ✅ ported this session (not scheduled) | 🟢→🟡 |
| Everything else (nav extraction, Matrix well-known, model rename, `config/site.php` v1) | — | Already equivalent/superseded | ✅ No gap |

</details>
