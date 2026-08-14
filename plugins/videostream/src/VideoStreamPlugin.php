<?php
class VideoStreamPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Video Stream www package missing'];
        }
        return ['success' => true, 'message' => 'Video Stream site plugin ready', 'slug' => 'videostream'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
