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
if (isset($_GET['title'])) {
  $colname_productpublisher = $_GET['title'];
}
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_productpublisher = sprintf("SELECT * FROM productpublisher WHERE title LIKE %s AND status = 'published' ORDER BY productpublisherid DESC", GetSQLValueString("%" . $colname_productpublisher . "%", "text"));
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
<title>Contact us</title>
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

mysqli_free_result($productpublisher);

mysqli_free_result($links);

mysqli_free_result($featuredadposts);

mysqli_free_result($members);
?>