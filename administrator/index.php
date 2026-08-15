<?php
/**
 * Admin entry — redirect to dashboard or login.
 */
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/LegacyAuth.php';

Session::start();

$isAdmin = (function_exists('hasRole') && (hasRole('admin') || hasRole('administrator')))
    || (!empty($_SESSION['MM_UserGroup']) && in_array($_SESSION['MM_UserGroup'], ['admin', 'administrator'], true));

if (!$isAdmin || empty($_SESSION['MM_Username'])) {
    header('Location: login.php');
    exit;
}

header('Location: dashboard.php');
exit;
