# Contributing to MultiCMS

Thanks for your interest. MultiCMS is a Fresh-core CMS (installer, `includes/`, `themes/default`, admin Posts/Settings/Flagship/Updates). Optional starters live under `sites/`.

## Before you start

1. Prefer changes to the modern core (`install.php`, `includes/`, `themes/default`, modern admin pages).
2. Do not commit `includes/db_config.php`, `env.php`, locks, or real credentials.
3. Do not reintroduce old Dreamweaver pack trees under `custom/` or `plugins/*/www`.

## Development notes

- PHP 7.4+ (8.x recommended)
- Before a PR:
  - `php scripts/php-lint-smoke.php` (syntax check)
  - `php scripts/smoke-phase2.php` (needs local MySQL on `127.0.0.1:3307` — same as CI)
- Keep security fixes focused and documented

## Pull requests

- One concern per PR when possible
- Describe whether the change affects **core**, **admin**, **flagship sites**, or **docs**

## Security

Report vulnerabilities privately to the contact in [SECURITY.md](SECURITY.md).
