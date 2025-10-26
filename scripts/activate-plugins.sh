#!/bin/bash
set -e
WP_PATH="$1"
cd "$WP_PATH"

mapfile -t desired < <(grep -vE '^\s*#|^\s*$' /tmp/plugin-activate.txt | sort -u)
mapfile -t installed < <(wp --path="$WP_PATH" plugin list --field=name | sort -u)

for p in "${installed[@]}"; do
  wp --path="$WP_PATH" plugin deactivate "$p" 2>/dev/null || true
done

for p in "${desired[@]}"; do
  wp --path="$WP_PATH" plugin activate "$p" 2>/dev/null || true
done