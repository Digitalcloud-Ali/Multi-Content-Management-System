# Multi-Content Management System (MultiCMS)

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](LICENSE.md)

Open-source PHP/MySQL CMS. Install it on shared hosting or a VPS, run the web installer, then publish posts from admin.

## Quick start

1. Upload this project to your web root (or clone with git).
2. Create a MySQL database in your host panel.
3. Open `https://yoursite.com/install.php` and finish the wizard.
4. Log in at `/administrator/login.php`.
5. Use **Posts**, **Site Settings**, and optionally **Flagship Sites**.
6. Delete or block `install.php` (and `test_installation.php`) after install.

At install you can choose **Fresh** (empty core) or a **Flagship site** (Blog, Business, Portfolio, Clinic, Nonprofit). Packages live under `sites/`.

Do **not** commit `includes/db_config.php` or `includes/env.php`.

## After install (what is created)

| File | Purpose |
|------|---------|
| `includes/db_config.php` | Database credentials (auto-generated, gitignored) |
| `includes/env.php` | Sets `MULTICMS_ENV` to `production` by default |
| `includes/installed.lock` | Blocks re-running the installer (returns 403) |
| `uploads/.htaccess` | Denies PHP execution in uploads |

## Permalinks & `.htaccess`

Root `.htaccess` (Apache + `mod_rewrite`) is included in the repo:

- Rewrites clean URLs to `index.php?mc_route=…`
- Blocks direct PHP under `includes/`, `images/`, and `uploads/`
- Denies web access to `db_config.php` / `env.php` basenames where supported

Examples after install:

- `/blog` — post listing  
- `/about`, `/contact` — pages  
- `/post/your-post-slug` — single post  
- Legacy `index.php?page=…` still works  

If the site lives in a **subdirectory**, uncomment `RewriteBase /your-subdir/` in `.htaccess`.  
Nginx needs an equivalent `try_files` rule (not shipped as Apache `.htaccess`).

## Security baseline

- Installer CSRF + password hashing (`password_hash`)
- Installer locked after `installed.lock` exists
- Admin/login CSRF on modern + legacy POST handlers
- Prepared statements for DB access
- Production-safe default environment via `env.php`
- Upload dirs deny script execution (`.htaccess` + rewrite rules)

Still required on your host: delete/block `install.php` after setup, use HTTPS, keep PHP/MySQL updated. No CMS is “zero risk” — report issues per [SECURITY.md](SECURITY.md).

## Requirements

- PHP 7.4+ (8.x recommended)
- MySQL 5.7+ / MariaDB 10.2+
- Extensions: mysqli, gd, curl
- Writable `includes/` and `uploads/`
- Apache `mod_rewrite` for pretty URLs (query-string URLs work without it)

## Architecture

| Layer | Path | Role |
|-------|------|------|
| Core | `index.php`, `includes/`, `themes/default/`, `install.php` | The product |
| Flagship sites | `sites/<slug>/` | Optional 1-click complete starters |
| Admin | `administrator/` (dashboard, posts, settings, flagship) | Manage the site |

## Documentation

- [Installation guide](docs/INSTALLATION_GUIDE.md)
- [Demo / production checklist](docs/DEMO_READY.md)
- [Security policy](SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## Development smoke check

```bash
php scripts/php-lint-smoke.php
# With MySQL on 127.0.0.1:3307 (see CI):
php scripts/smoke-phase2.php
```

## License

See [LICENSE.md](LICENSE.md) (GPLv3).
