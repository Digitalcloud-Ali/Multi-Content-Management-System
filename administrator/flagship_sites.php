<?php
/**
 * Apply / re-seed a flagship starter site (modern core packages under /sites).
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
$packages = FlagshipSite::listPackages();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $slug = basename(trim((string) ($_POST['slug'] ?? '')));
        $authorId = 1;
        try {
            $u = getDB()->queryOne('SELECT userid FROM users WHERE username = ? LIMIT 1', 's', [$_SESSION['MM_Username']]);
            if ($u) {
                $authorId = (int) $u['userid'];
            }
        } catch (Throwable $e) {
        }
        $result = FlagshipSite::apply($slug, null, $authorId);
        if (!empty($result['success'])) {
            $message = $result['message'];
        } else {
            $error = $result['message'] ?? 'Apply failed.';
        }
    }
}

$active = [];
$activeFile = __DIR__ . '/../includes/active_site.json';
if (is_file($activeFile)) {
    $active = json_decode((string) file_get_contents($activeFile), true) ?: [];
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
    <title>Flagship Sites — <?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:720px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Flagship Sites</h1>
            <p class="text-muted mb-0">1-click complete starter sites (sample posts &amp; pages)</p>
        </div>
        <a class="btn btn-outline-secondary btn-sm" href="dashboard.php">Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if (!empty($active['slug'])): ?>
        <p class="small text-muted">Active marker: <code><?php echo htmlspecialchars($active['slug'] . ' (' . ($active['mode'] ?? '') . ')', ENT_QUOTES, 'UTF-8'); ?></code></p>
    <?php endif; ?>

    <div class="alert alert-info small">
        Applying a package only adds missing posts/pages (same slug is skipped). It does not wipe your content.
    </div>

    <?php if (empty($packages)): ?>
        <p class="text-muted">No packages found under <code>content/sites/</code>.</p>
    <?php else: ?>
        <?php foreach ($packages as $pkg): ?>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h5"><?php echo htmlspecialchars($pkg['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="mb-2"><?php echo htmlspecialchars($pkg['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="small text-muted mb-3">v<?php echo htmlspecialchars($pkg['version'], ENT_QUOTES, 'UTF-8'); ?> · <code><?php echo htmlspecialchars($pkg['slug'], ENT_QUOTES, 'UTF-8'); ?></code></p>
                    <form method="post" onsubmit="return confirm('Seed missing content from this flagship package?');">
                        <?php echo multicms_csrf_field(); ?>
                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($pkg['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Apply / re-seed</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
