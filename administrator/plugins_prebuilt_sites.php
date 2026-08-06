<?php
require_once('../includes/bootstrap.php');

// Only admins
if (!hasRole('administrator')) {
    header('Location: index.php?page=login');
    exit;
}

require_once('../includes/PluginManager.php');

$sites = PluginManager::listPrebuiltSites();

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action']) && $_POST['action'] === 'apply' && !empty($_POST['slug'])) {
    $slug = basename($_POST['slug']);
    $selected = null;
    foreach ($sites as $s) {
        if (($s['slug'] ?? '') === $slug) { $selected = $s; break; }
    }
    if (!$selected) {
        $flash = 'Site package not found.';
    } else {
        $result = PluginManager::applySiteTheme($selected);
        $flash = $result['message'];
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prebuilt Sites - Admin</title>
    <link rel="stylesheet" href="rayicecms.css">
    <style>
        .site-grid{display:flex;flex-wrap:wrap;gap:16px}
        .site-card{background:#fff;border:1px solid #eee;padding:12px;width:260px;border-radius:6px}
        .site-card img{width:100%;height:140px;object-fit:cover;border-radius:4px}
        .actions{margin-top:8px}
    </style>
</head>
<body>
<?php include('topdocs.php'); ?>
<div class="mainbody">
    <h2>Prebuilt Sites (Digitalcloud)</h2>
    <?php if ($flash): ?><div style="padding:8px;background:#fffbdd;border:1px solid #ffe58f;margin-bottom:12px"><?php echo htmlspecialchars($flash); ?></div><?php endif; ?>

    <div class="site-grid">
        <?php if (empty($sites)): ?>
            <div>No site packages found in plugins/prebuilt-sites/sites/</div>
        <?php endif; ?>

        <?php foreach ($sites as $site): ?>
            <div class="site-card">
                <?php if (!empty($site['thumbnail']) && file_exists($site['path'].'/'.$site['thumbnail'])): ?>
                    <img src="<?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', $site['path'].'/'.$site['thumbnail']); ?>" alt="<?php echo htmlspecialchars($site['name']); ?>">
                <?php else: ?>
                    <div style="width:100%;height:140px;background:#eee;display:flex;align-items:center;justify-content:center;color:#777">No thumbnail</div>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($site['name']); ?></h3>
                <div style="font-size:13px;color:#666"><?php echo htmlspecialchars($site['description'] ?? ''); ?></div>
                <div class="actions">
                    <?php if (!empty($site['preview']) && file_exists($site['path'].'/'.$site['preview'])): ?>
                        <a href="<?php echo '../plugins/prebuilt-sites/sites/'.$site['slug'].'/'.$site['preview']; ?>" target="_blank">Preview</a>
                    <?php endif; ?>

                    <form method="post" style="display:inline">
                        <input type="hidden" name="action" value="apply">
                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($site['slug']); ?>">
                        <button type="submit" style="margin-left:8px">Apply Theme</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin-top:20px">
        <strong>Note:</strong> "Apply Theme" copies the package theme into the themes/ directory. You may need to set the copied theme as active in Settings or the database. This first iteration is non-destructive; no demo content is imported yet.
    </div>
</div>
<?php include('footer.php'); ?>
</body>
</html>
