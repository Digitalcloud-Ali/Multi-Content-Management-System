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
?><?php
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "adpost")) {
  $insertSQL = sprintf("INSERT INTO contacts (name, email, phone, subject, message) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['subject'], "text"),
                       GetSQLValueString($_POST['message'], "text"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$insertSQL) or die(mysqli_connect_error());

  $insertGoTo = "contact.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "adpost")) {
	

   // UPLOAD CODE BY SYED RAZA ALI START //
   	$allowed_filetypes = array('.jpg','.gif','.bmp','.png');
  	$no_ID = $row_rsT['videoid'];
	$max_filesize = 5250000;
  	$upload_path = 'images/videoimages/';
	if($_FILES['imageurl']['size'] == 0 || empty($_FILES['imageurl']['name']))
		{
   		   $filename = $row_setting['missingimage'];
		}
	else
		{
   		   $filename = $no_ID."".str_replace(' ', '_', $_FILES['imageurl']['name']);
		   $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
 	 	   if(!in_array($ext,$allowed_filetypes))
   		   {die('The file you attempted to upload is not allowed.');} 
   		   if(filesize($_FILES['imageurl']['tmp_name']) > $max_filesize)
           {die('The file you attempted to upload is too large.');}
           if(!is_writable($upload_path))
           {die('You cannot upload to the specified directory, please CHMOD it to 777.');}   
           if(move_uploaded_file($_FILES['imageurl']['tmp_name'],$upload_path . $filename))
           {echo 'Your file upload was successful';} 
           else{echo'There was an error during the file upload.  Please try again.';} 
		}
   // UPLOAD CODE BY SYED RAZA ALI END //

   // UPLOAD CODE BY SYED RAZA ALI START //
   	$allowed_filetypes1 = array('.FLV','.mp3','.mp4','.3gp','.avi');
  	$no_IDD = $row_rsT['videoid'];
	$max_filesize1 = 525000000;
  	$upload_path1 = 'videos/';
	if($_FILES['videourl']['size'] == 0 || empty($_FILES['videourl']['name']))
		{
   		   $filename1 = $row_rsT['videourl'];
		}
	else
		{
   		   $filename1 = $no_IDD."".str_replace(' ', '_', $_FILES['videourl']['name']);
		   $ext = substr($filename1, strpos($filename1,'.'), strlen($filename1)-1); // Get the extension from the filename.
 	 	   if(!in_array($ext1,$allowed_filetypes1))
   		   {die('The file you attempted to upload is not allowed.');} 
   		   if(filesize($_FILES['videourl']['tmp_name']) > $max_filesize1)
           {die('The file you attempted to upload is too large.');}
           if(!is_writable($upload_path1))
           {die('You cannot upload to the specified directory, please CHMOD it to 777.');}   
           if(move_uploaded_file($_FILES['videourl']['tmp_name'],$upload_path1 . $filename1))
           {echo 'Your file upload was successful';} 
           else{echo'There was an error during the file upload.  Please try again.';} 
		}
   // UPLOAD CODE BY SYED RAZA ALI END //

  $insertSQL = sprintf("INSERT INTO video (title, `description`, imageurl, videourl, embedcode, `date`, videotype, catename, status, rating, views, `position`, users) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($filename, "text"),
                       GetSQLValueString($filename1, "text"),
                       GetSQLValueString($_POST['embedcode'], "text"),
                       GetSQLValueString($_POST['date'], "text"),
                       GetSQLValueString($_POST['videotype'], "text"),
                       GetSQLValueString($_POST['catename'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['rating'], "text"),
                       GetSQLValueString($_POST['views'], "text"),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString($_POST['users'], "text"));

  mysqli_select_db(dbconnect(),$database_rayicecms);
  $Result1 = mysqli_query(dbconnect(),$insertSQL) or die(mysqli_connect_error());

  $insertGoTo = "myvideos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
$query_blogcates = "SELECT * FROM categories WHERE selecttopic = 'videostream'";
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


$maxRows_video = 10;
$pageNum_video = 0;
if (isset($_GET['pageNum_video'])) {
  $pageNum_video = $_GET['pageNum_video'];
}
$startRow_video = $pageNum_video * $maxRows_video;

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_video = "SELECT * FROM video WHERE status = 'published' ORDER BY videoid DESC";
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
$query_links = "SELECT * FROM friendlinks";
$links = mysqli_query(dbconnect(),$query_links) or die(mysqli_connect_error());
$row_links = mysqli_fetch_assoc($links);
$totalRows_links = mysqli_num_rows($links);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_featuredvideos = "SELECT * FROM video WHERE position = 'featured' ORDER BY videoid DESC";
$featuredvideos = mysqli_query(dbconnect(),$query_featuredvideos) or die(mysqli_connect_error());
$row_featuredvideos = mysqli_fetch_assoc($featuredvideos);
$totalRows_featuredvideos = mysqli_num_rows($featuredvideos);

mysqli_select_db(dbconnect(),$database_rayicecms);
$query_cates = "SELECT * FROM categories WHERE selecttopic = 'videostream'";
$cates = mysqli_query(dbconnect(),$query_cates) or die(mysqli_connect_error());
$row_cates = mysqli_fetch_assoc($cates);
$totalRows_cates = mysqli_num_rows($cates);

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
<title>Contact us</title>
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
        <td valign="top">
        <table class="tables">
          <tr>
                <td valign="top"><div class="title">Contact Us</div>
          <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="adpost" id="adpost">
            <table>
              <tr>
                <td class="texts"><strong>NAME:*</strong></td>
                <td><input name="name" type="text" class="formsim" id="name" /></td>
              </tr>
              <tr>
                <td class="texts"><strong>EMAIL:*</strong></td>
                <td><input name="email" type="text" class="formsim" id="email" /></td>
              </tr>
              <tr>
                <td class="texts"><strong>PHONE:*</strong></td>
                <td><input name="phone" type="text" class="formsim" id="phone" /></td>
              </tr>
              <tr>
                <td class="texts"><strong>SUBJECT:*</strong></td>
                <td><input name="subject" type="text" class="formsim" id="subject" /></td>
              </tr>
              <tr>
                <td valign="top" class="texts"><strong>MESSAGE:*</strong></td>
                <td><textarea name="message" cols="55" rows="5" class="formtextarea" id="message"></textarea></td>
              </tr>
              <tr>
                <td ></td>
                <td><input name="button2" type="submit" class="button" id="button2" value="Send" /></td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="adpost" />
          </form>
        <script language="JavaScript" type="text/javascript">

  var frmvalidator  = new Validator("adpost");
  frmvalidator.addValidation("subject","req","Please Enter Subject");
  frmvalidator.addValidation("subject","maxlen=100","Max length for Subject is 100");
  
  frmvalidator.addValidation("message","req","Please Enter Message");
  
  frmvalidator.addValidation("name","req","Please Enter Full Name");
  frmvalidator.addValidation("name","maxlen=30","Max length for Full Name is 30");
      
  frmvalidator.addValidation("email","maxlen=100","Max length for email is 100");
  frmvalidator.addValidation("email","req","Please Enter Your Email");
  frmvalidator.addValidation("email","email");
  
  frmvalidator.addValidation("phone","maxlen=20","Max length for phone is 20");
  frmvalidator.addValidation("phone","req","Please Enter Your Phone Number");
  frmvalidator.addValidation("phone","numeric","Please Enter Correct Phone Number");

</script></td>
                  
          </tr>
        </table>
        
            <?php
$widgetstatus_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '12'");
$widgetstatus = mysqli_fetch_assoc($widgetstatus_query);

$widgets_query = mysqli_query(dbconnect(),"SELECT * FROM widgets WHERE position = 'contact'");
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

</td>
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

mysqli_free_result($video);

mysqli_free_result($links);

mysqli_free_result($featuredvideos);

mysqli_free_result($cates);

mysqli_free_result($members);
?>