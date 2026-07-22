# Gap Analysis v2: `feature-updates` (master) vs. `main` and `feature-refactor` (PR #48)

**Date:** 2026-07-22
**Framing:** `feature-updates` (current branch, → `production`) is treated as the reference/master. This document tracks where **`main`** and **`feature-refactor`** contain functionality or content that master does **not** have.

## Headline finding

This reframing surfaced something bigger than the original PR #48 comparison: **`main` is not behind `feature-updates` — the two branches split into two different rewrites of the same site**, and `main` went much further architecturally. A single large commit on `main` (`20ff73c`, 2026-06-01, "wip") replaced the entire DB-seeded content model with a flat-file `content/` system, deleted ~10 legacy Eloquent models, added a much larger UI kit, custom error pages, CSP/security hardening, sitemap generation, and an internal "coding-guidelines" package — none of which ever made it into `feature-updates`. At the same time, `feature-updates` has 68 commits of its own (AI/llms.txt feature, dependabot bumps, various fixes) that `main` doesn't have.

In other words: neither branch is a strict superset of the other. If `production` (built on `feature-updates`) becomes the sole surviving line, **you would be shipping backwards** on everything in the table below unless it's deliberately re-integrated.

`feature-refactor` (PR #48), by contrast, really is the smaller/older of the three — it forked earlier and was mostly superseded, with the exceptions noted in §3.

---

## 1. Branch relationship (current tri-graph)

| Comparison | Commits only in left | Commits only in right |
|---|---|---|
| `main` ↔ `feature-updates` | **52** (incl. the big `20ff73c` rewrite) | **68** |
| `main` ↔ `feature-refactor` | forked earlier (`bc47f78`), mostly superseded | 34 |
| `feature-updates` ↔ `production` | 0 | 20 (production is behind, PR #76 open to catch it up) |

All three (`main`, `feature-refactor`, `feature-updates`) share the same common ancestor for the relevant comparison (`e3ef885`), except `feature-refactor` which forked slightly earlier (`bc47f78`).

---

## 2. Gap table: `main` → `feature-updates` (master)

Everything below exists on `main` and is **absent** from `feature-updates`/`production` right now.

| Area | On `main` | On master (`feature-updates`) | Gap severity |
|---|---|---|---|
| **Legal pages enabled** | `TermsIndexController` and `JobsIndexController` render real views (no redirect-home) | Both still `redirect()` to homepage behind commented-out code (`// @todo Notification`) | 🔴 High — main already fixed exactly the two disabled pages flagged in the original analysis |
| **Content architecture** | Flat-file `content/{news,products,services,open-source,technologies,co-working}/{locale}/*.md` + `App\Content\MarkdownContentService` / `ContentItem` | Still DB-seeded via `database/seeders/Codebar/*TableSeeder.php` + Eloquent models | 🔴 High — fundamentally different content pipeline; merging later means reconciling two content sources |
| **Legacy model layer** | `Configuration`, `Contact`, `News`, `OpenSource`, `Page`, `Product`, `ProductModule`, `Reference`, `Role`, `Service`, `Technology`, `User` models **all deleted**, along with their factories, policies, and Nova/permission config | All still present and in active use | 🔴 High — this is the crux of the divergence; any merge will be a real migration project, not a fast-forward |
| **Co-working feature** | New `CoWorkingIndexController` + `content/co-working/{de_CH,en_CH}/page.md` + view | Does not exist at all | 🟠 Medium — a real, shippable new business offering/page missing from master |
| **`config/site.php`** | Rich config: real office addresses (Zunzgen HQ + Oberwil branch, with lat/lng + Maps links), contact email/phone, per-section feature flags, and full bilingual "Who we are / How we work" intro copy | No `config/site.php`; office addresses are hardcoded directly in `resources/views/app/contact/index.blade.php` (content itself matches, just not centralized/data-driven) | 🟢 Low — same content, different (less flexible) implementation; not a content loss |
| **UI component kit** | Much larger `resources/views/components/ui/*` (button, card, hero, hero-home, feature-card, feature-row, logo-cloud, nav-link, quote, tag, footer-column, footer-link, section-header, divider, mono, lead…) + `blocks/*` (contact-cta, pricing-card, timeline, plus the ones also seen in `feature-design`) | Its own smaller, independently-built `ui/*` set (panel, row, link, prose, button, badge, badge-link) | 🟡 Low–Medium — two incompatible design systems now exist; worth a design-system reconciliation decision, not an urgent content loss |
| **Custom error pages** | Full set: `401,402,403,404,419,429,4xx,500,503,5xx` + partials (`_auth-error`, `_http-error-client/server`, `_simple`) | No custom error views — falls back to Laravel/browser defaults | 🟠 Medium — real UX/branding gap on error states (404, 500, maintenance, rate-limit) |
| **Security/CSP hardening** | `app/Security/SecurityPolicyBasic.php`, `app/Support/CspAllowlist.php`, `config/csp-allowlists.json`, expanded `config/csp.php`, `config/feature-policy.php` updates | `config/csp.php` exists but without the allowlist/security-policy layer | 🟠 Medium — a security-relevant hardening pass that didn't carry over |
| **Sitemap generation** | New `GenerateSitemapCommand`, updated `SitemapBuilder`/`SitemapController`, `SitemapControllerTest` removed in favor of new test | `SitemapController`/`SitemapBuilder` exist but not the generator command; old `SitemapControllerTest` still present | 🟡 Low–Medium — worth checking if sitemap output is now stale relative to the new content model |
| **`packages/coding-guidelines`** | A full internal package: Laravel Boost-style `SKILL.md` files (blade, controllers, models, testing, docuware, etc.), an `AGENTS.md` for AI-assisted coding, a `ValidateSkillsCommand`, and tests for it | Nothing — no `AGENTS.md`, no `boost.json`, no skills package | 🟢 Low (dev-tooling, not customer-facing) — but directly useful if you work on this repo with Claude Code/Boost; worth pulling over independent of the content-model question |
| **Observability/ops config** | `config/nightwatch.php` (Laravel Nightwatch), `config/history.php`, `config/pillars.php`, `config/team.php`, `config/content.php` | None of these exist | 🟡 Low–Medium — depends whether these tools are actually wired to a live service; verify before assuming they're "just config" |
| **Honeypot spam protection view** | `resources/views/vendor/honeypot/honeypotFormFields.blade.php` published | `config/honeypot.php` exists on master already, but no published view override | 🟢 Low |
| **Test suite renames/cleanup** | `phpunit.xml.dist` → `phpunit.xml`, `tests/Core/ClassnamesTest.php`, various renamed Enum tests, removed tests for deleted models | Old naming/structure retained (matches its still-present legacy models) | 🟢 Low — consequence of the model deletion, not independently actionable |

---

## 3. Gap table: `feature-refactor` (PR #48) → `feature-updates` (master)

Narrower — most of PR #48 is superseded. What's still uniquely present there and missing from master:

| Area | On `feature-refactor` | On master | Gap severity |
|---|---|---|---|
| **Terms & Conditions content** | Full 188-line German AGB draft in `database/files/legal/de_CH/terms.md`, plus `terms_feedback.md` with unresolved stakeholder review notes | `terms.md` empty (0 lines); page redirects home | 🔴 High — **note:** `main` independently re-enabled the Terms *page*, but `main` did **not** carry this drafted AGB content (its version of `terms.md` was deleted outright as part of the content-model migration). This draft + feedback currently exists **only** on `feature-refactor` and nowhere else. |
| **Opening-hours contact widget** | `ContactIndexController` passes structured weekly hours; new `<x-opening-hours>` component with live open/closed indicator | No such data or component | 🟠 Medium — small, self-contained, easy to port |
| **GKI consulting service copy** | `GkiServiceData` + `Ai{Index,Show}Controller` — "GKI Strategy/Sprint/Build" consulting packages with real German marketing copy | No equivalent copy anywhere (current AI section is about llms.txt generation, a different feature) | 🟡 Low–Medium — confirm with business side whether this offering is still wanted |
| **`SyncRepositoriesCommand`** | Automated GitHub org repo sync (stars/forks/downloads via API + Packagist) into the open-source listing | `OpenSourceTableSeeder` is fully hand-maintained | 🟢 Low — ops convenience, not content |
| Everything else (nav component extraction, Matrix well-known, `OpenSource`→`GithubRepository` rename, `config/site.php` v1) | — | Already equivalent or superseded on master | ✅ No gap |

---

## 4. What master has that neither `main` nor `feature-refactor` have

For completeness — these are *not* gaps, but confirm `feature-updates` isn't simply "behind":

- The AI/llms.txt feature (`AiLlmIndexController`, `AiLlmAnalyticsIndexController`, `llm:fetch-analytics` scheduled command) — merged from `feauter-llms`, exists only on master/production.
- 68 commits of dependabot bumps, CI fixes, and WIP work not present on `main`.

---

## 5. Recommendation

Given `main` contains an architecture rewrite this substantial, **the real decision isn't "can we delete `feature-refactor`/`feature-design`"** (yes, safely, modulo §3) — **it's whether `main`'s `20ff73c` rewrite is meant to become the site's actual direction, and if so, how/whether it reconciles with `feature-updates`/`production`.** Concretely:

1. Confirm with whoever ran the `20ff73c` migration on `main` (git blame says the same author) whether it's a live intent (new architecture going forward) or an abandoned experiment — the diff is too large and too central (deletes core models) to assume either way.
2. If it's the intended direction: treat merging `main` into `feature-updates` as its own planned migration project (content-model change, not a simple merge), and pull the Terms AGB draft + feedback from `feature-refactor` into it while you're at it, since `main`'s rewrite deleted the old `terms.md` without carrying that draft forward.
3. If `main`'s rewrite is not going forward: it still contains real, isolated wins worth cherry-picking regardless of the content-model question — custom error pages, CSP/security hardening, the co-working page, and the `packages/coding-guidelines`/`AGENTS.md` dev-tooling package.
4. Either way, port the opening-hours widget from `feature-refactor` — it's small and uncontroversial.
5. Once the Terms content and opening-hours widget are ported (or consciously dropped), `feature-refactor` can be deleted. `feature-design` was already self-archived by its own commit message and is safe to delete now. Resolve `main` vs. `feature-updates` as a separate, larger workstream before touching `main`.
