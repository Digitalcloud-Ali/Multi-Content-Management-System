<?php
/**
 * BlogPlugin — ready-made blog site package.
 */
class BlogPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Blog www package missing'];
        }
        return ['success' => true, 'message' => 'Blog site plugin ready', 'slug' => 'blog'];
    }

    public static function onDeactivate() {
        return ['success' => true, 'message' => 'Blog site plugin deactivated (files kept)'];
    }
}
