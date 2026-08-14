<?php
/**
 * Updates, backups, restore — checks GitHub version.json / releases.
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

$message = '';
$error = '';
$check = UpdateService::checkForUpdates(false);
$backups = UpdateService::listBackups();
$local = UpdateService::localVersion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'check') {
            $check = UpdateService::checkForUpdates(true);
            $message = $check['message'];
        } elseif ($action === 'backup') {
            $result = UpdateService::createBackup('manual');
            if (!empty($result['success'])) {
                $message = $result['message'];
            } else {
                $error = $result['message'] ?? 'Backup failed';
            }
            $backups = UpdateService::listBackups();
        } elseif ($action === 'update') {
            if (empty($_POST['confirm_backup'])) {
                $error = 'You must confirm that a backup will be created before updating.';
            } else {
                $bak = UpdateService::createBackup('pre-update');
                if (empty($bak['success'])) {
                    $error = 'Update aborted — backup failed: ' . ($bak['message'] ?? '');
                } else {
                    $upd = UpdateService::applyUpdateFromGitHub();
                    if (!empty($upd['success'])) {
                        $message = $bak['message'] . ' ' . $upd['message'];
                        $check = UpdateService::checkForUpdates(true);
                        $local = UpdateService::localVersion();
                    } else {
                        $error = $upd['message'] ?? 'Update failed';
                    }
                }
            }
            $backups = UpdateService::listBackups();
        } elseif ($action === 'restore') {
            $file = (string) ($_POST['backup_file'] ?? '');
            $withFiles = !empty($_POST['restore_files']);
            if (empty($_POST['confirm_restore'])) {
                $error = 'Please confirm restore.';
            } else {
                // Safety backup before restore
                UpdateService::createBackup('pre-restore');
                $res = UpdateService::restoreBackup($file, $withFiles);
                if (!empty($res['success'])) {
                    $message = $res['message'];
                } else {
                    $error = $res['message'] ?? 'Restore failed';
                }
            }
            $backups = UpdateService::listBackups();
        }
    }
}

$db = getDB();
$settings = $db->queryOne('SELECT * FROM settings WHERE settingid = 1') ?: [];
$siteTitle = $settings['site_title'] ?? ($settings['title'] ?? 'MultiCMS');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Updates &amp; Backup — <?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Updates &amp; Backup</h1>
            <p class="text-muted mb-0">Checks GitHub for newer MultiCMS versions</p>
        </div>
        <a class="btn btn-outline-secondary btn-sm" href="dashboard.php">Dashboard</a>
    </div>

    <?php if ($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5">Version</h2>
            <p class="mb-1">Installed: <strong><?php echo htmlspecialchars((string) ($local['version'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <?php if (!empty($check['remote']['version'])): ?>
                <p class="mb-1">Latest on GitHub: <strong><?php echo htmlspecialchars((string) $check['remote']['version'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <?php endif; ?>
            <p class="small text-muted"><?php echo htmlspecialchars((string) ($check['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if (!empty($check['remote']['notes'])): ?>
                <p class="small"><?php echo htmlspecialchars((string) $check['remote']['notes'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form method="post" class="d-inline">
                <?php echo multicms_csrf_field(); ?>
                <input type="hidden" name="action" value="check">
                <button class="btn btn-outline-primary btn-sm" type="submit">Check again now</button>
            </form>

            <?php if (!empty($check['update_available'])): ?>
                <div class="alert alert-warning mt-3 mb-0">
                    An update is available. MultiCMS will create a backup first, then download the latest files from GitHub (your database login, uploads, and site path settings are kept).
                </div>
                <form method="post" class="mt-3" onsubmit="return confirm('Create a backup and update from GitHub now?');">
                    <?php echo multicms_csrf_field(); ?>
                    <input type="hidden" name="action" value="update">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="confirm_backup" id="confirm_backup" value="1" required>
                        <label class="form-check-label" for="confirm_backup">I understand a backup will be created, then files will be updated from GitHub</label>
                    </div>
                    <button class="btn btn-warning" type="submit">Backup &amp; update to latest</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5">Create backup</h2>
            <p class="small text-muted">Saves a ZIP of site files plus a SQL dump of the database into <code>backups/</code> (not web-accessible).</p>
            <form method="post">
                <?php echo multicms_csrf_field(); ?>
                <input type="hidden" name="action" value="backup">
                <button class="btn btn-primary btn-sm" type="submit">Download backup now</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5">Restore</h2>
            <?php if (empty($backups)): ?>
                <p class="text-muted mb-0">No backups yet.</p>
            <?php else: ?>
                <form method="post" onsubmit="return confirm('Restore this backup? A safety backup will be created first.');">
                    <?php echo multicms_csrf_field(); ?>
                    <input type="hidden" name="action" value="restore">
                    <div class="mb-2">
                        <label class="form-label">Backup file</label>
                        <select name="backup_file" class="form-select" required>
                            <?php foreach ($backups as $b): ?>
                                <option value="<?php echo htmlspecialchars($b['file'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($b['file'], ENT_QUOTES, 'UTF-8'); ?>
                                    (<?php echo date('Y-m-d H:i', (int) $b['mtime']); ?>,
                                    <?php echo number_format(((int) $b['size']) / 1048576, 2); ?> MB)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="restore_files" id="restore_files" value="1">
                        <label class="form-check-label" for="restore_files">Also restore files from ZIP (not only database)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="confirm_restore" id="confirm_restore" value="1" required>
                        <label class="form-check-label" for="confirm_restore">I confirm I want to restore</label>
                    </div>
                    <button class="btn btn-danger btn-sm" type="submit">Restore selected backup</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
