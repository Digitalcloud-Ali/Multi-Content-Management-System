<?php
class ImageGalleryPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Image Gallery www package missing'];
        }
        return ['success' => true, 'message' => 'Image Gallery site plugin ready', 'slug' => 'imagegallery'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
