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

$maxRows_video = 10;
$pageNum_video = 0;
if (isset($_GET['pageNum_video'])) {
  $pageNum_video = $_GET['pageNum_video'];
}
$startRow_video = $pageNum_video * $maxRows_video;

$colname_video = "-1";
if (isset($_GET['catename'])) {
  $colname_video = $_GET['catename'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_video = sprintf("SELECT * FROM video WHERE catename = %s AND status = 'published' ORDER BY videoid DESC", GetSQLValueString($colname_video, "text"));
$query_limit_video = sprintf("%s LIMIT %d, %d", $query_video, $startRow_video, $maxRows_video);
$video = mysqli_query(dbconnect(),$query_limit_video) or die(mysqli_connect_error());
$row_video = mysqli_fetch_assoc($video);

if (isset($_GET['totalRows_video'])) {
  $totalRows_video = $_GET['totalRows_video'];
} else {
  $all_video = mysqli_query(dbconnect(),$query_video);
  $totalRows_video = mysqli_num_rows($all_video);
}
$totalPages_video = ceil($totalRows_video/$maxRows_video)-1;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'videostream'";
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

$queryString_video = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_video") == false && 
        stristr($param, "totalRows_video") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_video = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_video = sprintf("&totalRows_video=%d%s", $totalRows_video, $queryString_video);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "videostream"))
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
		<div class="title"><?php echo $row_video['catename']; ?></div>
		<?php if ($totalRows_video > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <table>
                <tr>
                  <td colspan="2"><a href="video.php?videoid=<?php echo $row_video['videoid']; ?>" class="posttitle"><?php echo $row_video['title']; ?></a></td>
                </tr>
                <tr>
                  <td rowspan="2"><a href="video.php?videoid=<?php echo $row_video['videoid']; ?>"><img src="images/videoimages/<?php echo $row_video['imageurl']; ?>" width="115" height="97" border="0"></a></td>
                </tr>
                <tr>
                  <td valign="top" class="texts"><p><?php echo $row_video['description']; ?></p>
                    <p><span class="textsmall"><?php echo $row_video['date']; ?> / <a href="category.php?catename=<?php echo $row_video['catename']; ?>"><?php echo $row_video['catename']; ?></a></span></p></td>
                </tr>
              </table>
              
              <?php } while ($row_video = mysqli_fetch_assoc($video)); ?>
              <div align="center"><a href="<?php printf("%s?pageNum_video=%d%s", $currentPage, 0, $queryString_video); ?>" class="navigationbuttons">FIRST</a> <a href="<?php printf("%s?pageNum_video=%d%s", $currentPage, max(0, $pageNum_video - 1), $queryString_video); ?>" class="navigationbuttons">PREVIOUS</a> <a href="<?php printf("%s?pageNum_video=%d%s", $currentPage, min($totalPages_video, $pageNum_video + 1), $queryString_video); ?>" class="navigationbuttons">NEXT</a> <a href="<?php printf("%s?pageNum_video=%d%s", $currentPage, $totalPages_video, $queryString_video); ?>" class="navigationbuttons">LAST</a></div>
            <?php } // Show if recordset not empty ?>
            <?php if ($totalRows_video == 0) { // Show if recordset empty ?>
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

mysqli_free_result($video);

mysqli_free_result($blogcates);

mysqli_free_result($members);
?>