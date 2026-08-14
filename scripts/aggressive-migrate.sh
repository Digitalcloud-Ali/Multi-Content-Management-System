#!/bin/bash
set -e

# Aggressive migration helper
# WARNING: This script will move legacy module folders into plugins/*/www and delete the legacy folders.
# Run locally in your cloned repository. Make a backup branch first.

if ! git diff --quiet; then
  echo "You have uncommitted changes. Commit or stash them before running this script." >&2
  exit 1
fi

read -p "This will create a new branch 'feature/move-modules-aggressive' and move legacy folders. Continue? [y/N] " confirm
if [[ "$confirm" != "y" && "$confirm" != "Y" ]]; then
  echo "Aborted by user."; exit 1
fi

git checkout -b feature/move-modules-aggressive

MODULES=(adposting marketplace portfolio productpublisher imagegallery videostream doctors searchengine tutorials blog)

for m in "${MODULES[@]}"; do
  if [ -d "$m" ]; then
    echo "Processing $m..."
    mkdir -p plugins/$m/www
    # try git mv if possible
    if git mv "$m" plugins/$m/www 2>/dev/null; then
      echo "Moved $m -> plugins/$m/www via git mv"
    else
      # fallback: copy then remove
      cp -a "$m"/* plugins/$m/www/ || true
      git add plugins/$m/www
      rm -rf "$m"
      git add -A
      echo "Copied $m -> plugins/$m/www and removed legacy folder"
    fi
    # add a marker file
    mkdir -p plugins/$m/www
    echo "migrated_on=$(date -u +%Y-%m-%dT%H:%M:%SZ)" > plugins/$m/www/.migrated
    git add plugins/$m/www/.migrated
  else
    echo "Legacy folder $m not found; skipping."
  fi
done

# Commit changes
git commit -m "Aggressive migration: move legacy modules into plugins/<module>/www and remove legacy folders"

echo "Done. Branch feature/move-modules-aggressive contains the moved modules. Push with:\n  git push origin feature/move-modules-aggressive"
