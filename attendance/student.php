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


	if ($access != 'student') {
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

	$sql = "SELECT * from students where class is NULL and email = '".$email."' and username = '".$username."'";
	$query = mysqli_query($link,$sql);
	if (!$query) {
		echo "<div classs='greeting'>";
		die("<br> Error :" . mysqli_error($link));
		echo "</div>";
	}

	$numResults = mysqli_fetch_array($query);
	
	if (count($numResults)>=1) {
		echo "<div class = 'View'><p>We Need some more information!</p>";
		echo "<div>Select Your Class";
		echo "<form id='selectclass' method = 'post' action = ''><br>
			<input type = 'hidden' name = 'action' value = 'Select Class'>
			<p><input type = 'radio' name = 'class' value = 'A'>A&nbsp;&nbsp;
			<input type = 'radio' name = 'class' value = 'B'>B&nbsp;&nbsp;
			<input type = 'radio' name = 'class' value = 'C'>C&nbsp;&nbsp;
			<input type = 'radio' name = 'class' value = 'D'>D&nbsp;&nbsp;
			<input type = 'radio' name = 'class' value = 'E'>E&nbsp;&nbsp;
			<input type = 'radio' name = 'class' value = 'F'>F&nbsp;&nbsp;</p>
			<p><input type = 'submit' value = 'submit'></p>
		</form>";
		echo "</div></div>";
	}
	else{

		echo "<div class = 'View'><p>View Attendance</p>";
		$sql = "SELECT * from students where username = '".$username."'";
		$query = mysqli_query($link,$sql);
		$result = mysqli_fetch_array($query);
		for ($i = 1; $i <= 5; $i++) { 
			if ($result['sub'.$i] != NULL) {
				$class = $result['class'];
				$subject = $result['sub'.$i];
				$teachersql = "SELECT * from teachers WHERE subject = '".$subject."' AND (class1 ='".$class."' OR class2 ='".$class."' OR class3 ='".$class."' OR class4 ='".$class."')";
				$teacherquery = mysqli_query($link,$teachersql);
				$teacherresult = mysqli_fetch_array($teacherquery);

				if ($result['sub'.$i.'total'] == 0) {
					$percent = "-";
				}
				else{
					$percent = round(($result['sub'.$i.'attend'] / $result['sub'.$i.'total'])	* 100, 2);
				}
				if ($percent == "-") {
					echo "<div style = 'background-color: #bbb;' class='Add'>";
				}
				else if ($percent >= 85) {
					echo "<div style = 'background-color: #99ff66;' class='Add'>";
				}
				else if ($percent >= 75) {
					echo "<div class='Add'>";
				}
				else{
					echo "<div style = 'background-color: #ff6666;' class='Add'>";
				}
				echo "<p>Subject : <u>".$result['sub'.$i]."</u></p>
					<p>Teacher In-Charge : ".$teacherresult['name']."</p>
					<p>Total Classes : ".$result['sub'.$i.'total']."<br>
					Classes Attended : ".$result['sub'.$i.'attend']."</p>";
				if ($percent == "-") {
				echo "<p>Percentage Attendance : ".$percent."</p>";
				}
				else{
				echo "<p>Percentage Attendance : ".$percent."%</p>";
				}
				echo "</div>";
			}
		}
		echo "</div>";
	}



	//form actions...
	if (isset($_POST['action'])) {
		if ($_POST['action'] == 'Select Class') {
			$class = mysqli_real_escape_string($link,$_POST['class']);
			if($class == ""){
				header("Location: student.php");
			}
			else{
				$sql = "UPDATE students SET class='".$class."' WHERE username = '".$username."'";
				$query = mysqli_query($link,$sql);
				if (!$query) {
					echo "<div classs='greeting'>";
					die("<br> Error :" . mysqli_error($link));
					echo "</div>";
				}				
			}
			//refresh the page
			echo '<script type="text/javascript">
      			document.getElementById("selectclass").submit();
      			alert("Class selected Successfully!");
     		</script>';
		}
	}
	

?>
</body>
</html>