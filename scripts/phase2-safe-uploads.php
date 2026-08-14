<?php
$root = dirname(__DIR__) . '/plugins';
$replacement = <<<'PHP'
// UPLOAD CODE — MultiCMS Phase2 safe upload
  	$upload_path = (defined('MULTICMS_ROOT') ? MULTICMS_ROOT : dirname(__DIR__, 3)) . '/images/members/';
	if($_FILES['photo']['size'] == 0 || empty($_FILES['photo']['name']))
		{
   		   $filename = $row_rsM['photo'];
		}
	else
		{
           $up = multicms_safe_upload('photo', $upload_path);
           if (!$up['success']) { die(htmlspecialchars($up['message'] ?? 'Upload failed')); }
           $filename = $up['filename'];
           echo 'Your file upload was successful';
		}
// UPLOAD CODE END
PHP;

$n = 0;
foreach (glob($root . '/*/www/setting.php') as $path) {
    $c = file_get_contents($path);
    $start = strpos($c, '// UPLOAD CODE BY SYED RAZA ALI START');
    $end = strpos($c, '// UPLOAD CODE BY SYED RAZA ALI END');
    if ($start === false || $end === false) {
        echo "SKIP $path\n";
        continue;
    }
    $endLine = strpos($c, "\n", $end);
    if ($endLine === false) {
        $endLine = strlen($c);
    }
    $c = substr($c, 0, $start) . $replacement . substr($c, $endLine);
    file_put_contents($path, $c);
    $n++;
    echo "OK $path\n";
}
echo "Updated $n setting.php files\n";
