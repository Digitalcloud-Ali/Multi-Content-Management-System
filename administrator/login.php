<?php
require_once('../includes/rayicecms.php');

mysqli_select_db(dbconnect(), $database_rayicecms);
$query_setting = "SELECT * FROM settings WHERE settingid = 1";
$setting = mysqli_query(dbconnect(), $query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);

if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

$loginError = '';

if (isset($_POST['datauser'])) {
  if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
    $loginError = 'Invalid security token. Please try again.';
  } else {
    $loginUsername = trim((string) $_POST['datauser']);
    $password = (string) $_POST['datapass'];
    $MM_redirectLoginSuccess = "dashboard.php";
    $MM_redirectLoginFailed = "login.php?status=fail";

    $user = null;

    // Prefer modern users table (Phase 1 core)
    $stmt = mysqli_prepare(dbconnect(), "SELECT userid, username, password, role, status FROM users WHERE username = ? LIMIT 1");
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, 's', $loginUsername);
      mysqli_stmt_execute($stmt);
      $res = mysqli_stmt_get_result($stmt);
      $user = $res ? mysqli_fetch_assoc($res) : null;
      mysqli_stmt_close($stmt);
    }

    $ok = false;
    $group = '';

    if ($user && ($user['status'] ?? '') === 'active') {
      list($ok, $rehash) = multicms_verify_password_flexible($password, $user['password']);
      if ($ok) {
        $group = $user['role'] ?: 'admin';
        if ($rehash) {
          $newHash = password_hash($password, PASSWORD_DEFAULT);
          $upd = mysqli_prepare(dbconnect(), "UPDATE users SET password = ? WHERE userid = ?");
          if ($upd) {
            mysqli_stmt_bind_param($upd, 'si', $newHash, $user['userid']);
            mysqli_stmt_execute($upd);
            mysqli_stmt_close($upd);
          }
        }
      }
    } else {
      // Fallback: legacy members table (ready-made / old dumps)
      $stmt = mysqli_prepare(dbconnect(), "SELECT memberid, users, passs, level FROM members WHERE users = ? LIMIT 1");
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $loginUsername);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $legacy = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        if ($legacy) {
          list($ok, $rehash) = multicms_verify_password_flexible($password, $legacy['passs']);
          if ($ok) {
            $group = $legacy['level'] ?: 'administrator';
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
    }

    if ($ok) {
      session_regenerate_id(true);
      $_SESSION['MM_Username'] = $loginUsername;
      $_SESSION['MM_UserGroup'] = $group;
      header('Location: ' . $MM_redirectLoginSuccess);
      exit;
    }
    header('Location: ' . $MM_redirectLoginFailed);
    exit;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo htmlspecialchars($row_setting['title'] ?? $row_setting['site_title'] ?? 'MultiCMS'); ?> - Login Box</title>
<link href="rayicecms.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="bgcontent">
<div class="bginner">
<table width="100%" height="34" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" bgcolor="#000000" class="texts4">Administrator Section</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="300" border="0" align="center" cellpadding="12" cellspacing="0">
  <tr>
    <td bgcolor="#db3300"><table width="88" height="88" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="91" height="88" align="center" class="topbigbuttons"><a href="index.php" class="headbuttons">
          <div title="Back to Home!"><img src="../content/assets/logo-normal.png" alt="Back to Home" width="122" height="111" border="0" /></div>
        </a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><form id="form1" name="form1" method="POST" action="<?php echo htmlspecialchars($loginFormAction); ?>">
      <?php echo multicms_csrf_field(); ?>
      <table width="100%" border="0" cellspacing="4" cellpadding="4">
        <tr>
          <td width="1" class="texts"><strong>USERNAME:</strong></td>
          <td><input name="datauser" type="text" class="form" id="textfield" placeholder="Username" required /></td>
        </tr>
        <tr>
          <td class="texts"><strong>PASSWORD:</strong></td>
          <td><input name="datapass" type="password" class="form" id="textfield2" placeholder="Password" required /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="button" type="submit" class="button" id="button" value="Login" /></td>
        </tr>
      </table>
      <?php if (!empty($_GET['status']) && $_GET['status'] === 'fail'): ?>
      <div style="color:#990000; text-align:center; background:#D5E8FF;">Login Failed</div>
      <?php endif; ?>
      <?php if ($loginError): ?>
      <div style="color:#990000; text-align:center; background:#D5E8FF;"><?php echo htmlspecialchars($loginError); ?></div>
      <?php endif; ?>
    </form></td>
  </tr>
</table>
</div>
</div>
</body>
</html>
<?php
mysqli_free_result($setting);
