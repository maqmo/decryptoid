<?php
require_once "sanitize.php";
require_once "markup.html";
require_once "login.php";
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (!empty($_POST['username']) && !empty($_POST['password1'])){
	$un_temp = mysql_entities_fix_string($conn, $_POST['username']);
	$pw_temp = mysql_entities_fix_string($conn, $_POST['password1']);
	//echo $un_temp, $pw_temp;
	if (!validate_then_sessionize($conn,$un_temp,$pw_temp)){
		die("<a href='authenticate.php'>Invalid username/password, try again</a>");
	}
	else
		header("Location: continue.php");
}
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
echo <<< _i
<div class='container'>
<div class="card" style="width:40rem;">
	<div class="card-body">
	<h3 class="card-title text-primary">sign in</h3>
    <h6 class="card-subtitle mb-2 text-muted">but don't expect too much</h6>
	 	<form method='post' action='authenticate.php' enctype="multipart/form-data">
			<div class="form-group">
		    	<label for='un'>Enter Your User Name</label> <input type='text' name='username' id='un' maxlength="64">
			</div>
			<div class="form-group">
		    	<label for='pw'>Enter Your Password </label><input id='pw' type='password' name='password1' maxlength="64">
			</div>
		  	<button type="submit" class="btn btn-primary btn-block">Enter Credentials</button>
		</form>
		<marquee scrollamount="1"
direction="left"
behavior="alternate">
<a href="setupusers.php"><h1>...or create an account here</h1></a>
</marquee>
	</div>
	</div>
</body>
</html>

_i;
?>