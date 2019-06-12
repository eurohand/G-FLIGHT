<?PHP

function DEBUG($array){
    Global $CFG;

	switch($CFG['DEBUG']){
		case 'ON':
			$ar_str = '[]'; 
			if(!is_array($array)){ 
				//$str =  $array;
				$str = '<span style="color:#60A7D6;">$'.array_search ($array, $GLOBALS).'</span><span style="font-family:Dotumche; font-size:11px;"> => '.$array;
				$ar_str = ''; 
			}else{ 
				$ar_str = '['.count($array).']'; 
				ob_start(); 
				print_r($array); 
				$str = ob_get_contents(); 
				ob_end_clean(); 
				$str = preg_replace('/ /', ' ', $str); 
				$str = preg_replace('/Array/', '<span style="color:#60A7D6;">$'.array_search ($array, $GLOBALS).$ar_str.'</span>', $str); 
				$str = nl2br('<span style="font-family:Dotumche; font-size:11px;color:#565656;">'.$str.'</span>'); 
			} 
			echo '<div align="left" style="font-size:11px; margin:10px; padding:10px;border: 1px solid gold">'; 
			echo '<font color=#C99260><b>DEBUG MODE</b></font><p>';
			echo $str; 
			echo '</div>'; 
			break;
		case 'OFF':
			break;
	}
}

function trim_Arr($arr){
	if(count($arr) > 0){
		$new_arr = Array();
		foreach($arr as $key => $val){
			$new_arr[$key] = trim($val);
		}
		return $new_arr;
	} else return $arr;
}

function Inc($num){
	return ($num+1);
}

function Dec($num){
	return ($num-1);
}

function strcut_utf8($str, $size){
	$substr = substr($str, 0, $size*2);
	$multi_size = preg_match_all('/[\x80-\xff]/', $substr, $multi_chars);
	if($multi_size >0) $size = $size + intval($multi_size/3)-1;
	if(strlen($str)>$size)
	{
		$str = substr($str, 0, $size);
		$str = preg_replace('/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str);
		$str = trim($str).'..';
	}
	return $str;
}

function GetChk($str, $value){
	if (is_array($value)) {
		unset($ar);
		foreach ($value as $val) {
			if ($val) $ar[] = $val;
		}
		$chk = (@in_array($str, $ar)) ? ' checked="checked"' : '';
	} else {
		$chk = ($str == $value) ? ' checked="checked"' : '';
	}
	return $chk;
}

function GetSelected($str, $value){
	$chk = ($str == $value) ? ' selected="selected"' : '';
	return $chk;
}

function paging($page, $totalpage, $qs="", $paging=10) {
	// 페이지 네비게이션에 보여질 페이지 개수
	if(!$paging) $paging = 10;

	// 시작 페이지 번호 설정
	if($page%$paging==0){
		$startpage = $page-($paging-1);
	}else{
		$startpage = intval($page/$paging)*$paging+1;
	}

	// 이전 페이지 설정
	$prevpage = $startpage-1;
	// 다음 페이지 설정
	$nextpage = $startpage+$paging;

	// 마지막 페이징 번호 설정
	if($totalpage/$paging>1){
		$laststartpage = (intval($totalpage/$paging)*$paging)+1;
	}else{
		$laststartpage = 1;
	}

	$rt = "<div class=\"paging_wrap\">\n";
	// 첫 페이지로 돌아가기 버튼
	if($page>$paging){
		if($qs){
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=1&".$qs."' class=\"paging_btn active\">First</a>\n";
		}else{
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=1' class=\"paging_btn active\">First</a>\n";
		}
	}else{
		$rt .= "<a class=\"paging_btn\">First</a>\n";
	}

	// 이전 페이지로 돌아가기 버튼
	if($totalpage>$paging && $page>$paging){
		if($qs){
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$prevpage."&".$qs."' class=\"paging_btn active\">Prev</a>\n";
		}else{
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$prevpage."' class=\"paging_btn active\">Prev</a>\n";
		}		
	}else{
		$rt .= "<a class=\"paging_btn\">Prev</a>\n";
	}

	$rt .= "<div class=\"paging\">\n";

	if($totalpage<=1){
		$rt .= "<a class=\"page active\">1</a>\n";
	}else{
		// 페이지 링크 번호 나열
		for($i=$startpage; $i<=($startpage+($paging-1)); $i++){

			if($page!=$i){
				if($qs){
					$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$i."&".$qs."' class=\"page\">".$i."</a>\n";
				}else{
					$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$i."' class=\"page\">".$i."</a>\n";
				}
			}else{
				$rt .= "<a class=\"page active\">".$i."</a>\n";
			}

			if($i>=$totalpage){
				break;
			}
		}
	}

	$rt .= "</div>\n";

	// 다음 페이지로 넘어가기 버튼
	if($startpage+$paging-1<$totalpage){
		if($qs){
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$nextpage."&".$qs."' class=\"paging_btn active\">Next</a>\n";
		}else{
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$nextpage."' class=\"paging_btn active\">Next</a>\n";
		}
	}else{
		$rt .= "<a class=\"paging_btn\">Next</a>\n";
	}

	// 마지막 페이지로 이동 버튼
	if($page<intval($laststartpage)){
		if ( $qs ) {
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$totalpage."&".$qs."' class=\"paging_btn active\">Last</a>\n";
		} else {
			$rt .= "<a href='".$_SERVER["PHP_SELF"]."?page=".$totalpage."' class=\"paging_btn active\">Last</a>\n";
		}
	}else{
		$rt .= "<a class=\"paging_btn\">Last</a>\n";
	}
	$rt .= "</div>\n";

	return $rt;
}

// 메인 페이지 검색 목록 페이징 처리
function main_search_paging($page, $totalpage, $paging=10) {
	global $CFG;
	// 페이지 네비게이션에 보여질 페이지 개수
	if(!$paging) $paging = 10;

	// 시작 페이지 번호 설정
	if($page%$paging==0){
		$startpage = $page-($paging-1);
	}else{
		$startpage = intval($page/$paging)*$paging+1;
	}

	// 이전 페이지 설정
	$prevpage = $startpage-1;
	// 다음 페이지 설정
	$nextpage = $startpage+$paging;

	// 마지막 페이징 번호 설정
	if($totalpage/$paging>1){
		$laststartpage = (intval($totalpage/$paging)*$paging)+1;
	}else{
		$laststartpage = 1;
	}

	$rt = "<div class=\"clear_b\"></div>\n
			<div class=\"paging pc_only\">";
	// 첫 페이지로 돌아가기 버튼
	if($page>$paging){
		$rt .= "<a href=\"javascript:jQuery('#page').val('1');main_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_first.png' alt='처음으로' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_first.png' alt='처음으로' /></a>\n";
	}

	// 이전 페이지로 돌아가기 버튼
	if($totalpage>$paging && $page>$paging){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$prevpage."');main_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_prev.png' alt='이전' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_prev.png' alt='이전' /></a>\n";
	}

	if($totalpage<=1){
		$rt .= "<a style='color:#ff6d24;'>1</a>\n";
	}else{
		// 페이지 링크 번호 나열
		for($i=$startpage; $i<=($startpage+($paging-1)); $i++){

			if($page!=$i){
				$rt .= "<a href=\"javascript:jQuery('#page').val('".$i."');main_search_submit();\">".$i."</a>\n";
			}else{
				$rt .= "<a style='color:#ff6d24;'>".$i."</a>\n";
			}

			if($i>=$totalpage){
				break;
			}
		}
	}

	// 다음 페이지로 넘어가기 버튼
	if($startpage+$paging-1<$totalpage){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$nextpage."');main_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_next.png' alt='다음' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_next.png' alt='다음' /></a>\n";
	}

	// 마지막 페이지로 이동 버튼
	if($page<intval($laststartpage)){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$totalpage."');main_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_last.png' alt='마지막으로' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_last.png' alt='마지막으로' /></a>\n";
	}
	$rt .= "</div>\n";

	return $rt;
}

// 리스트 페이지 검색 목록 페이징 처리
function list_search_paging($page, $totalpage, $paging=10) {
	global $CFG;
	// 페이지 네비게이션에 보여질 페이지 개수
	if(!$paging) $paging = 10;

	// 시작 페이지 번호 설정
	if($page%$paging==0){
		$startpage = $page-($paging-1);
	}else{
		$startpage = intval($page/$paging)*$paging+1;
	}

	// 이전 페이지 설정
	$prevpage = $startpage-1;
	// 다음 페이지 설정
	$nextpage = $startpage+$paging;

	// 마지막 페이징 번호 설정
	if($totalpage/$paging>1){
		$laststartpage = (intval($totalpage/$paging)*$paging)+1;
	}else{
		$laststartpage = 1;
	}

	$rt = "<div class=\"clear_b\"></div>\n
			<div class=\"paging pc_only\">";
	// 첫 페이지로 돌아가기 버튼
	if($page>$paging){
		$rt .= "<a href=\"javascript:jQuery('#page').val('1');list_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_first.png' alt='처음으로' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_first.png' alt='처음으로' /></a>\n";
	}

	// 이전 페이지로 돌아가기 버튼
	if($totalpage>$paging && $page>$paging){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$prevpage."');list_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_prev.png' alt='이전' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_prev.png' alt='이전' /></a>\n";
	}

	if($totalpage<=1){
		$rt .= "<a style='color:#ff6d24;'>1</a>\n";
	}else{
		// 페이지 링크 번호 나열
		for($i=$startpage; $i<=($startpage+($paging-1)); $i++){

			if($page!=$i){
				$rt .= "<a href=\"javascript:jQuery('#page').val('".$i."');list_search_submit();\">".$i."</a>\n";
			}else{
				$rt .= "<a style='color:#ff6d24;'>".$i."</a>\n";
			}

			if($i>=$totalpage){
				break;
			}
		}
	}

	// 다음 페이지로 넘어가기 버튼
	if($startpage+$paging-1<$totalpage){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$nextpage."');list_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_next.png' alt='다음' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_next.png' alt='다음' /></a>\n";
	}

	// 마지막 페이지로 이동 버튼
	if($page<intval($laststartpage)){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$totalpage."');list_search_submit();\"><img src='".$CFG['URL']."/images/sub/paging_last.png' alt='마지막으로' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_last.png' alt='마지막으로' /></a>\n";
	}
	$rt .= "</div>\n";

	return $rt;
}

// 리스트 페이지 검색 목록 페이징 처리
function list_normal_paging($page, $totalpage, $paging=10) {
	global $CFG;
	// 페이지 네비게이션에 보여질 페이지 개수
	if(!$paging) $paging = 10;

	// 시작 페이지 번호 설정
	if($page%$paging==0){
		$startpage = $page-($paging-1);
	}else{
		$startpage = intval($page/$paging)*$paging+1;
	}

	// 이전 페이지 설정
	$prevpage = $startpage-1;
	// 다음 페이지 설정
	$nextpage = $startpage+$paging;

	// 마지막 페이징 번호 설정
	if($totalpage/$paging>1){
		$laststartpage = (intval($totalpage/$paging)*$paging)+1;
	}else{
		$laststartpage = 1;
	}

	$rt = "<div class=\"clear_b\"></div>\n
			<div class=\"paging\">";
	// 첫 페이지로 돌아가기 버튼
	if($page>$paging){
		$rt .= "<a href=\"javascript:jQuery('#page').val('1');list_normal_submit();\"><img src='".$CFG['URL']."/images/sub/paging_first.png' alt='처음으로' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_first.png' alt='처음으로' /></a>\n";
	}

	// 이전 페이지로 돌아가기 버튼
	if($totalpage>$paging && $page>$paging){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$prevpage."');list_normal_submit();\"><img src='".$CFG['URL']."/images/sub/paging_prev.png' alt='이전' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_prev.png' alt='이전' /></a>\n";
	}

	if($totalpage<=1){
		$rt .= "<a style='color:#ff6d24;'>1</a>\n";
	}else{
		// 페이지 링크 번호 나열
		for($i=$startpage; $i<=($startpage+($paging-1)); $i++){

			if($page!=$i){
				$rt .= "<a href=\"javascript:jQuery('#page').val('".$i."');list_normal_submit();\">".$i."</a>\n";
			}else{
				$rt .= "<a style='color:#ff6d24;'>".$i."</a>\n";
			}

			if($i>=$totalpage){
				break;
			}
		}
	}

	// 다음 페이지로 넘어가기 버튼
	if($startpage+$paging-1<$totalpage){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$nextpage."');list_normal_submit();\"><img src='".$CFG['URL']."/images/sub/paging_next.png' alt='다음' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_next.png' alt='다음' /></a>\n";
	}

	// 마지막 페이지로 이동 버튼
	if($page<intval($laststartpage)){
		$rt .= "<a href=\"javascript:jQuery('#page').val('".$totalpage."');list_normal_submit();\"><img src='".$CFG['URL']."/images/sub/paging_last.png' alt='마지막으로' /></a>\n";
	}else{
		$rt .= "<a><img src='".$CFG['URL']."/images/sub/paging_last.png' alt='마지막으로' /></a>\n";
	}
	$rt .= "</div>\n";

	return $rt;
}



// htmlspecialchars 역변환 함수
function unhtmlentities($string){
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	$ret = strtr($string, $trans_tbl);
	return preg_replace('/\&\#([0-9]+)\;/me', "chr('\\1')", $ret);
}

// 문자열 자르기
function resizeString($Str, $size, $addStr="..."){
	if(mb_strlen($Str, "utf-8") > $size) return mb_substr($Str, 0, $size, "utf-8").$addStr;
	else return $Str;
}

function arr_cut($arr,$chk){
	if(count($arr)>0){
		$new_arr = Array();		
		foreach($arr as $key => $val){
			$is_ok = False;
			for($i=0;$i<count($chk);$i++){
				if($chk[$i] == $key && !$is_ok) $is_ok = True;
			}
			if(!$is_ok) $new_arr[$key] = $val;
		}
		return $new_arr;
	} else  return $arr;
}

// 세션변수 생성
function set_session($session_name, $value){
    if (PHP_VERSION < '5.3.0')
        session_register($session_name);
    // PHP 버전별 차이를 없애기 위한 방법
    $$session_name = $_SESSION["$session_name"] = $value;
}

function del_session($session_name){
    if (PHP_VERSION < '5.3.0')
        session_unregister($session_name);
    unset($_SESSION["$session_name"]);
}

// 세션변수값 얻음
function get_session($session_name)
{
    return $_SESSION[$session_name];
}

// 세션변수값 얻음
function get_sns_session($session_name)
{
	$snsID="";
	if($_SESSION['snsLogin']=='Y'){
		$snsData=unserialize($_SESSION['snsLoginData']);
		if($snsData['sns_id']) $snsID=$snsData['sns_id'];
	}

	return $snsID;
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    global $g4;

    setcookie(md5($cookie_name), base64_encode($value), $g4[server_time] + $expire, '/', $g4[cookie_domain]);
}


// 쿠키변수값 얻음
function get_cookie($cookie_name){
    return base64_decode($_COOKIE[md5($cookie_name)]);
}


// 문자열에 utf8 문자가 들어 있는지 검사하는 함수
// 코드 : http://in2.php.net/manual/en/function.mb-check-encoding.php#95289
function is_utf8($str) { 
    $len = strlen($str); 
    for($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]); 
        if ($c > 128) { 
            if (($c > 247)) return false; 
            elseif ($c > 239) $bytes = 4; 
            elseif ($c > 223) $bytes = 3; 
            elseif ($c > 191) $bytes = 2; 
            else return false; 
            if (($i + $bytes) > $len) return false; 
            while ($bytes > 1) { 
                $i++; 
                $b = ord($str[$i]); 
                if ($b < 128 || $b > 191) return false; 
                $bytes--; 
            } 
        } 
    } 
    return true; 
}

function is_han($str) { 
    $preg_hangul = '{\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}';
    if(preg_match('/['.$preg_hangul.']+/u', $str)){
        return true;
    }else return false;
}

function get_filesize($size){
    //$size = @filesize(addslashes($file));
    if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "M";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "K";
    } else {
        $size = number_format($size, 0) . "byte";
    }
    return $size;
}

function get_filesize_t($size){
    //$size = @filesize(addslashes($file));
	if ($size >= 1099511627776) {
		$size = number_format($size/1099511627776, 1) . "TB";
	} else if ($size >= 1073741824) {
        $size = number_format($size/1073741824, 1) . "GB";
	} else if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "M";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "K";
    } else {
        $size = number_format($size, 0) . "byte";
    }
    return $size;
}

function get_filesize_k($size){
    if ($size >= 1073741824) {
        $size = number_format($size/1073741824, 1) . "TB";
	} else if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "GB";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "M";
    } else {
        $size = number_format($size, 0) . "K";
    }
    return $size;
}

function get_filesize_km($size){
    if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "M";
    } else {
        $size = number_format($size, 0) . "K";
    }
    return $size;
}

function sectotime($sec){
	$h = floor($sec/3600); 
	$m = floor($sec/60)%60; 
	$s = $sec%60; 
	return sprintf('%02d:%02d:%02d',$h,$m,$s);
}

function array_ins($arr, $key, $key_val, $name, $val){
	$chk = True;
	for($i=0;$i<count($arr);$i++){
		if($arr[$i][$key] == $key_val){
			$arr[$i][$name] = $val;
			$chk = False;
			$res = $arr;
			break;
		}
	}
	if($chk){
		array_push($arr, array($key => $key_val));		
		$res = array_ins($arr, $key, $key_val, $name, $val);
	}
	return $res;
}

function key_encode($str){
	$key = Array();
	list($usec, $sec) = explode(" ", microtime());
	$key_time = (float)$usec+(float)$sec;
	$key['ori'] = $str;		
	$str = "key:".$str;
	$str = md5($str);
	$key['md5'] = $str;
	$str = base64_encode("NAYANA;".$str.";".$key_time);
	$key['res'] = $str;
	return $key;
}

function key_decode($str){
	$key = base64_decode($str);
	$key = explode(";",$key);
	return $key;
}

function key_reload($str){
	$key = Array();
	list($usec, $sec) = explode(" ", microtime());
	$key_time = (float)$usec+(float)$sec;
	$key['md5'] = $str;
	$str = base64_encode("NAYANA;".$str.";".$key_time);
	$key['res'] = $str;
	return $key;
}

function hp_num_check($str){
	if(preg_match ('/[0][1][0-9]{8}/', $str)){
		return true;
	} else return false;
}

function num_check($str){
	if(preg_match ('/[0-9]/', $str)){
		return true;
	} else return false;
}

function mail_check($str){
	if(preg_match ('/[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+/', $str)){
		return true;
	} else return false;
}

function pass_check($str){
    if ( !preg_match('/^[0-9A-Za-z~!@#%^&*]{8,20}$/', $str) || !preg_match('/\d/', $str) || !preg_match('/[a-zA-Z]/', $str) || !preg_match('/[~!@#%^&*]/', $str) ) {
        return false;
    } else return true;
}

function mail_id_check($str){
    if( !preg_match('/^[a-z0-9]{2,8}$/', $str)){
        return false;
    } else return true;
}

function ac_id_check($str){
    if( !preg_match('/^[a-z0-9]{6,15}$/', $str)){
        return false;
    } else return true;
}

function attack_check($str){
	$res = true;
	if(preg_match('/[\'"]/', $str)){
		$res = false;
	}
	if(preg_match('/[\/\\\\]/', $str)){
		$res = false;
	}
	if(preg_match('/(\band\b|\bnull\b|\bwhere\b|\blimit\b)/i', $str)){
		$res = false;
	}
	return $res;
}

//파일 확장자 가져오기
function file_exec($fname){
	$file = @explode('.', $fname);
	$exec = $file[(count($file) - 1)];

	return $exec;
}

// 공간, 지역 카테고리명 가져오기
if(!function_exists('mg_get_category_name')){
	function mg_get_category_name($cType="space", $cIdx=""){ // 공간/지역, 인덱스
		global $CFG, $TBL;

		$tblName=$rtnCategoryName="";

		if($cType=='space') $tblName=$TBL['SP_CATEGORY'];
		else $tblName=$TBL['LO_CATEGORY'];

		$data = dbfetch("SELECT * FROM {$tblName} WHERE idx='{$cIdx}'");
		if($cType=='space') $rtnCategoryName=$data['c_name'];
		else $rtnCategoryName=$data['l_name'];

		return $rtnCategoryName;
	}
}


//썸네일 이미지 생성
function getThumb($param){
	if(empty($param['o_path']))		return array('bool' => false, 'msg' => '원본 파일 경로가 없습니다.');
	if(empty($param['n_path']))		return array('bool' => false, 'msg' => '원본 파일 경로가 없습니다.');
	if(!in_array($param['mode'], array('ratio', 'fixed')))	$param['mode'] = 'ratio';
	if(empty($param['width']))		$param['width'] = 300;
	if(empty($param['height']))		$param['height'] = 300;
	if(!in_array($param['fill_yn'], array('Y', 'N')))		$param['fill_yn'] = 'N';
	if(!in_array($param['preview_yn'], array('Y', 'N')))	$param['preview_yn'] = 'Y';

	// 미리보기 방지 이미지 url
	if($param['preview_yn'] == 'N')	$param['o_path'] = './hidden.png';

	$src = array();
	$dst = array();

	$src['path'] = $param['o_path'];
	$dst['path'] = $param['n_path'];

	// 썸네일 이미지 갱신 기간 (1주일)
	if(file_exists($dst['path'])){
		if(mktime() - filemtime($dst['path']) < 60 * 60 * 24 * 7)	return array('bool' => true, 'src' => $dst['path']);
	}

	$imginfo = getimagesize($src['path']);
	$src['mime'] = $imginfo['mime'];

	// 원본 이미지 리소스 호출
	switch($src['mime']){
		case 'image/jpeg' :	$src['img'] = imagecreatefromjpeg($src['path']);	break;
		case 'image/gif' :	$src['img'] = imagecreatefromgif($src['path']);		break;
		case 'image/png' :	$src['img'] = imagecreatefrompng($src['path']);		break;
		case 'image/bmp' :	$src['img'] = imagecreatefrombmp($src['path']);		break;
		// mime 타입이 해당되지 않으면 return false
		default :		return array('bool' => false, 'msg' => '이미지 파일이 아닙니다.');						break;
	}

	// 원본 이미지 크기 / 좌표 초기값
	$src['w'] = $imginfo[0];
	$src['h'] = $imginfo[1];
	$src['x'] = 0;
	$src['y'] = 0;

	// 썸네일 이미지 좌표 초기값 설정
	$dst['x'] = 0;
	$dst['y'] = 0;

	// 썸네일 이미지 가로, 세로 비율 계산
	$dst['ratio']['w'] = $src['w'] / $param['width'];
	$dst['ratio']['h'] = $src['h'] / $param['height'];

	switch($param['mode']){
		case 'ratio' :
			// 썸네일 이미지의 비율계산 (가로 == 세로)
			if($dst['ratio']['w'] == $dst['ratio']['h']){
				$dst['w'] = $param['width'];
				$dst['h'] = $param['height'];
			}
			// 썸네일 이미지의 비율계산 (가로 > 세로)
			elseif($dst['ratio']['w'] > $dst['ratio']['h']){
				$dst['w'] = $param['width'];
				$dst['h'] = round(($param['width'] * $src['h']) / $src['w']);
			}
			// 썸네일 이미지의 비율계산 (가로 < 세로)
			elseif($dst['ratio']['w'] < $dst['ratio']['h']){
				$dst['w'] = round(($param['height'] * $src['w']) / $src['h']);
				$dst['h'] = $param['height'];
			}

			if($param['fill_yn'] == 'Y'){
				$dst['canvas']['w'] = $param['width'];
				$dst['canvas']['h'] = $param['height'];
				$dst['x'] = $param['width'] > $dst['w'] ? ($param['width'] - $dst['w']) / 2 : 0;
				$dst['y'] = $param['height'] > $dst['h'] ? ($param['height'] - $dst['h']) / 2 : 0;
			}
			else{
				$dst['canvas']['w'] = $dst['w'];
				$dst['canvas']['h'] = $dst['h'];
			}
			break;
		case 'fixed' :
			// 썸네일 이미지의 비율계산 (가로 == 세로)
			if($dst['ratio']['w'] == $dst['ratio']['h']){
				$dst['w'] = $param['width'];
				$dst['h'] = $param['height'];
			}
			// 썸네일 이미지의 비율계산 (가로 > 세로)
			elseif($dst['ratio']['w'] > $dst['ratio']['h']){
				$dst['w'] = $src['w'] / $dst['ratio']['h'];
				$dst['h'] = $param['height'];

				$src['x'] = ($dst['w'] - $param['width']) / 2;
			}
			// 썸네일 이미지의 비율계산 (가로 < 세로)
			elseif($dst['ratio']['w'] < $dst['ratio']['h']){
				$dst['w'] = $param['width'];
				$dst['h'] = $src['h'] / $dst['ratio']['w'];

				$dst['y'] = 0;
			}
			$dst['canvas']['w'] = $param['width'];
			$dst['canvas']['h'] = $param['height'];
			break;
	}

	// 썸네일 이미지 리소스 생성
	$dst['img'] = imagecreatetruecolor($dst['canvas']['w'], $dst['canvas']['h']);

	// 배경색 처리
	if(in_array($src['mime'], array('image/png', 'image/gif'))){
		// 배경 투명 처리
		imagetruecolortopalette($dst['img'], false, 255);
		$bgcolor = imagecolorallocatealpha($dst['img'], 255, 255, 255, 127);
		imagefilledrectangle($dst['img'], 0, 0, $dst['canvas']['w'],$dst['canvas']['h'], $bgcolor);	
	}
	else{
		// 배경 흰색 처리
		$bgclear = imagecolorallocate($dst['img'],255,255,255);
		imagefill($dst['img'],0,0,$bgclear);
	}

	// 원본 이미지 썸네일 이미지 크기에 맞게 복사
	imagecopyresampled($dst['img'],$src['img'],$dst['x'],$dst['y'],$src['x'],$src['y'],$dst['w'],$dst['h'],$src['w'],$src['h']);

	// imagecopyresampled 함수 사용 불가시 사용
	//imagecopyresized($dst['img'],$src['img'],$dst['x'],$dst['y'],$src['x'],$src['y'],$dst['w'],$dst['h'],$src['w'],$src['h']);

	ImageInterlace($dst['img']);

	// 썸네일 이미지 리소스를 기반으로 실제 이미지 생성
	switch($src['mime']){
		case 'image/jpeg' :	imagejpeg($dst['img'], $dst['path']);	break;
		case 'image/gif' :	imagegif($dst['img'], $dst['path']);	break;
		case 'image/png' :	imagepng($dst['img'], $dst['path']);	break;
		case 'image/bmp' :	imagebmp($dst['img'], $dst['path']);	break;
	}

	// 원본 이미지 리소스 종료
	imagedestroy($src['img']);
	// 썸네일 이미지 리소스 종료
	imagedestroy($dst['img']);

	// 썸네일 파일경로 존재 여부 확인후 리턴
	return file_exists($dst['path']) ? array('bool' => true, 'src' => $dst['path']) : array('bool' => false, 'msg' => '파일 생성에 실패하였습니다.');
}


// 모바일 여부 체크  : mg_is_mobile() => result : true/false (모바일 : true)
if(!function_exists('mg_is_mobile')){
	function mg_is_mobile() {
		if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
			$is_mobile = false;
		} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
				$is_mobile = true;
		} else {
			$is_mobile = false;
		}
	 
		return $is_mobile;
	}
}

// 예약 시 공휴일 체크
if(!function_exists('mg_time_holiday_check')){
	function mg_time_holiday_check($forDate,$cHoliday){
		$targetArr=explode("-",$forDate);
		$targetTime=mktime(0, 0, 0, $targetArr[1], $targetArr[2], $targetArr[0]);
		$rtnFlag=false;
		for($z=0;$z<sizeof($cHoliday['common_holiday_period']);$z++){
			$cmPeriod=str_replace(" ","",$cHoliday['common_holiday_period'][$z]);
			$tmpCmPeriod=explode("~",$cmPeriod);
			$stCmPeriod=explode("-",$tmpCmPeriod['0']);
			$startTime=mktime(0, 0, 0, $stCmPeriod[0], $stCmPeriod[1], date("Y"));
			$enCmPeriod=explode("-",$tmpCmPeriod['1']);
			$endTime=mktime(0, 0, 0, $enCmPeriod[0], $enCmPeriod[1], date("Y"));

			if($targetTime>=$startTime && $targetTime<=$endTime){
				$rtnFlag=true;
				break;
			}
		}
		return $rtnFlag;
	}
}


?>
