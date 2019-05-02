<?php
require_once "sanitize.php";
require_once "login.php";


$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

// while (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])){
//     header('WWW-Authenticate: Basic realm="My Realm"');
//     header('HTTP/1.0 401 Unauthorized');
//     echo 'You gotta log';
//     exit;
// }

// $un = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']);
// $pw = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']);
// $token = hash('ripemd128',"$salt$pw$salt");

if (all_set()){
	$fn = (mysql_entities_fix_string($conn,$_POST['firstname']));
	$ln = (mysql_entities_fix_string($conn,$_POST['lastname']));
	$un = (mysql_entities_fix_string($conn,$_POST['username']));
	$pw1 = (mysql_entities_fix_string($conn,$_POST['password1']));
	$pw2 = (mysql_entities_fix_string($conn,$_POST['password2']));
	$token = hash('ripemd128', "$salt$pw1$salt");
	if ($pw1 !== $pw2){
	echo ("passwords don't match, try again");
	// reload_page();
	exit();
	}
	add_user($conn,$un, $fn, $ln, $token);
}


// add_user($conn,$un,$fn, $ln, $token);
// echo "<a href='authenticate.php'>Return to auth page</a>";

echo <<< _mark
	<form method='post' action='setupusers.php' enctype="multipart/form-data">
		<div class="form-group">
		    	Enter Your First Name <input type='text' name='firstname' maxlength="64">
			</div>
			<div class="form-group">
		    	Enter Your Last Name <input type='text' name='lastname' maxlength="64">
			</div>
	</form>
_mark;
require_once "auth_markup.html";


function add_user($conn, $un, $fn, $ln, $tk){
	// $query = "INSERT INTO users VALUES('$fn','$ln'$un','$tk')";
	// $result = $conn-> quere($query);
	// if (!$result) die($conn->error);
	echo "entered values: $un, $fn, $ln, $tk";
}

function reload_page(){
	header('Location: '.$_SERVER['PHP_SELF']);
	header('HTTP/1.0 401 Unauthorized');
}

function all_set(){
	return (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username'])&& !empty($_POST['password1']) && !empty($_POST['password2']));
}
?>