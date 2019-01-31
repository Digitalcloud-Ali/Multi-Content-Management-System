<?php
if (!isset($_SESSION)) {
  session_start();
}
function dbconnect()
{
  $hostname_rayicecms = 'localhost';
  $database_rayicecms = 'masterk_cms';
  $username_rayicecms = 'masterk_cms';
  $password_rayicecms = 'raza1234';
  $rayicecms = mysqli_connect($hostname_rayicecms, $username_rayicecms, $password_rayicecms, $database_rayicecms) or trigger_error(mysqli_error(),E_USER_ERROR);
  return $rayicecms;
}
?>