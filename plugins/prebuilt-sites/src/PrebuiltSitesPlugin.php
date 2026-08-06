<?php
// Simple plugin class for Prebuilt Sites
class PrebuiltSitesPlugin {
    public static function getSitesPath() {
        return __DIR__ . '/sites';
    }

    public static function listSites() {
        $sitesDir = self::getSitesPath();
        $sites = [];
        if (!is_dir($sitesDir)) return $sites;
        $items = scandir($sitesDir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $manifest = $sitesDir . '/' . $item . '/manifest.json';
            if (is_file($manifest)) {
                $json = @file_get_contents($manifest);
                $data = @json_decode($json, true);
                if ($data) {
                    $data['path'] = $sitesDir . '/' . $item;
                    $sites[] = $data;
                }
            }
        }
        return $sites;
    }
}
