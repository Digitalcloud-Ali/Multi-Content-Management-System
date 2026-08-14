<?php
/**
 * Shared admin top nav (Bootstrap).
 * Expects: $siteTitle, optional $adminNavActive (dashboard|posts|settings|flagship|updates)
 */
$adminNavActive = $adminNavActive ?? '';
$localVerLabel = '';
if (class_exists('UpdateService')) {
    $lv = UpdateService::localVersion();
    $localVerLabel = (string) ($lv['version'] ?? '');
}
$navUser = htmlspecialchars((string) ($_SESSION['MM_Username'] ?? ''), ENT_QUOTES, 'UTF-8');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">MultiCMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?php echo $adminNavActive === 'dashboard' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $adminNavActive === 'posts' ? 'active' : ''; ?>" href="posts.php">Posts</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $adminNavActive === 'settings' ? 'active' : ''; ?>" href="settings_core.php">Settings</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $adminNavActive === 'flagship' ? 'active' : ''; ?>" href="flagship_sites.php">Flagships</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $adminNavActive === 'updates' ? 'active' : ''; ?>" href="updates.php">Updates</a></li>
            </ul>
            <span class="navbar-text text-white-50 small me-3">
                <?php echo $navUser; ?><?php echo $localVerLabel !== '' ? ' · v' . htmlspecialchars($localVerLabel, ENT_QUOTES, 'UTF-8') : ''; ?>
            </span>
            <a class="btn btn-outline-light btn-sm" href="../index.php" target="_blank" rel="noopener">View site</a>
            <a class="btn btn-outline-warning btn-sm ms-2" href="login.php?doLogout=true">Logout</a>
        </div>
    </div>
</nav>
