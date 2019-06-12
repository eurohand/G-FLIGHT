<?php
//데이터 베이스 설정
$url = getenv('mysql://wnvaak8rsg3pchfw:pimtbqjggrbf1tiv@thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/clp3n4gm746vekgy');
$dbparts = parse_url($url);

$mydb['host'] = $dbparts['host'];
$mydb['user'] = $dbparts['user'];
$mydb['pass'] = $dbparts['pass'];
$mydb['name'] = ltrim($dbparts['path'],'/');
?>
