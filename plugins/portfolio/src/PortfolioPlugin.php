<?php
class PortfolioPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Portfolio www package missing'];
        }
        return ['success' => true, 'message' => 'Portfolio site plugin ready', 'slug' => 'portfolio'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
