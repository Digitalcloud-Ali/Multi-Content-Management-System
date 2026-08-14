<?php
class DoctorsPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Doctors www package missing'];
        }
        return ['success' => true, 'message' => 'Doctors site plugin ready', 'slug' => 'doctors'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
