# Contributing to MultiCMS

Thanks for your interest. MultiCMS is a Fresh-core CMS (installer, `includes/`, `themes/default`, admin Posts/Settings). Plugin packs will be redesigned later.

## Before you start

1. Read [plugins/README.md](plugins/README.md) for the core vs plugin boundary.
2. Prefer changes to the modern core over large rewrites of legacy packs.
3. Do not commit `includes/db_config.php`, locks, or real credentials.

## Development notes

- PHP 7.4+ (8.x recommended)
- Before a PR:
  - `php scripts/php-lint-smoke.php` (syntax check)
  - `php scripts/smoke-phase2.php` (needs local MySQL on `127.0.0.1:3307` — same as CI)
- Keep security fixes focused and documented

## Pull requests

- One concern per PR when possible
- Describe whether the change affects **core**, **admin**, or **legacy packs**
- Note any intentional leftover legacy risk

## Security

Report vulnerabilities privately to the contact in [SECURITY.md](SECURITY.md).
