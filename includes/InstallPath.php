<?php
/**
 * Detect public URL base path and apply RewriteBase automatically.
 */
class InstallPath {
    /** e.g. "" or "/cms" (no trailing slash) */
    public static function detectBasePath() {
        if (defined('SITE_BASE_PATH')) {
            return (string) SITE_BASE_PATH;
        }
        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/install.php'));
        $dir = dirname($script);
        // Admin / install scripts live one level under the CMS public root
        $baseName = basename($dir);
        if (in_array($baseName, ['administrator', 'install'], true)) {
            $dir = dirname($dir);
        }
        if ($dir === '/' || $dir === '\\' || $dir === '.' || $dir === '') {
            return '';
        }
        return rtrim($dir, '/');
    }

    /** Apache RewriteBase value e.g. "/" or "/cms/" */
    public static function rewriteBase($basePath = null) {
        $base = $basePath === null ? self::detectBasePath() : $basePath;
        if ($base === '' || $base === '/') {
            return '/';
        }
        return rtrim($base, '/') . '/';
    }

    /**
     * Write includes/site_path.php and patch root .htaccess RewriteBase + ErrorDocument.
     * @param bool $forceOverwrite when false, keep an existing site_path.php (used after GitHub update)
     */
    public static function apply($rootDir = null, $forceOverwrite = true) {
        $root = $rootDir ?: dirname(__DIR__);
        $sitePathFile = $root . '/includes/site_path.php';

        if (!$forceOverwrite && is_file($sitePathFile)) {
            if (!defined('SITE_BASE_PATH')) {
                require_once $sitePathFile;
            }
            return [
                'success' => true,
                'base' => defined('SITE_BASE_PATH') ? SITE_BASE_PATH : '',
                'rewrite_base' => defined('SITE_REWRITE_BASE') ? SITE_REWRITE_BASE : '/',
                'message' => 'Kept existing install path settings.',
            ];
        }

        $base = self::detectBasePath();
        $rewrite = self::rewriteBase($base);

        $php = "<?php\n"
            . "/** Auto-generated at install — public URL path of this MultiCMS copy */\n"
            . "if (!defined('SITE_BASE_PATH')) {\n"
            . "    define('SITE_BASE_PATH', " . var_export($base, true) . ");\n"
            . "}\n"
            . "if (!defined('SITE_REWRITE_BASE')) {\n"
            . "    define('SITE_REWRITE_BASE', " . var_export($rewrite, true) . ");\n"
            . "}\n";

        if (file_put_contents($sitePathFile, $php) === false) {
            return ['success' => false, 'message' => 'Could not write includes/site_path.php'];
        }

        $htaccess = $root . '/.htaccess';
        if (is_file($htaccess) && is_writable($htaccess)) {
            $content = (string) file_get_contents($htaccess);
            if (preg_match('/^\s*#?\s*RewriteBase\s+.+$/mi', $content)) {
                $content = preg_replace('/^\s*#?\s*RewriteBase\s+.+$/mi', '    RewriteBase ' . $rewrite, $content, 1);
            } else {
                $content = preg_replace(
                    '/(RewriteEngine\s+On\s*)/i',
                    "$1\n    RewriteBase " . $rewrite . "\n",
                    $content,
                    1
                );
            }
            $edBase = rtrim($rewrite, '/');
            $content = preg_replace(
                '/^ErrorDocument\s+403\s+.+$/mi',
                'ErrorDocument 403 ' . $edBase . '/themes/default/403.php',
                $content
            );
            $content = preg_replace(
                '/^ErrorDocument\s+404\s+.+$/mi',
                'ErrorDocument 404 ' . $edBase . '/themes/default/404.php',
                $content
            );
            $content = preg_replace(
                '/^ErrorDocument\s+500\s+.+$/mi',
                'ErrorDocument 500 ' . $edBase . '/themes/default/500.php',
                $content
            );
            @file_put_contents($htaccess, $content);
        }

        return [
            'success' => true,
            'base' => $base,
            'rewrite_base' => $rewrite,
            'message' => $base === ''
                ? 'Installed at web root (RewriteBase /).'
                : 'Detected subdirectory install at ' . $base . ' (RewriteBase set automatically).',
        ];
    }
}
