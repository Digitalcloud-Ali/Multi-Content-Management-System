<?php require_once('../includes/rayicecms.php'); ?>
<?php

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings WHERE settingid = 1";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}


if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(dbconnect(), $theValue) : mysqli_escape_string(dbconnect(), $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (isset($_POST['datauser'])) {
  $loginUsername=$_POST['datauser'];
  $password=$_POST['datapass'];
  $MM_fldUserAuthorization = "level";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php?status=fail";
  $MM_redirecttoReferrer = false;
  mysqli_select_db(dbconnect(),$database_rayicecms);
  	
  $LoginRS__query=sprintf("SELECT users, passs, level FROM members WHERE users=%s AND passs=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysqli_query(dbconnect(),$LoginRS__query) or die(mysqli_connect_error());
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title><?php echo $row_setting['title']; ?> - Login Box</title>
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
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="300" border="0" align="center" cellpadding="12" cellspacing="0">
  <tr>
    <td bgcolor="#db3300"><table width="88" height="88" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="91" height="88" align="center" class="topbigbuttons"><a href="index.php" class="headbuttons">
          <div title="Back to Home!"><img src="../images/logo-normal.png" alt="Back to Home" width="122" height="111" border="0" /></div>
        </a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
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
      </table><?php if($_GET['status'] == 'fail')
	  {
	  ?>
      <div style="color:#990000; text-align:center; background:#D5E8FF;">Login Failed</div>
      <?php
	  }
	  ?>
    </form></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><br />
</p>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="textsmall"><?php echo $row_setting['footer']; ?></td>
  </tr>
</table>
</div>
</div>
</body>
</html>
<?php

mysqli_free_result($setting);

?>