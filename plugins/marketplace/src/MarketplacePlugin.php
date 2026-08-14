<?php
class MarketplacePlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Marketplace www package missing'];
        }
        return ['success' => true, 'message' => 'Marketplace site plugin ready', 'slug' => 'marketplace'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
