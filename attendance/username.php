<?php
	require_once('config.php'); 

	$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	if (isset($_GET['val'])) {
		$username = mysqli_real_escape_string($link,$_GET['val']);
		$query = mysqli_query($link, "SELECT * from users where username = '". $username."'");
		$result = mysqli_fetch_array($query);
		if(count($result)>=1){
			echo "username exists";
		}
		else{
			echo "ok";
		}
	}
?>