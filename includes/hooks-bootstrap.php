<?php
/**
 * Core hook registrations (loaded by Multicms_Hooks).
 * Keep light — product plugins should use content/plugins/<slug>/hooks.php.
 */

// Example filter: allow changing public pack URL label in admin responses later
add_filter('multicms_active_site_public_url', function ($url) {
    return $url ?: 'index.php';
}, 10);
