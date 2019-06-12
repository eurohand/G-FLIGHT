<?php 
    
@header('Content-Type: text/html; charset=UTF-8');
@date_default_timezone_set('Asia/Seoul');
include_once("https://gflight.herokuapp.com/config/config.php");
include_once($CFG['ABSPATH']."/config/set_db.php");
include_once($CFG['ABSPATH']."/config/set_tbl.php");

$connect = mysqli_connect($hostname, $username, $password, $database);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connection was successfully established!";

mysqli_select_db($connect, $database);

$V = $_POST;

$score=trim($V['score']);
$date = date("Y-m-d");

if($score > 0){
    $name=trim($V['name']);
    mysqli_query($connect, "INSERT INTO ranking (score, name, date) VALUES ('$score', '$name', '$date')");
}
mysqli_close($connect);

// header("Location: ranking.php"); 

?>