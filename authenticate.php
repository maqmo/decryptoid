<?php
require_once "sanitize.php";
require_once "auth_markup.html";
require_once "login.php";
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (!empty($_POST['username']) && !empty($_POST['password1'])){
	$un_temp = mysql_entities_fix_string($conn, $_POST['username']);
	$pw_temp = mysql_entities_fix_string($conn, $_POST['password1']);
	//echo $un_temp, $pw_temp;
	if (!validate_then_sessionize($conn,$un_temp,$pw_temp))
		die("Invalid username/password");
	else
		header("Location: upload.php");
}
echo <<< _i
	<a href="setupusers.php"><h1>...or create an account here</h1></a>
_i;
$conn->close();
function validate_then_sessionize($conn, $un, $pw){
	$salt = "qwe*&^";
	$query = "SELECT * FROM users WHERE username='$un'";
	$result = $conn-> query($query);
	if (!$result) die($connection->error);
	elseif ($result->num_rows){
		$row = $result->fetch_array(MYSQLI_NUM);
		$result->close();
		$token = hash('ripemd128', "$salt$pw$salt");
		if ($token == $row[3]){
			$fn = $row[0];
			$ln = $row[1];
			sessionize($conn, $un, $pw, $fn, $ln);
		}
		else return 0;
	}
	return 1;
}

function sessionize($conn, $un, $pw, $fn, $ln){
	session_start();
	$_SESSION['username'] = $un;
	$_SESSION['password'] = $pw;
	$_SESSION['forename'] = $fn;
	$_SESSION['surname'] = $ln;
}

?>