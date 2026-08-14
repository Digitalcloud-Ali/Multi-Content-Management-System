<?php
$n = 0;
foreach (['plugins', 'custom'] as $base) {
    if (!is_dir($base)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
    foreach ($it as $f) {
        if (!$f->isFile() || $f->getFilename() !== 'register.php') {
            continue;
        }
        $c = file_get_contents($f->getPathname());
        $rep = str_replace(
            'GetSQLValueString($_POST[\'passs\'], "text")',
            'GetSQLValueString(password_hash((string)$_POST[\'passs\'], PASSWORD_DEFAULT), "text")',
            $c,
            $count
        );
        if ($count) {
            file_put_contents($f->getPathname(), $rep);
            $n += $count;
            echo $f->getPathname() . "\n";
        }
    }
}
echo "hashed register inserts: $n\n";
