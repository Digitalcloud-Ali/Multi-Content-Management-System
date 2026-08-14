# Multi-Content Management System (MultiCMS)

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](LICENSE.md)
[![Version](https://img.shields.io/badge/version-1.2.0-blue.svg)](version.json)

Open-source PHP/MySQL CMS. Install it on shared hosting or a VPS, run the web installer, then publish posts from admin.

## Quick start

1. Upload this project to your web root **or a subfolder** (e.g. `yoursite.com/cms/`).
2. Create a MySQL database in your host panel.
3. Open `/install.php` (or `/cms/install.php`) — the wizard checks your host (green = OK, red = fix first).
4. Log in at `/administrator/login.php`.
5. Use **Posts**, **Site Settings**, **Flagship Sites**, and **Updates & Backup**.

Install path and pretty URLs are detected automatically — you do **not** edit `RewriteBase` by hand.  
`install.php` stays after setup and only shows “already installed” (like WordPress).

Do **not** commit `includes/db_config.php` or `includes/env.php`.

## After install (created automatically)

| File | Purpose |
|------|---------|
| `includes/db_config.php` | Database credentials |
| `includes/env.php` | `MULTICMS_ENV=production` |
| `includes/site_path.php` | Detected URL base (`/` or `/cms`) |
| `includes/installed.lock` | Locks the installer |
| `.htaccess` `RewriteBase` | Written to match that path |
| `uploads/.htaccess` | Blocks PHP in uploads |

## Permalinks

Root `.htaccess` + front controller:

- `/blog`, `/about`, `/contact`
- `/post/your-post-slug`
- Legacy `index.php?page=…` still works

## Updates from GitHub (all installs)

Admin → **Updates & Backup**:

1. Checks `version.json` on GitHub (`master`) every few hours (or “Check again now”).
2. Dashboard shows a banner when a newer version exists.
3. **Backup & update** creates a ZIP+SQL backup first, then downloads the latest code from GitHub (keeps DB login, uploads, and path config).
4. **Restore** can reload database (and optionally files) from a backup.

When we ship a new release, bump `version.json` on `master` — every installed site can see it in Admin.

## Security baseline

- Installer CSRF + password hashing  
- Installer locked after setup  
- Admin CSRF  
- Prepared statements  
- Upload / backups folders deny web script execution  
- Update flow refuses to overwrite `db_config.php`, `env.php`, lock, uploads, backups  

## Requirements

- PHP 7.4+ (8.x recommended)  
- MySQL 5.7+ / MariaDB 10.2+  
- Extensions: mysqli, gd, curl, zip, json  
- Writable `includes/`, `uploads/`, `backups/`  

## Architecture

| Layer | Path | Role |
|-------|------|------|
| Core | `index.php`, `includes/`, `themes/default/`, `install.php` | The product |
| Flagship sites | `sites/<slug>/` | Optional 1-click starters |
| Admin | dashboard, posts, settings, flagship, updates | Manage + update |

## Documentation

- [Installation guide](docs/INSTALLATION_GUIDE.md)
- [Demo / production checklist](docs/DEMO_READY.md)
- [Security policy](SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## Development smoke check

```bash
php scripts/php-lint-smoke.php
php scripts/smoke-phase2.php
```

## License

See [LICENSE.md](LICENSE.md) (GPLv3).
