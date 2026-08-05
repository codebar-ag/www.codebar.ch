#!/bin/bash
# Lighthouse audit sweep for web.codebar.
#
# Builds production assets, serves them (bypassing the Vite dev server, whose
# JS-injected CSS causes false-positive layout-shift scores), runs Lighthouse
# (desktop preset) against every page in pages.json, then restores dev mode.
#
# Usage:
#   tests/lighthouse/run.sh                 # all pages in pages.json
#   tests/lighthouse/run.sh home ai_index    # only the named pages
#
#   # the Zunscan sub-site, which lives on its own domain:
#   BASE_URL=https://zunscan.web.codebar.test \
#     PAGES_JSON=tests/lighthouse/pages.zunscan.json \
#     WARMUP_PATH=/de-ch \
#     tests/lighthouse/run.sh
#
# Env vars:
#   BASE_URL     default https://web.codebar.test
#   PAGES_JSON   default tests/lighthouse/pages.json
#   WARMUP_PATH  default /en-ch — any real page on BASE_URL; used only to poll
#                PHP-FPM workers until they serve the production build
#
# Output: tests/lighthouse/reports/<timestamp>/<name>.json + summary.md

set -uo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$DIR/../.." && pwd)"
BASE_URL="${BASE_URL:-https://web.codebar.test}"
RUN_LABEL="$(date +%Y%m%d-%H%M%S 2>/dev/null || echo run)"
OUT_DIR="$DIR/reports/$RUN_LABEL"
PAGES_JSON="${PAGES_JSON:-$DIR/pages.json}"
WARMUP_PATH="${WARMUP_PATH:-/en-ch}"

mkdir -p "$OUT_DIR"

cd "$REPO_ROOT"

echo "==> Building production assets"
npm run build

# spatie/laravel-responsecache caches full HTML responses independently of
# the Vite dev/prod check below — a page cached while dev mode was active
# (or vice versa) keeps serving that stale snapshot forever, immune to the
# worker warm-up loop further down. Always start from a clean slate.
echo "==> Clearing response cache"
php artisan responsecache:clear

HOT_STASH="${TMPDIR:-/tmp}/web-codebar-hot.$$"
HOT_MOVED=0
if [ -f public/hot ]; then
  mv public/hot "$HOT_STASH"
  HOT_MOVED=1
fi

restore_hot() {
  if [ "$HOT_MOVED" = "1" ] && [ -f "$HOT_STASH" ]; then
    mv "$HOT_STASH" public/hot
  fi
}
trap restore_hot EXIT

# Herd's PHP-FPM workers each cache file_exists(public/hot) independently, so a
# worker that served requests before the mv above may keep reporting dev mode
# for a while after. Hammer the homepage until every worker in the pool has
# picked up the change before trusting any Lighthouse run against this build.
echo "==> Waiting for all PHP-FPM workers to see the production build"
stale=1
for _ in $(seq 1 40); do
  stale=0
  for _ in $(seq 1 8); do
    if curl -sk "${BASE_URL}${WARMUP_PATH}" | grep -q 'vite/client'; then
      stale=1
      break
    fi
  done
  [ "$stale" = "0" ] && break
  sleep 0.5
done
if [ "$stale" = "1" ]; then
  echo "!! Still seeing dev-mode markup after retries — results may be unreliable. Consider 'herd restart'."
fi

# Filter pages.json down to requested names (all, if none given)
if [ "$#" -gt 0 ]; then
  FILTER_JSON=$(printf '%s\n' "$@" | jq -R . | jq -s .)
  PAGES=$(jq -c --argjson names "$FILTER_JSON" '[.[] | select(.name as $n | $names | index($n))]' "$PAGES_JSON")
else
  PAGES=$(jq -c '.' "$PAGES_JSON")
fi

COUNT=$(echo "$PAGES" | jq 'length')
echo "==> Running Lighthouse against $COUNT page(s), output -> $OUT_DIR"

echo "$PAGES" | jq -c '.[]' | while read -r page; do
  name=$(echo "$page" | jq -r '.name')
  path=$(echo "$page" | jq -r '.path')
  echo ">>> $name -> $path"
  # Same stale-worker guard, scoped to this page's own URL.
  for _ in $(seq 1 8); do
    curl -sk "${BASE_URL}${path}" | grep -q 'vite/client' || break
  done
  npx --yes lighthouse "${BASE_URL}${path}" \
    --output=json --output-path="$OUT_DIR/${name}.json" \
    --chrome-flags="--headless=new --ignore-certificate-errors" \
    --preset=desktop \
    --only-categories=performance,accessibility,best-practices,seo \
    --quiet || echo "FAILED: $name"
done

echo "==> Building summary"
{
  echo "# Lighthouse summary — $RUN_LABEL"
  echo
  printf '| %-20s | %-10s | %-6s | %-6s | %-13s | %-6s | %-6s |\n' "page" "perf" "a11y" "bp" "seo" "cls" "lcp"
  printf '|%s|%s|%s|%s|%s|%s|%s|\n' "----------------------" "------------" "--------" "--------" "---------------" "--------" "--------"
  for f in "$OUT_DIR"/*.json; do
    [ -e "$f" ] || continue
    name=$(basename "$f" .json)
    perf=$(jq -r '(.categories.performance.score // "n/a") | if type=="number" then .*100 else . end' "$f")
    a11y=$(jq -r '(.categories.accessibility.score // "n/a") | if type=="number" then .*100 else . end' "$f")
    bp=$(jq -r '(.categories["best-practices"].score // "n/a") | if type=="number" then .*100 else . end' "$f")
    seo=$(jq -r '(.categories.seo.score // "n/a") | if type=="number" then .*100 else . end' "$f")
    cls=$(jq -r '.audits["cumulative-layout-shift"].displayValue // "n/a"' "$f")
    lcp=$(jq -r '.audits["largest-contentful-paint"].displayValue // "n/a"' "$f")
    printf '| %-20s | %-10s | %-6s | %-6s | %-13s | %-6s | %-6s |\n' "$name" "$perf" "$a11y" "$bp" "$seo" "$cls" "$lcp"
  done
} | tee "$OUT_DIR/summary.md"

echo
echo "==> Reports saved in $OUT_DIR"
