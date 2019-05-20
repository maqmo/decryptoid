<?php
require_once "login.php";
require_once "sanitize.php";
require_once "markup.html";
require_once "encrypt.php";

$table_disp = 0;
$username = 0;
$cipher = "";
if (!empty($_COOKIE['username']))
    $username =($_COOKIE['username']);
session_start();
if (empty($_SESSION['username']) && !$username){
    echo "<marquee scrollamount='1' behavior='alternate'><a href=\"authenticate.php\"> <h2>...you can sign in here</h2></a></marquee>";
}else{
    $forename = $_SESSION['forename'];
    $surname = $_SESSION['surname'];
    echo <<< _logged
    <h3 class=text-secondarym>hello, {$forename}, you're logged in as
    "{$_SESSION['username']}"</h3>
    <a href="logout.php" style="text-align:right" href="authenticate.php"><h1>sign out</h1></a>;
_logged;

}

require_once "markup.html";
echo <<< _body
        <div class="container">
            <form method='post' action='upload.php' enctype="multipart/form-data">
                <div class="form-group">
                    Enter a string, or upload a text file:<br> <input type='text' name='string' maxlength="128">
                </div>
                <div class="form-group">
                    <input type='file' name='filename' accept='.txt'
                    class="form-control-file">
                 </div>
                <button type="submit" class="btn btn-primary btn-block">Load Input</button>
                <div style="padding-top: 2.0em"class="form-group">
                    <button name='del_db' type="submit" class="btn btn-danger btn-block">Erase Table</button>
                    <div style="padding-top: 2.0em"class="form-group">
                    <button name='del_db' type="submit" class="btn btn-warning btn-block">Load Model</button>
            </form>
        </div>
    </body>
</html>

_body;
//$user = $_SESSION['username'];
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if ($_FILES && $_FILES['filename']['name']){
    $name = $_FILES['filename']['name'];
    $t_name = $_FILES['filename']['tmp_name'];
    $type = $_FILES['filename']['type'];
    $err = $_FILES['filename']['error'];
    $size = $_FILES['filename']['size'];

    $name = strtolower(preg_replace("[^A-Za-z0-9.]", "", $name));
    if ($size > 1000000) die("Input file is too large");
    if ($err !== 0) handleError($err);
    if (!move_uploaded_file($t_name, $name)){
        die("Could Not Move File To Current Directory");
    }
    $content = addslashes($content);
    $content =  sanitizeInputs(file_get_contents($name));
    $query = "INSERT INTO input(file_input) values('{$content}')";
	table_query($query, $conn);
	table_print($conn);
    $table_disp++;
}


if (isset($_POST['string']) && $_POST['string'] !== "") {
    $val = $_POST['string'];
    $str = sanitizeInputs($val);
    $query = "INSERT INTO input(string_input) values('{$str}');";
    table_query($query,$conn);
    table_print($conn);
    $table_disp++;
}

if(isset($_POST['del_db'])){
    $query = "TRUNCATE input;";
    table_query($query, $conn);
}

if (!empty($_POST['logout'])){
    logout();
    die(header("Location:upload.php"));
}

if (!empty($_POST['cipher'])){
    $cipher = $_POST['cipher'];
    echo "cipher chosen was $cipher";
}

if($table_disp === 0){
    table_print($conn);
}

function table_query($query, $connection){
    $data = $connection->query($query);
    if (!$data) die($connection->error);
    return $data;
}

function table_print($conn){
    // $result = table_query("SELECT * FROM input WHERE username = '{$user};'",$conn);
    $result = table_query("SELECT * FROM input",$conn);
    $rows = $result->num_rows;
    if ($rows > 0){
        echo <<< _b
        <div class=jumbotron>
            <h1 class=display-5> Your information entered</h3>
            <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Input Type</th>
                    <th scope="col">String Value</th>
                </tr>
            </thead>
                <tr>
_b;
        while ($row = $result->fetch_assoc()){
            foreach ($row as $field => $value){
                if ($value){
                    $field = explode("_", $field);
                    echo "<td><em>{$field[0]}</em></td><td> $value </td></tr>";
                }
            }
        }
        echo <<< _e
            </tbody>
        </table>
        </div>
_e;
    }
}

function handleError($errCode){
    $errorCodes = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );
    if ($errCode === 4){
        return;
    }
    die($errorCodes[$errCode]);
}
?>