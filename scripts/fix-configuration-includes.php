<?php
/**
 * Replace include("../configuration.php") in plugin www with MULTICMS_ROOT-safe require.
 */
$root = dirname(__DIR__);
$fixed = 0;
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/plugins'));
foreach ($it as $file) {
    if (!$file->isFile() || !in_array(strtolower($file->getExtension()), ['php', 'html'], true)) {
        continue;
    }
    $c = file_get_contents($file->getPathname());
    $n = preg_replace(
        '/include\s*\(\s*[\'"]\.\.\/configuration\.php[\'"]\s*\)\s*;/',
        "require_once (defined('MULTICMS_ROOT') ? MULTICMS_ROOT : dirname(__DIR__, 3)) . '/configuration.php';",
        $c,
        -1,
        $count
    );
    // also require_once variants
    $n = preg_replace(
        '/require(?:_once)?\s*\(\s*[\'"]\.\.\/configuration\.php[\'"]\s*\)\s*;/',
        "require_once (defined('MULTICMS_ROOT') ? MULTICMS_ROOT : dirname(__DIR__, 3)) . '/configuration.php';",
        $n,
        -1,
        $count2
    );
    if ($count + $count2 > 0) {
        file_put_contents($file->getPathname(), $n);
        $fixed += $count + $count2;
    }
}
// custom leftover
if (is_dir($root . '/custom')) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/custom'));
    foreach ($it as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;
        $c = file_get_contents($file->getPathname());
        $n = preg_replace(
            '/include\s*\(\s*[\'"]\.\.\/configuration\.php[\'"]\s*\)\s*;/',
            "require_once (defined('MULTICMS_ROOT') ? MULTICMS_ROOT : dirname(__DIR__)) . '/configuration.php';",
            $c,
            -1,
            $count
        );
        if ($count > 0) {
            file_put_contents($file->getPathname(), $n);
            $fixed += $count;
        }
    }
}
echo "configuration.php includes fixed: $fixed\n";
