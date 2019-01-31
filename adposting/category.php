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

//initialize the session
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
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'adposting'";
$blogcates = mysqli_query(dbconnect(),$query_blogcates) or die(mysqli_connect_error());
$row_blogcates = mysqli_fetch_assoc($blogcates);
$totalRows_blogcates = mysqli_num_rows($blogcates);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_pages = "SELECT * FROM pages";
$pages = mysqli_query(dbconnect(),$query_pages) or die(mysqli_connect_error());
$row_pages = mysqli_fetch_assoc($pages);
$totalRows_pages = mysqli_num_rows($pages);


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


$maxRows_adposting = 10;
$pageNum_adposting = 0;
if (isset($_GET['pageNum_adposting'])) {
  $pageNum_adposting = $_GET['pageNum_adposting'];
}
$startRow_adposting = $pageNum_adposting * $maxRows_adposting;

$colname_adposting = "-1";
if (isset($_GET['catename'])) {
  $colname_adposting = $_GET['catename'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_adposting = sprintf("SELECT * FROM adposting WHERE catename = %s AND status = 'published' ORDER BY adpostingid DESC", GetSQLValueString($colname_adposting, "text"));
$query_limit_adposting = sprintf("%s LIMIT %d, %d", $query_adposting, $startRow_adposting, $maxRows_adposting);
$adposting = mysqli_query(dbconnect(),$query_limit_adposting) or die(mysqli_connect_error());
$row_adposting = mysqli_fetch_assoc($adposting);

if (isset($_GET['totalRows_adposting'])) {
  $totalRows_adposting = $_GET['totalRows_adposting'];
} else {
  $all_adposting = mysqli_query(dbconnect(),$query_adposting);
  $totalRows_adposting = mysqli_num_rows($all_adposting);
}
$totalPages_adposting = ceil($totalRows_adposting/$maxRows_adposting)-1;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_links = "SELECT * FROM friendlinks";
$links = mysqli_query(dbconnect(),$query_links) or die(mysqli_connect_error());
$row_links = mysqli_fetch_assoc($links);
$totalRows_links = mysqli_num_rows($links);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_featuredadposts = "SELECT * FROM adposting WHERE position = 'featured' ORDER BY adpostingid DESC";
$featuredadposts = mysqli_query(dbconnect(),$query_featuredadposts) or die(mysqli_connect_error());
$row_featuredadposts = mysqli_fetch_assoc($featuredadposts);
$totalRows_featuredadposts = mysqli_num_rows($featuredadposts);

$colname_members = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_members = $_SESSION['MM_Username'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_members = sprintf("SELECT * FROM members WHERE users = %s", GetSQLValueString($colname_members, "text"));
$members = mysqli_query(dbconnect(),$query_members) or die(mysqli_connect_error());
$row_members = mysqli_fetch_assoc($members);
$totalRows_members = mysqli_num_rows($members);

$queryString_adposting = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_adposting") == false && 
        stristr($param, "totalRows_adposting") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_adposting = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_adposting = sprintf("&totalRows_adposting=%d%s", $totalRows_adposting, $queryString_adposting);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "adposting"))
	  {
?>
<!-- index start -->
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
<table class="mainbody">
      <tr>
        <?php include($theme_path."".$row_setting['theme']."/leftmenu.php"); ?>
        <td valign="top"><?php if ($totalRows_adposting > 0) { // Show if recordset not empty ?>
<div class="title"><span><?php echo $row_adposting['catename']; ?></span></div>
           <?php do { ?>
           <table>
                    <tr>
                      <td width="1"><a href="adposting.php?adpostingid=<?php echo $row_adposting['adpostingid']; ?>"><img src="images/adposting/<?php echo $row_adposting['imageurl']; ?>" width="40" height="40" border="0"></a></td>
                      <td><a href="adposting.php?adpostingid=<?php echo $row_adposting['adpostingid']; ?>" class="posttitle"><?php echo $row_adposting['title']; ?></a></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo $row_adposting['owner']; ?> / <?php echo $row_adposting['date']; ?> / View : <?php echo $row_adposting['views']; ?> / Rating : <?php echo $row_adposting['rating']; ?> / <?php echo $row_adposting['catename']; ?></td>
                    </tr>
                  </table>
                  <?php } while ($row_adposting = mysqli_fetch_assoc($adposting)); ?>
                  
           <div align="center"><a href="<?php printf("%s?pageNum_adposting=%d%s", $currentPage, 0, $queryString_adposting); ?>" class="navigationbuttons">FIRST</a> <a href="<?php printf("%s?pageNum_adposting=%d%s", $currentPage, max(0, $pageNum_adposting - 1), $queryString_adposting); ?>" class="navigationbuttons">PREVIOUS</a> <a href="<?php printf("%s?pageNum_adposting=%d%s", $currentPage, min($totalPages_adposting, $pageNum_adposting + 1), $queryString_adposting); ?>" class="navigationbuttons">NEXT</a> <a href="<?php printf("%s?pageNum_adposting=%d%s", $currentPage, $totalPages_adposting, $queryString_adposting); ?>" class="navigationbuttons">LAST</a></div>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_adposting == 0) { // Show if recordset empty ?>
<div align="center">Sorry! No Post Found Here</div>
  <?php } // Show if recordset empty ?></td>
        <?php
include($theme_path."".$row_setting['theme']."/rightmenu.php"); 
?>
      </tr>
    </table><?php
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

mysqli_free_result($blogcates);

mysqli_free_result($pages);

mysqli_free_result($theme);

mysqli_free_result($parts);

mysqli_free_result($adposting);

mysqli_free_result($links);

mysqli_free_result($featuredadposts);

mysqli_free_result($members);
?>