<?php
/**
 * Patch legacy isAuthorized bypass + register level POST across admin + packs.
 */
$root = dirname(__DIR__);
$dirs = [
    $root . '/administrator',
    $root . '/plugins',
    $root . '/custom',
];

$fixedAuth = 0;
$fixedRegister = 0;
$filesTouched = 0;

$authNeedle = 'if (($strUsers == "") && true)';
$authNeedleAlt = "if ((\$strUsers == \"\") && true)";

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach ($it as $file) {
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
        continue;
    }
    $path = $file->getPathname();
    // Skip vendor-ish / this script
    if (strpos($path, DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR) !== false) {
        continue;
    }

    $c = file_get_contents($path);
    $n = $c;

    // Remove always-true authorization bypass (keep surrounding braces block)
    $n = preg_replace(
        '/\s*if\s*\(\(\s*\$strUsers\s*==\s*""\s*\)\s*&&\s*true\s*\)\s*\{\s*\$isValid\s*=\s*true;\s*\}/s',
        "\n    // MultiCMS Phase1: empty \$strUsers no longer grants access",
        $n,
        -1,
        $countAuth
    );
    $fixedAuth += $countAuth;

    // Lock register role
    $n2 = str_replace(
        'GetSQLValueString($_POST[\'level\'], "text")',
        'GetSQLValueString(\'member\', "text")',
        $n,
        $countReg
    );
    $n = $n2;
    $fixedRegister += $countReg;

    if ($n !== $c) {
        file_put_contents($path, $n);
        $filesTouched++;
    }
}

echo "Files touched: $filesTouched\n";
echo "isAuthorized bypass removed: $fixedAuth\n";
echo "register level locked: $fixedRegister\n";
