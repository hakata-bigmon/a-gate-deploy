#!/bin/bash
set -e
WP_PATH="$1"
cd "$WP_PATH"

while IFS= read -r line; do
  [[ -z "$line" || "$line" =~ ^# ]] && continue
  slug="${line%@*}"; ver="${line#*@}"
  if [[ "$ver" =~ \.zip$ || "$ver" =~ ^https?:// ]]; then
    wp --path="$WP_PATH" plugin install "$ver" --force
  else
    if wp --path="$WP_PATH" plugin is-installed "$slug"; then
      wp --path="$WP_PATH" plugin update "$slug" --version="$ver"
    else
      wp --path="$WP_PATH" plugin install "$slug" --version="$ver"
    fi
  fi
done < /tmp/plugin-lock.txt