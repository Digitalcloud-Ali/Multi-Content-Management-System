<?php
$f = dirname(__DIR__) . '/includes/raycms.sql';
$c = file_get_contents($f);
$c = preg_replace_callback(
    '/INSERT INTO `members`[\s\S]*?;/',
    function ($m) {
        // (id, 'user', 'PASSWORD', 'level'
        return preg_replace(
            '/\((\d+)\s*,\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'/',
            '($1, \'$2\', \'[SCRUBBED]\'',
            $m[0]
        );
    },
    $c,
    -1,
    $n
);
$banner = "-- SECURITY: Credential values scrubbed for public distribution. Do NOT import this dump for production passwords.\n";
if (strpos($c, 'SECURITY: Credential') === false) {
    $c = $banner . $c;
}
file_put_contents($f, $c);
echo "members INSERT blocks scrubbed: $n\n";
echo "SCRUBBED count: " . substr_count($c, '[SCRUBBED]') . "\n";
