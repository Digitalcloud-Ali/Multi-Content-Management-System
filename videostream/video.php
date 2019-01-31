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
                       GetSQLValueString($_POST['vcomment'], "text"),
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

$colname_video = "-1";
if (isset($_GET['videoid'])) {
  $colname_video = $_GET['videoid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_video = sprintf("SELECT * FROM video WHERE videoid = %s", GetSQLValueString($colname_video, "int"));
$video = mysqli_query(dbconnect(),$query_video) or die(mysqli_connect_error());
$row_video = mysqli_fetch_assoc($video);
$totalRows_video = mysqli_num_rows($video);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'videostream'";
$blogcates = mysqli_query(dbconnect(),$query_blogcates) or die(mysqli_connect_error());
$row_blogcates = mysqli_fetch_assoc($blogcates);
$totalRows_blogcates = mysqli_num_rows($blogcates);

$colname_Comments = "-1";
if (isset($_GET['videoid'])) {
  $colname_Comments = $_GET['videoid'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_Comments = sprintf("SELECT * FROM comments WHERE id LIKE %s AND selecttopic = 'videostream' AND status = 'published'
ORDER BY commentid DESC", GetSQLValueString("%" . $colname_Comments, "text"));
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
<?php

$getviews = $_GET["videoid"];
$views_query = mysqli_query(dbconnect(),"SELECT * FROM video WHERE videoid = $getviews");
$views = mysqli_fetch_assoc($views_query);

if(isset($_GET["videoid"]))
{
	$addview = $views['views']+1;
	$viewsupdate_query = mysqli_query(dbconnect(),"UPDATE video SET views = $addview WHERE videoid = $getviews");
}

?>
<?php include($theme_path."".$row_setting['theme']."/topdocs.php"); ?>
<title><?php echo $row_setting['title']; ?></title>
<meta name="keywords" content="<?php echo $row_setting['metakey']; ?>">
<meta name="description" content="<?php echo $row_setting['metadesc']; ?>">
<link href="/themes/<?php echo $row_setting['theme']; ?>/multicms.css" rel="stylesheet" type="text/css">
<?php $favicon ?>
<script type="text/javascript">
function MM_CheckFlashVersion(reqVerStr,msg){
  with(navigator){
    var isIE  = (appVersion.indexOf("MSIE") != -1 && userAgent.indexOf("Opera") == -1);
    var isWin = (appVersion.toLowerCase().indexOf("win") != -1);
    if (!isIE || !isWin){  
      var flashVer = -1;
      if (plugins && plugins.length > 0){
        var desc = plugins["Shockwave Flash"] ? plugins["Shockwave Flash"].description : "";
        desc = plugins["Shockwave Flash 2.0"] ? plugins["Shockwave Flash 2.0"].description : desc;
        if (desc == "") flashVer = -1;
        else{
          var descArr = desc.split(" ");
          var tempArrMajor = descArr[2].split(".");
          var verMajor = tempArrMajor[0];
          var tempArrMinor = (descArr[3] != "") ? descArr[3].split("r") : descArr[4].split("r");
          var verMinor = (tempArrMinor[1] > 0) ? tempArrMinor[1] : 0;
          flashVer =  parseFloat(verMajor + "." + verMinor);
        }
      }
      // WebTV has Flash Player 4 or lower -- too low for video
      else if (userAgent.toLowerCase().indexOf("webtv") != -1) flashVer = 4.0;

      var verArr = reqVerStr.split(",");
      var reqVer = parseFloat(verArr[0] + "." + verArr[2]);
  
      if (flashVer < reqVer){
        if (confirm(msg))
          window.location = "http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash";
      }
    }
  } 
}
</script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body onLoad="MM_CheckFlashVersion('7,0,0,0','Content on this page requires a newer version of Adobe Flash Player. Do you want to download it now?');">
<?php
if($row_setting['onlinestatus'] == "yes")
{
?>
<!-- HEADER INCLUDED -->
<?php include($theme_path."".$row_setting['theme']."/header.php"); ?><table class="mainbody">
      <tr>
        <?php include($theme_path."".$row_setting['theme']."/leftmenu.php"); ?> 
        <td><?php if ($totalRows_video > 0) { // Show if recordset not empty ?>
              <div class="title"><?php echo $row_video['title']; ?></div><table class="tables">
               
                <tr>
                  <td height="28"><p><span class="textsmall"><?php echo $row_video['date']; ?> / <a href="category.php?catename=<?php echo $row_video['catename']; ?>"><?php echo $row_video['catename']; ?></a></span></p></td>
                </tr>
                <tr>
                  <td><?php
	  if(($row_video['status'] == "published") && ($row_video['videotype'] == "video"))
	  {
?>
                            <div>
                              <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0','width','100%','height','496','id','FLVPlayer','src','FLVPlayer_Progressive','flashvars','&MM_ComponentVersion=1&skinName=Clear_Skin_3&streamName=videos/<?php echo $row_video['videourl']; ?>&autoPlay=false&autoRewind=false','quality','high','scale','noscale','name','FLVPlayer','salign','lt','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','FLVPlayer_Progressive' ); //end AC code
                      </script>
                              <noscript>
                              <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="100%" height="496" id="FLVPlayer">
                                <param name="movie" value="FLVPlayer_Progressive.swf" />
                                <param name="salign" value="lt" />
                                <param name="quality" value="high" />
                                <param name="scale" value="noscale" />
                                <param name="FlashVars" value="&MM_ComponentVersion=1&skinName=Clear_Skin_3&streamName=videos/<?php echo $row_video['videourl']; ?>&autoPlay=false&autoRewind=false" />
                                <embed src="FLVPlayer_Progressive.swf" flashvars="&MM_ComponentVersion=1&skinName=Clear_Skin_3&streamName=videos/<?php echo $row_video['videourl']; ?>&autoPlay=false&autoRewind=false" quality="high" scale="noscale" width="100%" height="496" name="FLVPlayer" salign="LT" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" />                                
</object>
                              </noscript>
                          </div>
                      <?php
}
?>
                      <?php
if(($row_video['status'] == "published") && ($row_video['videotype'] == "embed"))
{
?>
                      <div align="center"><?php echo $row_video['embedcode']; ?></div>
                    <?php
}
?>
                  </td>
                </tr>
                <tr>
                  <td><table class="tables">
                      <tr>
                        <td><a href="video.php?videoid=<?php echo $row_video['videoid']; ?>"><img src="images/videoimages/<?php echo $row_video['imageurl']; ?>" width="115" height="97" border="0"></a></td>
                        <td><?php echo $row_video['description']; ?></td>
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

            <table class="tables">
                <tr>
                  <td><div class="title">COMMENTS:</div><br />
                    <?php if ($totalRows_Comments > 0) { // Show if recordset not empty ?>
            <?php do { ?>
                        <table class="tables">
                          <tr>
                          <td class="textsmall"><?php echo $row_Comments['name']; ?> / <?php echo $row_Comments['email']; ?> / <a href="<?php echo $row_Comments['website']; ?>"><?php echo $row_Comments['website']; ?> <span class="texts">
                              <input name="status" type="hidden" id="status" value="<?php echo $row_Comments['status']; ?>">
                            </span></a></td>
                          </tr>
                          <tr>
                            <td class="texts"><table width="100%" border="0" cellpadding="8" cellspacing="4">
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
                      <?php } // Show if recordset not empty ?><form action="<?php echo $editFormAction; ?>" method="POST" name="commentform" target="_blank" id="commentform">
                    
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
                        <td><table width="100%">
                          <tr>
                            <td><div style="width:400px;">
                              <textarea name="comment" cols="45" rows="5" class="formtextarea" id="comment"></textarea>
                            </div></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><input name="button" type="submit" class="button" id="button" value="Submit">
                            <input name="id" type="hidden" id="id" value="<?php echo $row_video['videoid']; ?>">
                        <input name="status" type="hidden" id="status" value="pending">
                        <input name="selecttopic" type="hidden" id="selecttopic" value="<?php echo $row_setting['selecttopic']; ?>"></td>
                      </tr>
                    </table>
                                    <input type="hidden" name="MM_insert" value="commentform">
                  </form>
                  </td>
                </tr>
          </table>
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

mysqli_free_result($Comments);

mysqli_free_result($members);
?>