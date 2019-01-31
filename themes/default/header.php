<!-- Start top header -->
<link href="multicms.css" rel="stylesheet" type="text/css" />

<header>
<div style="background-color:#C30; height:47px;">

<?php
if(($row_setting['selecttopic'] != 'doctors') && ($row_setting['selecttopic'] != 'portfolio') && ($row_setting['selecttopic'] != 'searchengine'))
{
?>

<?php if($row_setting['selecttopic'] != 'productpublisher')
{
?>

<?php if ($totalRows_members == 0) { // Show if recordset empty ?>
<section>
	<div style="float:left; padding-top:14px; padding-left:12px;">
		<a href="login.php" class="textswhite1">Login</a> | <a href="register.php" class="textswhite1">Register</a> | <a href="account.php" class="textswhite1">My Account</a>
	</div>
</section>
<?php } // Show if recordset empty ?>

<?php if ($totalRows_members > 0) { // Show if recordset not empty ?>
<section>
	<div style="float:left; padding-top:14px; padding-left:12px;">
<?php echo $_SESSION['MM_Username']; ?> | <a href="account.php" class="textswhite1">My Account</a> | <a href="<?php echo $logoutAction ?>" class="textswhite1">Logout</a>
	</div>
</section>
<?php } // Show if recordset not empty ?>

<?php
}
?>
<?php
}
?>

<div style="float:right; padding-top:4px;">
<?php
if(($row_setting['selecttopic'] != 'doctors') && ($row_setting['selecttopic'] != 'portfolio') && ($row_setting['selecttopic'] != 'searchengine'))
{
	?>
	<form name="form1" method="get" action="search.php">
		<section id="searchsection">
        	<input name="title" type="text" class="formmenusimple" id="title">
			<input name="button" type="submit" class="smallbut" id="button" value="Search">
		</section>
	</form>
<?php
}?>
</div>
</div>
</header>
<!-- End top header -->

<div class="headerinner">

<!-- logo start -->
<div style="float:left;"><a href="/"><img src="/images/<?php echo $row_setting['logo']; ?>" border="0"></a></div>
<!-- logo end -->

<!-- Navigation Start -->
<div style="float:right;">
<?php
$topmenu_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '6'");
$topmenu = mysqli_fetch_assoc($topmenu_query);
if($topmenu['status'] == 'active')
{
?>

<nav>
<div class="navigationarea">
<?php
if(($row_setting['selecttopic'] != 'doctors') && ($row_setting['selecttopic'] != 'portfolio') && ($row_setting['selecttopic'] != 'searchengine'))
{
?>

			<span><a href="index.php" class="headermenubutton">Home</a></span>
<?php
$tcategories_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '11'");
$tcategories = mysqli_fetch_assoc($tcategories_query);
if($tcategories['status'] == 'active')
{
?>
<?php
$tcategories_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '14'");
$tcategories = mysqli_fetch_assoc($tcategories_query);
if($tcategories['status'] == 'active')
{
?>
		<?php do { ?>
			<span>
				<a href="category.php?catename=<?php echo $row_blogcates['catename']; ?>" class="headermenubutton"><?php echo $row_blogcates['catename']; ?></a>
			</span>
		<?php } while ($row_blogcates = mysqli_fetch_assoc($blogcates)); ?>
 <?php
}
?>
 <?php
}
?>
<?php
$menu_query = mysqli_query(dbconnect(),"SELECT * FROM pages WHERE position = 'menu'");
$menu = mysqli_fetch_assoc($menu_query);

$footermenus_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '4'");
$footermenus = mysqli_fetch_assoc($footermenus_query);
if($footermenus['status'] == 'active')
{
?>
          <?php do { ?>
          	<span><a href="pages.php?pageid=<?php echo $menu['pageid']; ?>" class="headermenubutton"><?php echo $menu['name']; ?></a>
            </span>
          <?php } while ($menu = mysqli_fetch_assoc($menu_query)); ?>
 <?php
}
?>
<?php
}
?>

<?php
if($row_setting['selecttopic'] == 'doctors')
{
?>
			<span><a href="index.php" class="headermenubutton">Home</a></span>
			<span><a href="time.php" class="headermenubutton">Request Time ?</a></span>
			<span><a href="doctors.php" class="headermenubutton">Doctor Services</a></span>
<?php
$menu_query = mysqli_query(dbconnect(),"SELECT * FROM pages WHERE position = 'menu'");
$menu = mysqli_fetch_assoc($menu_query);

$footermenus_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '4'");
$footermenus = mysqli_fetch_assoc($footermenus_query);
if($footermenus['status'] == 'active')
{
?>
          <?php do { ?>
          	<span><a href="pages.php?pageid=<?php echo $menu['pageid']; ?>" class="headermenubutton"><?php echo $menu['name']; ?></a>
            </span>
          <?php } while ($menu = mysqli_fetch_assoc($menu_query)); ?>
 <?php
}
?>
 <?php
}
?>
<?php
if($row_setting['selecttopic'] == 'portfolio')
{
?>
			<span><a href="index.php" class="headermenubutton">Home</a></span>
			<span><a href="quote.php" class="headermenubutton">Request Quote</a></span>
			<span><a href="portfolio.php" class="headermenubutton">Portfolio</a></span>
<?php
$menu_query = mysqli_query(dbconnect(),"SELECT * FROM pages WHERE position = 'menu'");
$menu = mysqli_fetch_assoc($menu_query);

$footermenus_query = mysqli_query(dbconnect(),"SELECT * FROM parts WHERE partsid = '4'");
$footermenus = mysqli_fetch_assoc($footermenus_query);
if($footermenus['status'] == 'active')
{
?>
<?php do { ?>
			<span>
            <a href="pages.php?pageid=<?php echo $menu['pageid']; ?>" class="headermenubutton"><?php echo $menu['name']; ?></a>
            </span>
		  <?php } while ($menu = mysqli_fetch_assoc($menu_query)); ?>
<?php
}
?>
<?php
}
?>
<?php
if($row_setting['selecttopic'] == 'searchengine')
{
	?>
<div style="float:right; padding-top:4px; padding-right:12px;">
	<form name="form1" method="get" action="search.php">
		<section id="searchsection">
        	<input name="title" type="text" class="formsearch" id="title">
			<input name="button" type="submit" class="button" id="button" value="Search">
		</section>
	</form>
</div><?php
}
?>
</div>
</nav>

<?php
}
?>
<!-- Navigation End -->
</div>
</div>
    <div style="height:5px; background:#C30;"></div>