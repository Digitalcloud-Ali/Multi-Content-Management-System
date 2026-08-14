<?php
/**
 * Local version + remote GitHub version check, backup, restore, apply update.
 */
class UpdateService {
    public const GITHUB_REPO = 'Digitalcloud-Ali/Multi-Content-Management-System';
    public const RAW_VERSION_URL = 'https://raw.githubusercontent.com/Digitalcloud-Ali/Multi-Content-Management-System/master/version.json';
    public const ARCHIVE_ZIP_URL = 'https://github.com/Digitalcloud-Ali/Multi-Content-Management-System/archive/refs/heads/master.zip';
    public const CACHE_TTL = 21600; // 6 hours

    public static function root() {
        return dirname(__DIR__);
    }

    public static function localVersion() {
        $file = self::root() . '/version.json';
        if (!is_file($file)) {
            return ['version' => '0.0.0', 'notes' => '', 'released' => ''];
        }
        $data = json_decode((string) file_get_contents($file), true);
        return is_array($data) ? $data : ['version' => '0.0.0'];
    }

    public static function backupsDir() {
        $dir = self::root() . '/content/backups';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $deny = $dir . '/.htaccess';
        if (!is_file($deny)) {
            @file_put_contents($deny, "Require all denied\nDeny from all\n");
        }
        return $dir;
    }

    public static function cacheFile() {
        return self::root() . '/includes/update_check_cache.json';
    }

    /** @return array{ok:bool,local:string,remote:?array,update_available:bool,message:string,checked_at:?string} */
    public static function checkForUpdates($force = false) {
        $local = self::localVersion();
        $localVer = (string) ($local['version'] ?? '0.0.0');
        $cacheFile = self::cacheFile();

        if (!$force && is_file($cacheFile)) {
            $cache = json_decode((string) file_get_contents($cacheFile), true);
            if (is_array($cache) && !empty($cache['checked_at'])) {
                $age = time() - strtotime((string) $cache['checked_at']);
                if ($age >= 0 && $age < self::CACHE_TTL) {
                    return $cache;
                }
            }
        }

        $remote = self::fetchRemoteVersion();
        if ($remote === null) {
            $out = [
                'ok' => false,
                'local' => $localVer,
                'remote' => null,
                'update_available' => false,
                'message' => 'Could not reach GitHub to check for updates (curl/allow_url_fopen needed).',
                'checked_at' => date('c'),
            ];
            @file_put_contents($cacheFile, json_encode($out, JSON_PRETTY_PRINT));
            return $out;
        }

        $remoteVer = (string) ($remote['version'] ?? '0.0.0');
        $available = version_compare($remoteVer, $localVer, '>');
        $out = [
            'ok' => true,
            'local' => $localVer,
            'remote' => $remote,
            'update_available' => $available,
            'message' => $available
                ? ('Update available: ' . $remoteVer)
                : ('You are on the latest version (' . $localVer . ').'),
            'checked_at' => date('c'),
        ];
        @file_put_contents($cacheFile, json_encode($out, JSON_PRETTY_PRINT));
        return $out;
    }

    public static function fetchRemoteVersion() {
        $body = self::httpGet(self::RAW_VERSION_URL);
        if ($body === null) {
            // Fallback: GitHub Releases API
            $json = self::httpGet('https://api.github.com/repos/' . self::GITHUB_REPO . '/releases/latest');
            if ($json === null) {
                return null;
            }
            $rel = json_decode($json, true);
            if (!is_array($rel) || empty($rel['tag_name'])) {
                return null;
            }
            return [
                'version' => ltrim((string) $rel['tag_name'], 'v'),
                'notes' => (string) ($rel['body'] ?? ''),
                'released' => substr((string) ($rel['published_at'] ?? ''), 0, 10),
                'zipball_url' => $rel['zipball_url'] ?? self::ARCHIVE_ZIP_URL,
            ];
        }
        $data = json_decode($body, true);
        return is_array($data) ? $data : null;
    }

    public static function httpGet($url) {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 25,
                CURLOPT_USERAGENT => 'MultiCMS-UpdateCheck/1.0',
                CURLOPT_HTTPHEADER => ['Accept: application/vnd.github+json'],
            ]);
            $body = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($body !== false && $code >= 200 && $code < 300) {
                return $body;
            }
            return null;
        }
        if (ini_get('allow_url_fopen')) {
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 25,
                    'header' => "User-Agent: MultiCMS-UpdateCheck/1.0\r\nAccept: application/vnd.github+json\r\n",
                ],
            ]);
            $body = @file_get_contents($url, false, $ctx);
            return $body === false ? null : $body;
        }
        return null;
    }

    /** @return array{success:bool,message:string,file?:string} */
    public static function createBackup($label = 'manual') {
        if (!class_exists('ZipArchive')) {
            return ['success' => false, 'message' => 'PHP zip extension is required for backups.'];
        }
        $dir = self::backupsDir();
        $stamp = date('Ymd-His');
        $safe = preg_replace('/[^a-z0-9_-]+/i', '-', $label) ?: 'backup';
        $baseName = 'multicms-' . $safe . '-' . $stamp;
        $zipPath = $dir . '/' . $baseName . '.zip';
        $sqlPath = $dir . '/' . $baseName . '.sql';

        $sql = self::exportDatabaseSql();
        if ($sql === null) {
            return ['success' => false, 'message' => 'Database export failed.'];
        }
        if (file_put_contents($sqlPath, $sql) === false) {
            return ['success' => false, 'message' => 'Could not write SQL backup.'];
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return ['success' => false, 'message' => 'Could not create ZIP backup.'];
        }

        $root = self::root();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $file) {
            /** @var SplFileInfo $file */
            $full = $file->getPathname();
            $rel = ltrim(str_replace('\\', '/', substr($full, strlen($root))), '/');
            $top = explode('/', $rel)[0];
            if (in_array($top, ['.git', 'vendor', 'node_modules', '.github', 'docs'], true)) {
                continue;
            }
            if ($top === 'backups' || $rel === 'content/backups' || strpos($rel, 'content/backups/') === 0) {
                continue;
            }
            if ($file->isDir()) {
                $zip->addEmptyDir($rel);
            } else {
                $zip->addFile($full, $rel);
            }
        }
        $zip->addFile($sqlPath, 'database-backup.sql');
        $zip->close();
        @unlink($sqlPath);

        return [
            'success' => true,
            'message' => 'Backup created: ' . basename($zipPath),
            'file' => basename($zipPath),
        ];
    }

    public static function listBackups() {
        $dir = self::backupsDir();
        $files = glob($dir . '/multicms-*.zip') ?: [];
        rsort($files);
        $out = [];
        foreach ($files as $f) {
            $out[] = [
                'file' => basename($f),
                'size' => filesize($f),
                'mtime' => filemtime($f),
            ];
        }
        return $out;
    }

    /** Restore DB (+ optional files) from a backup zip name under backups/ */
    public static function restoreBackup($fileName, $restoreFiles = false) {
        $fileName = basename((string) $fileName);
        if (!preg_match('/^multicms-[a-zA-Z0-9._-]+\.zip$/', $fileName)) {
            return ['success' => false, 'message' => 'Invalid backup file name.'];
        }
        $path = self::backupsDir() . '/' . $fileName;
        if (!is_file($path)) {
            return ['success' => false, 'message' => 'Backup not found.'];
        }
        if (!class_exists('ZipArchive')) {
            return ['success' => false, 'message' => 'PHP zip extension required.'];
        }

        $tmp = self::backupsDir() . '/_restore_' . time();
        @mkdir($tmp, 0755, true);
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return ['success' => false, 'message' => 'Could not open backup ZIP.'];
        }
        $zip->extractTo($tmp);
        $zip->close();

        $sqlFile = $tmp . '/database-backup.sql';
        if (!is_file($sqlFile)) {
            self::rrmdir($tmp);
            return ['success' => false, 'message' => 'Backup ZIP missing database-backup.sql.'];
        }
        $imported = self::importDatabaseSql((string) file_get_contents($sqlFile));
        if (!$imported['success']) {
            self::rrmdir($tmp);
            return $imported;
        }

        if ($restoreFiles) {
            self::copyTreeFiltered($tmp, self::root(), true);
        }

        self::rrmdir($tmp);
        return [
            'success' => true,
            'message' => $restoreFiles
                ? 'Database and files restored from backup.'
                : 'Database restored from backup (files left as-is).',
        ];
    }

    /** Download latest from GitHub and apply (keeps config, uploads, backups). Requires backup first. */
    public static function applyUpdateFromGitHub() {
        if (!class_exists('ZipArchive')) {
            return ['success' => false, 'message' => 'PHP zip extension is required to update.'];
        }
        $zipData = self::httpGet(self::ARCHIVE_ZIP_URL);
        if ($zipData === null) {
            return ['success' => false, 'message' => 'Could not download update package from GitHub.'];
        }
        $tmpZip = self::backupsDir() . '/_update_' . time() . '.zip';
        $tmpDir = self::backupsDir() . '/_update_extract_' . time();
        if (file_put_contents($tmpZip, $zipData) === false) {
            return ['success' => false, 'message' => 'Could not save update ZIP.'];
        }
        @mkdir($tmpDir, 0755, true);
        $zip = new ZipArchive();
        if ($zip->open($tmpZip) !== true) {
            @unlink($tmpZip);
            return ['success' => false, 'message' => 'Could not open update ZIP.'];
        }
        $zip->extractTo($tmpDir);
        $zip->close();
        @unlink($tmpZip);

        // GitHub zip has a single top folder like Multi-Content-Management-System-master
        $entries = array_values(array_filter(scandir($tmpDir) ?: [], function ($e) {
            return $e !== '.' && $e !== '..';
        }));
        if (count($entries) !== 1 || !is_dir($tmpDir . '/' . $entries[0])) {
            self::rrmdir($tmpDir);
            return ['success' => false, 'message' => 'Unexpected update archive layout.'];
        }
        $source = $tmpDir . '/' . $entries[0];
        self::copyTreeFiltered($source, self::root(), false);
        self::rrmdir($tmpDir);

        // Re-apply path detection for this host
        if (is_file(self::root() . '/includes/InstallPath.php')) {
            require_once self::root() . '/includes/InstallPath.php';
            if (class_exists('InstallPath')) {
                // Keep the path detected at original install — do not overwrite from /administrator/
                InstallPath::apply(self::root(), false);
            }
        }

        $ver = self::localVersion();
        return [
            'success' => true,
            'message' => 'Updated to ' . ($ver['version'] ?? 'latest') . '. Review the site, then clear caches if any.',
        ];
    }

    private static function protectedRelative($rel) {
        $rel = str_replace('\\', '/', $rel);
        $protected = [
            'includes/db_config.php',
            'includes/env.php',
            'includes/installed.lock',
            'includes/site_path.php',
            'includes/active_site.json',
            'includes/update_check_cache.json',
        ];
        if (in_array($rel, $protected, true)) {
            return true;
        }
        if (strpos($rel, 'content/uploads/') === 0 || $rel === 'content/uploads' || strpos($rel, 'uploads/') === 0 || $rel === 'uploads') {
            return true;
        }
        if (strpos($rel, 'content/backups/') === 0 || $rel === 'content/backups' || strpos($rel, 'backups/') === 0 || $rel === 'backups') {
            return true;
        }
        return false;
    }

    private static function copyTreeFiltered($from, $to, $fromBackupZip) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($from, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $file) {
            /** @var SplFileInfo $file */
            $full = $file->getPathname();
            $rel = ltrim(str_replace('\\', '/', substr($full, strlen($from))), '/');
            if ($rel === 'database-backup.sql') {
                continue;
            }
            if (self::protectedRelative($rel)) {
                continue;
            }
            // Never copy nested .git from archive into production
            if (strpos($rel, '.git/') === 0 || $rel === '.git') {
                continue;
            }
            $dest = $to . '/' . $rel;
            if ($file->isDir()) {
                if (!is_dir($dest)) {
                    @mkdir($dest, 0755, true);
                }
            } else {
                $parent = dirname($dest);
                if (!is_dir($parent)) {
                    @mkdir($parent, 0755, true);
                }
                @copy($full, $dest);
            }
        }
    }

    private static function exportDatabaseSql() {
        try {
            if (!function_exists('getDB')) {
                return null;
            }
            $conn = getDB()->getConnection();
            $dbName = '';
            if (defined('DB_NAME')) {
                $dbName = DB_NAME;
            }
            $out = "-- MultiCMS SQL backup " . date('c') . "\nSET FOREIGN_KEY_CHECKS=0;\n\n";
            $tables = [];
            $res = $conn->query('SHOW TABLES');
            if (!$res) {
                return null;
            }
            while ($row = $res->fetch_array()) {
                $tables[] = $row[0];
            }
            foreach ($tables as $table) {
                $create = $conn->query('SHOW CREATE TABLE `' . $conn->real_escape_string($table) . '`');
                $crow = $create ? $create->fetch_assoc() : null;
                $createSql = $crow ? array_values($crow)[1] : null;
                if (!$createSql) {
                    continue;
                }
                $out .= "DROP TABLE IF EXISTS `{$table}`;\n{$createSql};\n\n";
                $data = $conn->query('SELECT * FROM `' . $table . '`');
                if (!$data) {
                    continue;
                }
                while ($r = $data->fetch_assoc()) {
                    $cols = array_map(function ($c) {
                        return '`' . $c . '`';
                    }, array_keys($r));
                    $vals = [];
                    foreach ($r as $v) {
                        if ($v === null) {
                            $vals[] = 'NULL';
                        } else {
                            $vals[] = "'" . $conn->real_escape_string((string) $v) . "'";
                        }
                    }
                    $out .= 'INSERT INTO `' . $table . '` (' . implode(',', $cols) . ') VALUES (' . implode(',', $vals) . ");\n";
                }
                $out .= "\n";
            }
            $out .= "SET FOREIGN_KEY_CHECKS=1;\n";
            return $out;
        } catch (Throwable $e) {
            return null;
        }
    }

    private static function importDatabaseSql($sql) {
        try {
            $conn = getDB()->getConnection();
            if (!$conn->multi_query($sql)) {
                return ['success' => false, 'message' => 'SQL import failed: ' . $conn->error];
            }
            while ($conn->more_results() && $conn->next_result()) {
                // drain
            }
            return ['success' => true, 'message' => 'SQL imported.'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private static function rrmdir($dir) {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                self::rrmdir($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
