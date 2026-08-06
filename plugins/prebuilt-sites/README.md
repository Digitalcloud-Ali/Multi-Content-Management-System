# Prebuilt Sites plugin

This plugin contains pre-built site packages (themes + optional demo content) provided by Digitalcloud.no.

Structure:
- plugin.json - plugin manifest
- src/ - helper PHP classes
- sites/ - each site is a folder with manifest.json, preview.html, theme/ and optional demo-data/

This initial commit provides a sample site (digitalcloud-portfolio) with a static preview. Applying the theme copies the package's theme to themes/<theme-name> — you will still need to set the active theme in the settings table or admin UI (this will be automated in follow-up changes).
