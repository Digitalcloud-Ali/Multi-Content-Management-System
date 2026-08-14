# Multi-Content Management System (MultiCMS)

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](LICENSE.md)

Open-source PHP/MySQL CMS. The **product core** is a modern installer + default theme. **Ready-made sites** are an optional flagship **plugin** layer (legacy packs under `plugins/`), not the core itself.

## Status (honest)

Phase 1–2 modernization focuses on:

- Fresh install that writes/reads `includes/db_config.php`
- Modern `users` / `posts` / `core_categories` schema for the core front door
- Auth fixes (hashed passwords; legacy `isAuthorized` gate closed)
- Quarantined ready-made packs with path bootstrap + critical auth patches
- **Phase 2:** pretty front-controller URLs for active packs, legacy table bootstrap on apply, Admin → Posts (core), targeted CSRF/XSS/upload hardening, Composer + CI lint

Ready-made packs are still largely Dreamweaver-era code. Prefer the fresh core for production. See [plugins/README.md](plugins/README.md).

## Requirements

- PHP 7.4+ (8.x recommended)
- MySQL 5.7+ / MariaDB 10.2+
- Extensions: mysqli, gd, curl
- Writable `includes/` (for install) and ideally an `uploads/` directory
- Apache `mod_rewrite` (or nginx equivalent) for pretty pack URLs

## Quick start

1. Upload or clone this repository to your web root.
2. Optionally run `composer dump-autoload` (classmap for core includes).
3. Visit `/install.php` and complete the wizard.
4. Choose **Fresh default** (recommended) or optionally a ready-made site pack.
5. For production, set `ENVIRONMENT` to `production` in [`includes/bootstrap.php`](includes/bootstrap.php).
6. Delete or block `install.php` after install if your host allows; keep `includes/installed.lock`.

Do **not** commit `includes/db_config.php`.

## Architecture

| Layer | Path | Role |
|-------|------|------|
| Core | `index.php`, `includes/`, `themes/default/`, `install.php` | Primary product |
| Admin | `administrator/` (Posts core + Ready Sites) | Legacy admin + modern posts |
| Ready-made sites | `plugins/<slug>/www/` | Optional legacy site packs (served via front controller when active) |
| Theme packages | `plugins/prebuilt-sites/` | Optional theme demos |

## Documentation

- [Installation guide](docs/INSTALLATION_GUIDE.md)
- [Plugins / ready-made sites](plugins/README.md)
- [Security policy](SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## Development smoke check

```bash
php scripts/php-lint-smoke.php
# or
composer lint
```

## License

See [LICENSE.md](LICENSE.md) (GPLv3).
