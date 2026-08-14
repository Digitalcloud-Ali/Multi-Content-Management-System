# Install & production checklist

## Automated

- [x] `php scripts/php-lint-smoke.php`
- [x] GitHub Actions PHP lint (+ smoke when MySQL available)

## Operator (on your hosting)

1. Upload files / clone repo  
2. Create MySQL database  
3. Open `/install.php` — choose **Fresh** or a **Flagship** starter (e.g. Blog)  
4. Log in → Admin dashboard → Posts / Site Settings / Flagship Sites  
5. Done — `install.php` stays locked (no delete required)  

## Notes

- Flagship packages under `content/sites/` seed modern `posts`, `core_categories`, and `pages`.
- Layout: core in `includes/` + `administrator/`; themes/uploads/flagships in `content/`.
- Admin → Updates & Backup checks GitHub `version.json` and can backup / update / restore.
- Do not commit `includes/db_config.php` or `includes/env.php`.
