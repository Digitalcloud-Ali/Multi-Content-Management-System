<?php
/**
 * Phase 2D: CSRF on pack write forms + htmlspecialchars on high-traffic echoes.
 */
$root = dirname(__DIR__);
$packs = ['blog', 'marketplace', 'portfolio', 'adposting', 'videostream', 'tutorials', 'imagegallery', 'doctors', 'productpublisher', 'searchengine'];

$writeFiles = ['postnew.php', 'postnewad.php', 'editpost.php', 'editvideo.php', 'contact.php', 'deletepost.php', 'deletevideo.php', 'register.php', 'setting.php'];

$csrfInserted = 0;
$csrfGuards = 0;
$xssFixed = 0;

foreach ($packs as $slug) {
    $www = "$root/plugins/$slug/www";
    if (!is_dir($www)) {
        continue;
    }

    foreach ($writeFiles as $wf) {
        $path = "$www/$wf";
        if (!is_file($path)) {
            continue;
        }
        $c = file_get_contents($path);
        $orig = $c;

        // After MM_insert / POST handlers that mutate — add CSRF check near top after rayicecms include
        if (strpos($c, 'multicms_require_csrf_post') === false && (
            strpos($c, '$_POST["MM_insert"]') !== false ||
            strpos($c, "\$_POST['MM_insert']") !== false ||
            strpos($c, 'isset($_POST') !== false && (strpos($c, 'delete') !== false || strpos($wf, 'delete') === 0 || $wf === 'contact.php' || $wf === 'setting.php' || strpos($wf, 'post') === 0 || strpos($wf, 'edit') === 0 || $wf === 'register.php')
        )) {
            // Insert after first rayicecms require
            $c = preg_replace(
                '/(require_once\([^;]+rayicecms\.php[^;]*\);\s*>\?>(?:\s*<\?php)?)/',
                "$1\nif (\$_SERVER['REQUEST_METHOD'] === 'POST') { multicms_require_csrf_post(); }\n",
                $c,
                1,
                $count
            );
            if ($count) {
                $csrfGuards += $count;
            } else {
                // alternate: after opening php of file that already required rayicecms on line 1
                if (preg_match('/^<\?php require_once\([^;]+rayicecms\.php[^;]*\); \?>/', $c)) {
                    $c = preg_replace(
                        '/^(<\?php require_once\([^;]+rayicecms\.php[^;]*\); \?>)/',
                        "$1\n<?php if (\$_SERVER['REQUEST_METHOD'] === 'POST') { multicms_require_csrf_post(); } ?>\n",
                        $c,
                        1,
                        $count2
                    );
                    $csrfGuards += $count2;
                }
            }
        }

        // Add csrf field inside forms if missing
        if (stripos($c, 'csrf_token') === false && preg_match('/<form[^>]*method=["\']POST["\']/i', $c)) {
            $c = preg_replace(
                '/(<form[^>]*method=["\']POST["\'][^>]*>)/i',
                "$1\n<?php echo multicms_csrf_field(); ?>",
                $c,
                -1,
                $fc
            );
            $csrfInserted += $fc;
        }

        if ($c !== $orig) {
            file_put_contents($path, $c);
        }
    }

    // XSS: high-traffic templates
    foreach (['index.php', 'blog.php', 'search.php', 'category.php', 'market.php', 'portfolio.php'] as $page) {
        $path = "$www/$page";
        if (!is_file($path)) {
            continue;
        }
        $c = file_get_contents($path);
        $orig = $c;

        // echo $row_blog['title'] etc.
        $c = preg_replace_callback(
            '/echo\s+(\$row_[a-zA-Z0-9_]+)\[[\'\"](title|catename|users|name|description|metadesc|metakey)[\'\"]\]/',
            function ($m) {
                // descriptions may be HTML in legacy — escape title/name/users/catename always; description leave for now if long HTML
                if ($m[2] === 'description') {
                    return $m[0]; // keep HTML content for now
                }
                return 'echo multicms_h(' . $m[1] . '[\'' . $m[2] . '\'])';
            },
            $c,
            -1,
            $n
        );
        $xssFixed += $n;

        if ($c !== $orig) {
            file_put_contents($path, $c);
        }
    }
}

echo "CSRF form fields inserted: $csrfInserted\n";
echo "CSRF POST guards added: $csrfGuards\n";
echo "XSS htmlspecialchars wraps: $xssFixed\n";
