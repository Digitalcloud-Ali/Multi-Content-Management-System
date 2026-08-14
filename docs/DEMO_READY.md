# MultiCMS demo / production checklist

## Automated

- [x] `php scripts/php-lint-smoke.php` — 0 failures
- [x] `composer dump-autoload` — succeeds
- [x] `php scripts/smoke-phase2.php` against Docker MySQL — **ALL PASS**
- [x] GitHub Actions: PHP Lint + MySQL acceptance smoke

## Publish gate (Phase 6)

- [x] `uploads/` shipped (with `.htaccess` denying PHP)
- [x] Fresh admin lands on `administrator/dashboard.php` (no fatal on missing pack tables)
- [x] `includes/raycms.sql` credentials scrubbed
- [x] `ENVIRONMENT` defaults to `production`; installer writes `includes/env.php`
- [x] `install.php` returns 403 when `installed.lock` exists
- [x] Legacy administrator POST handlers require CSRF

## Manual click-through (operator)

- [ ] Fresh install via `/install.php` (Fresh default)
- [ ] Admin login → dashboard → **Posts (core)** → published post on `index.php?page=blog`
- [ ] **Site Settings (core)** → change title
- [ ] Delete or block `install.php` on the host after install
- [ ] Ready-made packs treated as demos only

## Security baseline (honest)

- Prefer Fresh core for production
- Ready-made packs are Dreamweaver-era demos with partial hardening
- Do not commit `includes/db_config.php` or `includes/env.php`
- Do not import `raycms.sql` for live passwords (scrubbed; structure/demo only)
