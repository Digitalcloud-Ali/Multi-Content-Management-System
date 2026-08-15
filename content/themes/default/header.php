<?php
/**
 * Modern theme header (Bootstrap).
 */
if (!function_exists('getSetting')) {
    function getSetting($key, $default = null) { return $default; }
}
if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken() { return ''; }
}
if (!function_exists('isAuthenticated')) {
    function isAuthenticated() { return !empty($_SESSION['MM_Username']); }
}
if (!function_exists('mc_url')) {
    function mc_url($path = '') { return '/' . ltrim((string) $path, '/'); }
}
if (!function_exists('mc_theme_url')) {
    function mc_theme_url($file = '') { return 'content/themes/default/' . ltrim((string) $file, '/'); }
}
$currentUser = $currentUser ?? ['username' => $_SESSION['MM_Username'] ?? ''];
$siteName = getSetting('site_name', getSetting('site_title', 'MultiCMS'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(getSetting('site_title', 'MultiCMS'), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getSetting('site_description', 'A modern content management system'), ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars(mc_theme_url('theme.css'), ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars((function_exists('mc_base_path') ? mc_base_path() : '') . '/content/assets/favicon.png', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo htmlspecialchars(mc_url(), ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url(), ENT_QUOTES, 'UTF-8'); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url('blog'), ENT_QUOTES, 'UTF-8'); ?>">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url('about'), ENT_QUOTES, 'UTF-8'); ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url('contact'), ENT_QUOTES, 'UTF-8'); ?>">Contact</a></li>
                </ul>
                <form class="d-flex me-3" action="<?php echo htmlspecialchars(mc_url('search'), ENT_QUOTES, 'UTF-8'); ?>" method="get">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search…"
                           value="<?php echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                </form>
                <ul class="navbar-nav">
                    <?php if (isAuthenticated()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url('profile'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($currentUser['username'] ?? 'User', ENT_QUOTES, 'UTF-8'); ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url('logout'), ENT_QUOTES, 'UTF-8'); ?>">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url('login'), ENT_QUOTES, 'UTF-8'); ?>">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(mc_url('register'), ENT_QUOTES, 'UTF-8'); ?>">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main>
