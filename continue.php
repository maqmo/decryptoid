<?php
session_start();
if (!empty($_SESSION['username'])){
	$username = $_SESSION['username'];
	//86400 = 1 day
    setcookie('username',$username,time()+(86400*7),'/');
    header("Locaiton:upload.php");
	header("Location:upload.php");
}else echo "Please <a href='authenticate.php'>LOG IN</a>";

?>