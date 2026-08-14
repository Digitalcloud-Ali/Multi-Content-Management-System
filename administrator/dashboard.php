<?php
/**
 * Core admin dashboard.
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

$db = getDB();
$settings = $db->queryOne('SELECT * FROM settings WHERE settingid = 1') ?: [];
$siteTitle = $settings['site_title'] ?? ($settings['title'] ?? 'MultiCMS');

$postCount = 0;
$userCount = 0;
try {
    $r = $db->queryOne("SELECT COUNT(*) AS c FROM posts");
    $postCount = (int) ($r['c'] ?? 0);
} catch (Throwable $e) {
}
try {
    $r = $db->queryOne("SELECT COUNT(*) AS c FROM users");
    $userCount = (int) ($r['c'] ?? 0);
} catch (Throwable $e) {
}

$updateCheck = UpdateService::checkForUpdates(false);
$adminNavActive = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — <?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . '/_nav.php'; ?>
<div class="container pb-5">
    <h1 class="h3 mb-3">Dashboard</h1>

    <?php if (!empty($updateCheck['update_available'])): ?>
        <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Update available:</strong>
                v<?php echo htmlspecialchars((string) ($updateCheck['remote']['version'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                is on GitHub (you have v<?php echo htmlspecialchars((string) ($updateCheck['local'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>).
            </div>
            <a class="btn btn-sm btn-warning" href="updates.php">Review &amp; update</a>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Posts</div>
                    <div class="display-6"><?php echo $postCount; ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Users</div>
                    <div class="display-6"><?php echo $userCount; ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Site</div>
                    <div class="fw-semibold"><?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="list-group shadow-sm mb-4">
        <a class="list-group-item list-group-item-action" href="posts.php"><strong>Posts</strong> — create and publish content</a>
        <a class="list-group-item list-group-item-action" href="settings_core.php"><strong>Site Settings</strong> — title, description, online status</a>
        <a class="list-group-item list-group-item-action" href="flagship_sites.php"><strong>Flagship Sites</strong> — apply a complete starter site</a>
        <a class="list-group-item list-group-item-action" href="updates.php"><strong>Updates &amp; Backup</strong> — GitHub updates, backup, restore</a>
    </div>

    <p class="text-muted small mb-0">Installer is locked after setup — no need to delete <code>install.php</code>.</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
