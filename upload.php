<?php
require_once "login.php";
require_once "markup.html";

$table_disp = 0;
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if ($_FILES && $_FILES['filename']['name']){
    $name = $_FILES['filename']['name'];
    $t_name = $_FILES['filename']['tmp_name'];
    $type = $_FILES['filename']['type'];
    $err = $_FILES['filename']['error'];
    $size = $_FILES['filename']['size'];
//     echo <<< end
//     the name  is: {$name} <br>
//     the temp name is : {$t_name} <br>
//     the type is : {$type} <br>
//     the error code is: {$err} <br>
//     the size is: {$size} <br>
// end;

    $name = strtolower(preg_replace("[^A-Za-z0-9.]", "", $name));
    if ($size > 1000000) die("Input file is too large");
    if ($err !== 0) handleError($err);
    if (!move_uploaded_file($t_name, $name)){
        die("Could Not Move File To Current Directory");
    }
    $content =  sanitizeInputs(file_get_contents($name));
    // escape quotes
    $content = addslashes($content);
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

if($table_disp === 0){
    table_print($conn);
}

function table_query($query, $connection){
    $data = $connection->query($query);
    if (!$data) die($connection->error);
    return $data;
}

function sanitizeInputs($str){
    $str = stripslashes($str);
    $str = strip_tags($str);
    $str = htmlentities($str);
    return $str;
}

function table_print($conn){
    $result = table_query("SELECT * FROM input;",$conn);
    $rows = $result->num_rows;
    echo <<< _b
    <table class="table">
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
_e;
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