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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "commentform")) {
  $insertSQL = sprintf("INSERT INTO comments (`comment`, name, email, website, status, selecttopic, id) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['website'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['selecttopic'], "text"),
                       GetSQLValueString($_POST['id'], "text"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$insertSQL) or die(mysqli_connect_error());

  $insertGoTo = "success.html";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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


$colname_adposting = "-1";
if (isset($_GET['adpostingid'])) {
  $colname_adposting = $_GET['adpostingid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_adposting = sprintf("SELECT * FROM adposting WHERE adpostingid = %s AND status = 'published'", GetSQLValueString($colname_adposting, "int"));
$adposting = mysqli_query(dbconnect(),$query_adposting) or die(mysqli_connect_error());
$row_adposting = mysqli_fetch_assoc($adposting);
$totalRows_adposting = mysqli_num_rows($adposting);

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

$colname_Comments = "-1";
if (isset($_GET['adpostingid'])) {
  $colname_Comments = $_GET['adpostingid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_Comments = sprintf("SELECT * FROM comments WHERE id LIKE %s AND selecttopic = 'adposting' AND status = 'published'", GetSQLValueString("%" . $colname_Comments, "text"));
$Comments = mysqli_query(dbconnect(),$query_Comments) or die(mysqli_connect_error());
$row_Comments = mysqli_fetch_assoc($Comments);
$totalRows_Comments = mysqli_num_rows($Comments);

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
<?php

$getviews = $_GET["adpostingid"];
$views_query = mysqli_query(dbconnect(),"SELECT * FROM adposting WHERE adpostingid = $getviews");
$views = mysqli_fetch_assoc($views_query);

if(isset($_GET["adpostingid"]))
{
	$addview = $views['views']+1;
	$viewsupdate_query = mysqli_query(dbconnect(),"UPDATE adposting SET views = $addview WHERE adpostingid = $getviews");
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
<?php include($theme_path."".$row_setting['theme']."/header.php"); ?><table class="mainbody">
      <tr>
        <?php include($theme_path."".$row_setting['theme']."/leftmenu.php"); ?>
        <td valign="top" bgcolor="#FFFFFF"><?php if ($totalRows_adposting > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td width="854" height="32"><table >
        <tr>
          <td height="164" valign="top"><table width="100%" height="44" border="0" cellpadding="0" cellspacing="0" class="headermenured">
            <tr>
              <td width="1">&nbsp;</td>
              <td>Recent AdsPost</td>
              </tr>
            </table>
            <table >
              <tr>
                <td height="42"><table width="100%" border="0" cellpadding="0" cellspacing="2">
                  <tr>
                    <td width="1" rowspan="2" valign="top"><a href="adposting.php?adpostingid=<?php echo $row_adposting['adpostingid']; ?>"><img src="images/adposting/<?php echo $row_adposting['imageurl']; ?>" width="115" height="92" border="0"></a></td>
                    <td bgcolor="#F9F9F9" class="posttitle"><a href="adposting.php?adpostingid=<?php echo $row_adposting['adpostingid']; ?>" class="title"><?php echo $row_adposting['title']; ?></a></td>
                    </tr>
                  <tr>
                    <td valign="top" bgcolor="#F9F9F9"><p><?php echo $row_adposting['description']; ?></p>
                      <p>&nbsp;<?php echo $row_adposting['adpostingurl']; ?></p>
                      <p><span class="textsmall"><?php echo $row_adposting['owner']; ?> / <?php echo $row_adposting['date']; ?> / View : <?php echo $row_adposting['views']; ?> / Rating : <?php echo $row_adposting['rating']; ?> / <?php echo $row_adposting['catename']; ?></span></p></td>
                    </tr>
                  <tr></tr>
                  </table></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?>
   
  <?php
$widgetstatus_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '12'");
$widgetstatus = mysqli_fetch_assoc($widgetstatus_query);

$widgets_query = mysqli_query(dbconnect(),"SELECT * FROM widgets WHERE position = 'content'");
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
<table >
            <tr>
              <td bgcolor="#F9F9F9"><span class="title">Comments : </span>
                <?php if ($totalRows_Comments > 0) { // Show if recordset not empty ?>
                <?php do { ?>
                <table width="100%" border="0" cellpadding="0" cellspacing="4">
                  <tr>
                    <td bgcolor="#F9F9F9" class="textsmall"><?php echo $row_Comments['name']; ?> / <?php echo $row_Comments['email']; ?> / <a href="<?php echo $row_Comments['website']; ?>"><?php echo $row_Comments['website']; ?> <span class="texts">
                      <input name="status" type="hidden" id="status" value="<?php echo $row_Comments['status']; ?>">
                    </span></a></td>
                  </tr>
                  <tr>
                    <td bgcolor="#F3F3F3" class="texts"><table width="100%" border="0" cellpadding="8" cellspacing="4">
                      <tr>
                        <td><?php echo $row_Comments['comment']; ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="texts">&nbsp;</td>
                  </tr>
                </table>
                <?php } while ($row_Comments = mysqli_fetch_assoc($Comments)); ?>
                <?php } // Show if recordset not empty ?>
                <form action="<?php echo $editFormAction; ?>" method="POST" name="commentform" target="_blank" id="commentform">
                  <table width="100%" border="0" cellpadding="0" cellspacing="4">
                    <tr>
                      <td width="10%"><strong>Name :</strong></td>
                      <td width="90%" rowspan="3"><?php if ($totalRows_members == 0) { // Show if recordset empty ?>
                          <table width="1" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="1"><input name="name" type="text" id="name"></td>
                            </tr>
                            <tr>
                              <td><input name="email" type="text" id="email"></td>
                            </tr>
                            <tr>
                              <td><input type="text" name="website" id="website"></td>
                            </tr>
                          </table>
                          <?php } // Show if recordset empty ?>
                        <?php if ($totalRows_members > 0) { // Show if recordset not empty ?>
                          <table width="147" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="139"><input name="name" type="text" id="name" value="<?php echo $row_members['users']; ?>" readonly></td>
                            </tr>
                            <tr>
                              <td><input name="email" type="text" id="email" value="<?php echo $row_members['email']; ?>" readonly></td>
                            </tr>
                            <tr>
                              <td><input type="text" name="website" id="website"></td>
                            </tr>
                          </table>
                          <?php } // Show if recordset not empty ?></td>
                    </tr>
                    <tr>
                      <td><strong>Email :</strong></td>
                      </tr>
                    <tr>
                      <td><strong>Website :</strong></td>
                      </tr>
                    <tr>
                      <td valign="top"><strong>Comment :</strong></td>
                      <td colspan="2"><textarea name="comments" id="comments" cols="46" rows="5"></textarea></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><input name="button2" type="submit" class="search" id="button2" value="Submit">
                        <input name="id" type="hidden" id="id" value="<?php echo $row_adposting['adpostingid']; ?>">
                        <input name="status" type="hidden" id="status" value="pending">
                        <input name="selecttopic" type="hidden" id="selecttopic" value="<?php echo $row_setting['selecttopic']; ?>"></td>
                    </tr>
                  </table>
<input type="hidden" name="MM_insert" value="commentform">
                </form></td>
            </tr>
          </table>
<?php if ($totalRows_adposting == 0) { // Show if recordset empty ?>
  <table >
    <tr>
      <td height="36"><div align="center">Sorry! No Post Found Here</div></td>
      </tr>
  </table>
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

mysqli_free_result($Comments);

mysqli_free_result($members);
?>