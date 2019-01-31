<?php require_once('../includes/rayicecms.php'); ?>
<?php error_reporting(0); ?>
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

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_pages = "SELECT * FROM pages WHERE position = 'leftmenu'";
$pages = mysqli_query(dbconnect(),$query_pages) or die(mysqli_connect_error());
$row_pages = mysqli_fetch_assoc($pages);
$totalRows_pages = mysqli_num_rows($pages);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_pages1 = "SELECT * FROM pages WHERE position = 'bottommenu'";
$pages1 = mysqli_query(dbconnect(),$query_pages1) or die(mysqli_connect_error());
$row_pages1 = mysqli_fetch_assoc($pages1);
$totalRows_pages1 = mysqli_num_rows($pages1);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_parts = "SELECT * FROM parts";
$parts = mysqli_query(dbconnect(),$query_parts) or die(mysqli_connect_error());
$row_parts = mysqli_fetch_assoc($parts);
$totalRows_parts = mysqli_num_rows($parts);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_theme = "SELECT * FROM themes";
$theme = mysqli_query(dbconnect(),$query_theme) or die(mysqli_connect_error());
$row_theme = mysqli_fetch_assoc($theme);
$totalRows_theme = mysqli_num_rows($theme);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_pagesdetail = "SELECT * FROM news WHERE selecttopic = 'searchengine'";
$pagesdetail = mysqli_query(dbconnect(),$query_pagesdetail) or die(mysqli_connect_error());
$row_pagesdetail = mysqli_fetch_assoc($pagesdetail);
$totalRows_pagesdetail = mysqli_num_rows($pagesdetail);

$maxRows_searchengine = 10;
$pageNum_searchengine = 0;
if (isset($_GET['pageNum_searchengine'])) {
  $pageNum_searchengine = $_GET['pageNum_searchengine'];
}
$startRow_searchengine = $pageNum_searchengine * $maxRows_searchengine;

$colname_searchengine = "-1";
if (isset($_GET['title'])) {
  $colname_searchengine = $_GET['title'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_searchengine = sprintf("SELECT * FROM searchengine WHERE title LIKE %s AND status = 'published'", GetSQLValueString("%" . $colname_searchengine . "%", "text"));
$query_limit_searchengine = sprintf("%s LIMIT %d, %d", $query_searchengine, $startRow_searchengine, $maxRows_searchengine);
$searchengine = mysqli_query(dbconnect(),$query_limit_searchengine) or die(mysqli_connect_error());
$row_searchengine = mysqli_fetch_assoc($searchengine);

if (isset($_GET['totalRows_searchengine'])) {
  $totalRows_searchengine = $_GET['totalRows_searchengine'];
} else {
  $all_searchengine = mysqli_query(dbconnect(),$query_searchengine);
  $totalRows_searchengine = mysqli_num_rows($all_searchengine);
}
$totalPages_searchengine = ceil($totalRows_searchengine/$maxRows_searchengine)-1;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_links = "SELECT * FROM friendlinks";
$links = mysqli_query(dbconnect(),$query_links) or die(mysqli_connect_error());
$row_links = mysqli_fetch_assoc($links);
$totalRows_links = mysqli_num_rows($links);

$colname_members = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_members = $_SESSION['MM_Username'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_members = sprintf("SELECT * FROM members WHERE users = %s", GetSQLValueString($colname_members, "text"));
$members = mysqli_query(dbconnect(),$query_members) or die(mysqli_connect_error());
$row_members = mysqli_fetch_assoc($members);
$totalRows_members = mysqli_num_rows($members);

$colname_pagesdetail1 = "-1";
if (isset($_GET['newsid'])) {
  $colname_pagesdetail1 = $_GET['newsid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_pagesdetail1 = sprintf("SELECT * FROM news WHERE newsid = %s", GetSQLValueString($colname_pagesdetail1, "int"));
$pagesdetail1 = mysqli_query(dbconnect(),$query_pagesdetail1) or die(mysqli_connect_error());
$row_pagesdetail1 = mysqli_fetch_assoc($pagesdetail1);
$totalRows_pagesdetail1 = mysqli_num_rows($pagesdetail1);

$queryString_searchengine = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_searchengine") == false && 
        stristr($param, "totalRows_searchengine") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_searchengine = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_searchengine = sprintf("&totalRows_searchengine=%d%s", $totalRows_searchengine, $queryString_searchengine);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "searchengine"))
	  {
?>
<!-- index start -->
<?php
$getviews = $_GET["newsid"];
$views_query = mysqli_query(dbconnect(),"SELECT * FROM news WHERE newsid = $getviews");
$views = mysqli_fetch_assoc($views_query);

if(isset($_GET["newsid"]))
{
	$addview = $views['views']+1;
	$viewsupdate_query = mysqli_query(dbconnect(),"UPDATE news SET views = $addview WHERE newsid = $getviews");
}
?>
<?php include($theme_path."".$row_setting['theme']."/topdocs.php"); ?>
<title><?php echo $row_setting['title']; ?></title>
<meta name="keywords" content="<?php echo $row_setting['metakey']; ?>">
<meta name="description" content="<?php echo $row_setting['metadesc']; ?>">
<link href="/themes/<?php echo $row_setting['theme']; ?>/multicms.css" rel="stylesheet" type="text/css">
<?php $favicon ?>
<?php include($theme_path."".$row_setting['theme']."/botdocs.php"); ?>
<?php
if($row_setting['onlinestatus'] == "yes")
{
?>
<!-- HEADER INCLUDED -->
<?php include($theme_path."".$row_setting['theme']."/header.php"); ?>
<div style="float:left; width:227px; padding:0;position: relative;">
<?php include($theme_path."".$row_setting['theme']."/leftmenu.php"); ?>
</div>

  <div style="float:right; padding:0; padding-top:0px; width:83%;position: relative;">
    <?php if ($totalRows_pagesdetail1 == 0) { // Show if recordset empty ?>
    <div class="title">NEWS</div>
  <?php do { ?>
    <div style="padding-left:5px; padding-top:5px;">
      <div class="admintitle"><a href="info.php?newsid=<?php echo $row_pagesdetail['newsid']?>"><?php echo $row_pagesdetail['title']; ?></a></div>
    </div>
    <div style="height:2px;" class="texts"><?php echo $row_pagesdetail['description']; ?></div>
    
    <div style="height:22px;"></div>
    <?php } while ($row_pagesdetail = mysqli_fetch_assoc($pagesdetail)); ?>
      <?php } // Show if recordset empty ?>
      <?php if ($totalRows_pagesdetail == 0) { // Show if recordset empty ?>
  <div align="center">Sorry! No Content Found</div>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_pagesdetail1 > 0) { // Show if recordset not empty ?>
  <div style="padding-left:0px; padding-top:0px;">
    <div class="title"><?php echo $row_pagesdetail1['title']; ?></div>
  </div>
    <div class="texts"><?php echo $row_pagesdetail1['description']; ?></div>
        
        <?php } // Show if recordset not empty ?>

<?php
$widgetstatus_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '12'");
$widgetstatus = mysqli_fetch_assoc($widgetstatus_query);

$widgets_query = mysqli_query(dbconnect(),"SELECT * FROM widgets WHERE position = 'info'");
$widgets = mysqli_fetch_assoc($widgets_query);

if(($widgetstatus['status'] == 'active') && (!empty($widgets)))
{
?>
<div class="title">WIDGETS</div>
<?php do { ?>
<div align="center">
<?php echo $widgets['content']; ?></div>
<?php } while ($widgets = mysqli_fetch_assoc($widgets_query)); ?>
<?php
}
?>
      <div style="height:22px;"></div>

  </div>
<?php
include($theme_path."".$row_setting['theme']."/footer.php"); 
?>
<?php
}
else
{
?>
<div align="center">Site is currently Offline</div>
<?php
}
?>
</body>
</html>
<!-- index end -->
<?php
	  }
	  else
	  {
	  ?>
<script type="text/javascript"> location.replace("/install.php"); </script>
<?php
	  }
?>
<!-- code end -->
<?php
mysqli_free_result($setting);

mysqli_free_result($pages);

mysqli_free_result($pages1);

mysqli_free_result($theme);

mysqli_free_result($parts);

mysqli_free_result($searchengine);

mysqli_free_result($links);

mysqli_free_result($members);

mysqli_free_result($pagesdetail1);

mysqli_free_result($pagesdetail);

?>