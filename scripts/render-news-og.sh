#!/usr/bin/env bash
#
# Renders the og:image PNG counterpart for every news hero SVG.
#
# Social crawlers do not render SVG, so app/Support/NewsImage::ogImage() looks for a
# same-named .png next to the .svg. The size is fixed at 1200x630 because that is what
# config/seo.php declares in og:image:width / og:image:height for every page.
#
# Usage: scripts/render-news-og.sh [file.svg ...]   (no args = all news placeholders)

set -euo pipefail

cd "$(dirname "$0")/.."

if ! command -v rsvg-convert >/dev/null 2>&1; then
    echo "rsvg-convert missing — install with: brew install librsvg" >&2
    exit 1
fi

files=("$@")

if [ ${#files[@]} -eq 0 ]; then
    files=(public/images/news/placeholders/*.svg)
fi

for svg in "${files[@]}"; do
    png="${svg%.svg}.png"
    rsvg-convert -w 1200 -h 630 "$svg" -o "$png"
    echo "rendered $png"
done
