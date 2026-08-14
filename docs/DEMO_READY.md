# MultiCMS demo-ready checklist

Use this before calling a release “demo-ready”.

## Automated

- [ ] `php scripts/php-lint-smoke.php` — 0 failures
- [ ] `composer dump-autoload` — succeeds
- [ ] `php scripts/smoke-phase2.php` against Docker MySQL — **ALL PASS**
  - Requires: `docker run ... -p 3307:3306` with `MYSQL_ROOT_PASSWORD=smoke_test_pass` / `MYSQL_DATABASE=multicms_smoke`

## Manual click-through

- [ ] Fresh install via `/install.php` (Fresh default)
- [ ] Admin login → **Posts (core)** → create published post → visible on `index.php?page=blog`
- [ ] **Site Settings (core)** → change title → reflects on front
- [ ] **Ready Sites** → apply Blog → `/` serves pack UI (no `/plugins/blog/www/` redirect)
- [ ] Pack register/contact rejects missing CSRF
- [ ] Upload rejects `.php` in member settings

## Security baseline (honest)

- [ ] `includes/db_config.php` not committed
- [ ] `ENVIRONMENT` = `production` on live
- [ ] `install.php` removed or blocked after install
- [ ] Prefer Fresh core for production; Ready-made packs are legacy demos

## Done when

Smoke green + manual checklist complete + this file’s automated boxes checked on `master`.
