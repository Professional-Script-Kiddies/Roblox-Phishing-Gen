<?php
include('../function.php');
if(isset($_GET['username'])){
	echo requestId($_GET['username']);
}
?>
