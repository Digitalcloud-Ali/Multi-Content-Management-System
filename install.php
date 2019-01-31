<?php require_once('includes/rayicecms.php'); ?>
<?php
error_reporting(0);
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "database")) {
  $updateSQL = sprintf("UPDATE settings SET `host`=%s, username=%s, password=%s, `database`=%s WHERE settingid=%s",
                       GetSQLValueString($_POST['host'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['database'], "text"),
                       GetSQLValueString($_POST['settingid'], "int"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$updateSQL) or die(mysqli_connect_error());

  $updateGoTo = "install.php?status=selecttopic";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "" : "?";
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "selecttopic")) {
  $updateSQL = sprintf("UPDATE settings SET selecttopic=%s, installed=%s WHERE settingid=%s",
                       GetSQLValueString($_POST['selecttopic'], "text"),
                       GetSQLValueString($_POST['installed'], "text"),
                       GetSQLValueString($_POST['settingid'], "int"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$updateSQL) or die(mysqli_connect_error());

  $updateGoTo = "install.php?status=success";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "" : "?";
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="administrator/rayicecms.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/includes/validate.js"></script>
</head>

<body>
<div align="center">
  <table width="100%" height="34" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#000000">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p><table width="500" border="0" align="center" cellpadding="12" cellspacing="0">
    <tr>
      <td bgcolor="#db3300"><table width="88" height="88" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="91" height="88" align="center" class="topbigbuttons"><div title="Back to Home!"><a href="install.php"><img src="/images/logo-normal.png" alt="" width="88" height="79" border="0" /></a></div>          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><?php
	  if($row_setting['installed'] == "yes")
	  {
?>
        <table width="100%" height="51" border="0" cellpadding="0" cellspacing="0" class="topbigbuttons1">
          <tr>
            <td width="193" height="51"><a href="/administrator/">
              <div align="center">
              SITE ALREADY INSTALLED GO TO ADMIN AREA TO CHANGE SITE TOPIC</div>
            </a></td>
          </tr>
        </table>
        <?php
	  }
	  else
	  {
	  ?>
        <?php
		if($_GET['status'] == '')
		{
			?>
        <table width="100%" height="124" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="193" height="124"><p align="center">Welcome To </p>
              <p align="center">Multi Content Management System </p>
              <p align="center">Installation Process</p></td>
          </tr>
        </table>
        <table width="100%" height="51" border="0" cellpadding="0" cellspacing="0" class="topbigbuttons1">
          <tr>
            <td width="193" height="51"><a href="install.php?status=selecttopic">
            <div align="center">CLICK HERE TO CONTINUE</div></a></td>
          </tr>
        </table>
        <?php
	  }
	  ?>
        <?php
		if($_GET['status'] == 'database')
		{
			?>
            
		<table width="100%" border="0" cellspacing="4" cellpadding="4"><form action="<?php echo $editFormAction; ?>" name="database" method="POST" id="database">
          <tr>
            <td width="84" class="texts"><strong>HOSTNAME:</strong></td>
            
            <td><input name="host" type="text" class="form" id="host" /></td>
          </tr>
          <tr>
            <td class="texts"><strong>USERNAME:</strong></td>
            <td><input name="username" type="text" class="form" id="username" /></td>
          </tr>
          <tr>
            <td class="texts"><strong>PASSWORD:</strong></td>
            <td><input name="password" type="text" class="form" id="password" /></td>
          </tr>
          <tr>
            <td class="texts"><strong>DATABASE:</strong></td>
            <td><input name="database" type="text" class="form" id="database" /></td>
          </tr>
          <tr>
            <td><input name="settingid" type="hidden" id="settingid" value="1" /></td>
            <td><input name="button" type="submit" class="button" id="button" value="Continue" /></td>
          </tr>
          <input type="hidden" name="MM_insert" value="database" />
          <input type="hidden" name="MM_update" value="database" />
        </form>
        <script language="JavaScript" type="text/javascript">

  var frmvalidator  = new Validator("database");
  frmvalidator.addValidation("host","req","Please Enter Hostname");
  frmvalidator.addValidation("host","maxlen=50","Max length for Hostname is 50");

  frmvalidator.addValidation("username","req","Please Enter Username");
  frmvalidator.addValidation("username","maxlen=50","Max length for Username is 50");
 
  
  frmvalidator.addValidation("database","req","Please Enter Database");
  frmvalidator.addValidation("database","maxlen=50","Max length for Database is 50");

</script>
    </table>
		<?php
        }
        ?>
		<?php
		if($_GET['status'] == 'selecttopic')
		{
			?>
      <table width="100%" border="0" cellspacing="4" cellpadding="4">
          <form name="selecttopic" action="<?php echo $editFormAction; ?>" method="POST" id="selecttopic">
            <tr>
              <td width="84" class="texts"><strong>SELECT TOPIC: </strong></td>
              <td><select name="selecttopic" class="formmenu" id="selecttopic">
                <option value="blog">Portal / Blog</option>
                <option value="custom">Custom</option>
                <option value="videostream">Video Streaming</option>
                <option value="imagegallery">Image Gallery</option>
                <option value="doctors">Doctors / Clinic</option>
                <option value="marketplace">MarketPlace</option>
                <option value="portfolio">Portfolio / Resume</option>
                <option value="searchengine">Data Search / Search Engine</option>
                <option value="adposting">Ad Posting</option>
                <option value="tutorials">Tutorials</option>
                <option value="productpublisher">Product Publisher / Affiliate Shopping Store</option>
              </select></td>
            </tr>
            <tr>
              <td><input name="settingid" type="hidden" id="settingid" value="1" />
              <input name="installed" type="hidden" id="installed" value="yes" /></td>
              <td><input name="button2" type="submit" class="button" id="button2" value="Select" /></td>
            </tr>
            <input type="hidden" name="MM_insert2" value="database" />
            <input type="hidden" name="MM_update" value="selecttopic" />
          </form>
          
        </table>
      <?php
        }
        ?>
      <?php
        }
        ?>
      <?php
		if($_GET['status'] == 'success')
		{
		?>
        <table width="100%" height="124" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="193" height="124"><p align="center">SYSTEM IS INSTALLED</p>
            <p align="center">ENJOY MULTI FLAVOURS CONTENT MANAGEMENT SYSTEM</p>
            <p align="center"><strong>Username: </strong>admin / <strong>Password: </strong>123456<br />
            </p></td>
          </tr>
        </table>
        <table width="100%" height="51" border="0" cellpadding="0" cellspacing="0" class="topbigbuttons1">
          <tr>
            <td width="193" height="51"><div align="center"><a href="/administrator/">GO TO ADMIN</a><span class="title"> | </span><a href="/">GO TO INDEX</a></div></td>
          </tr>
        </table>
        <?php
	  }
	  ?></td>
    </tr>
  </table>
  <br />
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="textsmall"><?php echo $row_setting['footer']; ?></td>
    </tr>
  </table>
</div>
</body>
</html>
<?php
mysqli_free_result($setting);
?>