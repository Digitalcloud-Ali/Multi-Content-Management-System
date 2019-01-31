<?php
if(($row_setting['selecttopic'] != 'doctors') && ($row_setting['selecttopic'] != 'portfolio') && ($row_setting['selecttopic'] != 'searchengine'))
{
?>
<?php
$tcategories_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '11'");
$tcategories = mysqli_fetch_assoc($tcategories_query);
if($tcategories['status'] == 'active')
{
?>
<?php
$tcategories_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '13'");
$tcategories = mysqli_fetch_assoc($tcategories_query);
if($tcategories['status'] == 'active')
{
?>
<div class="title">CATEGORIES</div>
          <?php
		  $cata_query = mysqli_query(dbconnect(),"SELECT * FROM categories WHERE selecttopic = '$row_setting[selecttopic]'");
		  $cata = mysqli_fetch_assoc($cata_query);
		  do { ?>
            <div class="categorylist" align="center">
            <a href="category.php?catename=<?php echo $cata['catename']; ?>"><?php echo $cata['catename']; ?></a>
            </div>
          <?php } while ($cata = mysqli_fetch_assoc($cata_query)); ?>

<?php
}
?>
<?php
}
?>
<?php
}
?>
<?php
$leftmenu_query = mysqli_query(dbconnect(),"SELECT * FROM pages WHERE position = 'leftmenu'");
$leftmenu = mysqli_fetch_assoc($leftmenu_query);

$topmenu1_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '4'");
$topmenu1 = mysqli_fetch_assoc($topmenu1_query);
if($topmenu1['status'] == 'active')
{
?>
<div class="title">PAGES</div>
          <?php
			do { ?>

           <div class="categorylist" align="center">
<a href="pages.php?pageid=<?php echo $leftmenu['pageid']; ?>"><?php echo $leftmenu['name']; ?></a></div>
          <?php } while ($leftmenu = mysqli_fetch_assoc($leftmenu_query)); ?>

<?php
}
?>    
<?php
$topmenu2_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '5'");
$topmenu2 = mysqli_fetch_assoc($topmenu2_query);
if($topmenu2['status'] == 'active')
{
?>
          <div class="title">LINKS</div>
                <?php do { ?>
                <div class="categorylist" align="center">
<a href="<?php echo $row_links['linkurl']; ?>"><?php echo $row_links['linktitle']; ?></a></div>
<?php } while ($row_links = mysqli_fetch_assoc($links)); ?>
<?php
}
?>
<?php
$widgetstatus_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '12'");
$widgetstatus = mysqli_fetch_assoc($widgetstatus_query);

$widgets_query = mysqli_query(dbconnect(),"SELECT * FROM widgets WHERE position = 'sidemenu'");
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