<?php require_once('../../includes/rayicecms.php'); ?>
<?php

include("phpgraphlib.php");
$graph=new PHPGraphLib(680,300); 

     
mysqli_select_db(dbconnect(),$database_rayicecms);  
$dataArray=array();
  
//get data from database
$sql="SELECT * FROM pages ORDER BY views DESC LIMIT 0, 10";
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
$graph->addData($dataArray);
$graph->setGradient('teal', 'teal');
$graph->setXValuesHorizontal(true);
$graph->createGraph();
?>