<?php
require_once('../includes/bootstrap.php');
require_once('../includes/LegacyAuth.php');
require_once('../includes/PluginManager.php');

// Only admins (modern role or legacy MM_UserGroup)
$isAdmin = (function_exists('hasRole') && hasRole('administrator'))
    || (function_exists('hasRole') && hasRole('admin'))
    || (!empty($_SESSION['MM_UserGroup']) && in_array($_SESSION['MM_UserGroup'], ['admin', 'administrator'], true));
if (!$isAdmin) {
    header('Location: login.php');
    exit;
}

$siteModules = PluginManager::listSiteModules();
$themePackages = PluginManager::listPrebuiltSites();
$active = PluginManager::getActiveSite();

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $flash = 'Invalid security token. Please try again.';
    } elseif ($_POST['action'] === 'apply_site' && !empty($_POST['slug'])) {
        $result = PluginManager::applySiteAsMain($_POST['slug']);
        $flash = $result['message'];
        $active = PluginManager::getActiveSite();
    } elseif ($_POST['action'] === 'apply_fresh') {
        $result = PluginManager::applyFreshDefault();
        $flash = $result['message'];
        $active = PluginManager::getActiveSite();
    } elseif ($_POST['action'] === 'apply_theme' && !empty($_POST['slug'])) {
        $slug = basename($_POST['slug']);
        $selected = null;
        foreach ($themePackages as $s) {
            if (($s['slug'] ?? '') === $slug) {
                $selected = $s;
                break;
            }
        }
        if (!$selected) {
            $flash = 'Theme package not found.';
        } else {
            $result = PluginManager::applySiteTheme($selected);
            $flash = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ready-made Sites (legacy packs) - Admin</title>
    <link rel="stylesheet" href="rayicecms.css">
    <style>
        .site-grid{display:flex;flex-wrap:wrap;gap:16px}
        .site-card{background:#fff;border:1px solid #eee;padding:12px;width:280px;border-radius:6px}
        .site-card.active{border-color:#2f6fed}
        .badge{display:inline-block;padding:2px 8px;border-radius:999px;background:#eef3ff;color:#2f6fed;font-size:12px}
        .actions{margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        .muted{color:#666;font-size:13px}
        .section{margin:24px 0}
        .warn{padding:10px;background:#fff4e5;border:1px solid #ffd8a8;margin-bottom:16px;font-size:13px}
    </style>
</head>
<body>
<?php if (file_exists('topdocs.php')) { include('topdocs.php'); } ?>
<div class="mainbody">
    <h2>Ready-made Sites (legacy packs)</h2>
    <div class="warn">
        These are optional flagship <strong>plugin</strong> packages — not the MultiCMS core.
        They are legacy Dreamweaver-era site packs. Prefer the fresh default core for new sites.
    </div>
    <p class="muted">
        Active now: <strong><?php echo htmlspecialchars($active['name'] ?? $active['slug'] ?? 'default'); ?></strong>
        (<?php echo htmlspecialchars($active['mode'] ?? 'fresh'); ?>)
    </p>

    <?php if ($flash): ?>
        <div style="padding:8px;background:#fffbdd;border:1px solid #ffe58f;margin-bottom:12px">
            <?php echo htmlspecialchars($flash); ?>
        </div>
    <?php endif; ?>

    <div class="section">
        <h3>Fresh default (core)</h3>
        <form method="post">
            <?php echo multicms_csrf_field(); ?>
            <input type="hidden" name="action" value="apply_fresh">
            <button type="submit">Use fresh default theme as main site</button>
        </form>
    </div>

    <div class="section">
        <h3>Site plugins (legacy packs)</h3>
        <div class="site-grid">
            <?php if (empty($siteModules)): ?>
                <div>No site plugins found under plugins/*/www/</div>
            <?php endif; ?>

            <?php foreach ($siteModules as $site): ?>
                <?php $isActive = (($active['slug'] ?? '') === $site['slug']); ?>
                <div class="site-card<?php echo $isActive ? ' active' : ''; ?>">
                    <h3><?php echo htmlspecialchars($site['name']); ?></h3>
                    <?php if ($isActive): ?><span class="badge">Active main site</span><?php endif; ?>
                    <div class="muted"><?php echo htmlspecialchars($site['description']); ?></div>
                    <div class="actions">
                        <a href="<?php echo '../' . htmlspecialchars($site['url']); ?>" target="_blank">Open</a>
                        <form method="post" style="display:inline">
                            <?php echo multicms_csrf_field(); ?>
                            <input type="hidden" name="action" value="apply_site">
                            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($site['slug']); ?>">
                            <button type="submit" <?php echo $isActive ? 'disabled' : ''; ?>>
                                <?php echo $isActive ? 'Already main site' : 'Apply as main site'; ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <h3>Theme packages (Digitalcloud prebuilt)</h3>
        <div class="site-grid">
            <?php if (empty($themePackages)): ?>
                <div>No theme packages in plugins/prebuilt-sites/sites/</div>
            <?php endif; ?>

            <?php foreach ($themePackages as $site): ?>
                <div class="site-card">
                    <h3><?php echo htmlspecialchars($site['name']); ?></h3>
                    <div class="muted"><?php echo htmlspecialchars($site['description'] ?? ''); ?></div>
                    <div class="actions">
                        <?php if (!empty($site['preview']) && file_exists($site['path'].'/'.$site['preview'])): ?>
                            <a href="<?php echo '../plugins/prebuilt-sites/sites/'.$site['slug'].'/'.$site['preview']; ?>" target="_blank">Preview</a>
                        <?php endif; ?>
                        <form method="post" style="display:inline">
                            <?php echo multicms_csrf_field(); ?>
                            <input type="hidden" name="action" value="apply_theme">
                            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($site['slug']); ?>">
                            <button type="submit">Apply theme only</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php if (file_exists('footer.php')) { include('footer.php'); } ?>
</body>
</html>
