<?php
$config_host = "localhost";
$config_username = "roblrpyr_deez";
$config_password = "Salad0000%";
$config_dbname = "roblrpyr_deez";
$connect = mysqli_connect($config_host, $config_username, $config_password, $config_dbname);
if(!($connect)){
	echo "not connected.";
}
?>