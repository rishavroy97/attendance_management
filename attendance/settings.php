<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Attendance Web App</title>
	<link href="https://fonts.googleapis.com/css?family=Covered+By+Your+Grace|Permanent+Marker" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


</head>
<body>

<?php
	
	//session_start();
	$message="";
	require_once('config.php'); 

	if((!isset($_COOKIE['username'])) && (!isset($_SESSION['username']))){
		$message = "No valid username was received.";
		header("Location: login_signup.php");
		$_SESSION['reg_msg'] = $message;

	}

	$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
	}else{
		$email = $_COOKIE['email'];
	}
	
	if (isset($_SESSION['name'])) {
		$name = $_SESSION['name'];
	}else{
		$name = $_COOKIE['name'];
	}

	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
	}else{
		$username = $_COOKIE['username'];
	}

	//$password = $_SESSION['password'];
	if (isset($_SESSION['access'])) {
		$access = $_SESSION['access'];
	}else{
		$access = $_COOKIE['access'];
	}
	//$codeid = NULL;

	echo "<div class = 'heading'>
		<h1>Attendance Web App
		<span class = 'home'><i title = 'Homepage' class='material-icons' onClick='window.location.href =`homepage.php`;'>home</i>&nbsp;&nbsp;</span>
		<span class = 'home'><i title = 'Logout' class='material-icons' onClick='window.location.href =`logout.php`;'>power_settings_new</i>&nbsp;&nbsp;</span>
		<span class = 'home'><i title = 'Settings' class='material-icons' onClick='window.location.href =`settings.php`;'>&#xE8B8;</i>&nbsp;&nbsp;</span>
		</h1>
		</div>";
	echo "<div class = 'greeting'>
			<span class = 'greeting_span'>Hello, ".$name."!</span>
			</div>";
	echo "<div class = 'View'><p>Change Password</p>";
	echo "<form id = 'change_pwd' method='post' action = ''>
			<p><input id = 'cur_pwd' type = 'password' name = 'cur_pwd' placeholder = 'Current Password'></p>
			<p><input id = 'new_pwd' type = 'password' name = 'new_pwd' placeholder = 'New Password'></p>
			<p><input id = 're_new_pwd' type = 'password' name = 're_new_pwd' placeholder = 'Re-type new Password'></p>
			<input type = 'hidden' name = 'action' value = 'Change Password'>
		<input type = 'submit' value = 'Change Password'>
		</form>";
		if (isset($_SESSION['pwd-msg'])) {
			echo $_SESSION['pwd-msg'];
		}
	echo "</div>";

	if (isset($_POST['action'])) {
		if ($_POST['action'] == 'Change Password') {
			$current_pwd = mysqli_real_escape_string($link, $_POST['cur_pwd']);
			$new_pwd = mysqli_real_escape_string($link, $_POST['new_pwd']);
			$re_new_pwd = mysqli_real_escape_string($link, $_POST['re_new_pwd']);
			if ($new_pwd != $re_new_pwd) {
				$_SESSION['pwd-msg'] = "New password was incorrect the second time.";
				header("Location: settings.php");
			}
			$query = mysqli_query($link, "SELECT * FROM users WHERE username = '".$username."'");
			$result = mysqli_fetch_array($query);
			if ($result['password'] != md5($current_pwd)) {
				$_SESSION['pwd-msg'] = "Current Password is incorrect.";
				header("Location: settings.php");	
			}
			elseif ($new_pwd == '') {
				$_SESSION['pwd-msg'] = "Password cannot be empty";
				header("Location: settings.php");
			}
			else {
				mysqli_query($link, "UPDATE users SET password = '".md5($new_pwd)."' WHERE username = '".$username."'");
				echo "<script type='text/javascript'>
					alert('Password Changed Successfully');
					window.location.href = 'homepage.php';
					</script>";
			}
		}
	}
?>
</body>
</html>