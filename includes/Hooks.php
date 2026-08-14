<?php
/**
 * Minimal WordPress-style hooks API for MultiCMS (Phase 3).
 * Actions: do_action / add_action
 * Filters: apply_filters / add_filter
 */
class Multicms_Hooks {
    /** @var array<string, array<int, array<int, callable>>> */
    private static $actions = [];
    /** @var array<string, array<int, array<int, callable>>> */
    private static $filters = [];
    private static $loadedPluginHooks = false;

    public static function add_action($hook, $callback, $priority = 10) {
        self::$actions[$hook][$priority][] = $callback;
    }

    public static function add_filter($hook, $callback, $priority = 10) {
        self::$filters[$hook][$priority][] = $callback;
    }

    public static function do_action($hook, ...$args) {
        self::ensurePluginHooksLoaded();
        if (empty(self::$actions[$hook])) {
            return;
        }
        ksort(self::$actions[$hook], SORT_NUMERIC);
        foreach (self::$actions[$hook] as $callbacks) {
            foreach ($callbacks as $cb) {
                call_user_func_array($cb, $args);
            }
        }
    }

    public static function apply_filters($hook, $value, ...$args) {
        self::ensurePluginHooksLoaded();
        if (empty(self::$filters[$hook])) {
            return $value;
        }
        ksort(self::$filters[$hook], SORT_NUMERIC);
        foreach (self::$filters[$hook] as $callbacks) {
            foreach ($callbacks as $cb) {
                $value = call_user_func_array($cb, array_merge([$value], $args));
            }
        }
        return $value;
    }

    /**
     * Load content/plugins/<slug>/hooks.php once (and optional includes/hooks.php).
     */
    public static function ensurePluginHooksLoaded() {
        if (self::$loadedPluginHooks) {
            return;
        }
        self::$loadedPluginHooks = true;

        $coreHooks = __DIR__ . '/hooks-bootstrap.php';
        if (is_file($coreHooks)) {
            require_once $coreHooks;
        }

        $plugins = dirname(__DIR__) . '/content/plugins';
        if (!is_dir($plugins)) {
            return;
        }
        foreach (scandir($plugins) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $file = $plugins . '/' . $item . '/hooks.php';
            if (is_file($file)) {
                require_once $file;
            }
        }
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10) {
        Multicms_Hooks::add_action($hook, $callback, $priority);
    }
}
if (!function_exists('do_action')) {
    function do_action($hook, ...$args) {
        Multicms_Hooks::do_action($hook, ...$args);
    }
}
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10) {
        Multicms_Hooks::add_filter($hook, $callback, $priority);
    }
}
if (!function_exists('apply_filters')) {
    function apply_filters($hook, $value, ...$args) {
        return Multicms_Hooks::apply_filters($hook, $value, ...$args);
    }
}
