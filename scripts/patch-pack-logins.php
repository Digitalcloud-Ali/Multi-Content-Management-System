<?php
$root = dirname(__DIR__) . '/plugins';
$block = <<<'PHP'
  // MultiCMS Phase1: hashed/legacy password verify (no plaintext SQL compare)
  $stmt = mysqli_prepare(dbconnect(), "SELECT users, passs, level FROM members WHERE users = ? LIMIT 1");
  $loginFoundUser = 0;
  $loginStrGroup = 'member';
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $loginUsername);
    mysqli_stmt_execute($stmt);
    $LoginRS = mysqli_stmt_get_result($stmt);
    $rowLogin = $LoginRS ? mysqli_fetch_assoc($LoginRS) : null;
    mysqli_stmt_close($stmt);
    if ($rowLogin) {
      list($pwOk, $rehash) = multicms_verify_password_flexible($password, $rowLogin['passs']);
      if ($pwOk) {
        $loginFoundUser = 1;
        $loginStrGroup = !empty($rowLogin['level']) ? $rowLogin['level'] : 'member';
        if ($rehash) {
          $newHash = password_hash($password, PASSWORD_DEFAULT);
          $upd = mysqli_prepare(dbconnect(), "UPDATE members SET passs = ? WHERE users = ?");
          if ($upd) {
            mysqli_stmt_bind_param($upd, 'ss', $newHash, $loginUsername);
            mysqli_stmt_execute($upd);
            mysqli_stmt_close($upd);
          }
        }
      }
    }
  }
  if ($loginFoundUser) {
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
PHP;

$patched = 0;
foreach (glob($root . '/*/www/login.php') as $path) {
    $c = file_get_contents($path);
    $start = strpos($c, '$LoginRS__query=sprintf(');
    if ($start === false) {
        echo "SKIP no query $path\n";
        continue;
    }
    $endMarker = "\$_SESSION['MM_UserGroup'] = \$loginStrGroup;";
    // find the assignment after start
    $end = strpos($c, "\$_SESSION['MM_UserGroup'] = \$loginStrGroup;", $start);
    if ($end === false) {
        echo "SKIP no session group $path\n";
        continue;
    }
    $end += strlen("\$_SESSION['MM_UserGroup'] = \$loginStrGroup;");
    // keep trailing whitespace/tabs after assignment on same line if any
    while ($end < strlen($c) && ($c[$end] === ' ' || $c[$end] === "\t")) {
        $end++;
    }
    $n = substr($c, 0, $start) . $block . substr($c, $end);
    file_put_contents($path, $n);
    $patched++;
    echo "OK $path\n";
}
echo "Patched $patched\n";
