#!/usr/bin/env bash
# Promote a variation to the live SEO image.
# Usage: ./scripts/promote-og-variation.sh v11-midnight-bar

set -euo pipefail

SLUG="${1:-}"
if [[ -z "$SLUG" ]]; then
    echo "Usage: $0 <slug>"
    echo "Example: $0 v11-midnight-bar"
    exit 1
fi

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SRC="$ROOT/public/images/seo/variations/og-codebar-${SLUG}.svg"

if [[ ! -f "$SRC" ]]; then
    echo "Not found: $SRC"
    exit 1
fi

cp "$SRC" "$ROOT/public/images/seo/og-codebar.svg"
magick -background none -density 200 "$ROOT/public/images/seo/og-codebar.svg" -resize 1200x630! "$ROOT/public/images/seo/og-codebar.png"
magick "$ROOT/public/images/seo/og-codebar.png" -quality 92 "$ROOT/public/images/seo/og-codebar.webp"

echo "Promoted og-codebar-${SLUG} → public/images/seo/og-codebar.{svg,png,webp}"
