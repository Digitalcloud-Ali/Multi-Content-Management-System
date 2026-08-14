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

## Hooks (Phase 3)

Optional `plugins/<slug>/hooks.php` can register:

- `add_action('multicms_site_applied', …)` — after apply / fresh default
- `add_action('multicms_before_pack_dispatch', …)` / `multicms_after_pack_dispatch`
- `add_filter('multicms_active_site_public_url', …)`

Core also ships `includes/Hooks.php` (`do_action` / `apply_filters`).

## Legacy tables on apply

`PluginManager::applySiteAsMain()` runs [`includes/sql/legacy_pack_tables.sql`](../includes/sql/legacy_pack_tables.sql) (CREATE TABLE IF NOT EXISTS, no seed passwords). If a modern `categories` table exists, it is renamed to `core_categories` so legacy `categories` can be created.

## Security note

Ready-made packs are **legacy demos**. Prefer Fresh core for production. Phase 1–6 patched auth, CSRF on many write forms, XSS escaping, safer uploads, and admin CSRF — packs are still not fully modernized.

## Apply as main site

`PluginManager::applySiteAsMain($slug)` provisions legacy tables, calls `onActivate`, sets `settings.selecttopic`, and serves the pack from `/` via the front controller.
