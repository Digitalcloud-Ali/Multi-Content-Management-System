<?php
$topmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '9'");
$topmenu = mysqli_fetch_assoc($topmenu_query);
if($topmenu['status'] == 'active')
{
?>


<?php
if($row_setting['selecttopic'] != 'searchengine')
{
	?>
<footer>
<div align="center">
<?php
$botmenu_query = mysqli_query(dbconnect(),"SELECT * FROM pages WHERE position = 'bottommenu'");
$botmenu = mysqli_fetch_assoc($botmenu_query);

$footermenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '4'");
$footermenu = mysqli_fetch_assoc($footermenu_query);
if($footermenu['status'] == 'active')
{
?>
<?php do { ?>
            <a href="pages.php?pageid=<?php echo $botmenu['pageid']; ?>" class="categorytext"><?php echo $botmenu['name']; ?></a> |
            <?php } while ($botmenu = mysqli_fetch_assoc($botmenu_query)); ?>
<?php
}
?>
<?php
$topmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '1'");
$topmenu = mysqli_fetch_assoc($topmenu_query);
if($topmenu['status'] == 'active')
{
?>
<a href="info.php">News / Update</a>
<?php
}
?>
<?php
$topmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '2'");
$topmenu = mysqli_fetch_assoc($topmenu_query);
if($topmenu['status'] == 'active')
{
?>
 | <a href="contact.php">Contact us</a>

<?php
}
?>
</div>
<div align="center" style="float:none;"><?php echo $row_setting['footer']; ?></div>
</footer>
<?php
}
?>     
<?php
if($row_setting['selecttopic'] == 'searchengine')
{
	?>  
<footer>
<div align="center" class="footer" style="float:left;">
            <div><?php do { ?>
            <a href="pages.php?pageid=<?php echo $row_pages1['pageid']; ?>" class="categorytext"><?php echo $row_pages1['name']; ?></a> |
            <?php } while ($row_pages1 = mysqli_fetch_assoc($pages1)); ?>
            <a href="add.php">Add Url</a>
<?php
$topmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '1'");
$topmenu = mysqli_fetch_assoc($topmenu_query);
if($topmenu['status'] == 'active')
{
?>
| <a href="info.php">News / Update</a>

<?php
}
?>
 <?php
$topmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '2'");
$topmenu = mysqli_fetch_assoc($topmenu_query);
if($topmenu['status'] == 'active')
{
?>
| <a href="contact.php">Contact Us</a> 
<?php
}
?>
 </div>
            <div align="center"><?php echo $row_setting['footer']; ?></div>
</div>
</footer>
<?php
}
?>
<?php
}
?>