<?php
/**
 * Dev-only installation probe. Disabled when the site is installed.
 */
if (file_exists(__DIR__ . '/includes/installed.lock')) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Not available.";
    exit;
}

if (!defined('MULTICMS_ALLOW_INSTALL_TEST')) {
    // Allow local override: define('MULTICMS_ALLOW_INSTALL_TEST', true) in a local bootstrap if needed.
    $allow = (isset($_GET['dev']) && $_GET['dev'] === '1');
    if (!$allow) {
        http_response_code(403);
        header('Content-Type: text/plain; charset=utf-8');
        echo "Forbidden. Pass ?dev=1 on an uninstalled copy, or remove this file from production.";
        exit;
    }
}

/**
 * Installation System Test Script
 * Run this to verify all components are working correctly
 */

echo "<h1>Multi-Content CMS Installation System Test</h1>\n";
echo "<p>Testing installation system components...</p>\n";

echo "<h2>1. PHP Version Check</h2>\n";
echo "Current PHP Version: " . PHP_VERSION . "\n";
echo "Required: 7.4.0+\n";
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "✅ PHP version is compatible\n";
} else {
    echo "❌ PHP version is too old\n";
}

echo "<h2>2. Required Extensions</h2>\n";
$required_extensions = ['mysqli', 'gd', 'curl'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ {$ext} extension is loaded\n";
    } else {
        echo "❌ {$ext} extension is missing\n";
    }
}

echo "<h2>3. Directory Permissions</h2>\n";
$directories = ['includes', 'uploads'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ {$dir} directory is writable\n";
        } else {
            echo "❌ {$dir} directory is not writable\n";
        }
    } else {
        echo "⚠️ {$dir} directory does not exist\n";
    }
}

echo "<h2>4. Core files</h2>\n";
foreach (['install.php', 'index.php', 'includes/Database.php', 'includes/PluginManager.php'] as $f) {
    echo (is_file($f) ? "✅" : "❌") . " {$f}\n";
}

echo "<p>Done. Remove this file from production hosts.</p>\n";
