<?php
/**
 * PluginManager - discover site plugins / prebuilt packages and apply them as the main site.
 */
class PluginManager {
    public static function pluginsPath() {
        return __DIR__ . '/../plugins';
    }

    public static function getPrebuiltSitesPluginPath() {
        return self::pluginsPath() . '/prebuilt-sites';
    }

    /**
     * Ready-made site modules (legacy topics migrated into plugins/<slug>/www).
     */
    public static function listSiteModules() {
        $pluginsDir = self::pluginsPath();
        $sites = [];
        if (!is_dir($pluginsDir)) {
            return $sites;
        }

        foreach (scandir($pluginsDir) as $item) {
            if ($item === '.' || $item === '..' || $item === 'prebuilt-sites') {
                continue;
            }
            $base = $pluginsDir . '/' . $item;
            $manifest = $base . '/plugin.json';
            $www = $base . '/www';
            if (!is_file($manifest) || !is_dir($www)) {
                continue;
            }
            $raw = (string) file_get_contents($manifest);
            if (strncmp($raw, "\xEF\xBB\xBF", 3) === 0) {
                $raw = substr($raw, 3);
            }
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                continue;
            }
            $type = $data['type'] ?? 'site';
            if ($type !== 'site') {
                continue;
            }
            $sites[] = [
                'name' => $data['name'] ?? $item,
                'slug' => $data['slug'] ?? $item,
                'version' => $data['version'] ?? '1.0.0',
                'description' => $data['description'] ?? '',
                'path' => $base,
                'www' => $www,
                'url' => 'plugins/' . rawurlencode($data['slug'] ?? $item) . '/www/',
            ];
        }

        usort($sites, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return $sites;
    }

    public static function getSiteModule($slug) {
        $slug = basename((string) $slug);
        foreach (self::listSiteModules() as $site) {
            if ($site['slug'] === $slug) {
                return $site;
            }
        }
        return null;
    }

    public static function listPrebuiltSites() {
        $pluginFile = self::getPrebuiltSitesPluginPath() . '/src/PrebuiltSitesPlugin.php';
        if (!is_file($pluginFile)) {
            return [];
        }
        require_once $pluginFile;
        if (!class_exists('PrebuiltSitesPlugin')) {
            return [];
        }
        return PrebuiltSitesPlugin::listSites();
    }

    /**
     * Activate a ready-made site as the main front-end site.
     * Sets settings.selecttopic and records the active plugin slug.
     */
    public static function applySiteAsMain($slug, $mysqli = null) {
        $site = self::getSiteModule($slug);
        if (!$site) {
            return ['success' => false, 'message' => 'Site plugin not found: ' . $slug];
        }
        if (!is_file($site['www'] . '/index.php')) {
            return ['success' => false, 'message' => 'Site package is missing index.php'];
        }

        $slug = $site['slug'];
        $updatedDb = false;

        // Provision legacy tables before activation
        self::ensureLegacyPackTables($mysqli instanceof mysqli ? $mysqli : null);
        self::ensurePackSchema($slug, $mysqli instanceof mysqli ? $mysqli : null);

        // Call plugin onActivate when available
        $manifestFile = $site['path'] . '/plugin.json';
        if (is_file($manifestFile)) {
            $raw = (string) file_get_contents($manifestFile);
            if (strncmp($raw, "\xEF\xBB\xBF", 3) === 0) {
                $raw = substr($raw, 3);
            }
            $manifest = json_decode($raw, true);
            $entry = $manifest['entry'] ?? '';
            if ($entry) {
                $entryPath = $site['path'] . '/' . ltrim(str_replace('\\', '/', $entry), '/');
                if (is_file($entryPath)) {
                    require_once $entryPath;
                    $className = basename($entryPath, '.php');
                    if (class_exists($className) && method_exists($className, 'onActivate')) {
                        try {
                            $className::onActivate();
                        } catch (Exception $e) {
                            // non-fatal for apply
                        }
                    }
                }
            }
        }

        if ($mysqli instanceof mysqli) {
            $stmt = $mysqli->prepare('UPDATE settings SET selecttopic = ? WHERE settingid = 1');
            if ($stmt) {
                $stmt->bind_param('s', $slug);
                $updatedDb = $stmt->execute();
                $stmt->close();
            }
        } else {
            // Best-effort via modern Database helper when available
            try {
                if (function_exists('getDB')) {
                    $db = getDB();
                    if ($db && method_exists($db, 'execute')) {
                        $db->execute('UPDATE settings SET selecttopic = ? WHERE settingid = 1', 's', [$slug]);
                        $updatedDb = true;
                    } elseif ($db && method_exists($db, 'query')) {
                        $db->query('UPDATE settings SET selecttopic = ? WHERE settingid = 1', 's', [$slug]);
                        $updatedDb = true;
                    }
                }
            } catch (Exception $e) {
                // Fall through; marker file still written
            }
        }

        $publicUrl = 'index.php'; // pretty front door via front controller
        $markerDir = __DIR__;
        @file_put_contents($markerDir . '/active_site.json', json_encode([
            'slug' => $slug,
            'name' => $site['name'],
            'url' => $publicUrl,
            'pack_url' => $site['url'],
            'applied_at' => date('c'),
            'mode' => 'readymade',
        ], JSON_PRETTY_PRINT));

        return [
            'success' => true,
            'message' => $updatedDb
                ? 'Applied "' . $site['name'] . '" as the main site.'
                : 'Marked "' . $site['name'] . '" as main site (update settings.selecttopic if DB write failed).',
            'slug' => $slug,
            'url' => $publicUrl,
        ];
    }

    /**
     * Fresh/default MultiCMS front-end (modern theme) — not a legacy site plugin.
     */
    public static function applyFreshDefault($mysqli = null) {
        $slug = 'default';
        if ($mysqli instanceof mysqli) {
            $stmt = $mysqli->prepare('UPDATE settings SET selecttopic = ? WHERE settingid = 1');
            if ($stmt) {
                $stmt->bind_param('s', $slug);
                $stmt->execute();
                $stmt->close();
            }
        }

        @file_put_contents(__DIR__ . '/active_site.json', json_encode([
            'slug' => 'default',
            'name' => 'Fresh default',
            'url' => 'index.php',
            'applied_at' => date('c'),
            'mode' => 'fresh',
        ], JSON_PRETTY_PRINT));

        return ['success' => true, 'message' => 'Fresh default site selected.', 'slug' => 'default', 'url' => 'index.php'];
    }

    public static function getActiveSite() {
        $file = __DIR__ . '/active_site.json';
        if (is_file($file)) {
            $data = json_decode((string) file_get_contents($file), true);
            if (is_array($data)) {
                return $data;
            }
        }
        return ['slug' => 'default', 'mode' => 'fresh', 'url' => 'index.php', 'name' => 'Fresh default'];
    }

    /**
     * Dispatch an active ready-made pack from the site root (pretty URL front controller).
     * Serves PHP scripts and static files from plugins/<slug>/www without redirecting the browser.
     *
     * @param string $slug Site plugin slug
     * @param string $route Path relative to www (e.g. "" , "login.php", "images/x.png")
     * @return bool True if handled (script exits or file sent)
     */
    public static function dispatchActiveSite($slug, $route = '') {
        $site = self::getSiteModule($slug);
        if (!$site || empty($site['www']) || !is_dir($site['www'])) {
            return false;
        }

        $www = realpath($site['www']);
        if ($www === false) {
            return false;
        }

        $route = str_replace('\\', '/', (string) $route);
        $route = ltrim($route, '/');
        if ($route === '' || $route === 'index.php') {
            $route = 'index.php';
        }

        // Block traversal and hidden paths
        if (strpos($route, '..') !== false || strpos($route, "\0") !== false) {
            http_response_code(400);
            echo 'Bad request';
            return true;
        }

        $target = realpath($www . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $route));
        if ($target === false || strpos($target, $www) !== 0) {
            // try directory index
            $asDir = realpath($www . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $route));
            if ($asDir && is_dir($asDir) && strpos($asDir, $www) === 0) {
                $target = realpath($asDir . DIRECTORY_SEPARATOR . 'index.php');
            }
        }

        if ($target === false || strpos($target, $www) !== 0 || !is_file($target)) {
            http_response_code(404);
            echo 'Not found';
            return true;
        }

        $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        if ($ext === 'php') {
            $prevCwd = getcwd();
            chdir($www);
            // Make relative includes resolve; SCRIPT name for legacy $currentPage
            $_SERVER['SCRIPT_FILENAME'] = $target;
            $_SERVER['PHP_SELF'] = '/' . str_replace('\\', '/', substr($target, strlen($www) + 1));
            require $target;
            if ($prevCwd) {
                @chdir($prevCwd);
            }
            return true;
        }

        // Static asset from pack www
        $mime = 'application/octet-stream';
        if (function_exists('mime_content_type')) {
            $detected = @mime_content_type($target);
            if ($detected) {
                $mime = $detected;
            }
        } else {
            $map = [
                'css' => 'text/css', 'js' => 'application/javascript', 'png' => 'image/png',
                'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif',
                'webp' => 'image/webp', 'svg' => 'image/svg+xml', 'ico' => 'image/x-icon',
                'html' => 'text/html', 'htm' => 'text/html', 'txt' => 'text/plain',
            ];
            if (isset($map[$ext])) {
                $mime = $map[$ext];
            }
        }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($target));
        readfile($target);
        return true;
    }

    /**
     * Ensure legacy pack tables exist (idempotent). Handles modern vs legacy categories conflict.
     */
    public static function ensureLegacyPackTables($mysqli = null) {
        $conn = null;
        if ($mysqli instanceof mysqli) {
            $conn = $mysqli;
        } elseif (function_exists('getDB')) {
            try {
                $conn = getDB()->getConnection();
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
        if (!$conn instanceof mysqli) {
            return ['success' => false, 'message' => 'No database connection'];
        }

        // If modern categories (categoryid) exist, rename so legacy categories can be created
        $check = $conn->query("SHOW COLUMNS FROM categories LIKE 'categoryid'");
        if ($check && $check->num_rows > 0) {
            $existsCore = $conn->query("SHOW TABLES LIKE 'core_categories'");
            if ($existsCore && $existsCore->num_rows === 0) {
                $conn->query('RENAME TABLE categories TO core_categories');
            }
        }

        $sqlFile = __DIR__ . '/sql/legacy_pack_tables.sql';
        if (!is_file($sqlFile)) {
            return ['success' => false, 'message' => 'legacy_pack_tables.sql missing'];
        }
        $sql = file_get_contents($sqlFile);
        self::runSqlBatch($conn, $sql);

        // Optional pack-specific schema
        return ['success' => true, 'message' => 'Legacy pack tables ensured'];
    }

    public static function ensurePackSchema($slug, $mysqli = null) {
        $site = self::getSiteModule($slug);
        if (!$site) {
            return;
        }
        $packSql = $site['path'] . '/schema.sql';
        if (!is_file($packSql)) {
            return;
        }
        $conn = $mysqli instanceof mysqli ? $mysqli : null;
        if (!$conn && function_exists('getDB')) {
            try {
                $conn = getDB()->getConnection();
            } catch (Exception $e) {
                return;
            }
        }
        if ($conn instanceof mysqli) {
            self::runSqlBatch($conn, file_get_contents($packSql));
        }
    }

    private static function runSqlBatch(mysqli $conn, $sql) {
        // Strip comments and split on semicolons carefully enough for CREATE TABLE batches
        $sql = preg_replace('/^--.*$/m', '', $sql);
        $parts = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($parts as $stmt) {
            if ($stmt === '') {
                continue;
            }
            @$conn->query($stmt);
        }
    }

    // Copy theme folder from plugin site into themes/<themeName>
    public static function applySiteTheme($site) {
        if (empty($site['theme'])) {
            return ['success' => false, 'message' => 'No theme defined in site manifest.'];
        }
        $srcThemePath = rtrim($site['path'], '/') . '/theme';
        if (!is_dir($srcThemePath)) {
            return ['success' => false, 'message' => 'Theme folder not found in package.'];
        }
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

    private static function rcopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    if (!self::rcopy($src . '/' . $file, $dst . '/' . $file)) {
                        return false;
                    }
                } else {
                    if (!copy($src . '/' . $file, $dst . '/' . $file)) {
                        return false;
                    }
                }
            }
        }
        closedir($dir);
        return true;
    }
}
