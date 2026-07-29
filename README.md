<p align="center">
  <img src="public/images/logos/codebar-logo-colored.svg" alt="codebar Solutions AG" width="260">
</p>

<h1 align="center">www.codebar.ch</h1>

<p align="center">
  The public marketing website of <a href="https://www.codebar.ch">codebar Solutions AG</a> — a small software company based in the Basel region, Switzerland. Laravel, Blade and Tailwind, bilingual (DE/EN), content managed as version-controlled files rather than through an admin panel.
</p>

## Contents

- [Content architecture](#content-architecture)
- [Localization](#localization)
- [SEO](#seo)
- [LLM usage analytics](#llm-usage-analytics)
- [Local development](#local-development)
- [Testing & code quality](#testing--code-quality)
- [Deployment](#deployment-laravel-cloud)
- [Key packages](#key-packages)

## Content architecture

Almost everything editorial — pages, news articles, team members, services, products, technologies, network partners, the AI model catalogue — lives as YAML or Markdown files under `database/files/`, not as data entered through a CMS. The files are the source of truth; the database is a rebuildable cache of them.

```
database/files/
├── ai_models/        one YAML file per model
├── networks/          one YAML file per partner
├── news/{locale}/      one Markdown file per article, per language
├── pages/              one YAML file per page (SEO metadata: title, description, robots, image)
├── products/{locale}/
├── services/{locale}/
├── team/                one YAML file per person
└── technologies/{locale}/
```

Each content type has a matching import command that reads its files and upserts the database, and is safe to run repeatedly:

| Command | Reads |
|---|---|
| `php artisan pages:import` | `database/files/pages/*.yaml` |
| `php artisan news:import` | `database/files/news/{locale}/*.md` |
| `php artisan team:import` | `database/files/team/*.yaml` |
| `php artisan services:import` | `database/files/services/{locale}/*.md` |
| `php artisan products:import` | `database/files/products/{locale}/*.md` |
| `php artisan technologies:import` | `database/files/technologies/{locale}/*.md` |
| `php artisan networks:import` | `database/files/networks/*.yaml` |
| `php artisan ai-models:import` | `database/files/ai_models/*.yaml` |
| `php artisan sync:repositories` | live GitHub repositories (open-source content) |

`database/seeders/DatabaseSeeder.php` calls every one of these on `db:seed` — including in production. That is intentional: these seeders don't generate test fixtures, they publish the real content the same way running the command by hand would. The one exception is `AiModelDailyUsagesTableSeeder`, which loads a static local-dev fixture rather than real usage data (see [LLM usage analytics](#llm-usage-analytics)).

A file that disappears from `database/files/` removes the matching row on the next import — the importers are the single source of truth in both directions.

## Localization

The site ships in German (`de_CH`) and English (`en_CH`) under distinct, fully translated URL prefixes rather than a shared `/en/…` segment — e.g. `/dienstleistungen` and `/services`, `/aktuelles` and `/news`, `/ueber-uns` and `/about-us`. Every localized route pair cross-references the other via `hreflang`, including on detail pages where the slug itself is translated (`routes/web.php`, `resources/views/layouts/_partials/_seo.blade.php`).

## SEO

- **Structured data** — a single `@graph` JSON-LD payload (`App\Seo\SchemaGraph` / `App\Seo\SchemaNodes`) built from `config/company.php`, the one source of truth for the company's name, addresses, phone and `sameAs` profiles. Every page ships `Organization`, `WebSite`, `WebPage` and `BreadcrumbList` nodes; content pages add `Service`, `Person`, `BlogPosting`, etc.
- **Sitemap** — `App\Sitemap\SitemapBuilder` + `App\Http\Controllers\Sitemap\SitemapController` build `/sitemap.xml` from the same models the site renders, cached 24h via `Cache::remember`.
- **Response cache** — `spatie/laravel-responsecache` caches full HTTP responses for up to 7 days. `App\Observers\SitemapCacheObserver` drops the sitemap's own data cache on every relevant model save, and `responsecache:clear` runs hourly via the scheduler (`routes/console.php`) as a backstop for the full-page cache layer — these are two independent caches and both need clearing after a bulk content change (`php artisan responsecache:clear` after a fresh `db:seed`, for instance).
- **Social images** — article heroes that are local SVG placeholders (no real photography yet) are not usable as `og:image` — social crawlers don't render SVG. `App\Support\NewsImage::ogImage()` falls back to a same-named `.png` rendered from the SVG when one exists, or the site's default share image otherwise.
- **Tests** — `tests/Feature/Seo/` asserts on the actual rendered JSON-LD and meta tags (not just "does it look right"), and `tests/lighthouse/` audits real Lighthouse scores against the built (`npm run build`) output, not the Vite dev server.

## LLM usage analytics

`/ki` (`/ai`) publishes aggregate usage figures for the AI models codebar runs internally via a self-hosted [LiteLLM](https://www.litellm.ai/) proxy. `php artisan llm:fetch-analytics` pulls per-day usage from the proxy's `/spend/logs` endpoint and stores it in `ai_model_daily_usages`; it's scheduled hourly but only backfills the last 3 days by default — use `--full` (syncs from 2026-01-01) or `--from`/`--to` to backfill a specific range. Spend figures are stored but must never be displayed publicly. The dashboard intentionally shows monthly/yearly aggregates only, never a daily breakdown.

## Local development

```bash
composer install
cp .env.example .env
php artisan key:generate

php artisan migrate --seed   # imports real content — see "Content architecture" above

npm install
npm run build   # or `npm run dev` for the Vite dev server
```

If you use [Herd](https://herd.laravel.com):

```bash
herd link
herd secure
herd open
```

If you use [Valet](https://laravel.com/docs/valet):

```bash
valet link
valet secure
valet open
```

> Set `valetTls: 'your-domain.test'` below `refresh: true` in `vite.config.js` if you use `valet secure` / `herd secure`.

## Testing & code quality

```bash
./vendor/bin/pest              # test suite (Pest)
./vendor/bin/phpstan analyse   # static analysis (Larastan, see phpstan.neon.dist)
./vendor/bin/pint              # code style
./vendor/bin/pint --blade      # blade formatting (beta)
```

## Deployment (Laravel Cloud)

The site runs on [Laravel Cloud](https://cloud.laravel.com), behind Cloudflare. Required environment variables for security headers:

- `CSP_ENABLED=true` — enables Content-Security-Policy enforcement via Spatie CSP middleware
- `FPH_ENABLED=true` — enables Permissions-Policy headers

HSTS, COOP, `X-Content-Type-Options`, `Referrer-Policy` and `X-Frame-Options` are applied automatically by the `SecurityHeaders` middleware on every web response.

**Lighthouse note:** deprecated-API warnings for `/cdn-cgi/challenge-platform/scripts/jsd/main.js` come from Cloudflare's bot protection, injected at the edge — not from application code. Run Lighthouse in incognito without extensions for an accurate score, and prefer `tests/lighthouse/` (which audits the built output) over ad-hoc runs against the dev server.

## Key packages

- **Content & storage** — `spatie/laravel-translatable`, `codebar-ag/laravel-flysystem-cloudinary` (editorial images), `league/flysystem-aws-s3-v3` (DigitalOcean Spaces for other assets), `symfony/yaml`
- **SEO** — `spatie/laravel-sitemap`, `spatie/laravel-responsecache`
- **Security & health** — `spatie/laravel-csp`, `spatie/laravel-honeypot`, `spatie/laravel-permission`, `spatie/laravel-health`, `spatie/security-advisories-health-check`, `mazedlx/laravel-feature-policy`
- **Ops** — `laravel/nightwatch` (observability), `symfony/postmark-mailer`
- **Analytics** — [Fathom](https://usefathom.com) (privacy-friendly, no cookie banner)

## License

The application code is proprietary to codebar Solutions AG. The underlying Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
