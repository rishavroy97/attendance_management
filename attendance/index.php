<?php
session_start();
if (isset($_COOKIE['user']) || isset($_SESSION['email']) || isset($_SESSION['username']) || isset($_SESSION['password'])) {
	 header("Location: homepage.php");
}
else{
	header("Location: login_signup.php");	
}

?>