# Ready-made site plugins (flagship, not core)

These packages are an **optional flagship plugin layer**. The MultiCMS **core** is the modern install + `themes/default` front door.

```
plugins/<slug>/
  plugin.json      # type: "site"
  src/*Plugin.php  # activation helpers
  www/             # legacy front-end site package
  schema.sql       # optional pack-specific SQL (Phase 2+)
```

## Install choices

During `install.php` → Site Configuration:

1. **Fresh default** — modern MultiCMS theme (`selecttopic=default`) — recommended
2. **Ready-made site** — apply a legacy site pack (quarantined; limited security hardening)

Switch later from **Admin → Ready Sites** (`administrator/plugins_prebuilt_sites.php`). Prefer **Admin → Posts (core)** for modern content.

## Pretty URLs (Phase 2)

When a pack is active, `index.php` **internally dispatches** to `plugins/<slug>/www/` (no browser redirect to `/plugins/.../www/`). Apache `.htaccess` rewrites unknown paths to `index.php?mc_route=...`.

To force the modern core while a pack is selected: `index.php?mc_core=1&page=blog`.

## Legacy tables on apply

`PluginManager::applySiteAsMain()` runs [`includes/sql/legacy_pack_tables.sql`](../includes/sql/legacy_pack_tables.sql) (CREATE TABLE IF NOT EXISTS, no seed passwords). If a modern `categories` table exists, it is renamed to `core_categories` so legacy `categories` can be created.

## Security note

Legacy packs are Dreamweaver-era code. Phase 1–2 patched critical auth, CSRF on main write forms, some XSS escaping, and safer member photo uploads. They are **not** fully modernized.

## Apply as main site

`PluginManager::applySiteAsMain($slug)` provisions legacy tables, calls `onActivate`, sets `settings.selecttopic`, and serves the pack from `/` via the front controller.
