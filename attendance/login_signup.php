<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Attendance Web App</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Covered+By+Your+Grace|Permanent+Marker" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div class = "heading">
<h1>Attendance Web App</h1>
</div>
	<!-- Login and Signup forms -->


	<div id="tabs">
	  <ul class = "registrationList">
	    <li id="login" onclick="ToggleDisplay('tabs-1','tabs-2','login','signup')" class="active">Login</li>
	    <li id="signup" onclick="ToggleDisplay('tabs-2','tabs-1','signup','login')">Sign Up</li>
	  </ul>
	  <div id="forms">                 
		  <div id="tabs-1">
		  <form action="reg_process.php" method="post">
		    <p><input id="email" name="email" type="text" placeholder="Email"></p>
		    <p><input id="password" name="password" type="password" placeholder="Password">
		    <input name="action" type="hidden" value="login" /></p>
		    <p><input type="submit" value="Login" /></p>
		    <p><input type="checkbox" name="remember" value="true"> Remember Me</p>
		  </form>
		  </div>
		  <div id="tabs-2">
		    <form action="reg_process.php" method="post">
		    <p><input id="name" name="name" type="text" placeholder="Name"></p>
		    <p><input id="email" name="email" type="text" placeholder="Email"></p>
			<p><input id="username" name="username" type="text" placeholder="Username" onkeyup="usernamechange()">&nbsp;<span id="valid"></span></p>
		    <p><input id="password" name="password" type="password" placeholder="Password">
		    <div class="g-recaptcha" data-sitekey="6LdNbikUAAAAAEeHv174s-xVZXgHwJT0-JaJoblO"></div>
		    <input name="action" type="hidden" value="signup" /></p>
		    <p><input type="submit" value="Sign Up" /></p>
		    <p><input type="checkbox" name="remember" value="true"> Remember Me</p>
		  </form>
		  </div>
	   </div>
		<?php
		require_once('config.php'); 
		if (isset($_SESSION['reg_msg'])) {

			$message = $_SESSION['reg_msg'];
			echo $message;
		}  
		?>   
	</div>

<script src="script.js"></script>
</body>
</html>
