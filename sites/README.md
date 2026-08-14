# Flagship sites

Flagship packages are **complete starter sites** for MultiCMS (not old Dreamweaver packs).

Each folder under `sites/<slug>/` contains:

- `manifest.json` — name, description, version  
- `content.json` — site title/description, categories, posts, pages  

## Bundled packages

| Slug | Name | Focus |
|------|------|--------|
| `blog` | Blog Starter | News + guides |
| `business` | Business Starter | Company news + services |
| `portfolio` | Portfolio Starter | Projects + process |
| `clinic` | Clinic Starter | Practice news + health tips |
| `nonprofit` | Nonprofit Starter | Impact stories + programs |

Applying a package seeds modern core tables (`posts`, `core_categories`, `pages`). Same slugs are skipped on re-apply (About/Contact shared across packages will keep the first version applied).

Future: the same format can be offered from a hosted catalog URL.
