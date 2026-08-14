<?php
/**
 * PluginManager — Fresh core helpers (site packs removed).
 */
class PluginManager {
    public static function applyFreshDefault($mysqli = null) {
        $slug = 'default';
        if ($mysqli instanceof mysqli) {
            $stmt = $mysqli->prepare('UPDATE settings SET selecttopic = ? WHERE settingid = 1');
            if ($stmt) {
                $stmt->bind_param('s', $slug);
                $stmt->execute();
                $stmt->close();
            }
        } elseif (function_exists('getDB')) {
            try {
                getDB()->execute('UPDATE settings SET selecttopic = ? WHERE settingid = 1', 's', [$slug]);
            } catch (Exception $e) {
                // ignore
            }
        }

        @file_put_contents(__DIR__ . '/active_site.json', json_encode([
            'slug' => 'default',
            'name' => 'Fresh default',
            'url' => 'index.php',
            'applied_at' => date('c'),
            'mode' => 'fresh',
        ], JSON_PRETTY_PRINT));

        if (function_exists('do_action')) {
            do_action('multicms_site_applied', 'default', ['slug' => 'default', 'name' => 'Fresh default']);
        }

        return ['success' => true, 'message' => 'Fresh default site selected.', 'slug' => 'default', 'url' => 'index.php'];
    }
}
