# MultiCMS Installation Guide

## Overview

MultiCMS is a modern PHP/MySQL content management system with an installer similar to WordPress, optional Flagship starter sites, and an Updates & Backup admin screen.

## Prerequisites

- **PHP** 7.4 or higher
- **MySQL** 5.7+ or MariaDB 10.2+
- **Extensions**: mysqli, GD, cURL, zip (for updates/backups)
- **Writable**: `includes/` and `content/uploads/` (and `content/backups/` after install)
- **Web server**: Apache with `mod_rewrite` (bundled `.htaccess`) or Nginx

## Pretty URLs

Apache: root `.htaccess` rewrites unknown paths to `index.php` via `mc_route`.

Nginx example:

```nginx
location / {
    try_files $uri $uri/ /index.php?mc_route=$uri&$args;
}
location ~ ^/includes/.*\.php$ { deny all; }
location ~* /content/uploads/.*\.(php|phtml|phar)$ { deny all; }
```

After install: posts at `/administrator/posts.php`, settings at `/administrator/settings_core.php`, updates at `/administrator/updates.php`.

## Installation

1. Upload the project files to your web root (or a subdirectory).
2. Open `https://yourdomain.com/install.php` (or `/subdir/install.php`).
3. Complete the wizard: requirements → database → site → admin → install.
4. Choose **Fresh** (empty site) or a **Flagship** starter (Blog, Business, Portfolio, Clinic, Nonprofit).
5. When finished, `install.php` shows “already installed” — you do **not** need to delete it.

The installer writes `includes/db_config.php`, `includes/env.php`, `includes/site_path.php`, and `includes/installed.lock`, and sets `RewriteBase` / ErrorDocument paths in `.htaccess`.

## Post-installation

1. Sign in at `/administrator/` with the admin account you created.
2. Configure site title, description, email, and timezone under **Site Settings**.
3. Create posts/pages, or apply another Flagship from **Flagship sites** (admin).
4. Use **Updates & Backup** to download backups and apply GitHub releases when available.

## Layout

```
/
├── index.php                 # Front controller
├── install.php               # Installer (safe after install)
├── .htaccess
├── administrator/            # Admin UI (like wp-admin)
├── includes/                 # Core engine + config (not web-writable secrets in git)
├── content/
│   ├── themes/default/       # Front theme
│   ├── sites/                # Flagship starter packs
│   ├── uploads/
│   ├── backups/
│   ├── plugins/
│   └── assets/
└── docs/                     # Documentation (optional on host)
```

## Troubleshooting

| Issue | Check |
| --- | --- |
| Database connection failed | Credentials, MySQL running, DB exists, user grants |
| Permission errors | `includes/` and `content/uploads/` writable by the web user |
| Install won’t finish | PHP error log; disk space; memory limit |
| Can’t access admin | Account created; `installed.lock` present; session cookies |
| 404 on pretty URLs | `mod_rewrite`; `RewriteBase` matches install path |

## Security notes

- Leave `install.php` in place; it refuses reinstall when locked.
- Prefer HTTPS in production.
- Do not commit `includes/db_config.php` or `includes/env.php`.
- Keep MultiCMS updated via Admin → Updates when a new `version.json` release appears on GitHub.

## Support

Developed by [DigitalCloud.no](https://digitalcloud.no). Report issues on the GitHub repository for this project.
