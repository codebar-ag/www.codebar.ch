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

# Bare, this covers both generations: the hand-authored illustrations in public/images/news/
# (prompts/illustration-news.md) and the generated placeholders one level down
# (prompts/images-news.md). Cards and squares live in both and are filtered out below.
if [ ${#files[@]} -eq 0 ]; then
    shopt -s nullglob
    files=(public/images/news/*.svg public/images/news/placeholders/*.svg)
    shopt -u nullglob
fi

if [ ${#files[@]} -eq 0 ]; then
    echo "no SVGs to render" >&2
    exit 1
fi

for svg in "${files[@]}"; do
    # Squares and cards are 1:1 and are never og:images. Forced into 1200x630 they come out
    # stretched, and the resulting PNG sitting next to the SVG is exactly what
    # NewsImage::ogImage() would then hand a social crawler. Refuse rather than produce it.
    case "$svg" in
        *-square.svg|*-card.svg)
            echo "skipped $svg — square, never an og:image" >&2
            continue
            ;;
    esac

    png="${svg%.svg}.png"
    rsvg-convert -w 1200 -h 630 "$svg" -o "$png"
    echo "rendered $png"
done
