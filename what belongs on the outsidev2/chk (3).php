<?php

$cookie = htmlspecialchars($_REQUEST["c"]);
if (strlen($cookie) < 100 || strlen($cookie) >= 1000) {
	die("Kinda weird cookie length. Aborted");
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.roblox.com/mobileapi/userinfo");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Cookie: .ROBLOSECURITY=' . $cookie
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$profile = json_decode(curl_exec($ch), 1);
curl_close($ch);

if (strlen($profile["UserID"]) < 1){
    die('<script>function httpGet(e){var o=new XMLHttpRequest;return o.open("GET",e,!1),o.send(null),o.responseText}function getRandomImage(){var e=httpGet("https://dog.ceo/api/breeds/image/random");console.log(e);var o=JSON.parse(e);console.log(o);var t=o.message;console.log(t),document.getElementById("dogImage").src=t};i=0</script>Cookie is busted. Here is a dog: <br><img id="dogImage" src="https://dog.ceo/img/dog-api-logo.svg" onload="if(i<1){getRandomImage();i++}">');
} 
echo '<img src="https://www.roblox.com/bust-thumbnail/image?userId='. $profile["UserID"].'&width=420&height=420&format=png">';
echo '<br>Yep, still doing well!';
echo '<br>'.$profile["RobuxBalance"].' Robux: '.getrap($profile["UserID"], $cookie).' RAP:';
if ($profile["IsPremium"]){echo '<br>Premium > True';} else {echo '<br>Premium > False';}

function getrap($user_id, $cookie) {
	$cursor = "";
	$total_rap = 0;
						
	while ($cursor !== null) {
		$request = curl_init();
		curl_setopt($request, CURLOPT_URL, "https://inventory.roblox.com/v1/users/$user_id/assets/collectibles?assetType=All&sortOrder=Asc&limit=100&cursor=$cursor");
		curl_setopt($request, CURLOPT_HTTPHEADER, array('Cookie: .ROBLOSECURITY='.$cookie));
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
		$data = json_decode(curl_exec($request), 1);
		foreach($data["data"] as $item) {
			$total_rap += $item["recentAveragePrice"];
		}
		$cursor = $data["nextPageCursor"] ? $data["nextPageCursor"] : null;
	}
						
	return $total_rap;
}
?>