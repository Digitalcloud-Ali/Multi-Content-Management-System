<?php
/**
 * Site Settings — modern schema only.
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
$flash = '';
$row = $db->queryOne('SELECT * FROM settings WHERE settingid = 1') ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $flash = 'Invalid security token.';
    } else {
        $siteTitle = trim((string) ($_POST['site_title'] ?? ''));
        $siteDesc = trim((string) ($_POST['site_description'] ?? ''));
        $adminEmail = trim((string) ($_POST['admin_email'] ?? ''));
        $timezone = trim((string) ($_POST['timezone'] ?? 'UTC')) ?: 'UTC';

        if ($siteTitle === '') {
            $flash = 'Site title is required.';
        } else {
            $db->execute(
                'UPDATE settings SET site_title=?, site_description=?, admin_email=?, timezone=? WHERE settingid=1',
                'ssss',
                [$siteTitle, $siteDesc, $adminEmail, $timezone]
            );
            if (function_exists('do_action')) {
                do_action('multicms_settings_saved', $siteTitle);
            }
            $flash = 'Settings saved.';
            $row = $db->queryOne('SELECT * FROM settings WHERE settingid = 1') ?: [];
        }
    }
}

$titleVal = $row['site_title'] ?? '';
$descVal = $row['site_description'] ?? '';
$emailVal = $row['admin_email'] ?? '';
$tzVal = $row['timezone'] ?? 'UTC';
$siteTitle = $titleVal !== '' ? $titleVal : 'MultiCMS';
$adminNavActive = 'settings';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Site Settings — <?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . '/_nav.php'; ?>
<div class="container pb-5" style="max-width:720px;">
    <h1 class="h3 mb-3">Site Settings</h1>
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
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="site_description" rows="3"><?php echo htmlspecialchars($descVal, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Admin / contact email</label>
                    <input class="form-control" type="email" name="admin_email" value="<?php echo htmlspecialchars($emailVal, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Timezone</label>
                    <input class="form-control" name="timezone" value="<?php echo htmlspecialchars($tzVal, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <button class="btn btn-primary" type="submit">Save settings</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
