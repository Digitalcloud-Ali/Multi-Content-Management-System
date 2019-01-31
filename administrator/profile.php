<?php require_once('../includes/rayicecms.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "administrator,editor";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
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

$currentPage = $_SERVER["PHP_SELF"];

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

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_rsT = "SELECT * FROM members ORDER BY memberid DESC";
$rsT = mysqli_query(dbconnect(),$query_rsT) or die(mysqli_connect_error());
$row_rsT = mysqli_fetch_assoc($rsT);
$totalRows_rsT = mysqli_num_rows($rsT);

$colname_rsM = "-1";
if (isset($_GET['memberid'])) {
  $colname_rsM = $_GET['memberid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_rsM = sprintf("SELECT * FROM members WHERE memberid = %s", GetSQLValueString($colname_rsM, "int"));
$rsM = mysqli_query(dbconnect(),$query_rsM) or die(mysqli_connect_error());
$row_rsM = mysqli_fetch_assoc($rsM);
$totalRows_rsM = mysqli_num_rows($rsM);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "memberinsert")) {
	
// UPLOAD CODE BY SYED RAZA ALI START //
   	$allowed_filetypes = array('.jpg','.gif','.bmp','.png');
  	$no_ID = $row_rsT['memberid'];
	$max_filesize = 5250000;
  	$upload_path = '../images/members/';
	if($_FILES['photo']['size'] == 0 || empty($_FILES['photo']['name']))
		{
   		   $filename = $row_setting['missingimage'];
		}
	else
		{
   		   $filename = $no_ID."".str_replace(' ', '_', $_FILES['photo']['name']);
		   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
 	 	   if(!in_array($ext,$allowed_filetypes))
   		   {die('The file you attempted to upload is not allowed.');} 
   		   if(filesize($_FILES['photo']['tmp_name']) > $max_filesize)
           {die('The file you attempted to upload is too large.');}
           if(!is_writable($upload_path))
           {die('You cannot upload to the specified directory, please CHMOD it to 777.');}   
           if(move_uploaded_file($_FILES['photo']['tmp_name'],$upload_path . $filename))
           {echo 'Your file upload was successful';} 
           else{echo'There was an error during the file upload.  Please try again.';} 
		}
// UPLOAD CODE BY SYED RAZA ALI END //

  $insertSQL = sprintf("INSERT INTO members (users, passs, level, fullname, address, email, photo, zip, city, `state`, country, phone, yahooid, twitter, facebook, status, `position`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['users'], "text"),
                       GetSQLValueString($_POST['passs'], "text"),
					   GetSQLValueString($_POST['level'], "text"),
                       GetSQLValueString($_POST['fullname'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($filename, "text"),
                       GetSQLValueString($_POST['zip'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['yahooid'], "text"),
                       GetSQLValueString($_POST['twitter'], "text"),
                       GetSQLValueString($_POST['facebook'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['position'], "text"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$insertSQL) or die(mysqli_connect_error());

  $insertGoTo = "members.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "memberupdate")) {
  $updateSQL = sprintf("UPDATE members SET users=%s, passs=%s, `level`=%s, fullname=%s, address=%s, email=%s, zip=%s, city=%s, `state`=%s, country=%s, phone=%s, yahooid=%s, twitter=%s, facebook=%s, status=%s, `position`=%s WHERE memberid=%s",
                       GetSQLValueString($_POST['users'], "text"),
                       GetSQLValueString($_POST['passs'], "text"),
                       GetSQLValueString($_POST['level'], "text"),
                       GetSQLValueString($_POST['fullname'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['zip'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['yahooid'], "text"),
                       GetSQLValueString($_POST['twitter'], "text"),
                       GetSQLValueString($_POST['facebook'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString($_POST['memberid'], "int"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$updateSQL) or die(mysqli_connect_error());

  $updateGoTo = "profile.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "graphical")) {

// UPLOAD CODE BY SYED RAZA ALI START //
   	$allowed_filetypes = array('.jpg','.gif','.bmp','.png');
  	$no_ID = $row_rsM['memberid'];
	$max_filesize = 5250000;
  	$upload_path = '../images/members/';
	if($_FILES['photo']['size'] == 0 || empty($_FILES['photo']['name']))
		{
   		   $filename = $row_rsM['photo'];
		}
	else
		{
   		   $filename = $no_ID."".str_replace(' ', '_', $_FILES['photo']['name']);
		   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
 	 	   if(!in_array($ext,$allowed_filetypes))
   		   {die('The file you attempted to upload is not allowed.');} 
   		   if(filesize($_FILES['photo']['tmp_name']) > $max_filesize)
           {die('The file you attempted to upload is too large.');}
           if(!is_writable($upload_path))
           {die('You cannot upload to the specified directory, please CHMOD it to 777.');}   
           if(move_uploaded_file($_FILES['photo']['tmp_name'],$upload_path . $filename))
           {echo 'Your file upload was successful';} 
           else{echo'There was an error during the file upload.  Please try again.';} 
		}
// UPLOAD CODE BY SYED RAZA ALI END //

  $updateSQL = sprintf("UPDATE members SET photo=%s WHERE memberid=%s",
                       GetSQLValueString($filename, "text"),
                       GetSQLValueString($_POST['memberid'], "int"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$updateSQL) or die(mysqli_connect_error());

  $updateGoTo = "members.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_GET['memberiddelete'])) && ($_GET['memberiddelete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM members WHERE memberid=%s",
                       GetSQLValueString($_GET['memberiddelete'], "int"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$deleteSQL) or die(mysqli_connect_error());

  $deleteGoTo = "members.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_membersoverview = 20;
$pageNum_membersoverview = 0;
if (isset($_GET['pageNum_membersoverview'])) {
  $pageNum_membersoverview = $_GET['pageNum_membersoverview'];
}
$startRow_membersoverview = $pageNum_membersoverview * $maxRows_membersoverview;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_membersoverview = "SELECT * FROM members ORDER BY memberid DESC";
$query_limit_membersoverview = sprintf("%s LIMIT %d, %d", $query_membersoverview, $startRow_membersoverview, $maxRows_membersoverview);
$membersoverview = mysqli_query(dbconnect(),$query_limit_membersoverview) or die(mysqli_connect_error());
$row_membersoverview = mysqli_fetch_assoc($membersoverview);

if (isset($_GET['totalRows_membersoverview'])) {
  $totalRows_membersoverview = $_GET['totalRows_membersoverview'];
} else {
  $all_membersoverview = mysqli_query(dbconnect(),$query_membersoverview);
  $totalRows_membersoverview = mysqli_num_rows($all_membersoverview);
}
$totalPages_membersoverview = ceil($totalRows_membersoverview/$maxRows_membersoverview)-1;

$colname_membersupdate = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_membersupdate = $_SESSION['MM_Username'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_membersupdate = sprintf("SELECT * FROM members WHERE users = %s", GetSQLValueString($colname_membersupdate, "text"));
$membersupdate = mysqli_query(dbconnect(),$query_membersupdate) or die(mysqli_connect_error());
$row_membersupdate = mysqli_fetch_assoc($membersupdate);
$totalRows_membersupdate = mysqli_num_rows($membersupdate);

// this is for showing total comments record
$_SERVER['selecttopic'] = $row_setting['selecttopic'];
// this is end of showing comments

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_contentparts = "SELECT * FROM parts WHERE partsid = 4";
$contentparts = mysqli_query(dbconnect(),$query_contentparts) or die(mysqli_connect_error());
$row_contentparts = mysqli_fetch_assoc($contentparts);
$totalRows_contentparts = mysqli_num_rows($contentparts);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_contentnews = "SELECT * FROM parts WHERE partsid = 1";
$contentnews = mysqli_query(dbconnect(),$query_contentnews) or die(mysqli_connect_error());
$row_contentnews = mysqli_fetch_assoc($contentnews);
$totalRows_contentnews = mysqli_num_rows($contentnews);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_contentlinks = "SELECT * FROM parts WHERE partsid = 5";
$contentlinks = mysqli_query(dbconnect(),$query_contentlinks) or die(mysqli_connect_error());
$row_contentlinks = mysqli_fetch_assoc($contentlinks);
$totalRows_contentlinks = mysqli_num_rows($contentlinks);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_contentcontact = "SELECT * FROM parts WHERE partsid = 2";
$contentcontact = mysqli_query(dbconnect(),$query_contentcontact) or die(mysqli_connect_error());
$row_contentcontact = mysqli_fetch_assoc($contentcontact);
$totalRows_contentcontact = mysqli_num_rows($contentcontact);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_contentads = "SELECT * FROM parts WHERE partsid = 3";
$contentads = mysqli_query(dbconnect(),$query_contentads) or die(mysqli_connect_error());
$row_contentads = mysqli_fetch_assoc($contentads);
$totalRows_contentads = mysqli_num_rows($contentads);

$queryString_membersoverview = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_membersoverview") == false && 
        stristr($param, "totalRows_membersoverview") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_membersoverview = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_membersoverview = sprintf("&totalRows_membersoverview=%d%s", $totalRows_membersoverview, $queryString_membersoverview);


$colname_members = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_members = $_SESSION['MM_Username'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_members = sprintf("SELECT * FROM members WHERE users = %s", GetSQLValueString($colname_members, "text"));
$members = mysqli_query(dbconnect(),$query_members) or die(mysqli_connect_error());
$row_members = mysqli_fetch_assoc($members);
$totalRows_members = mysqli_num_rows($members);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title><?php echo $row_setting['title']; ?>- Profile</title>
<link href="rayicecms.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css3/styles.css" />
<link rel="shortcut icon" type="image/png" href="/images/<?php echo $row_setting['favicon']; ?>" />
<script language=javascript>
if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)))
{
location.replace("/mobadmin/");
}
</script><script type="text/javascript" src="/includes/validate.js"></script>
</head>

<body>
<div id="main">

<ul id="navigationMenu">
    <li>
	    <a class="home" href="index.php">
            <span>Home</span>
        </a>
    </li>
    
    <li>
    	<a class="profile" href="profile.php">
            <span>Profile</span>
        </a>
    </li>
    
    <li>
	     <a class="config" href="setting.php">
            <span>Config</span>
         </a>
    </li>
    
    <li>
    	<a class="multicms" href="topic.php">
            <span>MultiCMS</span>
        </a>
    </li>
    
    <li>
    	<a class="messages" href="contact.php">
            <span>Messages</span>
        </a>
    </li>
</ul>
    
</div>
<div class="bgcontent">
<div class="bginner">
<table width="100%" height="34" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#000000"><table width="940" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="1"><table border="0" cellspacing="0" cellpadding="4">
          <tr>
              <td><img src="images/userPic.png" width="22" alt="User Picture" height="20" /></td>
              <td class="textswhite1">Welcome <?php echo $row_members['users']; ?></td>
            </tr>
        </table></td>
        <td><table border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td><div class="textswhite1"><a href="index.php"><img src="images/topnav/contactAdmin.png" width="11" height="10" border="0" alt="Home" style="padding-right:9px;" />Home</a></div></td>
            <td><div class="textswhite1"><a href="profile.php"><img src="images/topnav/profile.png" width="11" height="11" border="0" alt="profile" style="padding-right:9px;" />Profile</a></div></td>
            
            <td><div class="textswhite1"><a href="setting.php"><img src="images/topnav/settings.png" width="10" alt="setting" height="11" border="0" style="padding-right:9px;" />Configuration</a></div></td>
            <td><div class="textswhite1"><a href="<?php echo $logoutAction ?>"><img src="images/topnav/logout.png" alt="logout" width="11" height="11" border="0" style="padding-right:9px;" />Logout</a></div></td>
          </tr>
        </table></td>
      </tr>
  </table></td>
  </tr>
</table>
<table border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
    <td width="704">
      
      <table width="200" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="8"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td height="81"><table width="140" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center">              <div title="Back to Home!"><a href="index.php" class="headbuttons"><img src="../images/logo-normal.png" alt="Back to Home" width="88" height="79" border="0" /></a></div></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="categories.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Categories"><img src="images/companies.png" alt="Categories" width="23" height="23" border="0" /><br />
                Categories</div>
            </a></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="comments.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Comments"><img src="images/bubbles2.png" width="23" height="23" alt="Comments" border="0" /><br />
                Comments</div>
            </a></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="themes.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Themes"><img src="images/theme.png" alt="Themes" width="23" height="23" border="0" /><br />
                Themes</div>
            </a></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="components.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Components"><img src="images/cog4.png" alt="Components" width="23" height="23" border="0" />Components</div>
            </a></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="modules.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Modules"><img src="images/outgoing.png" alt="Modules" width="23" height="23" border="0" /><br />
                Modules</div>
            </a></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="statistics.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Statistics"><img src="images/graph.png" alt="Statistics" width="23" height="23" border="0" /><br />
                Statistics</div>
            </a></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="members.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Members"><img src="images/users.png" alt="Members" width="36" height="23" border="0" />Members</div>
            </a></td>
          </tr>
        </table></td>
        <td align="right"><table width="88" height="88" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="91" height="88" align="center" class="topbigbuttons"><a href="topic.php" class="headbuttons">
              <div style="height:64px; padding-top:23px;" title="Change Topic"><img src="images/archive.png" alt="Change Topic" width="23" height="23" border="0" /><br />
                Multi CMS</div>
              </a></td>
            </tr>
        </table></td>
        </tr>
  </table></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="35"><table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td width="224" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="8" class="bgtitles">
                <tr>
                  <td class="admintitlewhite">CURRENT TOPIC</td>
                </tr>
              </table>
              <table width="40" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="4"></td>
                </tr>
              </table>
              <?php if($row_setting['selecttopic'] == 'blog')
			  {
			  ?>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                <tr>
                  <td height="35"><a href="blog.php">
                    <div style="line-height:37px;"><img src="images/addressBook.png" alt="Portal Blog" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Portal / Blog
                    </div></a></td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
              <?php
			  }
			  ?>
<?php if($row_setting['selecttopic'] == 'custom')
			  {
			  ?>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                <tr>
                  <td height="35"><a href="custom.php">
                    <div style="line-height:37px;"><img src="images/addressBook.png" alt="Portal Blog" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Custom
                    </div></a></td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
              <?php
			  }
			  ?>
              
              <?php if($row_setting['selecttopic'] == 'searchengine')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="searchengine.php">
                      <div style="line-height:37px;"><img src="images/blocks.png" alt="Search Engine" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Search Engine
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
              <?php if($row_setting['selecttopic'] == 'portfolio')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="portfolio.php">
                      <div style="line-height:37px;"><img src="images/user2.png" alt="Portfolio or Resume" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Portfolio / Resume
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
              <?php if($row_setting['selecttopic'] == 'adposting')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="adposting.php">
                      <div style="line-height:37px;"><img src="images/bandaid.png" alt="Ad Posting" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Ad Posting
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
              <?php if($row_setting['selecttopic'] == 'videostream')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="videostreaming.php">
                      <div style="line-height:37px;"><img src="images/coverflow.png" alt="Video Streaming" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Video Streaming
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?> <?php if($row_setting['selecttopic'] == 'doctors')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="doctors.php">
                      <div style="line-height:37px;"><img src="images/coverflow.png" alt="Dcotirs or Clinic" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Doctors / Clinic
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
              <?php if($row_setting['selecttopic'] == 'imagegallery')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="imagegallery.php">
                      <div style="line-height:37px;"><img src="images/image.png" alt="Image Gallery" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Image Gallery
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
              <?php if($row_setting['selecttopic'] == 'marketplace')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="marketplace.php">
                      <div style="line-height:37px;"><img src="images/mightyMouse.png" alt="Market Place" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Market Place
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
              <?php if($row_setting['selecttopic'] == 'tutorials')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="tutorials.php">
                      <div style="line-height:37px;"><img src="images/paintBrush.png" alt="Tutorials" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" /> Tutorials
                      </div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
              <?php if($row_setting['selecttopic'] == 'productpublisher')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="affiliateshop.php">
                      <div style="line-height:37px;"><img src="images/shoppingBag.png" alt="Product Publisher" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" />Product Publisher</div>
                      </a></td>
                    </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                    </tr>
                </table>
                <?php
			  }
			  ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="8" class="bgtitles">
                  <tr>
                    <td class="admintitlewhite">CONTENT</td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="media.php">
                      <div style="line-height:37px;"><img src="images/images.png" alt="Media" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" />Media</div>
                    </a></td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <?php
			if($row_contentparts['status'] == 'active')
				  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="pages.php">
                      <div style="line-height:37px;"><img src="images/create.png" alt="Pages" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" />Pages</div>
                    </a></td>
                  </tr>
            </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
              <?php
			  }
			  ?>
               <?php 
			if($row_contentnews['status'] == 'active')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="news.php">
                      <div style="line-height:37px;"><img src="images/buzz.png" alt="News" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" />News</div>
                    </a></td>
                  </tr>
              </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <?php
			  }
			  ?>
                <?php
			if($row_contentcontact['status'] == 'active')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="contact.php">
                      <div style="line-height:37px;"><img src="images/userComment.png" alt="Contact Management" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" />Contact Management</div>
                    </a></td>
                  </tr>
            </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <?php
			  }
			  ?>
                <?php
			if($row_contentads['status'] == 'active')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="ads.php">
                      <div style="line-height:37px;"><img src="images/paintBrush.png" alt="Ads Management" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" />Ads Management</div>
                    </a></td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <?php
			  }
			  ?>
                <?php
			if($row_contentlinks['status'] == 'active')
			  {
			  ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="leftmenu">
                  <tr>
                    <td height="35"><a href="links.php">
                      <div style="line-height:37px;"><img src="images/runningMan.png" alt="External Links" width="14" height="14" border="0" style="padding-left:9px;padding-right:7px;" />External Links</div>
                    </a></td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <?php
			  }
			  ?></td>
              <td width="13">&nbsp;</td>
              <td width="700" height="30" align="center" valign="top"><?php if ($totalRows_membersupdate > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
          <tr>
            <td background="images/leftNavBg.png" class="admintitle">PROFILE UPDATE</td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
          <tr>
            <td height="47" align="left" bgcolor="#FFFFFF"><form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="memberupdate" id="memberupdate">
              <table width="100%" border="0" cellspacing="4" cellpadding="4">
                <tr>
                  <td width="69" class="texts"><strong>USER:*</strong></td>
                  <td colspan="2"><span class="texts"><?php echo $row_membersupdate['users']; ?></span>                    <input name="users" type="hidden" class="form" id="users" value="<?php echo $row_membersupdate['users']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>PASS:*</strong></td>
                  <td colspan="2"><input name="passs" type="text" class="form" id="passs" value="<?php echo $row_membersupdate['passs']; ?>" />
                    <a href="<?php echo $logoutAction ?>"></a></td>
                </tr>
                <tr>
                  <td class="texts"><strong>FULLNAME:*</strong></td>
                  <td colspan="2"><input name="fullname" type="text" class="form" id="fullname" value="<?php echo $row_membersupdate['fullname']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>ADDRESS:*</strong></td>
                  <td colspan="2"><input name="address" type="text" class="form" id="address" value="<?php echo $row_membersupdate['address']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>EMAIL:*</strong></td>
                  <td colspan="2"><input name="email" type="text" class="form" id="email" value="<?php echo $row_membersupdate['email']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>ZIP:*</strong></td>
                  <td colspan="2"><input name="zip" type="text" class="form" id="zip" value="<?php echo $row_membersupdate['zip']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>CITY:*</strong></td>
                  <td colspan="2"><input name="city" type="text" class="form" id="city" value="<?php echo $row_membersupdate['city']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>STATE:*</strong></td>
                  <td colspan="2"><input name="state" type="text" class="form" id="state" value="<?php echo $row_membersupdate['state']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>COUNTRY:*</strong></td>
                  <td colspan="2"><input name="country" type="text" class="form" id="country" value="<?php echo $row_membersupdate['country']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>PHONE:*</strong></td>
                  <td colspan="2"><input name="phone" type="text" class="form" id="phone" value="<?php echo $row_membersupdate['phone']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>YAHOOID:</strong></td>
                  <td colspan="2"><input name="yahooid" type="text" class="form" id="yahooid" value="<?php echo $row_membersupdate['yahooid']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>TWITTER:</strong></td>
                  <td colspan="2"><input name="twitter" type="text" class="form" id="twitter" value="<?php echo $row_membersupdate['twitter']; ?>" /></td>
                </tr>
                <tr>
                  <td class="texts"><strong>FACEBOOK:</strong></td>
                  <td colspan="2"><input name="facebook" type="text" class="form" id="facebook" value="<?php echo $row_membersupdate['facebook']; ?>" /></td>
                </tr>
                <tr>
                  <td><input name="memberid" type="hidden" id="memberid" value="<?php echo $row_membersupdate['memberid']; ?>" />
                    <input name="level" type="hidden" class="form" id="level" value="<?php echo $row_membersupdate['level']; ?>" />
                    <input name="status" type="hidden" class="form" id="status" value="<?php echo $row_membersupdate['status']; ?>" />
                    <input name="position" type="hidden" class="form" id="position" value="<?php echo $row_membersupdate['position']; ?>" /></td>
                  <td width="82"><input name="button" type="submit" class="button" id="button" value="Update" /></td>
                  <td width="491">&nbsp;</td>
                </tr>
              </table>
              <input type="hidden" name="MM_update" value="memberupdate" />
            </form>
              <script language="JavaScript" type="text/javascript">

  var frmvalidator  = new Validator("memberupdate");

  frmvalidator.addValidation("passs","req","Please Enter Password");
  frmvalidator.addValidation("passs","minlen=6","Min length for Password is 30");
    
  frmvalidator.addValidation("fullname","req","Please Enter Full Name");
  frmvalidator.addValidation("fullname","maxlen=30","Max length for Full Name is 30");
  frmvalidator.addValidation("fullname","alphanumeric_space","Only AlphaNumeric Characters Allow and No Space Please");
      
  frmvalidator.addValidation("address","req","Please Your Address");

  frmvalidator.addValidation("email","maxlen=100","Max length for email is 100");
  frmvalidator.addValidation("email","req","Please Enter Your Email");
  frmvalidator.addValidation("email","email");
  
  frmvalidator.addValidation("phone","maxlen=20","Max length for phone is 20");
  frmvalidator.addValidation("phone","req","Please Enter Your Phone Number");
  frmvalidator.addValidation("phone","numeric","Please Enter Correct Phone Number");
  
  frmvalidator.addValidation("zip","req","Please Enter Zip");
  frmvalidator.addValidation("zip","alphanumeric","Only AlphaNumeric Characters Allow");
  
  frmvalidator.addValidation("city","req","Please Enter City");
  frmvalidator.addValidation("city","alphanumeric_space","Only AlphaNumeric Characters Allow");
  
  frmvalidator.addValidation("state","req","Please Enter State");
  frmvalidator.addValidation("state","alphanumeric_space","Only AlphaNumeric Characters Allow");
  
  frmvalidator.addValidation("country","req","Please Enter Country");
  frmvalidator.addValidation("country","alphanumeric_space","Only AlphaNumeric Characters Allow");

</script>
              <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                <tr>
                  <td background="images/leftNavBg.png" class="admintitle">MEMBER PHOTO</td>
                  </tr>
              </table>
              <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                <tr>
                  <td height="47" align="left" bgcolor="#FFFFFF"><form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="graphical" id="graphical">
                    <table width="100%" border="0" cellspacing="4" cellpadding="4">
                      <tr>
                        <td width="96"><span class="texts"><strong>PHOTO:</strong></span></td>
                        <td width="208" bgcolor="#F9F9F9">
                          <input name="photo" type="file" class="formtextarea" id="photo" /></td>
                        <td width="338" rowspan="2" align="center" bgcolor="#F9F9F9"><img src="/images/members/<?php echo $row_membersupdate['photo']; ?>" width="100" height="100" /></td>
                      </tr>
                      <tr>
                        <td><input name="memberid" type="hidden" id="memberid"   
      value="<?php echo $row_membersupdate['memberid']; ?>" /></td>
                        <td valign="top"><input name="upload" type="submit" class="button" id="upload" value="upload" /></td>
                      </tr>
                    </table>
                    
                    <input type="hidden" name="MM_update" value="graphical" />
                  </form></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <table width="40" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="4"></td>
            </tr>
        </table></td>
    </tr>
  </table>
                  <table width="40" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="4"></td>
                      </tr>
                  </table>
                  <?php } // Show if recordset not empty ?>
                <span class="textsmall"><?php echo $row_setting['footer']; ?></span></td>
            </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</div></div>
</body>
</html>
<?php

mysqli_free_result($members);

mysqli_free_result($contentparts);

mysqli_free_result($contentnews);

mysqli_free_result($contentlinks);

mysqli_free_result($contentcontact);

mysqli_free_result($contentads);

mysqli_free_result($rsM);

mysqli_free_result($rsT);

mysqli_free_result($setting);

mysqli_free_result($membersoverview);

mysqli_free_result($membersupdate);

?>