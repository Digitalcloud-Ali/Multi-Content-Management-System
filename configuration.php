<?php
/**
 * Shared front-end configuration for legacy ready-made site packs.
 * Always resolve paths from MULTICMS_ROOT (repo root), not relative ../ .
 */

if (!defined('MULTICMS_ROOT')) {
    define('MULTICMS_ROOT', __DIR__);
}

$theme_path = MULTICMS_ROOT . '/themes/';
$favicon = '';

// Convenience for templates that still expect a web-relative theme URL prefix
$theme_url = 'themes/';
