#!/bin/sh
set -e
cd "$(dirname "$0")/../.."
npm run build
tmp="$(mktemp -d)"
npx esbuild concepts/export/entry.js --bundle --format=iife --minify --outfile="$tmp/snapshot.js"
python3 concepts/export/export.py "$tmp"
