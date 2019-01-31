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
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'productpublisher'";
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


$maxRows_productpublisher = 10;
$pageNum_productpublisher = 0;
if (isset($_GET['pageNum_productpublisher'])) {
  $pageNum_productpublisher = $_GET['pageNum_productpublisher'];
}
$startRow_productpublisher = $pageNum_productpublisher * $maxRows_productpublisher;

$colname_productpublisher = "-1";
if (isset($_GET['catename'])) {
  $colname_productpublisher = $_GET['catename'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_productpublisher = sprintf("SELECT * FROM productpublisher WHERE catename = %s AND status = 'published' ORDER BY productpublisherid DESC", GetSQLValueString($colname_productpublisher, "text"));
$query_limit_productpublisher = sprintf("%s LIMIT %d, %d", $query_productpublisher, $startRow_productpublisher, $maxRows_productpublisher);
$productpublisher = mysqli_query(dbconnect(),$query_limit_productpublisher) or die(mysqli_connect_error());
$row_productpublisher = mysqli_fetch_assoc($productpublisher);

if (isset($_GET['totalRows_productpublisher'])) {
  $totalRows_productpublisher = $_GET['totalRows_productpublisher'];
} else {
  $all_productpublisher = mysqli_query(dbconnect(),$query_productpublisher);
  $totalRows_productpublisher = mysqli_num_rows($all_productpublisher);
}
$totalPages_productpublisher = ceil($totalRows_productpublisher/$maxRows_productpublisher)-1;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_links = "SELECT * FROM friendlinks";
$links = mysqli_query(dbconnect(),$query_links) or die(mysqli_connect_error());
$row_links = mysqli_fetch_assoc($links);
$totalRows_links = mysqli_num_rows($links);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_featuredadposts = "SELECT * FROM productpublisher WHERE position = 'featured' ORDER BY productpublisherid DESC";
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

$queryString_productpublisher = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_productpublisher") == false && 
        stristr($param, "totalRows_productpublisher") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_productpublisher = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_productpublisher = sprintf("&totalRows_productpublisher=%d%s", $totalRows_productpublisher, $queryString_productpublisher);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "productpublisher"))
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
<?php include($theme_path."".$row_setting['theme']."/header.php"); ?><table class="mainbody">
      <tr>
        <?php include($theme_path."".$row_setting['theme']."/leftmenu.php"); ?>
        <td valign="top"><?php if ($totalRows_productpublisher > 0) { // Show if recordset not empty ?>
<div class="title"><?php echo $row_productpublisher['catename']; ?></div>
            <?php do { ?>
                  <table>
                    <tr>
                      <td width="1"><a href="productpublisher.php?productpublisherid=<?php echo $row_productpublisher['productpublisherid']; ?>"><img src="images/productpublisher/<?php echo $row_productpublisher['imageurl']; ?>" width="40" height="40" border="0"></a></td>
                      <td><a href="productpublisher.php?productpublisherid=<?php echo $row_productpublisher['productpublisherid']; ?>"  class="posttitle"><?php echo $row_productpublisher['title']; ?></a></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo $row_productpublisher['owner']; ?> / <?php echo $row_productpublisher['date']; ?> / View : <?php echo $row_productpublisher['views']; ?> / Rating : <?php echo $row_productpublisher['rating']; ?> / <?php echo $row_productpublisher['catename']; ?></td>
                    </tr>
                  </table><?php } while ($row_productpublisher = mysqli_fetch_assoc($productpublisher)); ?>
                      <div align="center"><a href="<?php printf("%s?pageNum_productpublisher=%d%s", $currentPage, 0, $queryString_productpublisher); ?>" class="navigationbuttons">FIRST</a> <a href="<?php printf("%s?pageNum_productpublisher=%d%s", $currentPage, max(0, $pageNum_productpublisher - 1), $queryString_productpublisher); ?>" class="navigationbuttons">PREVIOUS</a> <a href="<?php printf("%s?pageNum_productpublisher=%d%s", $currentPage, min($totalPages_productpublisher, $pageNum_productpublisher + 1), $queryString_productpublisher); ?>" class="navigationbuttons">NEXT</a> <a href="<?php printf("%s?pageNum_productpublisher=%d%s", $currentPage, $totalPages_productpublisher, $queryString_productpublisher); ?>" class="navigationbuttons">LAST</a></div>
                      <?php } // Show if recordset not empty ?>
                      <?php if ($totalRows_productpublisher == 0) { // Show if recordset empty ?>
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

mysqli_free_result($productpublisher);

mysqli_free_result($links);

mysqli_free_result($featuredadposts);

mysqli_free_result($members);
?>