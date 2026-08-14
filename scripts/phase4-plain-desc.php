<?php
/**
 * Phase 4: escape legacy description echoes via multicms_plain().
 */
$root = dirname(__DIR__);
$packs = ['blog', 'marketplace', 'portfolio', 'adposting', 'videostream', 'tutorials', 'imagegallery', 'doctors', 'productpublisher', 'searchengine'];
$pages = [
    'index.php', 'blog.php', 'search.php', 'category.php', 'market.php', 'portfolio.php',
    'info.php', 'pages.php', 'account.php', 'profile.php', 'myads.php', 'myposts.php',
    'myvideos.php', 'myimage.php', 'mytutorials.php', 'doctors.php', 'productpublisher.php',
    'video.php', 'image.php', 'tutorials.php', 'adposting.php',
];

$fixed = 0;
$files = 0;

foreach ($packs as $slug) {
    $www = "$root/plugins/$slug/www";
    if (!is_dir($www)) {
        continue;
    }
    foreach ($pages as $page) {
        $path = "$www/$page";
        if (!is_file($path)) {
            continue;
        }
        $c = file_get_contents($path);
        $orig = $c;

        // echo $row_x['description'] → multicms_plain
        $c = preg_replace_callback(
            '/echo\s+(\$row_[a-zA-Z0-9_]+)\[[\'\"]description[\'\"]\]/',
            function ($m) {
                return 'echo multicms_plain(' . $m[1] . '[\'description\'], 400)';
            },
            $c,
            -1,
            $n1
        );
        // avoid double wrap
        $c = str_replace('echo multicms_plain(multicms_plain(', 'echo multicms_plain(', $c);
        $c = preg_replace('/multicms_plain\((\$row_[a-zA-Z0-9_]+\[[^\]]+\])(?:,\s*\d+)?\)\)/', 'multicms_plain($1, 400)', $c);

        if ($c !== $orig) {
            file_put_contents($path, $c);
            $files++;
            $fixed += $n1;
        }
    }
}

echo "Files touched: $files\n";
echo "description→multicms_plain wraps: $fixed\n";
