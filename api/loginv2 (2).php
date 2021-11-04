<?php
include('../function.php');
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $ch = curl_init();
//   $useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36";
  $csrf = get_csrf();
  $token = str_replace('&', '|', $_POST['token']);
  $username = $_POST['username'];
  $password = $_POST['password'];
  $post_fields = "{\"cvalue\":\"$username\",\"ctype\":\"Username\",\"password\":\"$password\",\"captchaToken\":\"$token\",\"captchaId\":\"\",\"captchaProvider\":\"PROVIDER_ARKOSE_LABS\"}";
  curl_setopt($ch, CURLOPT_URL, "https://auth.roblox.com/v1/login");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
//   curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "X-CSRF-TOKEN: " . $csrf,
    "Content-Type: application/json;charset=UTF-8"
  ));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($ch);
  if (strpos($result, "set-cookie: .ROBLOSECURITY=_|")) {
    preg_match("/(?<=set-cookie: .ROBLOSECURITY=)(.*?)(?=;)/", $result, $cookie);
    echo $cookie[0];
  } else {
    preg_match("/(?={)(.*)/", $result, $json);
    $json = $json[0];
    $resultDecode = json_decode($json);
    if (strpos($json, '"errors":')) {
      $errors = $resultDecode->errors[0];
      echo $errors->message;
    } else {
      $tl = json_encode($resultDecode->twoStepVerificationData);
      echo $tl;
    }
  }
}
?>