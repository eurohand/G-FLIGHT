<?PHP
if (!defined('_GLOBAL_NAYANA')) exit;

/////////호스팅 만료일 설정 ($start_date : mktime형태, period : 개월수)
if (!function_exists("last_date")) {
    function last_date ($start_date,$period) {
        $period = "+".$period." month";
        $end_date = strtotime($period,$start_date);

        return $end_date;
    }
}

////////남은 기간 계산
if (!function_exists("remain_date")) {
    function remain_date($end_date){
        $today = mktime(0,0,0,date(m),date(d),date(Y));
        $remain_day = round(($end_date - $today)/86400) -1;

        return $remain_day;
    }
}
?>