<?php
session_start();
if (!empty($_SESSION['username'])){
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$forenmae = $_SESSION['forename'];
	$forenmae = $_SESSION['surname'];

	destroy_session_and_data();
	header("Location:upload.php");
}else echo "Please <a href='authenticate.php'>LOG IN</a>";

function_destroy_session_and_data(){
	$_SESSION = array();
	setcookie(session_name(),'',time()-2592000, '/')
	session_destroy();
}

?>