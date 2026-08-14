<?php
class TutorialsPlugin {
    public static function onActivate() {
        $www = dirname(__DIR__) . '/www';
        if (!is_dir($www) || !is_file($www . '/index.php')) {
            return ['success' => false, 'message' => 'Tutorials www package missing'];
        }
        return ['success' => true, 'message' => 'Tutorials site plugin ready', 'slug' => 'tutorials'];
    }
    public static function onDeactivate() {
        return ['success' => true];
    }
}
