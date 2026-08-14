# Multi-Content Management System (MultiCMS)

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](LICENSE.md)

Open-source PHP/MySQL CMS. Install it on shared hosting or a VPS, run the web installer, then publish posts from admin.

## Quick start

1. Upload this project to your web root (or clone with git).
2. Create a MySQL database in your host panel.
3. Open `https://yoursite.com/install.php` and finish the wizard.
4. Log in at `/administrator/login.php`.
5. Use **Posts** and **Site Settings**.
6. Delete or block `install.php` after install.

Do **not** commit `includes/db_config.php` or `includes/env.php`.

## Requirements

- PHP 7.4+ (8.x recommended)
- MySQL 5.7+ / MariaDB 10.2+
- Extensions: mysqli, gd, curl
- Writable `includes/` and `uploads/`
- Apache `mod_rewrite` (or nginx equivalent) optional for pretty URLs

## Architecture

| Layer | Path | Role |
|-------|------|------|
| Core | `index.php`, `includes/`, `themes/default/`, `install.php` | The product |
| Admin | `administrator/` (dashboard, posts, settings) | Manage the site |

## Documentation

- [Installation guide](docs/INSTALLATION_GUIDE.md)
- [Demo / production checklist](docs/DEMO_READY.md)
- [Security policy](SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## Development smoke check

```bash
php scripts/php-lint-smoke.php
```

## License

See [LICENSE.md](LICENSE.md) (GPLv3).
