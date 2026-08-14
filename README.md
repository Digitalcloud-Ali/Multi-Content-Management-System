# Multi-Content Management System (MultiCMS)

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](LICENSE.md)
[![Version](https://img.shields.io/badge/version-1.3.0-blue.svg)](version.json)

Open-source PHP/MySQL CMS. Install it on shared hosting or a VPS, run the web installer, then publish posts from admin.

Developed by [DigitalCloud.no](https://digitalcloud.no) — visit the site if you want to know more about us.

## Quick start

1. Upload this project to your web root **or a subfolder** (e.g. `yoursite.com/cms/`).
2. Create a MySQL database in your host panel.
3. Open `/install.php` — green/red host check, then choose **Fresh** or a **Flagship** starter.
4. Log in at `/administrator/login.php`.
5. Use **Posts**, **Site Settings**, **Flagship Sites**, and **Updates & Backup**.

**Flagships included:** Blog, Business, Portfolio, Clinic, Nonprofit (`content/sites/`).

Install path and pretty URLs are detected automatically.  
`install.php` stays after setup and only shows “already installed” (like WordPress).

Do **not** commit `includes/db_config.php` or `includes/env.php`.

## Folder layout

```
MultiCMS/
├── index.php              # Public front door
├── install.php            # Web installer
├── administrator/         # Admin panel
├── includes/              # Core PHP (engine)
├── content/               # Themes, flagships, uploads, plugins, backups
├── docs/                  # Documentation for GitHub (not required to run the CMS)
├── .github/               # CI + release automation (+ smoke scripts)
├── version.json           # Version checked by Admin → Updates
└── .htaccess              # Permalinks + security
```

| Root item | Why it exists |
|-----------|----------------|
| `includes/` | Engine (DB, auth, routing, updates) — like core libraries |
| `administrator/` | Admin UI |
| `content/` | Everything site-specific (themes, starters, uploads) |
| `docs/` | Human docs on GitHub only — optional to upload to hosting |
| `.github/scripts/` | Developer/CI tests — not used by visitors |

## After install (created automatically)

| File | Purpose |
|------|---------|
| `includes/db_config.php` | Database credentials |
| `includes/env.php` | `MULTICMS_ENV=production` |
| `includes/site_path.php` | Detected URL base (`/` or `/cms`) |
| `includes/installed.lock` | Locks the installer |
| `.htaccess` `RewriteBase` | Written to match that path |

## Permalinks

- `/blog`, `/about`, `/contact`
- `/post/your-post-slug`
- Legacy `index.php?page=…` still works

## Updates from GitHub

Admin → **Updates & Backup** checks `version.json` on GitHub, can backup, update, and restore.

## Requirements

- PHP 7.4+ · MySQL 5.7+ / MariaDB 10.2+
- Extensions: mysqli, gd, curl, zip, json
- Writable `includes/`, `content/uploads/`, `content/backups/`

## Documentation

- [Installation guide](docs/INSTALLATION_GUIDE.md)
- [Demo / production checklist](docs/DEMO_READY.md)
- [Security policy](SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## License

See [LICENSE.md](LICENSE.md) (GPLv3).

Developed by [DigitalCloud.no](https://digitalcloud.no).
