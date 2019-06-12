<?php 
    
@header('Content-Type: text/html; charset=UTF-8');
@date_default_timezone_set('Asia/Seoul');
include_once("config/config.php");


$hostname = 'thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$username = 'wnvaak8rsg3pchfw';
$password = 'pimtbqjggrbf1tiv';
$database = 'clp3n4gm746vekgy';

$connect = mysqli_connect($hostname, $username, $password, $database);

// if (!$connect) {
//     die("Connection failed: " . mysqli_connect_error());
// }
// echo "Connection was successfully established!";

mysqli_select_db($connect, $database);

$V = $_POST;

$score=trim($V['score']);
$date = date("Y-m-d");


if($score > 0){
    $name=trim($V['name']);
    $hash=trim($V['hash']); 

    $sql="SELECT * FROM ranking WHERE name='$name' AND date='$date' LIMIT 1";
    $re = mysqli_query($connect, $sql);
    $data = mysqli_fetch_array($re);
    if($data['score'] > 0){
        if($data['score'] < $score ){
            mysqli_query($connect, "UPDATE ranking SET score='$score' WHERE date='$date' AND name='$name'");
        }
    }else{
        mysqli_query($connect, "INSERT INTO ranking (score, name, date, hash) VALUES ('$score', '$name', '$date', '$hash')");
    }

}
mysqli_close($connect);

header("Location: ranking.php"); 

?>