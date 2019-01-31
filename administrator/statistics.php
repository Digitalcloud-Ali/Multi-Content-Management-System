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
$MM_authorizedUsers = "administrator";
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

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);

// this is for showing total comments record
$_SERVER['selecttopic'] = $row_setting['selecttopic'];
// this is end of showing comments

$colname_totalcomments = "-1";
if (isset($_SERVER['selecttopic'])) {
  $colname_totalcomments = $_SERVER['selecttopic'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_totalcomments = sprintf("SELECT * FROM comments", GetSQLValueString($colname_totalcomments, "text"));
$totalcomments = mysqli_query(dbconnect(),$query_totalcomments) or die(mysqli_connect_error());
$row_totalcomments = mysqli_fetch_assoc($totalcomments);
$totalRows_totalcomments = mysqli_num_rows($totalcomments);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_totalusers = "SELECT * FROM members";
$totalusers = mysqli_query(dbconnect(),$query_totalusers) or die(mysqli_connect_error());
$row_totalusers = mysqli_fetch_assoc($totalusers);
$totalRows_totalusers = mysqli_num_rows($totalusers);

$colname_totalcategories = "-1";
if (isset($_SERVER['selecttopic'])) {
  $colname_totalcategories = $_SERVER['selecttopic'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_totalcategories = sprintf("SELECT * FROM categories", GetSQLValueString($colname_totalcategories, "text"));
$totalcategories = mysqli_query(dbconnect(),$query_totalcategories) or die(mysqli_connect_error());
$row_totalcategories = mysqli_fetch_assoc($totalcategories);
$totalRows_totalcategories = mysqli_num_rows($totalcategories);

$maxRows_commentsoverview = 10;
$pageNum_commentsoverview = 0;
if (isset($_GET['pageNum_commentsoverview'])) {
  $pageNum_commentsoverview = $_GET['pageNum_commentsoverview'];
}
$startRow_commentsoverview = $pageNum_commentsoverview * $maxRows_commentsoverview;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_commentsoverview = "SELECT * FROM comments ORDER BY commentid DESC";
$query_limit_commentsoverview = sprintf("%s LIMIT %d, %d", $query_commentsoverview, $startRow_commentsoverview, $maxRows_commentsoverview);
$commentsoverview = mysqli_query(dbconnect(),$query_limit_commentsoverview) or die(mysqli_connect_error());
$row_commentsoverview = mysqli_fetch_assoc($commentsoverview);

if (isset($_GET['totalRows_commentsoverview'])) {
  $totalRows_commentsoverview = $_GET['totalRows_commentsoverview'];
} else {
  $all_commentsoverview = mysqli_query(dbconnect(),$query_commentsoverview);
  $totalRows_commentsoverview = mysqli_num_rows($all_commentsoverview);
}
$totalPages_commentsoverview = ceil($totalRows_commentsoverview/$maxRows_commentsoverview)-1;

$maxRows_usersoverview = 15;
$pageNum_usersoverview = 0;
if (isset($_GET['pageNum_usersoverview'])) {
  $pageNum_usersoverview = $_GET['pageNum_usersoverview'];
}
$startRow_usersoverview = $pageNum_usersoverview * $maxRows_usersoverview;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_usersoverview = "SELECT * FROM members ORDER BY memberid DESC";
$query_limit_usersoverview = sprintf("%s LIMIT %d, %d", $query_usersoverview, $startRow_usersoverview, $maxRows_usersoverview);
$usersoverview = mysqli_query(dbconnect(),$query_limit_usersoverview) or die(mysqli_connect_error());
$row_usersoverview = mysqli_fetch_assoc($usersoverview);

if (isset($_GET['totalRows_usersoverview'])) {
  $totalRows_usersoverview = $_GET['totalRows_usersoverview'];
} else {
  $all_usersoverview = mysqli_query(dbconnect(),$query_usersoverview);
  $totalRows_usersoverview = mysqli_num_rows($all_usersoverview);
}
$totalPages_usersoverview = ceil($totalRows_usersoverview/$maxRows_usersoverview)-1;

$maxRows_catesoverview = 10;
$pageNum_catesoverview = 0;
if (isset($_GET['pageNum_catesoverview'])) {
  $pageNum_catesoverview = $_GET['pageNum_catesoverview'];
}
$startRow_catesoverview = $pageNum_catesoverview * $maxRows_catesoverview;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_catesoverview = "SELECT * FROM categories ORDER BY cateid DESC";
$query_limit_catesoverview = sprintf("%s LIMIT %d, %d", $query_catesoverview, $startRow_catesoverview, $maxRows_catesoverview);
$catesoverview = mysqli_query(dbconnect(),$query_limit_catesoverview) or die(mysqli_connect_error());
$row_catesoverview = mysqli_fetch_assoc($catesoverview);

if (isset($_GET['totalRows_catesoverview'])) {
  $totalRows_catesoverview = $_GET['totalRows_catesoverview'];
} else {
  $all_catesoverview = mysqli_query(dbconnect(),$query_catesoverview);
  $totalRows_catesoverview = mysqli_num_rows($all_catesoverview);
}
$totalPages_catesoverview = ceil($totalRows_catesoverview/$maxRows_catesoverview)-1;

$maxRows_totalcontent = 10;
$pageNum_totalcontent = 0;
if (isset($_GET['pageNum_totalcontent'])) {
  $pageNum_totalcontent = $_GET['pageNum_totalcontent'];
}
$startRow_totalcontent = $pageNum_totalcontent * $maxRows_totalcontent;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_totalcontent = "SELECT * FROM pages ORDER BY pageid DESC";
$query_limit_totalcontent = sprintf("%s LIMIT %d, %d", $query_totalcontent, $startRow_totalcontent, $maxRows_totalcontent);
$totalcontent = mysqli_query(dbconnect(),$query_limit_totalcontent) or die(mysqli_connect_error());
$row_totalcontent = mysqli_fetch_assoc($totalcontent);

if (isset($_GET['totalRows_totalcontent'])) {
  $totalRows_totalcontent = $_GET['totalRows_totalcontent'];
} else {
  $all_totalcontent = mysqli_query(dbconnect(),$query_totalcontent);
  $totalRows_totalcontent = mysqli_num_rows($all_totalcontent);
}
$totalPages_totalcontent = ceil($totalRows_totalcontent/$maxRows_totalcontent)-1;

$maxRows_totalexternal = 10;
$pageNum_totalexternal = 0;
if (isset($_GET['pageNum_totalexternal'])) {
  $pageNum_totalexternal = $_GET['pageNum_totalexternal'];
}
$startRow_totalexternal = $pageNum_totalexternal * $maxRows_totalexternal;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_totalexternal = "SELECT * FROM friendlinks";
$query_limit_totalexternal = sprintf("%s LIMIT %d, %d", $query_totalexternal, $startRow_totalexternal, $maxRows_totalexternal);
$totalexternal = mysqli_query(dbconnect(),$query_limit_totalexternal) or die(mysqli_connect_error());
$row_totalexternal = mysqli_fetch_assoc($totalexternal);

if (isset($_GET['totalRows_totalexternal'])) {
  $totalRows_totalexternal = $_GET['totalRows_totalexternal'];
} else {
  $all_totalexternal = mysqli_query(dbconnect(),$query_totalexternal);
  $totalRows_totalexternal = mysqli_num_rows($all_totalexternal);
}
$totalPages_totalexternal = ceil($totalRows_totalexternal/$maxRows_totalexternal)-1;

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

$maxRows_totaldata = 10;
$pageNum_totaldata = 0;
if (isset($_GET['pageNum_totaldata'])) {
  $pageNum_totaldata = $_GET['pageNum_totaldata'];
}
$startRow_totaldata = $pageNum_totaldata * $maxRows_totaldata;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_totaldata = "SELECT * FROM adposting";
$query_limit_totaldata = sprintf("%s LIMIT %d, %d", $query_totaldata, $startRow_totaldata, $maxRows_totaldata);
$totaldata = mysqli_query(dbconnect(),$query_limit_totaldata) or die(mysqli_connect_error());
$row_totaldata = mysqli_fetch_assoc($totaldata);

if (isset($_GET['totalRows_totaldata'])) {
  $totalRows_totaldata = $_GET['totalRows_totaldata'];
} else {
  $all_totaldata = mysqli_query(dbconnect(),$query_totaldata);
  $totalRows_totaldata = mysqli_num_rows($all_totaldata);
}
$totalPages_totaldata = ceil($totalRows_totaldata/$maxRows_totaldata)-1;


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

<title><?php echo $row_setting['title']; ?> - Statistics</title>
<link href="rayicecms.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css3/styles.css" />
<link rel="shortcut icon" type="image/png" href="/images/<?php echo $row_setting['favicon']; ?>" />
<script language=javascript>
if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)))
{
location.replace("/mobadmin/");
}
</script></head>

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
              <td width="700" height="30" align="center" valign="top"><table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                <tr>
                    <td background="images/leftNavBg.png" class="admintitle">STATISTICS</td>
                  </tr>
            </table>
                <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td height="47" align="left" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="87"><div align="center">
                          <table width="150" border="0" cellpadding="6" cellspacing="0">
                            <tr>
                              <td><table width="32" border="0" cellpadding="0" cellspacing="0" class="smallbut">
                                <tr>
                                  <td width="30" height="35" align="center"><div class="orangetitle" style="line-height:37px;"><?php echo $totalRows_totalcomments ?></div></td>
                                </tr>
                              </table></td>
                              <td class="texts">Total Comments</td>
                            </tr>
                          </table>
                        </div></td>
                        <td><div align="center">
                          <table width="150" border="0" cellpadding="6" cellspacing="0">
                            <tr>
                              <td><table width="32" border="0" cellpadding="0" cellspacing="0" class="smallbut">
                                <tr>
                                  <td width="30" height="35" align="center"><div class="orangetitle" style="line-height:37px;"><?php echo $totalRows_totalusers ?></div></td>
                                </tr>
                              </table></td>
                              <td class="texts">Total Members</td>
                            </tr>
                          </table>
                        </div></td>
                        <td><div align="center">
                          <table border="0" cellspacing="0" cellpadding="6">
                            <tr>
                              <td><table width="32" border="0" cellpadding="0" cellspacing="0" class="smallbut">
                                <tr>
                                  <td width="30" height="35" align="center"><div class="orangetitle" style="line-height:37px;"><?php echo $totalRows_totalcategories ?></div></td>
                                  </tr>
                                </table></td>
                              <td class="texts">Total Categories</td>
                              </tr>
                            </table>
                        </div></td>
                        </tr>
                      <tr>
                        <td height="87"><div align="center">
                          <table width="150" border="0" cellpadding="6" cellspacing="0">
                            <tr>
                              <td><table width="32" border="0" cellpadding="0" cellspacing="0" class="smallbut">
                                <tr>
                                  <td width="30" height="35" align="center"><div class="orangetitle" style="line-height:37px;"><?php echo $totalRows_totalcontent ?></div></td>
                                </tr>
                              </table></td>
                              <td class="texts">Total Pages</td>
                            </tr>
                          </table>
                        </div></td>
                        <td><div align="center">
                          <table width="150" border="0" cellpadding="6" cellspacing="0">
                            <tr>
                              <td><table width="32" border="0" cellpadding="0" cellspacing="0" class="smallbut">
                                <tr>
                                  <td width="30" height="35" align="center"><div class="orangetitle" style="line-height:37px;"><?php echo $totalRows_totalexternal ?></div></td>
                                </tr>
                              </table></td>
                              <td class="texts">External Links</td>
                            </tr>
                          </table>
                        </div></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td background="images/leftNavBg.png" class="admintitle">Top 10 ContentS</td>
                  </tr>
        </table>
                <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td height="47" align="left" bgcolor="#FFFFFF"><img src="graphs/graphcontent.php" /></td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td height="47" align="left" bgcolor="#FFFFFF"><?php include('graphs/content.php'); ?>&nbsp;</td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td background="images/leftNavBg.png" class="admintitle">PAGES OVERVIEW - By USER VIEWS</td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td height="47" align="left" bgcolor="#FFFFFF"><img src="graphs/graph.php" /></td>
                  </tr>
                </table>
                <table width="40" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="4"></td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td background="images/leftNavBg.png" class="admintitle">CATEGORIES OVERVIEW - Recent 10</td>
                  </tr>
            </table>
                <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td height="47" align="left" bgcolor="#FFFFFF"><?php if ($totalRows_catesoverview > 0) { // Show if recordset not empty ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="4">
                          <tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
                            <td width="30" align="center">ID</td>
                            <td width="30%">TOPIC</td>
                            <td width="747">CATENAME</td>
                          </tr>
                          <?php do { ?>
                            <tr>
                              <td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row_catesoverview['cateid']; ?></div></td>
                              <td bgcolor="#F4FCFF" class="borderorangetable"><?php echo $row_catesoverview['selecttopic']; ?></td>
                              <td bgcolor="#FBFEFF" class="borderorangetable"><?php echo $row_catesoverview['catename']; ?></td>
                            </tr>
                            <?php } while ($row_catesoverview = mysqli_fetch_assoc($catesoverview)); ?>
                        </table>
                        <?php } // Show if recordset not empty ?>
                      <?php if ($totalRows_catesoverview == 0) { // Show if recordset empty ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="4">
                          <tr class="orangetitle">
                            <td width="32" align="center">NO CATEGORIES FOUNDED</td>
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
                <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td background="images/leftNavBg.png" class="admintitle">MEMBERS OVERVIEW - recent 15</td>
                  </tr>
              </table>
                <table width="100%" border="0" cellpadding="8" cellspacing="1" bgcolor="#CCCCCC">
                  <tr>
                    <td height="47" align="left" bgcolor="#FFFFFF"><?php if ($totalRows_usersoverview > 0) { // Show if recordset not empty ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="4">
                          <tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
                            <td width="30" align="center">ID</td>
                            <td width="30%">Name</td>
                            <td width="747">Username</td>
                          </tr>
                          <?php do { ?>
                            <tr>
                              <td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row_usersoverview['memberid']; ?></div></td>
                              <td bgcolor="#FEFAE9" class="borderorangetable"><?php echo $row_usersoverview['fullname']; ?></td>
                              <td bgcolor="#FFFDF4" class="borderorangetable"><?php echo $row_usersoverview['users']; ?></td>
                            </tr>
                            <?php } while ($row_usersoverview = mysqli_fetch_assoc($usersoverview)); ?>
                        </table>
                        <?php } // Show if recordset not empty ?>
                      <?php if ($totalRows_usersoverview == 0) { // Show if recordset empty ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="4">
                          <tr class="orangetitle">
                            <td width="32" align="center">NO MEMBERS FOUNDED</td>
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

mysqli_free_result($totaldata);

mysqli_free_result($setting);

mysqli_free_result($totalcomments);

mysqli_free_result($totalusers);

mysqli_free_result($totalcategories);

mysqli_free_result($commentsoverview);

mysqli_free_result($usersoverview);

mysqli_free_result($catesoverview);

mysqli_free_result($totalcontent);

mysqli_free_result($totalexternal);
?>
