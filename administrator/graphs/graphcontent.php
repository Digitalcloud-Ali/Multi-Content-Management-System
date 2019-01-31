<?php require_once('../../includes/rayicecms.php'); ?>
<?php
mysqli_select_db(dbconnect(),$database_rayicecms);
$query_setting = "SELECT * FROM settings";
$setting = mysqli_query(dbconnect(),$query_setting) or die(mysqli_connect_error());
$row_setting = mysqli_fetch_assoc($setting);
$totalRows_setting = mysqli_num_rows($setting);
if($row_setting['selecttopic'] == 'blog')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM blog ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'searchengine')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM searchengine ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'portfolio')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM portfolio ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'adposting')
{
include("phpgraphlib.php");
$graph = new PHPGraphLib(680,200);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();

//get data from database
$sql="SELECT * FROM adposting ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["adpostingid"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();

$graph->createGraph();
}
if($row_setting['selecttopic'] == 'videostream')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM video ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'doctors')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM doctors ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'imagegallery')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM image ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'marketplace')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM market ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'tutorials')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM tutorials ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
if($row_setting['selecttopic'] == 'productpublisher')
{
include("phpgraphlib.php");
include('phpgraphlib_pie.php');
$graph = new PHPGraphLibPie(680,300);
     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM productpublisher ORDER BY views DESC LIMIT 0, 10";
$result = mysqli_query(dbconnect(),$sql) or die('Query failed: ' . mysqli_connect_error());
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
      $salesgroup=$row["title"];
      $count=$row["views"];
      //add to data areray
      $dataArray[$salesgroup]=$count;
  }
}
  
//configure graph
//configure graph
$graph->setBackgroundColor("gray");
$graph->addData($dataArray);
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
}
?>
<?php
mysqli_free_result($setting);
?>