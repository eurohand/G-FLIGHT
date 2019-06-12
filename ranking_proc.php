<?php 
    
@header('Content-Type: text/html; charset=UTF-8');
@date_default_timezone_set('Asia/Seoul');
include_once("config/config.php");


$hostname = 'thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$username = 'wnvaak8rsg3pchfw';
$password = 'pimtbqjggrbf1tiv';
$database = 'clp3n4gm746vekgy';

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

header("Location: ranking.php"); 

?>