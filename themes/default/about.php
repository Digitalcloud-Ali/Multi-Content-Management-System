<?php
$pageTitle = 'About';
?>
<h1 class="mb-3">About</h1>
<p class="lead"><?php echo htmlspecialchars(getSetting('site_description', 'MultiCMS is a modern content management system.'), ENT_QUOTES, 'UTF-8'); ?></p>
<p>You are running the <strong>fresh default core</strong>. Ready-made site packs (blog marketplace, doctors, and others) are optional plugins you can apply later from the administrator panel.</p>
