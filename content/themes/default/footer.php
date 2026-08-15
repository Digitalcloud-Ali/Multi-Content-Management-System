<?php
/**
 * Modern theme footer.
 */
if (!function_exists('getSetting')) {
    function getSetting($key, $default = null) { return $default; }
}
?>
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
                        &copy; <?php echo date('Y'); ?>
                        <?php echo htmlspecialchars(getSetting('site_name', getSetting('site_title', 'MultiCMS')), ENT_QUOTES, 'UTF-8'); ?>
                        · <a class="link-light" href="https://digitalcloud.no" target="_blank" rel="noopener">DigitalCloud.no</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
