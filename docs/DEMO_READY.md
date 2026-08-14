# Install & production checklist

## Automated

- [x] `php scripts/php-lint-smoke.php`
- [x] GitHub Actions PHP lint (+ smoke when MySQL available)

## Operator (on your hosting)

1. Upload files / clone repo  
2. Create MySQL database  
3. Open `/install.php` and finish the wizard  
4. Log in → Admin dashboard → Posts / Site Settings  
5. Delete or block `install.php`  

## Notes

- Ready-made packs were removed; this product is Fresh core only.
- Do not commit `includes/db_config.php` or `includes/env.php`.
