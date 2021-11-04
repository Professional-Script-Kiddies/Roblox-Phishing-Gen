<?php
include("function.php");
if(isset($_GET['placeId'])){
    $id = $_GET['placeId'];
	echo requestGamepass($id);
}
