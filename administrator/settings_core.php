<?php
/**
 * Core Site Settings (modern + legacy column sync) — Phase 3
 */
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/LegacyAuth.php';

Session::start();

$isAdmin = (function_exists('hasRole') && (hasRole('admin') || hasRole('administrator')))
    || (!empty($_SESSION['MM_UserGroup']) && in_array($_SESSION['MM_UserGroup'], ['admin', 'administrator'], true));

if (!$isAdmin) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$conn = $db->getConnection();
$flash = '';

// Ensure legacy settings columns exist when possible
if (class_exists('PluginManager')) {
    PluginManager::bridgeLegacySettings($conn);
}

$row = $db->queryOne('SELECT * FROM settings WHERE settingid = 1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $flash = 'Invalid security token.';
    } else {
        $siteTitle = trim((string) ($_POST['site_title'] ?? ''));
        $siteDesc = trim((string) ($_POST['site_description'] ?? ''));
        $adminEmail = trim((string) ($_POST['admin_email'] ?? ''));
        $theme = preg_replace('/[^a-z0-9_-]/i', '', (string) ($_POST['theme'] ?? 'default')) ?: 'default';
        $online = in_array($_POST['onlinestatus'] ?? '', ['yes', 'no'], true) ? $_POST['onlinestatus'] : 'yes';
        $metakey = trim((string) ($_POST['metakey'] ?? ''));
        $footer = (string) ($_POST['footer'] ?? '');

        if ($siteTitle === '') {
            $flash = 'Site title is required.';
        } else {
            // Modern columns (best effort)
            $hasSiteTitle = $conn->query("SHOW COLUMNS FROM settings LIKE 'site_title'");
            if ($hasSiteTitle && $hasSiteTitle->num_rows > 0) {
                $db->execute(
                    'UPDATE settings SET site_title=?, site_description=?, admin_email=? WHERE settingid=1',
                    'sss',
                    [$siteTitle, $siteDesc, $adminEmail]
                );
            }
            // Legacy columns
            $db->execute(
                'UPDATE settings SET title=?, metadesc=?, email=?, theme=?, onlinestatus=?, metakey=?, footer=? WHERE settingid=1',
                'sssssss',
                [$siteTitle, $siteDesc, $adminEmail, $theme, $online, $metakey, $footer]
            );
            do_action('multicms_settings_saved', $siteTitle);
            $flash = 'Settings saved.';
            $row = $db->queryOne('SELECT * FROM settings WHERE settingid = 1');
        }
    }
}

$titleVal = $row['site_title'] ?? ($row['title'] ?? '');
$descVal = $row['site_description'] ?? ($row['metadesc'] ?? '');
$emailVal = $row['admin_email'] ?? ($row['email'] ?? '');
$themeVal = $row['theme'] ?? 'default';
$onlineVal = $row['onlinestatus'] ?? 'yes';
$metakeyVal = $row['metakey'] ?? '';
$footerVal = $row['footer'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Site Settings (core) — MultiCMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Site Settings (core)</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="index.php">Admin home</a>
            <a class="btn btn-outline-primary btn-sm" href="posts.php">Posts (core)</a>
            <a class="btn btn-outline-primary btn-sm" href="plugins_prebuilt_sites.php">Ready Sites</a>
        </div>
    </div>
    <?php if ($flash): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <?php echo multicms_csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Site title</label>
                    <input class="form-control" name="site_title" required value="<?php echo htmlspecialchars($titleVal, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description / meta description</label>
                    <textarea class="form-control" name="site_description" rows="3"><?php echo htmlspecialchars($descVal, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Admin / contact email</label>
                    <input class="form-control" type="email" name="admin_email" value="<?php echo htmlspecialchars($emailVal, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Theme folder</label>
                        <input class="form-control" name="theme" value="<?php echo htmlspecialchars($themeVal, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Online status</label>
                        <select class="form-select" name="onlinestatus">
                            <option value="yes" <?php echo $onlineVal === 'yes' ? 'selected' : ''; ?>>Online</option>
                            <option value="no" <?php echo $onlineVal === 'no' ? 'selected' : ''; ?>>Offline</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Meta keywords</label>
                        <input class="form-control" name="metakey" value="<?php echo htmlspecialchars($metakeyVal, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Footer HTML</label>
                    <textarea class="form-control" name="footer" rows="3"><?php echo htmlspecialchars($footerVal, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Save settings</button>
            </form>
        </div>
    </div>
    <p class="text-muted small mt-3 mb-0">Writes both modern (<code>site_title</code>…) and legacy pack columns (<code>title</code>, <code>theme</code>, <code>onlinestatus</code>…).</p>
</div>
</body>
</html>
