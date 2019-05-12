<?php
require_once "sanitize.php";
require_once "login.php";
require_once "setup_markup.html";


$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (all_set()){
	$fn = (mysql_entities_fix_string($conn,$_POST['firstname']));
	$ln = (mysql_entities_fix_string($conn,$_POST['lastname']));
	$un = (mysql_entities_fix_string($conn,$_POST['username']));
	$pw1 = (mysql_entities_fix_string($conn,$_POST['password1']));
	$pw2 = (mysql_entities_fix_string($conn,$_POST['password2']));
	$token = hash('ripemd128', "$salt$pw1$salt");
	if ($pw1 !== $pw2){
	die ("passwords don't match, try again");

	}
	add_user($conn,$un,$fn,$ln,$token);
	header("Location:authenticate.php");
}

function add_user($conn, $un, $fn, $ln, $tk){
	$query = "INSERT INTO users VALUES('$fn','$ln','$un','$tk')";
	$result = $conn-> query($query);
	if (!$result) die($conn->error);
	echo "entered values: {$un}, {$fn}, {$ln}, {$tk}";
}

function all_set(){
	return (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username'])&& !empty($_POST['password1']) && !empty($_POST['password2']));
}
?>