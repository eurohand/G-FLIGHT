<?php
@header("Content-Type: text/html; charset=utf-8");

error_reporting(E_ALL& ~E_NOTICE);
ini_set("display_errors", 1);

define("_GLOBAL_MANGOMAP", "1");

include_once("../config/config.php");
include_once($CFG['ABSPATH']."/config/set_db.php");
include_once($CFG['ABSPATH']."/config/set_tbl.php");
include_once($CFG['ABSPATH']."/libs/lib.php");






$mailType="order-apply-ok";
$uniData="MG-20171023-1508745786";
mg_mail_send($mailType,$uniData); // (메일타입, 주문번호)







/*
$mailType="order-ready";
$uniData="MG-20171023-1508745786";
mg_mail_send($mailType,$uniData); // (메일타입, 주문번호)


$mailType="order-input";
$uniData="MG-20171023-1508745786";
mg_mail_send($mailType,$uniData); // (메일타입, 주문번호)


$mailType="order-insert";
$uniData="MG-20171023-1508745786";
mg_mail_send($mailType,$uniData); // (메일타입, 주문번호)


$mailType="order-cancel";
$uniData="MG-20171023-1508745786";
mg_mail_send($mailType,$uniData); // (메일타입, 주문번호)


$mailType="order-cancel-ok";
$uniData="MG-20171023-1508745786";
mg_mail_send($mailType,$uniData); // (메일타입, 주문번호)


$mailType="order-pre-1day";
$uniData="MG-20171023-1508745786";
mg_mail_send($mailType,$uniData); // (메일타입, 주문번호)

*/
?>