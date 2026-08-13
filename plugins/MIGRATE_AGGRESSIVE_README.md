# Aggressive migration: README

This branch prepared migration helpers and plugin manifests. To perform an aggressive repository-level migration (move & remove legacy folders) run the script below locally in your cloned repository.

Steps (local):

1. Create a backup branch and push it:
   git checkout -b backup-before-aggressive-move
   git push origin backup-before-aggressive-move

2. Fetch the migration branch files (if you haven't already), then run the script:
   git fetch origin
   git checkout feature/move-modules
   chmod +x scripts/aggressive-migrate.sh
   ./scripts/aggressive-migrate.sh

3. Inspect changes, run your tests, then push the new branch:
   git push origin feature/move-modules-aggressive

4. Open a PR on GitHub from feature/move-modules-aggressive into master for review and merging.

Note: This script will attempt to use git mv, but will fallback to copying if git mv fails. It will create a .migrated marker in each plugins/<module>/www folder.
