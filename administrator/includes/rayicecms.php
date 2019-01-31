<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_rayicecms = "localhost";
$database_rayicecms = "masterk_cms";
$username_rayicecms = "masterk_cms";
$password_rayicecms = "raza1234";
$rayicecms = mysqli_connect($hostname_rayicecms, $username_rayicecms, $password_rayicecms); 

if (!$rayicecms) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
	
?>