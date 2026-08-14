<?php
$root = dirname(__DIR__);
$removed = 0;
$files = 0;

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach ($it as $file) {
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
        continue;
    }
    $path = $file->getPathname();
    if (strpos($path, DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR) !== false) {
        continue;
    }
    if (basename($path) === 'LegacyAuth.php') {
        continue;
    }

    $lines = file($path);
    $out = [];
    $i = 0;
    $n = count($lines);
    $changed = false;
    while ($i < $n) {
        if (preg_match('/^\s*function\s+isAuthorized\s*\(/', $lines[$i])) {
            // skip until line that is only closing brace of function (depth)
            $depth = 0;
            $started = false;
            while ($i < $n) {
                $line = $lines[$i];
                $depth += substr_count($line, '{');
                $depth -= substr_count($line, '}');
                $started = $started || strpos($line, '{') !== false;
                $i++;
                if ($started && $depth <= 0) {
                    break;
                }
            }
            $out[] = "// isAuthorized provided by includes/LegacyAuth.php\n";
            $changed = true;
            $removed++;
            continue;
        }
        $out[] = $lines[$i];
        $i++;
    }
    if ($changed) {
        file_put_contents($path, implode('', $out));
        $files++;
    }
}

echo "Removed local isAuthorized from $files files ($removed functions)\n";
