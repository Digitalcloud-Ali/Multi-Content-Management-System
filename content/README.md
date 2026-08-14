# content/ — themes, flagships, uploads, plugins

Like WordPress’s `wp-content`, this folder holds everything that is **site content / packaging**, not core PHP.

| Path | What it is |
|------|------------|
| `themes/` | Front-end themes (`default` ships with MultiCMS) |
| `sites/` | Flagship starter packages (Blog, Business, …) — sample posts/pages only |
| `plugins/` | Future optional plugins (empty placeholder for now) |
| `uploads/` | User-uploaded files (not executed as PHP) |
| `backups/` | ZIP+SQL backups from Admin → Updates (web access denied) |
| `assets/` | Shared public images (logo, favicon) |

Core code stays in `/includes` and `/administrator`. Do not put PHP business logic here except theme templates.
