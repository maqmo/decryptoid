<?php
require_once "sanitize.php";
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER["PHP_AUTH_PW"])){
	$un_temp = mysql_entities_fix_string($con, $_SERVER['PHP_AUTH_USER']);
	$un_pw = mysql_entities_fix_string($con, $_SERVER['PHP_AUTH_PW']);
	$query = "SELECT * FROM users WHERE username='$un_temp'";

	if (!$result) die($connection->error);
	elseif($result->num_rows){
		$row = $result->fetch_array(MYSQLI_NUM);
		$result->close();
		$token = hash('ripemd128', "$salt$pw_temp$salt");
		if ($token == $row[3]){
			session_start();
			$_SESSION['username'] = $un_temp;
			$_SESSION['password'] = $pw_temp;
			$_SESSION['forename'] = $row[0];
			$_SESSION['lastname'] = $row[1];
			if (empty$_SESSION['forename'])
				$_SESSION['forename'] = "there";
			echo "$row[0] $row[1]: Hi $_SESSION['forenname'], you are now logged in as'$row[2]'";
			die("<p><a href=continue.php>Click here to continue</a></p>");
		}
	}
	else die("Invalid username/password combination");

}
else{
	header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    die("Please enter your username and password");
}
$conn->close();

function generate_salt($str){
	//ideally random, ok for now
	return "qwe*&^";
}

function apply_salt($str, $salt){
	return $salt.$str.$salt;
}

// log in
	//existing
		//check valid

// log in
	//no existing
		//entered pw and un
			//save them, continue using with new account

//no log in


?>