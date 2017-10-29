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
		<span class = 'home'><i title='homepage' class='material-icons' onClick='window.location.href =`homepage.php`;'>home</i>&nbsp;&nbsp;</span>
		<span class = 'home'><i title = 'Logout' class='material-icons' onClick='window.location.href =`logout.php`;'>power_settings_new</i>&nbsp;&nbsp;</span>
		<span class = 'home'><i title = 'Settings' class='material-icons' onClick='window.location.href =`settings.php`;'>&#xE8B8;</i>&nbsp;&nbsp;</span>
		</h1>
		</div>";

	if ($access == 'teacher') {
		header("Location: teacher.php");
	}

	//if Access value is admin

	else if ($access == 'admin') {
		header("Location: admin.php");
	}
	
	//if Access value is student

	else if($access == 'student'){
		header("Location: student.php");
	}
?>
</body>
</html>