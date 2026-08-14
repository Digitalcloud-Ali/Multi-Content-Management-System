<?php
/**
 * Build includes/sql/legacy_pack_tables.sql from raycms.sql CREATE TABLE statements (no INSERTs).
 */
$src = file_get_contents(dirname(__DIR__) . '/includes/raycms.sql');
$skip = ['settings' => true];
$out = "-- MultiCMS Phase 2: minimal legacy pack tables (structures only, no seed data)\n\n";

if (!preg_match_all('/CREATE TABLE IF NOT EXISTS `([^`]+)`\s*\(.*?\)\s*ENGINE=[^;]+;/s', $src, $m, PREG_SET_ORDER)) {
    fwrite(STDERR, "No CREATE TABLE found\n");
    exit(1);
}

foreach ($m as $row) {
    $name = $row[1];
    if (isset($skip[$name])) {
        continue;
    }
    $stmt = $row[0];
    if ($name === 'members') {
        $stmt = str_replace('`passs` varchar(100)', '`passs` varchar(255)', $stmt);
    }
    $out .= $stmt . "\n\n";
}

$dir = dirname(__DIR__) . '/includes/sql';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
file_put_contents($dir . '/legacy_pack_tables.sql', $out);
echo 'Wrote legacy_pack_tables.sql (' . strlen($out) . " bytes, " . substr_count($out, 'CREATE TABLE') . " tables)\n";
