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
# Env vars:
#   BASE_URL   default https://web.codebar.test
#
# Output: tests/lighthouse/reports/<timestamp>/<name>.json + summary.md

set -uo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$DIR/../.." && pwd)"
BASE_URL="${BASE_URL:-https://web.codebar.test}"
RUN_LABEL="$(date +%Y%m%d-%H%M%S 2>/dev/null || echo run)"
OUT_DIR="$DIR/reports/$RUN_LABEL"
PAGES_JSON="$DIR/pages.json"

mkdir -p "$OUT_DIR"

cd "$REPO_ROOT"

echo "==> Building production assets"
npm run build

HOT_MOVED=0
if [ -f public/hot ]; then
  mv public/hot public/hot.disabled-for-lighthouse
  HOT_MOVED=1
fi

restore_hot() {
  if [ "$HOT_MOVED" = "1" ] && [ -f public/hot.disabled-for-lighthouse ]; then
    mv public/hot.disabled-for-lighthouse public/hot
  fi
}
trap restore_hot EXIT

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
