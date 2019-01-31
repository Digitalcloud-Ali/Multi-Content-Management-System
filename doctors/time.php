<?php require_once('../includes/rayicecms.php'); ?>
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

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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
$query_pages = "SELECT * FROM pages WHERE `position` = 'leftmenu'";
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

$colname_members = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_members = $_SESSION['MM_Username'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_members = sprintf("SELECT * FROM members WHERE users = %s", GetSQLValueString($colname_members, "text"));
$members = mysqli_query(dbconnect(),$query_members) or die(mysqli_connect_error());
$row_members = mysqli_fetch_assoc($members);
$totalRows_members = mysqli_num_rows($members);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_pagesmenu = "SELECT * FROM pages WHERE `position` = 'menu' ORDER BY name ASC";
$pagesmenu = mysqli_query(dbconnect(),$query_pagesmenu) or die(mysqli_connect_error());
$row_pagesmenu = mysqli_fetch_assoc($pagesmenu);
$totalRows_pagesmenu = mysqli_num_rows($pagesmenu);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_doctors = "SELECT * FROM doctors";
$doctors = mysqli_query(dbconnect(),$query_doctors) or die(mysqli_connect_error());
$row_doctors = mysqli_fetch_assoc($doctors);
$totalRows_doctors = mysqli_num_rows($doctors);

$queryString_doctors = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_doctors") == false && 
        stristr($param, "totalRows_doctors") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_doctors = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_doctors = sprintf("&totalRows_doctors=%d%s", $totalRows_doctors, $queryString_doctors);
?>
<?php include("../configuration.php"); ?>
<!-- code start -->
<?php
	  if(($row_setting['installed'] == "yes") && ($row_setting['selecttopic'] == "doctors"))
	  {
?>
<!-- index start -->
<?php include($theme_path."".$row_setting['theme']."/topdocs.php"); ?>
<title><?php echo $row_doctors['title']; ?>- Appointment</title>
<link href="/themes/<?php echo $row_setting['theme']; ?>/multicms.css" rel="stylesheet" type="text/css">
<?php $favicon ?><?php include($theme_path."".$row_setting['theme']."/botdocs.php"); ?>
<?php
if($row_setting['onlinestatus'] == "yes")
{
?>
<!-- HEADER INCLUDED -->
<?php include($theme_path."".$row_setting['theme']."/header.php"); ?>
<table class="mainbody">
      <tr>
        <?php include($theme_path."".$row_setting['theme']."/leftmenu.php"); ?> 
        <td valign="top"><div class="title">Request Appointment</div>
          <table class="tables">
            <tr>
              <td><form name="form1" method="post" action="raq.php">
                <table>
                  <tr>
                    <td class="texts"><strong>FULL NAME:*</strong></td>
                    <td><input name="name" type="text" placeholder="your full name" class="form" id="name" min="2" max="20" required="required"></td>
                  </tr>
                  <tr>
                    <td class="texts"><strong>EMAIL:*</strong></td>
                    <td><input name="email" type="email" placeholder="enter email" class="form" id="email" min="2" max="50" required="required"></td>
                  </tr>
                  <tr>
                    <td class="texts"><strong>PHONE:*</strong></td>
                    <td><input name="phone" type="number" class="form" id="phone" placeholder="your phone number" required="required"></td>
                  </tr>
                  <tr>
                    <td class="texts"><strong>CITY:*</strong></td>
                    <td><input name="country" type="text" class="form" id="country" min="2" max="20" required="required"></td>
                  </tr>
                  <tr>
                    <td class="texts"><strong>APPOINTMENT FOR ?*</strong></td>
                    <td><input name="projecttitle" type="text" class="form" id="projecttitle" min="2" max="50" required="required"></td>
                  </tr>
                  <tr>
                    <td valign="top" class="texts"><strong>DETAIL:*</strong></td>
                    <td><textarea name="projectdetail" cols="45" rows="5" class="formtextarea" id="projectdetail" min="2" max=200" required="required"></textarea></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><input name="button" type="submit" class="button" id="button" value="Request"></td>
                  </tr>
                </table>
              </form></td>
            </tr>
          </table></td>
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
mysqli_free_result($members);

mysqli_free_result($pagesmenu);

mysqli_free_result($doctors);

mysqli_free_result($pages);

mysqli_free_result($theme);

mysqli_free_result($parts);

mysqli_free_result($setting);

mysqli_free_result($links);
?>