<?php
/**
 * PluginManager - simple helper to discover plugin site packages and copy theme folders
 */
class PluginManager {
    public static function pluginsPath() {
        return __DIR__ . '/../plugins';
    }

    public static function getPrebuiltSitesPluginPath() {
        return self::pluginsPath() . '/prebuilt-sites';
    }

    public static function listPrebuiltSites() {
        $pluginFile = self::getPrebuiltSitesPluginPath() . '/src/PrebuiltSitesPlugin.php';
        if (!is_file($pluginFile)) return [];
        require_once $pluginFile;
        if (!class_exists('PrebuiltSitesPlugin')) return [];
        return PrebuiltSitesPlugin::listSites();
    }

    // Copy theme folder from plugin site into themes/<themeName>
    public static function applySiteTheme($site) {
        if (empty($site['theme'])) return ['success' => false, 'message' => 'No theme defined in site manifest.'];
        $srcThemePath = rtrim($site['path'], '/') . '/theme';
        if (!is_dir($srcThemePath)) return ['success' => false, 'message' => 'Theme folder not found in package.'];
        $dest = __DIR__ . '/../themes/' . $site['theme'];
        if (is_dir($dest)) {
            return ['success' => false, 'message' => 'Destination theme already exists: ' . $site['theme']];
        }
        $ok = self::rcopy($srcThemePath, $dest);
        if ($ok) {
            return ['success' => true, 'message' => 'Theme copied to themes/' . $site['theme'] . '. You may need to set it as active in settings.'];
        }
        return ['success' => false, 'message' => 'Failed to copy theme. Check filesystem permissions.'];
    }

    // recursive copy
    private static function rcopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    if (!self::rcopy($src . '/' . $file, $dst . '/' . $file)) return false;
                }
                else {
                    if (!copy($src . '/' . $file, $dst . '/' . $file)) return false;
                }
            }
        }
        closedir($dir);
        return true;
    }
}
