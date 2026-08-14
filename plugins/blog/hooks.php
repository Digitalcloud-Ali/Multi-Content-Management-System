<?php
/**
 * Optional Blog pack hooks (Phase 3 demo).
 */
add_action('multicms_site_applied', function ($slug, $site) {
    if ($slug !== 'blog') {
        return;
    }
    // Ensure a blog category exists for the pack topic
    try {
        if (!function_exists('getDB') && !class_exists('Database')) {
            return;
        }
        $conn = function_exists('getDB') ? getDB()->getConnection() : null;
        if (!$conn instanceof mysqli) {
            return;
        }
        $check = $conn->query("SHOW TABLES LIKE 'categories'");
        if (!$check || $check->num_rows === 0) {
            return;
        }
        $exists = $conn->query("SELECT cateid FROM categories WHERE selecttopic='blog' LIMIT 1");
        if ($exists && $exists->num_rows > 0) {
            return;
        }
        $conn->query("INSERT INTO categories (catename, selecttopic) VALUES ('General', 'blog')");
    } catch (Throwable $e) {
        // non-fatal
    }
}, 10);
