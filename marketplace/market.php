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
                       GetSQLValueString($_POST['comment'], "text"),
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
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'marketplace'";
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


$colname_market = "-1";
if (isset($_GET['marketid'])) {
  $colname_market = $_GET['marketid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_market = sprintf("SELECT * FROM market WHERE marketid = %s AND status = 'published'", GetSQLValueString($colname_market, "int"));
$market = mysqli_query(dbconnect(),$query_market) or die(mysqli_connect_error());
$row_market = mysqli_fetch_assoc($market);
$totalRows_market = mysqli_num_rows($market);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_links = "SELECT * FROM friendlinks";
$links = mysqli_query(dbconnect(),$query_links) or die(mysqli_connect_error());
$row_links = mysqli_fetch_assoc($links);
$totalRows_links = mysqli_num_rows($links);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_featuredadposts = "SELECT * FROM market WHERE position = 'featured' ORDER BY marketid DESC";
$featuredadposts = mysqli_query(dbconnect(),$query_featuredadposts) or die(mysqli_connect_error());
$row_featuredadposts = mysqli_fetch_assoc($featuredadposts);
$totalRows_featuredadposts = mysqli_num_rows($featuredadposts);

$colname_Comments = "-1";
if (isset($_GET['marketid'])) {
  $colname_Comments = $_GET['marketid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_Comments = sprintf("SELECT * FROM comments WHERE id LIKE %s AND selecttopic = 'marketplace' AND status = 'published' ORDER BY commentid DESC", GetSQLValueString("%" . $colname_Comments, "text"));
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

$queryString_market = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_market") == false && 
        stristr($param, "totalRows_market") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_market = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_market = sprintf("&totalRows_market=%d%s", $totalRows_market, $queryString_market);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "marketplace"))
	  {
?>
<!-- index start -->
<?php

$getviews = $_GET["marketid"];
$views_query = mysqli_query(dbconnect(),"SELECT * FROM market WHERE marketid = $getviews");
$views = mysqli_fetch_assoc($views_query);

if(isset($_GET["marketid"]))
{
	$addview = $views['views']+1;
	$viewsupdate_query = mysqli_query(dbconnect(),"UPDATE market SET views = $addview WHERE marketid = $getviews");
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
        <td valign="top"><?php if ($totalRows_market > 0) { // Show if recordset not empty ?>
  <div class="title"><?php echo $row_market['title']; ?></div>
     <table width="100%">
                  <tr>
                    <td width="1" valign="top"><a href="market.php?marketid=<?php echo $row_market['marketid']; ?>"><img src="images/market/<?php echo $row_market['imageurl']; ?>" width="120" height="120" border="0"></a></td>
                    <td valign="top"><p><?php echo $row_market['description']; ?></p>
                      <p><span class="textsmall"><a href="profile.php?users=<?php echo $row_market['owner']; ?>"><?php echo $row_market['owner']; ?></a> / <?php echo $row_market['date']; ?> / View : <?php echo $row_market['views']; ?> / Rating : <?php echo $row_market['rating']; ?> / <a href="category.php?catename=<?php echo $row_market['catename']; ?>"><?php echo $row_market['catename']; ?></a></span></p></td>
                    <td align="center" valign="top"><?php if ($totalRows_members > 0) { // Show if recordset not empty ?>
                      <p><br>
                        <a href="message.php?users=<?php echo $row_market['owner']; ?>&marketid=<?php echo $row_market['marketid']; ?>" target="_blank" class="orangetitle">Contact Seller</a></p>
                      <?php } // Show if recordset not empty ?>
                      <?php if ($totalRows_members == 0) { // Show if recordset empty ?>
                        Please Login
                        <?php } // Show if recordset empty ?>
                      <p><?php echo $row_market['price']; ?></p></td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td colspan="2" valign="top">&nbsp;</td>
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
<table class="tables">
            <tr>
              <td><div class="title">Comments : </div>
                <?php if ($totalRows_Comments > 0) { // Show if recordset not empty ?>
                <?php do { ?>
                <table class="tables">
                  <tr>
                    <td ><?php echo $row_Comments['name']; ?> / <?php echo $row_Comments['email']; ?> / <a href="<?php echo $row_Comments['website']; ?>"><?php echo $row_Comments['website']; ?> <span class="texts">
                      <input name="status" type="hidden" id="status" value="<?php echo $row_Comments['status']; ?>">
                    </span></a></td>
                  </tr>
                  <tr>
                    <td class="texts"><table>
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
                  <table class="tables">
                      <tr>
                        <td width="1" height="32" class="texts"><strong>NAME:</strong></td>
                        <td rowspan="3"><?php if ($totalRows_members == 0) { // Show if recordset empty ?>
                          <table>
                            <tr>
                              <td ><input name="name" type="text" class="form" id="name"></td>
                            </tr>
                            <tr>
                              <td><input name="email" type="text" class="form" id="email"></td>
                            </tr>
                            <tr>
                              <td><input name="website" type="text" class="form" id="website"></td>
                            </tr>
                          </table>
                          <?php } // Show if recordset empty ?>
                        <?php if ($totalRows_members > 0) { // Show if recordset not empty ?>
                          <table>
                            <tr>
                              <td><input name="name" type="text" class="form" id="name" value="<?php echo $row_members['users']; ?>" readonly></td>
                            </tr>
                            <tr>
                              <td><input name="email" type="text" class="form" id="email" value="<?php echo $row_members['email']; ?>" readonly></td>
                            </tr>
                            <tr>
                              <td><input name="website" type="text" class="form" id="website"></td>
                            </tr>
                          </table>
                        <?php } // Show if recordset not empty ?></td>
                      </tr>
                      <tr class="texts">
                        <td height="32"><strong>EMAIL:</strong></td>
                      </tr>
                      <tr class="texts">
                        <td height="32"><strong>WEBSITE:</strong></td>
                      </tr>
                      <tr>
                        <td valign="top"><strong>COMMENT:</strong></td>
                        <td><div style="width:400px;"><textarea name="comment" cols="45" rows="5" class="formtextarea" id="comment"></textarea></div></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><input name="button" type="submit" class="button" id="button" value="Submit">
                            <input name="id" type="hidden" id="id" value="<?php echo $row_market['marketid']; ?>">
                        <input name="status" type="hidden" id="status" value="pending">
                        <input name="selecttopic" type="hidden" id="selecttopic" value="<?php echo $row_setting['selecttopic']; ?>"></td>
                      </tr>
                    </table>
<input type="hidden" name="MM_insert" value="commentform">
                </form></td>
            </tr>
          </table>
<?php if ($totalRows_market == 0) { // Show if recordset empty ?>
  <table class="tables">
    <tr>
      <td height="36"><div align="center">Sorry! No Post Found Here</div></td>
      </tr>
  </table>
  <?php } // Show if recordset empty ?>
 </td>
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

mysqli_free_result($market);

mysqli_free_result($links);

mysqli_free_result($featuredadposts);

mysqli_free_result($Comments);

mysqli_free_result($members);
?>