<?php
/**
 * Rewrite legacy ../includes|images|administrator|themes paths
 * after moving modules to plugins/<slug>/www/
 */
$root = dirname(__DIR__);
$mods = [
    'adposting', 'blog', 'doctors', 'imagegallery', 'marketplace',
    'portfolio', 'productpublisher', 'searchengine', 'tutorials', 'videostream'
];

$replacements = [
    "../includes/" => "../../../includes/",
    "../images/" => "../../../images/",
    "../administrator/" => "../../../administrator/",
    "../themes/" => "../../../themes/",
];

$fixedFiles = 0;
$replacementsDone = 0;

foreach ($mods as $m) {
    $dir = $root . "/plugins/$m/www";
    if (!is_dir($dir)) {
        echo "SKIP missing $dir\n";
        continue;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if (!$file->isFile()) continue;
        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['php', 'html', 'htm', 'js', 'css'], true)) continue;
        $path = $file->getPathname();
        $c = file_get_contents($path);
        $n = $c;
        foreach ($replacements as $from => $to) {
            // Avoid turning ../../../ into ../../../../../../
            $n = str_replace($to, "\0KEEP\0", $n);
            $count = 0;
            $n = str_replace($from, $to, $n, $count);
            $replacementsDone += $count;
            $n = str_replace("\0KEEP\0", $to, $n);
        }
        if ($n !== $c) {
            file_put_contents($path, $n);
            $fixedFiles++;
        }
    }
}

echo "Fixed files: $fixedFiles\n";
echo "Replacements: $replacementsDone\n";

// Verify no shallow ../includes remain in www php
$bad = 0;
foreach ($mods as $m) {
    $dir = $root . "/plugins/$m/www";
    if (!is_dir($dir)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;
        $lines = file($file->getPathname());
        foreach ($lines as $i => $line) {
            if (preg_match('/(?<!\.)\.\.\/includes\//', $line) && strpos($line, '../../../includes/') === false) {
                echo "BAD {$file->getPathname()}:" . ($i + 1) . " " . trim($line) . "\n";
                $bad++;
            }
        }
    }
}
echo "Remaining shallow ../includes: $bad\n";
