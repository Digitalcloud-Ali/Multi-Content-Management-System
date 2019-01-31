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
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'custom'";
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
$query_parts = "SELECT * FROM parts";
$parts = mysqli_query(dbconnect(),$query_parts) or die(mysqli_connect_error());
$row_parts = mysqli_fetch_assoc($parts);
$totalRows_parts = mysqli_num_rows($parts);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_theme = "SELECT * FROM themes";
$theme = mysqli_query(dbconnect(),$query_theme) or die(mysqli_connect_error());
$row_theme = mysqli_fetch_assoc($theme);
$totalRows_theme = mysqli_num_rows($theme);

$maxRows_custom = 10;
$pageNum_custom = 0;
if (isset($_GET['pageNum_custom'])) {
  $pageNum_custom = $_GET['pageNum_custom'];
}
$startRow_custom = $pageNum_custom * $maxRows_custom;

$colname_custom = "-1";
if (isset($_GET['catename'])) {
  $colname_custom = $_GET['catename'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_custom = sprintf("SELECT * FROM custom WHERE catename = %s AND status = 'published'  ORDER BY customid DESC", GetSQLValueString($colname_custom, "text"));
$query_limit_custom = sprintf("%s LIMIT %d, %d", $query_custom, $startRow_custom, $maxRows_custom);
$custom = mysqli_query(dbconnect(),$query_limit_custom) or die(mysqli_connect_error());
$row_custom = mysqli_fetch_assoc($custom);

if (isset($_GET['totalRows_custom'])) {
  $totalRows_custom = $_GET['totalRows_custom'];
} else {
  $all_custom = mysqli_query(dbconnect(),$query_custom);
  $totalRows_custom = mysqli_num_rows($all_custom);
}
$totalPages_custom = ceil($totalRows_custom/$maxRows_custom)-1;

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

$queryString_custom = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_custom") == false && 
        stristr($param, "totalRows_custom") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_custom = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_custom = sprintf("&totalRows_custom=%d%s", $totalRows_custom, $queryString_custom);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "custom"))
	  {
?>
<!-- index start -->
<?php include($theme_path."".$row_setting['theme']."/topdocs.php"); ?>
<title><?php echo $row_custom['catename']; ?></title>
<meta name="keywords" content="<?php echo $row_custom['catename']; ?>">
<meta name="description" content="<?php echo $row_custom['catename']; ?>">
<link href="/themes/<?php echo $row_setting['theme']; ?>/multicms.css" rel="stylesheet" type="text/css">
<?php $favicon ?>
<?php include($theme_path."".$row_setting['theme']."/botdocs.php"); ?>
<?php
if($row_setting['onlinestatus'] == "yes")
{
?>
<?php include($theme_path."".$row_setting['theme']."/header.php"); ?>

<table class="mainbody">
      <tr>
        <?php include($theme_path."".$row_setting['theme']."/leftmenu.php"); ?>
        <td valign="top">
		<div class="title"><?php echo $row_custom['catename']; ?></div>
                <div style="padding-bottom:5px;"></div>

		<?php if ($totalRows_custom > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <table class="tables">
                <tr>
                  <td class="orangetitle" style="padding:4px; border:1px;border-style:solid; border-color:#CCCCCC;"><div align="center"><?php echo $row_custom['customid']; ?></div></td>
                    <td><a href="custom.php?customid=<?php echo $row_custom['customid']; ?>" class="posttitle"><?php echo $row_custom['title']; ?></a></td>
                  </tr>
                <tr>
                  <td colspan="2"><?php echo $row_custom['users']; ?> / <?php echo $row_custom['dates']; ?> / <a href="category.php?catename=<?php echo $row_custom['catename']; ?>"><?php echo $row_custom['catename']; ?></a></td>
                  </tr>
                <tr>
                  <td colspan="2"><img src="images/post/<?php echo $row_custom['photo']; ?>" width="200" height="200" align="left" style="padding:16px;" /> <?php echo substr($row_custom['description'],0,1400); ?>.... <a href="custom.php?customid=<?php echo $row_custom['customid']; ?>">Read More</a></td>
                  </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                  </tr>
              </table>
              
              <?php } while ($row_custom = mysqli_fetch_assoc($custom)); ?>
              <div align="center"><a href="<?php printf("%s?pageNum_custom=%d%s", $currentPage, 0, $queryString_custom); ?>" class="navigationbuttons">FIRST</a> <a href="<?php printf("%s?pageNum_custom=%d%s", $currentPage, max(0, $pageNum_custom - 1), $queryString_custom); ?>" class="navigationbuttons">PREVIOUS</a> <a href="<?php printf("%s?pageNum_custom=%d%s", $currentPage, min($totalPages_custom, $pageNum_custom + 1), $queryString_custom); ?>" class="navigationbuttons">NEXT</a> <a href="<?php printf("%s?pageNum_custom=%d%s", $currentPage, $totalPages_custom, $queryString_custom); ?>" class="navigationbuttons">LAST</a></div>
              <?php } // Show if recordset not empty ?>
          <?php if ($totalRows_custom == 0) { // Show if recordset empty ?>
           <div align="center">Sorry! No Post Found Under This Category!</div>
          <?php } // Show if recordset empty ?></td>
        <?php
include($theme_path."".$row_setting['theme']."/rightmenu.php"); 
?>  
      </tr>
    </table>
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

mysqli_free_result($blogcates);

mysqli_free_result($pages);

mysqli_free_result($theme);

mysqli_free_result($parts);

mysqli_free_result($custom);

mysqli_free_result($links);

mysqli_free_result($members);
?>