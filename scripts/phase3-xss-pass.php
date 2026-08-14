<?php
/**
 * Phase 3C: broader XSS escaping on remaining pack templates.
 */
$root = dirname(__DIR__);
$packs = ['blog', 'marketplace', 'portfolio', 'adposting', 'videostream', 'tutorials', 'imagegallery', 'doctors', 'productpublisher', 'searchengine'];
$pages = [
    'index.php', 'blog.php', 'search.php', 'category.php', 'market.php', 'portfolio.php',
    'info.php', 'pages.php', 'account.php', 'profile.php', 'myads.php', 'myposts.php',
    'myvideos.php', 'myimage.php', 'mytutorials.php', 'doctors.php', 'productpublisher.php',
    'video.php', 'image.php', 'tutorials.php', 'adposting.php', 'receivebox.php', 'sendbox.php',
    'message.php', 'advance.php', 'add.php',
];

$fields = 'title|catename|users|name|linktitle|owner|author|username|email|phone|siteurl|productpublisherurl|adpostingurl';
$xssFixed = 0;
$filesTouched = 0;

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

        $c = preg_replace_callback(
            '/echo\s+(\$row_[a-zA-Z0-9_]+)\[[\'\"](' . $fields . ')[\'\"]\]/',
            function ($m) {
                // already wrapped?
                return 'echo multicms_h(' . $m[1] . '[\'' . $m[2] . '\'])';
            },
            $c,
            -1,
            $n
        );
        // Avoid double-wrapping
        $c = str_replace(
            'echo multicms_h(multicms_h(',
            'echo multicms_h(',
            $c
        );
        // Fix broken double close if any
        $c = preg_replace('/multicms_h\((\$row_[a-zA-Z0-9_]+\[[^\]]+\])\)\)/', 'multicms_h($1)', $c);

        if ($c !== $orig) {
            file_put_contents($path, $c);
            $filesTouched++;
            $xssFixed += $n;
        }
    }
}

echo "Files touched: $filesTouched\n";
echo "XSS htmlspecialchars wraps (raw matches): $xssFixed\n";
