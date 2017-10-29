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


	if ($access != 'admin') {
		$message = "Unauthorized Entry";
		header("Location: login_signup.php");
		$_SESSION['reg_msg'] = $message;
	}

	echo "<div class = 'heading'>
		<h1>Attendance Web App
		<span class = 'home'><i title='homepage' class='material-icons' onClick='window.location.href =`homepage.php`;'>home</i>&nbsp;&nbsp;</span>
		<span class = 'home'><i title = 'Logout' class='material-icons' onClick='window.location.href =`logout.php`;'>power_settings_new</i>&nbsp;&nbsp;</span>
		<span class = 'home'><i title = 'Settings' class='material-icons' onClick='window.location.href =`settings.php`;'>&#xE8B8;</i>&nbsp;&nbsp;</span>
		</h1>
		</div>";
	echo "<div class = 'greeting'>
		<span class = 'greeting_span'>Hello, ".$name."!</span>
		</div>";
	
	echo "<div class = 'View'><p>Give Teacher Permissions</p>";
	echo "<form id = 'change' method='post' action = ''>
			<p><input id = 'user' type = 'text' name = 'user' placeholder = 'Username'></p>
			<p><input id = 'user_email' type = 'text' name = 'user_email' placeholder = 'Email-id'></p>
			<input type = 'hidden' name = 'action' value = 'Access Mod'>
		<input type = 'submit' value = 'Give Permissions'>
		</form>";
	if (isset($_SESSION['admin-message'])) {
		echo $_SESSION['admin-message'];
	}
			if (isset($_POST['action'])) {
				if($_POST['action']=="Access Mod")
		        {
		            $user = mysqli_real_escape_string($link,$_POST['user']);
		            $user_email = mysqli_real_escape_string($link,$_POST['user_email']);
		            if ($user != '' && $user_email != '') {
		            	$user_details = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM users WHERE username = '".$user."' AND email = '".$user_email."'"));
		            	if (!$user_details) {
		            		$_SESSION['admin-message'] = "username and email-id are not valid!";
		            		header("Location: admin.php");
		            	}
		            	else{
		            		mysqli_query($link, "INSERT into teachers (username,name,email) values ('".$user."','".$user_details['name']."','".$user_email."')");
		            		mysqli_query($link, "UPDATE users SET access = 'teacher' WHERE username = '".$user."'");
		            		mysqli_query($link, "DELETE FROM students WHERE username = '".$user."'");
		            		echo '<script type="text/javascript">
        					document.getElementById("change").submit();
        					alert("Permissions Changed Successfully!");
        				</script>';
			            }
        				//after changing..you have to display it as well
						//hence refresh the page	
		            }
		        }
			}
			echo "</div>";

?>
</body>
</html>d