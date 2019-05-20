<?php
require_once "markup.html";
session_start();
if (!empty($_SESSION['username'])){
	$username = $_SESSION['username'];
	//86400 = 1 day
    setcookie('username',$username,time()+(86400*7),'/');
    header("Locaiton:upload.php");
	header("Location:upload.php");
}else {
	// echo "<a href='authenticate.php'>UNRECOGNIZED CREDENTIALS, CREATE AN ACCOUNT IF NEEDED</a>";
	header("Location:authenticate.php");
	echo "a;lsdkfjalsd;kjfl;aksdfj;lasdkfjl;asdjf;las";
}

?>