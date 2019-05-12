<?php
session_start();
logout($_COOKIE['username']);
header("Location:upload.php");
function destroy_session_and_data(){

	session_start();
    setcookie(session_name(),'',time()-2592000, '/');
    session_destroy();
}

function logout($username){
    setcookie('username',$username,time()-2592000,'/');
    session_destroy();
    destroy_session_and_data();
}

?>