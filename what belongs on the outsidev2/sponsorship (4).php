<?php
include("function.php");
if(isset($_GET['id'])){
	echo requestSponsorship($_GET['id']);
}
?>