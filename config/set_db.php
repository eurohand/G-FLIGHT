<?php
//데이터 베이스 설정

// $mydb['host'] = 'thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
// $mydb['user'] = 'wnvaak8rsg3pchfw';
// $mydb['pass'] = 'pimtbqjggrbf1tiv';
// $mydb['name'] = 'clp3n4gm746vekgy';

$url = getenv('mysql://wnvaak8rsg3pchfw:pimtbqjggrbf1tiv@thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/clp3n4gm746vekgy');
$dbparts = parse_url($url);

$hostname = $dbparts['host'];
$username = $dbparts['user'];
$password = $dbparts['pass'];
$database = ltrim($dbparts['path'],'/');
?>
