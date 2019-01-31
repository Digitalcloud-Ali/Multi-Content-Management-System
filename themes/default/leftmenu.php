<?php
$leftmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '7'");
$leftmenu = mysqli_fetch_assoc($leftmenu_query);

$rightmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '8'");
$rightmenu = mysqli_fetch_assoc($rightmenu_query);

if(($leftmenu['status'] == 'active') && ($rightmenu['status'] == 'disabled'))
{
?>
<td width="227" height="415" valign="top">
<?php
include($theme_path."".$row_setting['theme']."/sidepage.php"); 
?>

<?php
$settings_query = mysqli_query(dbconnect(),"SELECT * FROM settings");
$settings = mysqli_fetch_assoc($settings_query);

$adsmanagement_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '3'");
$adsmanagement = mysqli_fetch_assoc($adsmanagement_query);
if($adsmanagement['status'] == 'active')
{
?>
	<?php
	$lrads_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '15'");
	$lrads = mysqli_fetch_assoc($lrads_query);
	if($adsmanagement['status'] == 'active')
	{
	?>
		<div class="title">ADVERTISEMENT</div>
		<div align="center"><?php echo $settings['ad1']; ?></div>
	<?php
	}
	?>
<?php
}
?>
</td>
<?php
}
?>