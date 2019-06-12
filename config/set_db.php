<?php
//데이터 베이스 설정


$url = getenv('mysql://wnvaak8rsg3pchfw:pimtbqjggrbf1tiv@thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/clp3n4gm746vekgy');
$dbparts = parse_url($url);


$hostname = 'thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$username = 'wnvaak8rsg3pchfw';
$password = 'pimtbqjggrbf1tiv';
$database = 'clp3n4gm746vekgy';

// $hostname = $dbparts['host'];
// $username = $dbparts['user'];
// $password = $dbparts['pass'];
// $database = ltrim($dbparts['path'],'/');
?>
