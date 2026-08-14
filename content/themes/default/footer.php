<?php
/**
 * Theme footer — dual mode (pack vs modern core).
 */
$packMode = !empty($row_setting) && is_array($row_setting) && isset($theme_path);
if ($packMode) {
    $siteTitle = htmlspecialchars((string) ($row_setting['title'] ?? 'MultiCMS'), ENT_QUOTES, 'UTF-8');
    $footerHtml = (string) ($row_setting['footer'] ?? '');
    ?>
<div class="pack-footer" style="clear:both;padding:16px;margin-top:24px;border-top:1px solid #ccc;color:#666;font-size:12px;">
  <?php if ($footerHtml !== ''): ?>
    <?php echo $footerHtml; ?>
  <?php else: ?>
    &copy; <?php echo date('Y'); ?> <?php echo $siteTitle; ?>
  <?php endif; ?>
</div>
</body>
</html>
    <?php
    return;
}

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null) { return $default; }
}
?>
        </div>
    </main>
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        <?php echo htmlspecialchars(getSetting('site_description', 'A modern content management system.'), ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-muted">
                        &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(getSetting('site_name', getSetting('site_title', 'MultiCMS')), ENT_QUOTES, 'UTF-8'); ?>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
