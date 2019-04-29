<?php
require_once "sanitize.php";
require_once "login.php";
require_once "auth_markup.html";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

while (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])){
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'You gotta log';
    exit;
}

$un = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']);
$pw = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']);
$token = hash('ripemd128',"$salt$pw$salt");

$fn = "";
$ln = "";
if(!empty($_POST['firstname']) || !empty($_POST['lastname'])){
	$fn = (mysql_entities_fix_string($_POST['firstname']));
	$ln = (mysql_entities_fix_string($_POST['lastname']));
}
add_user($conn,$un,$fn, $ln, $token);


function add_user($conn, $un, $fn, $ln, $tk){
	$query = "INSERT INTO users(username, password) VALUES('$fn','$ln'$un','$tk')";
	$result = $conn-> quere($query);
	if (!$result) die($conn->error);
}
?>