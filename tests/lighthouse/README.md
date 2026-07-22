# Lighthouse audit sweep

Runs Google Lighthouse (Performance, Accessibility, Best Practices, SEO;
desktop preset) against every page listed in `pages.json`.

Requires the site reachable at `BASE_URL` (default `https://web.codebar.test`,
served by Herd) and `npx` (Node 18+).

## Usage

```bash
tests/lighthouse/run.sh                 # all pages
tests/lighthouse/run.sh home ai_index    # only the named pages (see pages.json for names)
```

The script builds production assets (`npm run build`) and temporarily moves
`public/hot` aside so Laravel serves the built assets instead of the Vite dev
server, then restores it afterwards (even on failure/Ctrl-C).

**Always audit against the production build, not the Vite dev server.** In
dev mode, `@tailwindcss/vite` injects CSS via JS instead of a blocking
stylesheet, which causes the nav (and anything else styled late) to flash
unstyled before snapping into place — Lighthouse records this as a massive,
entirely fake Cumulative Layout Shift (0.8–1.0 instead of the real ~0.01).
Perf/CLS numbers from dev mode are not meaningful; only audit with this
script.

## Output

Each run writes to `reports/<timestamp>/`:
- `<page-name>.json` — full Lighthouse report per page
- `summary.md` — score table (perf/a11y/bp/seo/CLS/LCP) across all pages run

`reports/` is gitignored — commit findings/fixes, not raw report dumps.

## Maintaining `pages.json`

Some entries are marked with a `note` because the underlying page currently
redirects to the homepage (WIP controllers, or no seeded records for
News/OpenSource). Update `pages.json` as those pages are built out — real
slugs for show pages, once the content and its controllers are back in place.
