<?php
/**
 * Inject CSRF guard + form fields into legacy administrator modules.
 */
$root = dirname(__DIR__) . '/administrator';
$skip = ['login.php', 'posts.php', 'settings_core.php', 'plugins_prebuilt_sites.php', 'dashboard.php'];
$guards = 0;
$fields = 0;

foreach (glob($root . '/*.php') as $path) {
    $base = basename($path);
    if (in_array($base, $skip, true)) {
        continue;
    }
    $c = file_get_contents($path);
    $orig = $c;

    if (strpos($c, 'multicms_require_csrf_post') === false && strpos($c, 'rayicecms.php') !== false) {
        $c = preg_replace(
            '/(require_once\([^;]*rayicecms\.php[^;]*\);\s*\?>)/',
            "$1\n<?php if (\$_SERVER['REQUEST_METHOD'] === 'POST') { multicms_require_csrf_post(); } ?>\n",
            $c,
            1,
            $count
        );
        $guards += $count;
        if (!$count && preg_match('/^<\?php require_once\([^;]+rayicecms\.php[^;]*\);/', $c)) {
            $c = preg_replace(
                '/^(<\?php require_once\([^;]+rayicecms\.php[^;]*\);)/',
                "$1\nif (\$_SERVER['REQUEST_METHOD'] === 'POST') { multicms_require_csrf_post(); }\n",
                $c,
                1,
                $count2
            );
            $guards += $count2;
        }
    }

    if (stripos($c, 'csrf_token') === false && preg_match('/<form[^>]*method=["\']POST["\']/i', $c)) {
        $c = preg_replace(
            '/(<form[^>]*method=["\']POST["\'][^>]*>)/i',
            "$1\n<?php if (function_exists('multicms_csrf_field')) { echo multicms_csrf_field(); } ?>",
            $c,
            -1,
            $fc
        );
        $fields += $fc;
    }

    if ($c !== $orig) {
        file_put_contents($path, $c);
    }
}

echo "Admin CSRF guards: $guards\n";
echo "Admin CSRF form fields: $fields\n";
