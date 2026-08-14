<?php
$pageTitle = htmlspecialchars((string) ($cmsPage['title'] ?? 'Page'), ENT_QUOTES, 'UTF-8');
?>
<h1 class="mb-3"><?php echo $pageTitle; ?></h1>
<div class="page-body"><?php echo $cmsPage['content'] ?? ''; ?></div>
