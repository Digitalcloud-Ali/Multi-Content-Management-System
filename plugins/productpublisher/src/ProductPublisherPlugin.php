<?php
class ProductPublisherPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Product Publisher www package missing'];
        }
        return ['success' => true, 'message' => 'Product Publisher site plugin ready', 'slug' => 'productpublisher'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
