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

	if ($access != 'teacher') {
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


		
	$sql = "SELECT * from teachers where subject is NULL and email = '".$email."' and username = '".$username."'";
	$query = mysqli_query($link,$sql);
	if (!$query) {
		echo "<div classs='greeting'>";
		die("<br> Error :" . mysqli_error($link));
		echo "</div>";
	}

	$numResults = mysqli_fetch_array($query);

/*Entering the details*/
	
	if (count($numResults)>=1) {
		echo "<div class = 'View'><p>We Need some more information!</p>";
		echo "<form id='selectclass' method = 'post' action = ''>
			
			<div class = 'formdiv'>
			<p><u>Select Your Subject</u></p>
			<p><input type = 'radio' name = 'subject' value = 'Computer Science'>Computer Science&nbsp;
			<input type = 'radio' name = 'subject' value = 'Mathematics'>Mathematics&nbsp;
			<input type = 'radio' name = 'subject' value = 'Physics'>Physics&nbsp;
			<input type = 'radio' name = 'subject' value = 'Chemistry'>Chemistry&nbsp;
			<input type = 'radio' name = 'subject' value = 'English'>English&nbsp;</p>

			<p><u>Select the Classes you Take</u></p><p>";
		for($i = 1; $i <= 4; $i++){
			echo "<select name='class".$i."' placeholder = 'Class".$i."'>
				<option value=''>Class ".$i."</option>
          		<option value='A'>A</option>
          		<option value='B'>B</option>
          		<option value='C'>C</option>
          		<option value='D'>D</option>
          		<option value='E'>E</option>
        	</select>";
        	echo "&nbsp;&nbsp;";
		}
		
		echo "</div>";	
		echo "</p><p><input type = 'hidden' name = 'action' value = 'Select Class'>
			<input type = 'submit' value = 'submit'></p>
		</form>";
		if (isset($_SESSION['teacher-form-message'])) {
			echo $_SESSION['teacher-form-message'];
		}
		echo "</div></div>";
	}

/*Taking Attendance*/
	else{

		echo "<div class = 'View'><p>Take Attendance</p>";
		$sql = "SELECT * from teachers where username = '".$username."'";
		$query = mysqli_query($link,$sql);
		$result = mysqli_fetch_array($query);
		for($i = 1; $i <= 4; $i++){
			if ($result['class'.$i] != '') {
				echo "<div class='Add'>";
				echo "<form method='post' action = ''>
						<p>Class : ".$result['class'.$i]."<br>
						Subject : ".$result['subject']."</p>
						<p class = 'namelist'>";
				$student_sql = "SELECT * from students where class ='".$result['class'.$i]."' ORDER BY name ASC";
				$student_query = mysqli_query($link,$student_sql);
				$student_result = mysqli_fetch_array($student_query);
				$sub_no;
				for ($j = 1; $j <= 4 ; $j++) { 
					if ($student_result['sub'.$j] == $result['subject']) {
						$sub_no = $j;
					}
				}
				echo "<input type = 'checkbox' name = 'attendlist[]' value = '".$student_result['username']."'> ".$student_result['name']."<br>";
				
				while($student_result = mysqli_fetch_array($student_query)){
					echo "<input type = 'checkbox' name = 'attendlist[]' value = '".$student_result['username']."'>". $student_result['name']."<br>";
				}
				
				echo "<input type = 'hidden' name = 'sub_no' value = '".$sub_no."'>
					<input type = 'hidden' name = 'class' value = '".$result['class'.$i]."'>";

				echo "</p>
						<input type = 'hidden' name = 'subject' value = '".$result['subject']."'>
						<input type = 'hidden' name = 'action' value = 'Submit Attendance'>
						<input type = 'submit' value = 'Submit'>
				</form>";
				echo "</div>";
			}
		}
		echo "</div></li>";
	}



	//form actions...
	if (isset($_POST['action'])) {
/*Registering teacher details*/
		if ($_POST['action'] == 'Select Class') {
			$success = NULL;
			$subject = mysqli_real_escape_string($link,$_POST['subject']);
			if($subject == ""){
				$success = 0;
				$_SESSION['teacher-form-message'] = "Subject was not selected!";
			}
			elseif ($_POST['class1'] == '' && $_POST['class2'] == '' && $_POST['class3'] == '' && $_POST['class4'] == '') {
					$success = 0;
					$_SESSION['teacher-form-message'] = "You need to teach atleast 1 class!";
			}
			else{
				$arrayClass = array();
				for ($i=1; $i <=4 ; $i++) { 
					if ($_POST['class'.$i] != '') {
						array_push($arrayClass, $_POST['class'.$i]);
					}
				}
				$uniquearrayClass = array_unique($arrayClass);

				function notRepeat($class, $subject){
					$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

					// Check connection
					if (mysqli_connect_errno()) {
	  					echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
					$sql = "SELECT * from teachers WHERE subject = '".$subject."' AND (class1 ='".$class."' OR class2 ='".$class."' OR class3 ='".$class."' OR class4 ='".$class."')";
					$query = mysqli_query($link,$sql);
					$count = mysqli_num_rows($query);
					return $count;
				}

				if (count($arrayClass) != count($uniquearrayClass)) {
					$success = 0;
					$_SESSION['teacher-form-message'] = "Cannot teach the same class twice! ";
				}
				elseif( notRepeat($_POST['class1'], $subject) >= 1){
					$success = 0;
					$_SESSION['teacher-form-message'] = "Another teacher is already taking this subject for class ".$_POST['class1']."!";
				}
				elseif( notRepeat($_POST['class2'], $subject) >= 1){
					$success = 0;
					$_SESSION['teacher-form-message'] = "Another teacher is already taking this subject for class ".$_POST['class2']."!";
				}
				elseif( notRepeat($_POST['class3'], $subject) >= 1){
					$success = 0;
					$_SESSION['teacher-form-message'] = "Another teacher is already taking this subject for class ".$_POST['class3']."!";
				}
				elseif( notRepeat($_POST['class4'], $subject) >= 1){
					$success = 0;
					$_SESSION['teacher-form-message'] = "Another teacher is already taking this subject for class ".$_POST['class4']."!";
				}
				else{	
					$success = 1;
				}
			}

			//success check
			if ($success == 0) {
				header("Location: teacher.php");
			}
			else if ($success == 1) {
				echo '<script type="text/javascript">
	     			alert("Class selected Successfully!");
	   			</script>';
				$sql = "UPDATE teachers SET subject='".$subject."', class1 = '".$_POST['class1']."', class2 = '".$_POST['class2']."', class3 = '".$_POST['class3']."', class4 = '".$_POST['class4']."' WHERE username = '".$username."'";
				$query = mysqli_query($link,$sql);
				if (!$query) {
					echo "<div classs='greeting'>";
					die("<br> Error :" . mysqli_error($link));
					echo "</div>";
				}
				for($i=1; $i <= 4; $i++){
					$sql = "SELECT * from students where class = '".$_POST['class'.$i]."'";
					$query = mysqli_query($link,$sql);
					$result = mysqli_fetch_array($query);
					if($result['sub1'] == NULL || $result['sub1'] == ''){
						mysqli_query($link,"UPDATE students SET sub1='".$subject."' WHERE class = '".$_POST['class'.$i]."'");
					}
					elseif($result['sub2'] == NULL || $result['sub2'] == ''){
						mysqli_query($link,"UPDATE students SET sub2='".$subject."' WHERE class = '".$_POST['class'.$i]."'");
					}
					elseif($result['sub3'] == NULL || $result['sub3'] == ''){
						mysqli_query($link,"UPDATE students SET sub3='".$subject."' WHERE class = '".$_POST['class'.$i]."'");
					}
					elseif($result['sub4'] == NULL || $result['sub4'] == ''){
						mysqli_query($link,"UPDATE students SET sub4='".$subject."' WHERE class = '".$_POST['class'.$i]."'");
					}
					elseif($result['sub5'] == NULL || $result['sub5'] == ''){
						mysqli_query($link,"UPDATE students SET sub5='".$subject."' WHERE class = '".$_POST['class'.$i]."'");
					}
				}
				//refresh the page
				echo '<script type="text/javascript">
	     			window.location.href = "teacher.php";
	      			</script>';
			}
		}
/*Submitting Attendance*/
		elseif($_POST['action']=="Submit Attendance")
        {
        	echo '<script type="text/javascript">
	     			alert("Attendance submitted successfully");
	      			</script>';
        	mysqli_query($link,"UPDATE students SET sub".$_POST['sub_no']."total = sub".$_POST['sub_no']."total + 1 WHERE sub".$_POST['sub_no']." = '".$_POST['subject']."' AND class = '".$_POST['class']."'");
        	foreach($_POST['attendlist'] as $student_attended){
        		mysqli_query($link, "UPDATE students SET sub".$_POST['sub_no']."attend = sub".$_POST['sub_no']."attend + 1 WHERE username = '".$student_attended."'");
        	}        	
		}
	}	

?>
</body>
</html>