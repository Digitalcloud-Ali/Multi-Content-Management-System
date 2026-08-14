<?php
/**
 * Lightweight syntax smoke test for core PHP files.
 */
$root = dirname(__DIR__);
$paths = [
    $root . '/includes',
    $root . '/themes/default',
    $root . '/sites',
    $root . '/install.php',
    $root . '/index.php',
    $root . '/administrator/login.php',
    $root . '/administrator/posts.php',
    $root . '/administrator/settings_core.php',
    $root . '/administrator/dashboard.php',
    $root . '/administrator/flagship_sites.php',
    $root . '/administrator/updates.php',
    $root . '/includes/Hooks.php',
    $root . '/includes/Routing.php',
    $root . '/includes/FlagshipSite.php',
    $root . '/includes/UpdateService.php',
    $root . '/includes/InstallPath.php',
];

$files = [];
foreach ($paths as $path) {
    if (is_file($path)) {
        $files[] = $path;
        continue;
    }
    if (!is_dir($path)) {
        continue;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($it as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
            $files[] = $file->getPathname();
        }
    }
}

$failed = 0;
foreach ($files as $file) {
    $out = [];
    $code = 0;
    exec('php -l ' . escapeshellarg($file) . ' 2>&1', $out, $code);
    if ($code !== 0) {
        echo implode("\n", $out) . "\n";
        $failed++;
    }
}

echo 'Checked ' . count($files) . " files; failures: $failed\n";
exit($failed > 0 ? 1 : 0);
