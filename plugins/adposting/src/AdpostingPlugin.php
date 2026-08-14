<?php
class AdpostingPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Adposting www package missing'];
        }
        return ['success' => true, 'message' => 'Adposting site plugin ready', 'slug' => 'adposting'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
