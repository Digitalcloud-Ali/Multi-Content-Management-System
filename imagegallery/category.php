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


mysqli_select_db(dbconnect(),$database_rayicecms);
$query_links = "SELECT * FROM friendlinks";
$links = mysqli_query(dbconnect(),$query_links) or die(mysqli_connect_error());
$row_links = mysqli_fetch_assoc($links);
$totalRows_links = mysqli_num_rows($links);

$maxRows_image = 10;
$pageNum_image = 0;
if (isset($_GET['pageNum_image'])) {
  $pageNum_image = $_GET['pageNum_image'];
}
$startRow_image = $pageNum_image * $maxRows_image;

$colname_image = "-1";
if (isset($_GET['catename'])) {
  $colname_image = $_GET['catename'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_image = sprintf("SELECT * FROM image WHERE catename = %s AND status = 'published' ORDER BY imageid DESC", GetSQLValueString($colname_image, "text"));
$query_limit_image = sprintf("%s LIMIT %d, %d", $query_image, $startRow_image, $maxRows_image);
$image = mysqli_query(dbconnect(),$query_limit_image) or die(mysqli_connect_error());
$row_image = mysqli_fetch_assoc($image);

if (isset($_GET['totalRows_image'])) {
  $totalRows_image = $_GET['totalRows_image'];
} else {
  $all_image = mysqli_query(dbconnect(),$query_image);
  $totalRows_image = mysqli_num_rows($all_image);
}
$totalPages_image = ceil($totalRows_image/$maxRows_image)-1;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'imagegallery'";
$blogcates = mysqli_query(dbconnect(),$query_blogcates) or die(mysqli_connect_error());
$row_blogcates = mysqli_fetch_assoc($blogcates);
$totalRows_blogcates = mysqli_num_rows($blogcates);

$colname_members = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_members = $_SESSION['MM_Username'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_members = sprintf("SELECT * FROM members WHERE users = %s", GetSQLValueString($colname_members, "text"));
$members = mysqli_query(dbconnect(),$query_members) or die(mysqli_connect_error());
$row_members = mysqli_fetch_assoc($members);
$totalRows_members = mysqli_num_rows($members);

$queryString_image = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_image") == false && 
        stristr($param, "totalRows_image") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_image = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_image = sprintf("&totalRows_image=%d%s", $totalRows_image, $queryString_image);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "imagegallery"))
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
        <td valign="top">
		<div class="title"><?php echo $row_image['catename']; ?></div>
		<?php if ($totalRows_image > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <table>
                <tr>
                  <td class="orangetitle" colspan="2"><a href="image.php?imageid=<?php echo $row_image['imageid']; ?>" class="posttitle"><?php echo $row_image['title']; ?></a></td>
                </tr>
                <tr>
                  <td rowspan="2"><a href="image.php?imageid=<?php echo $row_image['imageid']; ?>"><img src="images/gallery/<?php echo $row_image['imageurl']; ?>" width="115" height="97" border="0"></a></td>
                </tr>
                <tr>
                  <td valign="top" class="texts"><p><?php echo $row_image['description']; ?></p>
                      <p><span class="textsmall"><?php echo $row_image['users']; ?> / <?php echo $row_image['date']; ?> / <a href="category.php?catename=<?php echo $row_image['catename']; ?>"><?php echo $row_image['catename']; ?></a></span></p></td>
                </tr>
              </table><div align="center"><a href="<?php printf("%s?pageNum_image=%d%s", $currentPage, 0, $queryString_image); ?>" class="navigationbuttons">FIRST</a> <a href="<?php printf("%s?pageNum_image=%d%s", $currentPage, max(0, $pageNum_image - 1), $queryString_image); ?>" class="navigationbuttons">PREVIOUS</a> <a href="<?php printf("%s?pageNum_image=%d%s", $currentPage, min($totalPages_image, $pageNum_image + 1), $queryString_image); ?>" class="navigationbuttons">NEXT</a> <a href="<?php printf("%s?pageNum_image=%d%s", $currentPage, $totalPages_image, $queryString_image); ?>" class="navigationbuttons">LAST</a></div>
              <?php } while ($row_image = mysqli_fetch_assoc($image)); ?>
            <?php } // Show if recordset not empty ?>
            <?php if ($totalRows_image == 0) { // Show if recordset empty ?>
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

mysqli_free_result($pages);

mysqli_free_result($theme);

mysqli_free_result($parts);

mysqli_free_result($links);

mysqli_free_result($image);

mysqli_free_result($blogcates);

mysqli_free_result($members);
?>