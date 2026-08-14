<?php
/**
 * Theme header — dual mode:
 * - Ready-made packs set $row_setting + $theme_path (Dreamweaver chrome)
 * - Modern core uses getSetting() / Bootstrap
 */
$packMode = !empty($row_setting) && is_array($row_setting) && isset($theme_path);
if ($packMode) {
    $siteTitle = htmlspecialchars((string) ($row_setting['title'] ?? 'MultiCMS'), ENT_QUOTES, 'UTF-8');
    $topic = htmlspecialchars((string) ($row_setting['selecttopic'] ?? 'blog'), ENT_QUOTES, 'UTF-8');
    ?>
<div class="pack-header" style="padding:12px 16px;background:#1a1a1a;color:#fff;margin-bottom:8px;">
  <strong style="font-size:1.1rem;"><?php echo $siteTitle; ?></strong>
  <span style="opacity:.7;margin-left:8px;"><?php echo $topic; ?></span>
  <nav style="margin-top:8px;">
    <a href="index.php" style="color:#fff;margin-right:12px;">Home</a>
    <a href="search.php" style="color:#fff;margin-right:12px;">Search</a>
    <a href="contact.php" style="color:#fff;margin-right:12px;">Contact</a>
    <?php if (!empty($_SESSION['MM_Username'])): ?>
      <a href="account.php" style="color:#fff;margin-right:12px;">Account</a>
      <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] ?? 'index.php', ENT_QUOTES, 'UTF-8'); ?>?doLogout=true" style="color:#fff;">Logout</a>
    <?php else: ?>
      <a href="login.php" style="color:#fff;margin-right:12px;">Login</a>
      <a href="register.php" style="color:#fff;">Register</a>
    <?php endif; ?>
  </nav>
</div>
    <?php
    return;
}

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null) { return $default; }
}
if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken() { return ''; }
}
if (!function_exists('isAuthenticated')) {
    function isAuthenticated() { return !empty($_SESSION['MM_Username']); }
}
$currentUser = $currentUser ?? ['username' => $_SESSION['MM_Username'] ?? ''];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(getSetting('site_title', 'Multi-Content Management System'), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getSetting('site_description', 'A modern content management system'), ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars(mc_theme_url('style.css'), ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars((function_exists('mc_base_path') ? mc_base_path() : '') . '/content/assets/favicon.png', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo htmlspecialchars(mc_url(), ENT_QUOTES, 'UTF-8'); ?>">
                <i class="fas fa-home"></i>
                <?php echo htmlspecialchars(getSetting('site_name', getSetting('site_title', 'MultiCMS')), ENT_QUOTES, 'UTF-8'); ?>
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
                <form class="d-flex me-3" action="<?php echo htmlspecialchars(mc_url('search'), ENT_QUOTES, 'UTF-8'); ?>" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search..."
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
    <main class="container py-4">
