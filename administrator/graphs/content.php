<?php require_once('../includes/rayicecms.php'); ?>
<?php
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);
?>
<?php
if($row_setting['selecttopic'] == 'blog')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM blog ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['blogid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'searchengine')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
$sql="SELECT * FROM searchengine ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['searchengineid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'portfolio')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM portfolio ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['portfolioid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'adposting')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM adposting ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['adpostingid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'videostream')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM video ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['videoid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'doctors')
{   
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
//get data from database
$sql="SELECT * FROM doctors ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['doctorsid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'imagegallery')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
//get data from database
$sql="SELECT * FROM image ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['imageid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'marketplace')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
//get data from database
$sql="SELECT * FROM market ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['marketid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'tutorials')
{
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
//get data from database
$sql="SELECT * FROM tutorials ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['tutorialsid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'productpublisher')
{    
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
//get data from database
$sql="SELECT * FROM productpublisher ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
$row = mysqli_fetch_assoc($result);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="orangetitle" style="background-image:url(images/spinner-bg.gif);">
<td width="32" align="center">ID</td>
<td colspan="3">CONTENT TITLE</td>
</tr>
<?php do { ?>
<tr>
<td height="11" align="center" class="smallbut"><div class="textsmallred"><?php echo $row['productpublisherid']; ?></div></td>
<td bgcolor="#FBFEFF" class="borderorangetable"><div class="texts"><?php echo $row['title']; ?></div></td>
</tr>
<?php } while ($row = mysqli_fetch_assoc($result)); ?>
</table>
<?php
}
?>