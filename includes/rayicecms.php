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

$query = "SELECT * FROM settings WHERE settingid = 1";
$result = mysqli_query(dbconnect(), $query);
$row = mysqli_fetch_array($result);

if ($row['selecttopic'] != $_SESSION['sitetopic']) {
?>
<script type="text/javascript"> location.replace("Location: /"); </script>
<?php
}
else
{
unset($_SESSION['sitetopic']); 
}
?>