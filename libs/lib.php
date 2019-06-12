<?PHP

// 공용 라이브러리
function DB_Conn(){
	Global $mydb;
	Global $CFG;

	$conn = mysqli_connect($mydb['host'], $mydb['user'], $mydb['pass']);

    if($CFG['LANG']=="euc-kr") mysql_query("set names euckr");
	else if($CFG['LANG']=="utf-8") mysql_query(" set names utf8 ");

    Return mysqli_select_db($mydb['name'], $conn);
}

function query($sql){
	$result =@mysql_query($sql);
	return $result;
}

function dbfetch($sql){
	$result = @query($sql);
	$result = @mysql_fetch_assoc($result);
	return $result;
}

function Alert_Msg($msg){
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>alert('".$CFG['TITLE']."\\n\\n".$msg."'); </script>";
}

function Alert_Msg_progress($msg){
	Global $CFG;
	//echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>parent.progress_view(1); alert('".$CFG['TITLE']."\\n\\n".$msg."'); </script>";
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>alert('".$CFG['TITLE']."\\n\\n".$msg."'); </script>";
}

function Alert_Msg_Move($msg, $link){
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>alert('".$CFG['TITLE']."\\n\\n".$msg."'); location.href='".$link."';</script>";
}

function Alert_Msg_preset($msg){
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>alert('".$CFG['TITLE']."\\n\\n".$msg."'); parent.location.reload();</script>";
}

function Alert_Msg_pMove($msg, $link){
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>alert('".$CFG['TITLE']."\\n\\n".$msg."'); parent.location.href='".$link."';</script>";
}

function Alert_Msg_ppMove($msg, $link){
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>alert('".$CFG['TITLE']."\\n\\n".$msg."'); parent.parent.location.href='".$link."';</script>";
}

function Location($link) {
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'>location.href='".$link."';</script>";exit;
}

function PLocation($link) {
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'> parent.location.href='".$link."';</script>";exit;
}

function Preset() {
	Global $CFG;
	echo "<meta http-equiv='content-type' content='text/html; charset=".$CFG['LANG']."'><script language='JavaScript'> parent.location.reload();</script>";exit;
}

// [사용자] GNB 카테고리 출력
if(!function_exists('get_gnb_category')){
	function get_gnb_category(){
		global $conn,$TBL,$CFG;

		$rtnGnb="";
		$nCnt = mysql_fetch_array(query("SELECT count(*) AS cnt FROM {$TBL['SP_CATEGORY']} WHERE c_use='Y'"));

		if($nCnt['cnt']>'0'){
			$rtnGnb .="<ul>";
			$result = query("SELECT * FROM {$TBL['SP_CATEGORY']} WHERE c_use='Y' AND depth_1>'0' AND depth_2='0' AND depth_3='0' ORDER BY c_rank ASC");

			for($z=1;$n_data=mysql_fetch_array($result);$z++){
				$rtnGnb .="<li class='m0".$z."'><a href='".$CFG['URL']."/place/list.html?space=".$n_data['c_link']."' title='".$n_data['c_name']."'>".$n_data['c_name']."</a>";
				$sCnt = mysql_fetch_array(query("SELECT count(*) AS cnt FROM {$TBL['SP_CATEGORY']} WHERE c_use='Y' AND depth_1='".$n_data['depth_1']."' AND depth_2>'0'"));

				if($sCnt['cnt']>'0'){
					$rtnGnb .="<ul>";
					$sResult = query("SELECT * FROM {$TBL['SP_CATEGORY']} WHERE c_use='Y' AND depth_1='".$n_data['depth_1']."' AND depth_2>'0' AND depth_3='0' ORDER BY c_rank ASC");

					for($s=1;$s_data=mysql_fetch_array($sResult);$s++){

						$rtnGnb .="<li><a href='".$CFG['URL']."/place/list.html?space=".$s_data['c_link']."' title='".$s_data['c_name']."'>".$s_data['c_name']."</a></li>";
					}

					$rtnGnb .="</ul>";
				}

				$rtnGnb .="</li>";
			}

			$rtnGnb .="</ul>";
		}

		return $rtnGnb;
	}
}


if(!function_exists('mg_mail_send')){
	function mg_mail_send($mailType,$uniData){ // 메일타입, USER ID
		global $TBL,$CFG;
		DB_Conn();

		$siteData = mysql_fetch_array(query("SELECT * from {$TBL['CF_SITE']} WHERE idx<>'' ORDER BY idx DESC LIMIT 1"));

		$fname=$CFG['MAIL_TITLE'];
		$mail_from=$siteData['common_email'];

		$headers = "Content-Type: text/html;charset=utf-8\n";
		$headers .= "From: =?utf-8?B?".base64_encode($fname)."?= <".$mail_from."> \n";
		$headers .= "X-Sender: <".$mail_from.">\n";
		$headers .= "X-Mailer: PHP\n";
		$headers .= "Reply-To: =?utf-8?B?".base64_encode($fname)."?= <".$mail_from."> \n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Return-Path: <".$mail_from.">\n";

		$mailData=mg_get_mail_content($mailType,$uniData);                             // 메일내용
		$mailData['subject']= "=?UTF-8?B?".base64_encode($mailData['subject'])."?="."\r\n";

		if($mailData['to'] && $mailData['subject'] && $mailData['message']){
			$result = mail($mailData['to'], $mailData['subject'], $mailData['message'], $headers, '-f'.$mail_from);
			//echo "메일방송 결과 : ".$result;
		}
	}
}

// 메일 내용 가져오기
if(!function_exists('mg_get_mail_content')){
	function mg_get_mail_content($mailType,$uniData){
		global $TBL,$CFG;
		DB_Conn();

		unset($rtnData);
		$mailFile=Array("order-ready"=>"order_ready.php","order-input"=>"order_input.php","order-pre-1day"=>"order_pre_1day.php","order-apply-ok"=>"order_apply_ok.php","order-cancel"=>"order_cancel_reg.php","order-cancel-ok"=>"order_cancel_ok.php");
		$mailStatus=Array("order-ready"=>"R","order-input"=>"A","order-pre-1day"=>"F","order-apply-ok"=>"F","order-cancel"=>"C","order-cancel-ok"=>"X"); // 'R'=>"입금대기",'A'=>"접수완료",'T'=>"승인대기",'F'=>"승인완료",'U'=>"이용완료",'C'=>"취소요청", 'Z'=>"취소대기",'X'=>"취소완료"

		$mailSubject=Array("order-ready"=>"{mem_name}님 예약이 정상적으로 접수되었습니다.","order-input"=>"{mem_name}님 입금이 정상적으로 처리되었습니다.","order-pre-1day"=>"{mem_name}님 예약일 1일 전입니다.","order-apply-ok"=>"{mem_name}님 예약이 승인완료 되었습니다.","order-cancel"=>"{mem_name}님 예약취소가 정상적으로 접수되었습니다.","order-cancel-ok"=>"{mem_name}님 예약취소가 정상적으로 완료되었습니다.");

		$rtnData['message']=file_get_contents($CFG['URL']."/mail/".$mailFile[$mailType]);
		$rtnData['subject']=$mailSubject[$mailType];
		$rtnData['to']="jyseo@nayana.com";

		if($uniData && ($mailType=="order-ready" || $mailType=="order-input" || $mailType=="order-pre-1day" || $mailType=="order-apply-ok" || $mailType=="order-cancel" || $mailType=="order-cancel-ok")){ // 예약

			$repStr="";

			if($mailType=='order-cancel' || $mailType=='order-cancel-ok'){
				$oData=dbfetch("SELECT * FROM {$TBL['OR_INFO']} WHERE order_num='".$uniData."' AND cancel_status='".$mailStatus[$mailType]."'");
			}
			else{
				$oData=dbfetch("SELECT * FROM {$TBL['OR_INFO']} WHERE order_num='".$uniData."' AND (cancel_status='' OR cancel_status is NULL) AND order_status='".$mailStatus[$mailType]."'");
			}

			if($oData['cancel_status']) $orderStatusStr=$CFG['ORDER_STATUS'][$oData['cancel_status']];
			else $orderStatusStr=$CFG['ORDER_STATUS'][$oData['order_status']];


			$telOrderInfo=(strlen($oData['order_tel'])>'9')?$oData['order_tel']:"-";

			if($oData['idx']>'0'){
				$spaceData= dbfetch("SELECT * FROM {$TBL['SP_INFO']} WHERE  idx='".$oData['space_idx']."'");

				$repStr .="<table summary=\"타이틀명- 예약정보\" style=\"margin-top:30px;width:100%;text-align:left;font-size:12px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
									<tbody>
										<tr>
											<th style=\"background:#ffffff;color:#2c2e36;font-size:14px;\" height=\"26\" valign=\"top\" align=\"left\">
												예약정보
											</th>
											<td style=\"background:#ffffff;color:#94959d;font-size:11px;letter-spacing:-1px;\" height=\"26\" align=\"right\"></td>
										</tr>
									</tbody>
									</table>
									<table summary=\"예약정보 테이블입니다.\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"100%;border-top:1px solid #2c2c2c;border-bottom:1px solid #d9d9de;font-size:12px;text-align:left\" width=\"100%\"><tbody>
									<tbody>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">공간명</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$spaceData['space_name']."(".$oData['detail_space'].")</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">예약인원</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$oData['order_personnel']."명</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">예약일시</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$oData['reservation_start_date']." (".$oData['order_startTime']."~".$oData['order_endTime'].")</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">주문번호</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$oData['order_num']."</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">예약상태</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#ff0000;font-family:Verdana,Geneva,Tahoma,sans-serif;font-weight:bold;\">".$orderStatusStr."</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">예약자명</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$oData['order_name']."</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">연락처</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$telOrderInfo."</td>
										</tr>";

				if(($mailType=="order-cancel" || $mailType=="order-cancel-ok" )&& $oData['refund_bank_name'] && $oData['refund_bank_no'] && $oData['refund_bank_owner']){
					$repStr .="	<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">취소신청일</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".date("Y.m.d H:i:s",$oData['cancel_reg_date'])."</td>
										</tr>";

					if($mailType=="order-cancel-ok"){
						$repStr .="<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">취소완료일</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".date("Y.m.d H:i:s",$oData['cancel_end_date'])."</td>
										</tr>";
					}

					$repStr .="	<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">환불계좌</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$oData['refund_bank_name']." / ".$oData['refund_bank_no']."  (예금주 : ".$oData['refund_bank_owner'].")</td>
										</tr>";
				}

				$repStr .="		<tr><td colspan=\"2\" style=\"padding:0;border-top:1px solid #DBDBDB;\"></td></tr>
									</tbody>
									</table>";

				if($oData['pay_how']=='B') $payType="무통장입금";
				elseif($oData['pay_how']=='C') $payType="카드결제";
				elseif($oData['pay_how']=='K') $payType="실시간계좌이체";
				elseif($oData['pay_how']=='V') $payType="가상계좌";

				$repStr .="	<table summary=\"타이틀명- 결제정보\" style=\"margin-top:30px;width:100%;text-align:left;font-size:12px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
									<tbody>
										<tr>
											<th style=\"background:#ffffff;color:#2c2e36;font-size:14px;\" height=\"26\" valign=\"top\" align=\"left\">
												결제정보
											</th>
											<td style=\"background:#ffffff;color:#94959d;font-size:11px;letter-spacing:-1px;\" height=\"26\" align=\"right\"></td>
										</tr>
									</tbody>
									</table>
									<table summary=\"결제정보 테이블입니다.\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"100%;border-top:1px solid #2c2c2c;border-bottom:1px solid #d9d9de;font-size:12px;text-align:left\" width=\"100%\"><tbody>
									<tbody>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">총결제금액</th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\"><strong>".number_format($oData['total_price'])."원</strong></td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">결제방법 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$payType."</td>
										</tr>";

				if($oData['pay_how']=='B'){
					$imputName=($oData['input_name'])?$oData['input_name']:$oData['order_name'];
					unset($bankInfo);
					if($oData['input_bank']) $bankInfo=explode("|||",$oData['input_bank']);

					$repStr .="	<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">입금자명 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$imputName."</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">입금계좌 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$bankInfo['0']." / ".$bankInfo['1']." (예금주 : ".$bankInfo['2'].")</td>
										</tr>";
				}
				else{
					$niceData=dbfetch("SELECT * FROM {$TBL['PG_RESULT']} WHERE n_Moid='".$oData['order_num']."'");

					if($oData['pay_how']=='C'){
						$cardName=$niceData['n_CardName']; // 카드종류
						if($niceData['n_ErrorCD']=="000" || $niceData['n_ErrorCD']=="0000") $appNoStr="결제(승인)완료 (".$cardName." 카드 , 승인번호 : ".$niceData['n_AuthCode'].")";// 승인번호
						else	$appNoStr="<span style=\"color:#ff0000;\">결제(승인)실패</span> (".$cardName." 카드 , 에러 : ".$niceData['n_ErrorMsg'].")";// 에러 메세지

						$repStr .="<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">결제정보 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$appNoStr."</td>
										</tr>";

						if($niceData['n_ErrorCD']=="000" || $niceData['n_ErrorCD']=="0000"){
							$applyTime=date("Y-m-d H:i:s",$niceData['write_date']);
							$repStr .="<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">승인완료 시간 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$applyTime."</td>
										  </tr>";
						}
					}
					elseif($oData['pay_how']=='K'){
						if($niceData['n_ErrorCD']=="000" || $niceData['n_ErrorCD']=="0000") $appNoStr="결제(이체)완료";// 결제(이체)완료
						else	$appNoStr="<span style=\"color:#ff0000;\">결제(이체)실패</span> (에러 : ".$niceData['n_ErrorMsg'].")";// 에러 메세지

						$repStr .="<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">결제정보 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$appNoStr."</td>
										</tr>";
					}
					elseif($oData['pay_how']=='V'){
						$bankName=$niceData['n_VbankBankName']; // 가상계좌 은행명
						$bankNo=$niceData['n_VbankNum'];

						$applyTime=date("Y-m-d H:i:s",$niceData['write_date']);
						$endInputTime=substr($niceData['n_VbankExpDate'],0,4)."-".substr($niceData['n_VbankExpDate'],4,2)."-".substr($niceData['n_VbankExpDate'],6,2);

						$repStr .="<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">결제정보 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$bankName." (계좌번호 : ".$bankNo.")</td>
										</tr>
										<tr>
											<th width=\"100\" valign=\"middle\" align=\"left\" style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;background-color:#f6f6f7;font-weight:normal;\">가상계좌 발급일자 </th>
											<td style=\"padding:10px 10px 8px 20px;border-top:1px solid #DBDBDB;color:#000;font-family:Verdana,Geneva,Tahoma,sans-serif\">".$applyTime." (입금예정 기한 : ".$endInputTime.")</td>
										</tr>";
					}
				}

				$repStr .="		 <tr><td colspan=\"2\" style=\"padding:0;border-top:1px solid #DBDBDB;\"></td></tr>
									</tbody>
									</table>";

				if($mailType=="order-apply-ok"){ // 승인완료
					$spaceMemData = mysql_fetch_array(query("SELECT email FROM {$TBL['MEMBER']} WHERE idx='".$oData['space_mem_name']."'"));
					$rtnData['to']=$spaceMemData['email'];
				}
				else 	$rtnData['to']=$oData['order_email'];

				$rtnData['subject']=str_replace("{mem_name}",$oData['order_name'],$rtnData['subject']);

				$rtnData['message']=str_replace("{mem_name}",$oData['order_name'],$rtnData['message']);
				$rtnData['message']=str_replace("{site_name}",$CFG['MAIL_TITLE'],$rtnData['message']);
				$rtnData['message']=str_replace("{mypage_link}",$CFG['URL']."/mypage/mypage.html",$rtnData['message']);
				$rtnData['message']=str_replace("{reservation_date}",$oData['reservation_start_date'],$rtnData['message']);
				$rtnData['message']=str_replace("{cancel_reg_date}",date("Y-m-d",$oData['cancel_reg_date']),$rtnData['message']);
				$rtnData['message']=str_replace("{cancel_ok_date}",date("Y-m-d",$oData['cancel_end_date']),$rtnData['message']);

				$rtnData['message']=str_replace("{reservation_info}",$repStr,$rtnData['message']);
			}
			else $rtnData['message']=$rtnData['subject']="";
		}

		return $rtnData;
	}
}

if(!function_exists('mg_send_sms')){
	function mg_send_sms($smsType,$ordNum){ // iot_alert: 화재경보, input_ready:입금대기, input_ok:입금완료(접수완료), befor_1day:예정일 1일전, apply_ok:승인완료, cancel_reg:취소요청, cancel_ok:취소완료, sms_use_end:이용완료, calculate_apply_reg:정산승인요청
		global $TBL,$CFG;
		DB_Conn();

		$siteData = mysql_fetch_array(query("SELECT * from {$TBL['CF_SITE']} WHERE idx<>'' ORDER BY idx DESC LIMIT 1"));
		if(!$siteData['callback_tel']) return;

		$smsFROM=$siteData['callback_tel']; // 발신자 번호
		$smsMSG=$siteData['sms_'.$smsType]; // 발송 메세지

		$oData=dbfetch("SELECT * FROM {$TBL['OR_INFO']} WHERE order_num='".$ordNum."' AND (cancel_status='' OR cancel_status is NULL)");
		if(!$oData['idx']) return;

		$ord_name=$oData['order_name']; // 치환 {ord_name} : 예약자명

		$spaceData= dbfetch("SELECT * FROM {$TBL['SP_INFO']} WHERE  idx='".$oData['space_idx']."'");
		if(!$spaceData['space_name']) return;

		if($oData['detail_space']) $sp_name=$spaceData['space_name']."(".$oData['detail_space'].")"; // 치환 {sp_name} : 예약 공간 정보
		else $sp_name=$spaceData['space_name'];

		if($smsType=='apply_ok'){ // 승인완료는 공간 관리자에게만 전송
			$mData=dbfetch("SELECT * FROM {$TBL['MEMBER']} WHERE idx='".$oData['space_mem_name']."'");
			if(!$mData['phone']) return;

			$smsPHONE=$mData['phone']; // 수신번호
		}
		else{
			$mData=dbfetch("SELECT * FROM {$TBL['MEMBER']} WHERE mem_id='".$oData['mem_id']."'");

			if(!$oData['order_tel'] && !$mData['phone']) return;

			if($oData['order_tel'] && $mData['phone']){
				$ordPhone=str_replace("-","",$oData['order_tel']);
				$memPhone=str_replace("-","",$mData['phone']);

				if($ordPhone==$memPhone) $smsPHONE=$ordPhone; // 수신번호
				else $smsPHONE=$ordPhone.",".$memPhone; // 수신번호
			}
			elseif($oData['order_tel']){
				$ordPhone=str_replace("-","",$oData['order_tel']);
				$smsPHONE=$ordPhone; // 수신번호
			}
			elseif($mData['phone']){
				$memPhone=str_replace("-","",$mData['phone']);
				$smsPHONE=$memPhone;
			}
			else return;
		}

		$order_no=$ordNum; // 치환 {order_no} : 주문번호
		$res_link=$CFG['URL']."/mypage/mypage.html"; // 치환 {res_link} : 예약 정보 링크

		$smsMSG=str_replace("{ord_name}",$ord_name,$smsMSG); // 발송 메세지 (예약자명 치환)
		$smsMSG=str_replace("{sp_name}",$sp_name,$smsMSG); // 발송 메세지 (예약 공간 정보 치환)
		$smsMSG=str_replace("{res_link}",$res_link,$smsMSG); // 발송 메세지 (예약 정보 링크 치환)
		$smsMSG=str_replace("{order_no}",$order_no,$smsMSG); // 발송 메세지 (주문번호 치환)


		include_once($CFG['ABSPATH']."/sms/class.socket.php");

		# create objects
		$smsObj=new asadalSocket();

		$smsRESERVE="000000000000"; // 예약 발송이 아닌 경우

		# create daemon packet data
		$PACKETDATA['header']='1';	// 1 or 2
		$PACKETDATA['id']=$CFG['SMS']['ID'];	// user id
		$PACKETDATA['pw']=$CFG['SMS']['PW'];	// user password
		$PACKETDATA['msg']=iconv("UTF-8","EUC-KR",$smsMSG);	// sending message
		$PACKETDATA['phone']=$smsPHONE;	// receive phone number
		$PACKETDATA['from']=$smsFROM;	// sender phone number
		$PACKETDATA['reserve']=$smsRESERVE;	// reserve date or 000000000000 (년월일시분)

		/***** SAMPLE PACKET
		// 핸드폰 메세지 발송.
		$PACKETDATA['header']='1';
		$PACKETDATA['id']=$CFG['SMS']['ID'];	// user id
		$PACKETDATA['pw']=$CFG['SMS']['PW'];	// user password
		$PACKETDATA['msg']='테스트 메시지입니다.';
		$PACKETDATA['phone']='01030987697';	// receive phone number
		$PACKETDATA['from']=$smsFROM;  // 대량 발송 시 콤마(,)로 구분한 문자열로 세팅
		$PACKETDATA['reserve']='000000000000'; // '000000000000';


		// 해당 유저의 잔여 건수.
		$PACKETDATA['header']='2';
		$PACKETDATA['id']='USERID';
		$PACKETDATA['pw']='USERPW';
		/SAMPLE PACKET *****/

		# socket connect and sending packet data

		if ($smsObj->socketOpen()){
			$smsObj->makePaket($PACKETDATA);
			$smsObj->socketPutData();
			$RESINFO=$smsObj->socketReade();
			$smsObj->delConnect();
		}

		switch ($RESINFO['RESULT']){ // $RESINFO['RESULT'] (발송 결과)

			// 발송 성공.
			case '1' :
				/****** RECEIVE DATA
					$RESINFO['SENDCNT']	:	// 전체 보유 발송 건수.
					$RESINFO['SENDINGCNT']	:	// 전체 발송 건수.
					$RESINFO['VALUE']	:	// 발송 건수.
				******/
			break;

			// 일부 발송 실패
			case '2' :
				/****** RECEIVE DATA
					$RESINFO['SENDCNT']	:	// 전체 보유 건수.
					$RESINFO['SENDINGCNT']	:	// 전체 사용 건수.
					$RESINFO['SUCCESS']	:	// 발송 성공한 건수.
					$RESINFO['FAIL']	:	// 발송 실패한 건수.
					$RESINFO['VALUE']	:	// 실패한 핸드폰 번호.
				******/
			break;

			// 사용자 아이디나 비밀번호가 잘못되었을 경우
			case '3' :
			break;

			// 전체 보유 건수 부족으로 실패한 경우
			case '4' :
			break;
		}

		if($smsType=='iot_alert'){
				$party_list = unserialize($oData['party_list']);
			for($d=0;$d<sizeof($party_list[party_tel]);$d++){

				$smsObj=new asadalSocket();

			$PACKETDATA['phone']=$party_list[party_tel][$d];

			if ($smsObj->socketOpen()){
				$smsObj->makePaket($PACKETDATA);
				$smsObj->socketPutData();
				$smsObj->delConnect();
			}
			sleep(2);

		}
		}

	}
}



?>
