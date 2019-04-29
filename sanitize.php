<?php

$salt = "qwe*&^";
function mysql_entities_fix_string($conn, $str){
	return htmlentities((mysql_fix_string($conn,$str)));
}
function mysql_fix_string($conn, $str){
	if (get_magic_quotes_gpc())$str = stripslashes($str);
	return $conn->real_escape_string($str);
}

function sanitizeInputs($str){
    $str = stripslashes($str);
    $str = strip_tags($str);
    $str = htmlentities($str);
    return $str;
}
?>