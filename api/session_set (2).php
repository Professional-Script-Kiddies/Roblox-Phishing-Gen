<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$_SESSION['password'] = $_POST['password'];
	echo $_SESSION['password'];
}
?>
