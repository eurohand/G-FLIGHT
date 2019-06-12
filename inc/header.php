<?PHP
session_start();
@header('Content-Type: text/html; charset=UTF-8');
@date_default_timezone_set('Asia/Seoul');

include_once("C:/xampp/htdocs/game/config/config.php");
include_once($CFG['ABSPATH']."/config/set_db.php");
include_once($CFG['ABSPATH']."/config/set_tbl.php");
include_once($CFG['ABSPATH']."/libs/lib.php");
include_once($CFG['ABSPATH']."/libs/func.php");
include_once($CFG['ABSPATH']."/libs/curl_lib.php");

DB_Conn();

$V = $_POST;
$G = $_GET;
$R = $_REQUEST;
$F = $_FILES;

$CIP = $_SERVER['REMOTE_ADDR'];
$SELF = $_SERVER['PHP_SELF'];
$THIS_TIME = time();
?>
