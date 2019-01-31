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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add")) {
  $insertSQL = sprintf("INSERT INTO searchengine (title, siteurl, dates, metadesc, metakey, status, `position`) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['siteurl'], "text"),
                       GetSQLValueString($_POST['dates'], "text"),
                       GetSQLValueString($_POST['metadesc'], "text"),
                       GetSQLValueString($_POST['metakey'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['position'], "text"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$insertSQL) or die(mysqli_connect_error());
  
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add")) {
  $insertSQL = sprintf("INSERT INTO searchengine (title, dates, selecttopic) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['dates'], "text"),
                       GetSQLValueString($_POST['selecttopic'], "text"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$insertSQL) or die(mysqli_connect_error());

  $insertGoTo = "searchengine.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "update")) {
  $updateSQL = sprintf("UPDATE searchengine SET title=%s, siteurl=%s, dates=%s, metadesc=%s, metakey=%s, status=%s, `position`=%s WHERE searchengineid=%s",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['siteurl'], "text"),
                       GetSQLValueString($_POST['dates'], "text"),
                       GetSQLValueString($_POST['metadesc'], "text"),
                       GetSQLValueString($_POST['metakey'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString($_POST['searchengineid'], "int"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$updateSQL) or die(mysqli_connect_error());

  $updateGoTo = "searchengine.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

if ((isset($_GET['delete'])) && ($_GET['delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM searchengine WHERE searchengineid=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$deleteSQL) or die(mysqli_connect_error());

  $deleteGoTo = "searchengine.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);

$maxRows_blogoverview = 20;
$pageNum_blogoverview = 0;
if (isset($_GET['pageNum_blogoverview'])) {
  $pageNum_blogoverview = $_GET['pageNum_blogoverview'];
}
$startRow_blogoverview = $pageNum_blogoverview * $maxRows_blogoverview;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_blogoverview = "SELECT * FROM searchengine ORDER BY searchengineid DESC";
$query_limit_blogoverview = sprintf("%s LIMIT %d, %d", $query_blogoverview, $startRow_blogoverview, $maxRows_blogoverview);
$blogoverview = mysqli_query(dbconnect(),$query_limit_blogoverview) or die(mysqli_connect_error());
$row_blogoverview = mysqli_fetch_assoc($blogoverview);

if (isset($_GET['totalRows_blogoverview'])) {
  $totalRows_blogoverview = $_GET['totalRows_blogoverview'];
} else {
  $all_blogoverview = mysqli_query(dbconnect(),$query_blogoverview);
  $totalRows_blogoverview = mysqli_num_rows($all_blogoverview);
}
$totalPages_blogoverview = ceil($totalRows_blogoverview/$maxRows_blogoverview)-1;

$colname_blogupdate = "-1";
if (isset($_GET['searchengineid'])) {
  $colname_blogupdate = $_GET['searchengineid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_blogupdate = sprintf("SELECT * FROM searchengine WHERE searchengineid = %s", GetSQLValueString($colname_blogupdate, "int"));
$blogupdate = mysqli_query(dbconnect(),$query_blogupdate) or die(mysqli_connect_error());
$row_blogupdate = mysqli_fetch_assoc($blogupdate);
$totalRows_blogupdate = mysqli_num_rows($blogupdate);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_categorylist = "SELECT * FROM categories WHERE selecttopic = 'searchengine'";
$categorylist = mysqli_query(dbconnect(),$query_categorylist) or die(mysqli_connect_error());
$row_categorylist = mysqli_fetch_assoc($categorylist);
$totalRows_categorylist = mysqli_num_rows($categorylist);

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

$queryString_blogoverview = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_blogoverview") == false && 
        stristr($param, "totalRows_blogoverview") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_blogoverview = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_blogoverview = sprintf("&totalRows_blogoverview=%d%s", $totalRows_blogoverview, $queryString_blogoverview);


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

<title><?php echo $row_setting['title']; ?> - Search Engine</title>
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
              <td width="700" height="30" align="center" valign="top"><?php if ($totalRows_blogupdate == 0) { // Show if recordset empty ?>
                  <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                    <tr>
                      <td background="images/leftNavBg.png" class="admintitle">Search Engine OVERVIEW</td>
                    </tr>
                  </table>
                  <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                    <tr>
                      <td height="47" align="left" bgcolor="#FFFFFF"><?php if ($totalRows_blogoverview > 0) { // Show if recordset not empty ?>
                          <table width="100%" border="0" cellspacing="2" cellpadding="4">
                            <tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
                              <td width="32" align="center">ID</td>
                              <td width="321">TITLE</td>
                              <td colspan="3">URL</td>
                            </tr>
                            <?php do { ?>
                              <tr>
                                <td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row_blogoverview['searchengineid']; ?></div></td>
                                <td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row_blogoverview['title']; ?></div></td>
                                <td bgcolor="#FBFEFF" class="borderorangetable"><?php echo $row_blogoverview['siteurl']; ?></td>
                                <td width="23" align="center" bgcolor="#FBFEFF" class="borderorangetable"><a href="searchengine.php?searchengineid=<?php echo $row_blogoverview['searchengineid']; ?>" class="textsmallgreen">
                                  <div>Edit</div>
                                </a></td>
                                <td width="36" align="center" bgcolor="#FBFEFF" class="borderorangetable"><a href="searchengine.php?delete=<?php echo $row_blogoverview['searchengineid']; ?>" class="textsmallred" onClick="javascript:return confirm('Please Confirm Before Delete ?')">
                                  <div>Delete</div>
                                </a></td>
                              </tr>
                              <?php } while ($row_blogoverview = mysqli_fetch_assoc($blogoverview)); ?>
                          </table>
                          <table width="40" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td height="4"></td>
                            </tr>
                          </table>
                          <table border="0" align="center" cellpadding="0" cellspacing="4">
                            <tr>
                              <td align="center" class="pagesmove"><a href="<?php printf("%s?pageNum_blogoverview=%d%s", $currentPage, 0, $queryString_blogoverview); ?>">&lt;</a></td>
                              <td align="center" class="pagesmove"><a href="<?php printf("%s?pageNum_blogoverview=%d%s", $currentPage, max(0, $pageNum_blogoverview - 1), $queryString_blogoverview); ?>">&lt;&lt;</a></td>
                              <td align="center" class="pagesmove"><a href="<?php printf("%s?pageNum_blogoverview=%d%s", $currentPage, min($totalPages_blogoverview, $pageNum_blogoverview + 1), $queryString_blogoverview); ?>">&gt;&gt;</a></td>
                              <td align="center" class="pagesmove"><a href="<?php printf("%s?pageNum_blogoverview=%d%s", $currentPage, $totalPages_blogoverview, $queryString_blogoverview); ?>">&gt;</a></td>
                            </tr>
                          </table>
                          <?php } // Show if recordset not empty ?>
                        <?php if ($totalRows_blogoverview == 0) { // Show if recordset empty ?>
  <table width="100%" border="0" cellspacing="2" cellpadding="4">
    <tr class="orangetitle">
      <td width="32" align="center">NO Url FOUNDED</td>
      </tr>
  </table>
  <?php } // Show if recordset empty ?></td>
                    </tr>
                  </table>
                  <table width="40" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="4"></td>
                    </tr>
                  </table>
                  <?php } // Show if recordset empty ?>
                  <?php if ($totalRows_blogupdate == 0) { // Show if recordset empty ?>
                    <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                      <tr>
                        <td background="images/leftNavBg.png" class="admintitle">ADD URL</td>
                      </tr>
                    </table>
                    <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                      <tr>
                        <td height="47" align="left" bgcolor="#FFFFFF"><form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="add" id="add">
                          <table width="100%" height="225" border="0" cellpadding="0" cellspacing="4">
                            <tr>
                              <td width="101" height="22" class="texts"><strong>TITLE:*</strong></td>
                              <td width="728"><input name="title" type="text" class="form" id="title" /></td>
                            </tr>
                            <tr>
                              <td height="23" class="texts"><strong>URL:*</strong></td>
                              <td><input name="siteurl" type="text" class="form" id="siteurl" value="" size="55" /></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>DATE:*</strong></td>
                              <td><input name="dates" type="text" class="formmenusimple" id="dates" value="<?php print(Date("d-m-Y")); ?>" />
                                <span class="textsmall">FORMAT : DD-MM-YYYY</span></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>META DESC:*</strong></td>
                              <td><input name="metadesc" type="text" class="form" id="metadesc" /></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>META KEY:*</strong></td>
                              <td><input name="metakey" type="text" class="form" id="image3" /></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>POSITION:</strong></td>
                              <td>
                                <select name="position" class="formmenu" id="position">
                                  <option value="normal">normal</option>
                                  <option value="featured">featured</option>
                                </select></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>STATUS:</strong></td>
                              <td><select name="status" class="formmenu" id="status">
                                <option value="pending">pending</option>
                                <option value="published">published</option>
                                <input name="selecttopic" type="hidden" id="selecttopic" value="searchengine" />
                              </select></td>
                            </tr>
                            <tr>
                              <td height="24">&nbsp;</td>
                              <td><input name="button2" type="submit" class="button" id="button2" value="Add" /></td>
                            </tr>
                          </table>
                          <input type="hidden" name="MM_insert" value="add" />
                        </form>
                        <script language="JavaScript" type="text/javascript">

  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("title","req","Please Enter Post Title");
  frmvalidator.addValidation("title","maxlen=100","Max length for Post is 100");
  frmvalidator.addValidation("title","alphanumeric_space","Only AlphaNumeric Characters Allow");

  frmvalidator.addValidation("dates","req","Please Enter Date DD-MM-YYYY");
  frmvalidator.addValidation("dates","maxlen=10","Please Enter Correct Date");
   
  frmvalidator.addValidation("siteurl","req","Please Enter Website Url");

  frmvalidator.addValidation("metadesc","req","Please Enter Meta Description");
  frmvalidator.addValidation("metadesc","maxlen=160","Max length for Meta Description is 160");

  frmvalidator.addValidation("metakey","req","Please Enter Meta Keyword");
  frmvalidator.addValidation("metakey","maxlen=250","Max length for Meta Keyword is 250");

</script></td>
                      </tr>
                    </table>
                    <table width="40" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="4"></td>
                      </tr>
                    </table>
                    <?php } // Show if recordset empty ?>
                  <?php if ($totalRows_blogupdate > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#ECCF5E">
    <tr>
      <td align="center" bgcolor="#FAF1D1" class="texts"><a href="searchengine.php" class="textsmallred">Back To Search Engine</a></td>
    </tr>
  </table>
  <table width="40" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td height="4"></td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
    <tr>
      <td background="images/leftNavBg.png" class="admintitle">UPDATE URL</td>
      </tr>
  </table>
                    <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                      <tr>
                        <td height="47" align="left" bgcolor="#FFFFFF"><form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="update" id="update">
                          <table width="100%" height="225" border="0" cellpadding="0" cellspacing="4">
                            <tr>
                              <td width="101" height="22" class="texts"><strong>TITLE:*</strong></td>
                              <td width="728"><input name="title" type="text" class="form" id="title" value="<?php echo $row_blogupdate['title']; ?>" /></td>
                              </tr>
                            <tr>
                              <td height="23" class="texts"><strong>URL:*</strong></td>
                              <td><input name="siteurl" type="text" class="form" id="siteurl" value="<?php echo $row_blogupdate['siteurl']; ?>" size="55" /></td>
                              </tr>
                            <tr>
                              <td height="24" class="texts"><strong>DATE:*</strong></td>
                              <td><input name="dates" type="text" class="formmenusimple" id="dates" value="<?php echo $row_blogupdate['dates']; ?>" />
                                <span class="textsmall">FORMAT : DD-MM-YYYY</span></td>
                              </tr>
                            <tr>
                              <td height="24" class="texts"><strong>META DESC:*</strong></td>
                              <td><input name="metadesc" type="text" class="form" id="metadesc" value="<?php echo $row_blogoverview['metadesc']; ?>" /></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>META KEY:*</strong></td>
                              <td><input name="metakey" type="text" class="form" id="image4" value="<?php echo $row_blogoverview['metakey']; ?>" /></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>POSITION:</strong></td>
                              <td>
                                <select name="position" class="formmenu" id="position">
                                  <option value="normal" <?php if (!(strcmp("normal", $row_blogupdate['position']))) {echo "selected=\"selected\"";} ?>>normal</option>
                                  <option value="featured" <?php if (!(strcmp("featured", $row_blogupdate['position']))) {echo "selected=\"selected\"";} ?>>featured</option>
                                </select></td>
                            </tr>
                            <tr>
                              <td height="24" class="texts"><strong>STATUS:</strong></td>
                              <td><select name="status" class="formmenu" id="status">
                                <option value="pending" <?php if (!(strcmp("pending", $row_blogupdate['status']))) {echo "selected=\"selected\"";} ?>>pending</option>
                                <option value="published" <?php if (!(strcmp("published", $row_blogupdate['status']))) {echo "selected=\"selected\"";} ?>>published</option>
                                </select></td>
                              </tr>
                            <tr>
                              <td height="24">&nbsp;</td>
                              <td><input name="button" type="submit" class="button" id="button" value="Update" /></td>
                              </tr>
                            </table>
                          <input name="searchengineid" type="hidden" id="searchengineid" value="<?php echo $row_blogupdate['searchengineid']; ?>" />
                          <input type="hidden" name="MM_update" value="update" />
                        </form>
                        <script language="JavaScript" type="text/javascript">

  var frmvalidator  = new Validator("update");
  frmvalidator.addValidation("title","req","Please Enter Post Title");
  frmvalidator.addValidation("title","maxlen=100","Max length for Post is 100");
  frmvalidator.addValidation("title","alphanumeric_space","Only AlphaNumeric Characters Allow");

  frmvalidator.addValidation("dates","req","Please Enter Date DD-MM-YYYY");
  frmvalidator.addValidation("dates","maxlen=10","Please Enter Correct Date");
   
  frmvalidator.addValidation("siteurl","req","Please Enter Website Url");

  frmvalidator.addValidation("metadesc","req","Please Enter Meta Description");
  frmvalidator.addValidation("metadesc","maxlen=160","Max length for Meta Description is 160");

  frmvalidator.addValidation("metakey","req","Please Enter Meta Keyword");
  frmvalidator.addValidation("metakey","maxlen=250","Max length for Meta Keyword is 250");

</script></td>
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

mysqli_free_result($setting);

mysqli_free_result($blogoverview);

mysqli_free_result($blogupdate);

mysqli_free_result($categorylist);
?>