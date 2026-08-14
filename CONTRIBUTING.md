# Contributing to MultiCMS

Thanks for your interest. MultiCMS core is `includes/` + `administrator/` + `install.php`. Site packaging lives under `content/` (themes, flagships, uploads).

## Before you start

1. Prefer changes to the modern core (`install.php`, `includes/`, `content/themes/default`, modern admin pages).
2. Do not commit `includes/db_config.php`, `env.php`, locks, or real credentials.
3. Do not reintroduce old Dreamweaver pack trees.

## Development notes

- PHP 7.4+ (8.x recommended)
- Before a PR:
  - `php .github/scripts/php-lint-smoke.php`
  - `php .github/scripts/smoke-phase2.php` (needs MySQL on `127.0.0.1:3307` — same as CI)

## Pull requests

- One concern per PR when possible
- Say whether the change affects **core**, **admin**, **content/sites**, or **docs**

## Security

Report vulnerabilities privately to the contact in [SECURITY.md](SECURITY.md).
