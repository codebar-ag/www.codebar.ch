---
name: new-page
description: Checklist for adding a public page to www.codebar.ch (web.codebar) or reviewing one before release. Use when a page, landing page, calculator or section gets its own URL, and before any release PR that contains a new route. Covers routes in both languages, texts, SEO metadata, the preview illustration, sitemap, structured data, tests and the manual import on production.
---

# A new page on www.codebar.ch

A page is not finished when it renders. It is finished when a crawler, a chat preview and the
sitemap all describe it correctly **on production**. On 2026-11-18 the Odoo pricing page went
live without its page metadata and without a preview illustration: no title, `noindex`, no Open
Graph tags, no sitemap entry. Every item below exists because one of those was missed.

## 1. Show the page first

When asked for a concept, build the real Blade page with `<x-app-layout>` and the existing
components and show it on `https://web.codebar.test`. Sebastian judges the real page, not a
description. Keep background (decisions, sources, open points) out of the repository unless
Sebastian asks for a concept file.

## 2. Code

- [ ] Route in **both** locale groups in `routes/web.php`, same route name, translated path.
- [ ] Texts in `lang/de_CH/components.php` and `lang/en_CH/components.php`. Plain Swiss business
      German, full sentences, «ss» instead of «ß».
- [ ] Controller uses `PageAction(... routeName: '<key>')->default()` and passes `schema`.
- [ ] No explanatory comments in code.
- [ ] Alpine components go through `Alpine.data()` in `resources/js/app.js` (CSP build): no inline
      expressions, numbers and labels through `data-` attributes.
- [ ] `npm run build` and `php artisan responsecache:clear` after every Blade change.

## 3. SEO and marketing, all of it

- [ ] `database/files/pages/<key>.yaml` with `robots`, `title`, `description` in both languages
      **and `image: images/pages/<key>.svg`**.
- [ ] **Preview illustration**: `public/images/pages/<key>.svg` plus the rendered PNG. Follow
      `prompts/illustration-seo-card.md` (read `prompts/illustration-services.md` first):
      one sentence with a middle, an act no other page uses, an entry in
      `public/images/illustrations.json` whose quotes appear verbatim in the page's texts.
      Render with `scripts/render-news-og.sh public/images/pages/<key>.svg`, then
      `scripts/check-illustrations.py pages` must pass. Look at the PNG.
- [ ] `SitemapController::DEFAULT_ROUTES` contains the route.
- [ ] `PageNavigation::chain()` sends the page somewhere.
- [ ] Structured data: `WebPage` and `BreadcrumbList` come from the graph; add page nodes through
      `SchemaNodes` (for questions: `SchemaNodes::faqPage()`).
- [ ] A link to the page from where a visitor would look for it.

## 4. Tests

- [ ] `tests/Feature/Controllers/RouteStatusTest.php` lists the route in both locales.
- [ ] A feature test for content, title, canonical, hreflang, robots, JSON-LD and the sitemap
      (seed `PagesTableSeeder` for those).
- [ ] Both language arrays have the same keys.
- [ ] Vitest for any Alpine component, including empty, negative and out-of-range input.
- [ ] Locally: `vendor/bin/pint --dirty`, `vendor/bin/phpstan analyse`, `php artisan test --parallel`,
      `npm test`. CI also enforces 90 % coverage and 90 % type coverage.

## 5. Release

Commit on `main`, push, release PR `main` → `production`. Sebastian merges:
`gh pr merge <n> --merge --admin -R codebar-ag/www.codebar.ch`.

## 6. After the deploy: the step that is easy to forget

**The deploy does not import content.** Its command only runs `migrate`; `db:seed` is commented
out. Until the import runs on production the page has no title, no description, no Open Graph
tags, `noindex`, no JSON-LD page node and no sitemap entry, and it looks perfectly fine in a
browser.

Sebastian runs (commands on production are not available to the agent):

```
cloud command:run <production environment id> --cmd="php artisan pages:import && php artisan responsecache:clear && php artisan cache:forget sitemap_xml" -n
```

The environment ID is in `~/Projects/infrastructure/laravel-cloud/projects/www.codebar.ch/production/environment.json`
(not in this repository, which is public). Say this **before** the merge, not after.

## 7. Verify on production

Only report the page as done when all of these are true on `https://www.codebar.ch`:

```bash
curl -s -A "Mozilla/5.0" https://www.codebar.ch/<path> | grep -oE '<title>[^<]*|name="robots" content="[^"]*"|property="og:(title|image)" content="[^"]*"|hreflang="[^"]*"'
curl -s -A "Mozilla/5.0" https://www.codebar.ch/sitemap.xml | grep '<path>'
```

- [ ] Title and description are the page's own, in both languages.
- [ ] `robots` is `index,follow`.
- [ ] `og:image` is `images/pages/<key>.png`, **not** `images/seo/og-codebar.png`.
- [ ] The JSON-LD graph contains `WebPage` and the page's own nodes.
- [ ] Both URLs are in the sitemap.

Chat clients cache previews per URL; test a fresh preview with `?v=2` appended.
