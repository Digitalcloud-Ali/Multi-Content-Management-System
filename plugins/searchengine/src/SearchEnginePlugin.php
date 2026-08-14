<?php
class SearchEnginePlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Search Engine www package missing'];
        }
        return ['success' => true, 'message' => 'Search Engine site plugin ready', 'slug' => 'searchengine'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
