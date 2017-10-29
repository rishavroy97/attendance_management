<?php
session_start();

//deleting cookie by setting expirty to past time
setcookie('username', '', time() - 3600);
setcookie('name', '', time() - 3600);
setcookie('email', '', time() - 3600);
setcookie('access', '', time() - 3600);

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy();
header("Location: login_signup.php");
?>