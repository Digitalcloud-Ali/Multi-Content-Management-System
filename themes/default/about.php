<?php
$pageTitle = 'About';
$cmsPage = null;
if (isset($contentService) && method_exists($contentService, 'getPageBySlug')) {
    $cmsPage = $contentService->getPageBySlug('about');
}
?>
<?php if ($cmsPage): ?>
    <h1 class="mb-3"><?php echo htmlspecialchars($cmsPage['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <div class="page-body"><?php echo $cmsPage['content']; ?></div>
<?php else: ?>
    <h1 class="mb-3">About</h1>
    <p class="lead"><?php echo htmlspecialchars(getSetting('site_description', 'MultiCMS is a modern content management system.'), ENT_QUOTES, 'UTF-8'); ?></p>
    <p>You are running the MultiCMS core. Edit this page by applying the Blog Starter flagship site, or replace this template later.</p>
<?php endif; ?>
