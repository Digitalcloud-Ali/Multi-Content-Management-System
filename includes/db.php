<?php require_once('../includes/rayicecms.php'); ?>
<?php
$filename = 'raycms.sql';
// Connect to MySQL server
mysqli_connect($hostname_rayicecms, $username_rayicecms, $password_rayicecms) or die('Error connecting to MySQL server: ' . mysqli_connect_error());
// Select database
mysqli_select_db($database_rayicecms) or die('Error selecting MySQL database: ' . mysqli_connect_error());
 
// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line)
{
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;
 
    // Add this line to the current segment
    $templine .= $line;
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';')
    {
        // Perform the query
        mysqli_query(dbconnect(),$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_connect_error() . '<br /><br />');
        // Reset temp variable to empty
        $templine = '';
    }
}

header( 'Location: /index.php' );
?>
