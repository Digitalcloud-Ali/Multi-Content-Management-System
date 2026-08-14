<?php
/**
 * Root / content path helpers (WordPress-style content/ directory).
 */
if (!defined('MC_ROOT')) {
    define('MC_ROOT', dirname(__DIR__));
}
if (!defined('MC_CONTENT')) {
    define('MC_CONTENT', MC_ROOT . '/content');
}

if (!function_exists('mc_root_path')) {
    function mc_root_path($relative = '') {
        $relative = ltrim(str_replace('\\', '/', (string) $relative), '/');
        return $relative === '' ? MC_ROOT : MC_ROOT . '/' . $relative;
    }
}

if (!function_exists('mc_content_path')) {
    function mc_content_path($relative = '') {
        $relative = ltrim(str_replace('\\', '/', (string) $relative), '/');
        return $relative === '' ? MC_CONTENT : MC_CONTENT . '/' . $relative;
    }
}

if (!function_exists('mc_theme_path')) {
    function mc_theme_path($file = '') {
        $file = ltrim(str_replace('\\', '/', (string) $file), '/');
        return $file === ''
            ? mc_content_path('themes/default')
            : mc_content_path('themes/default/' . $file);
    }
}

if (!function_exists('mc_theme_url')) {
    /** Public URL path to default theme asset (respects SITE_BASE_PATH). */
    function mc_theme_url($file = '') {
        $base = function_exists('mc_base_path') ? mc_base_path() : '';
        $file = ltrim((string) $file, '/');
        $path = 'content/themes/default' . ($file !== '' ? '/' . $file : '');
        return ($base === '' ? '' : $base) . '/' . $path;
    }
}
