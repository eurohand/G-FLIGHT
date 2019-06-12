	<div id="commerceZipcodeLayer" style="display:none;border:5px solid;position:fixed;width:320px;height:500px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;">
		<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode()" alt="닫기 버튼">
	</div>
	<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
	<script>
		function closeDaumPostcode() {
			jQuery("#commerceZipcodeLayer").css("display","none");
		}
		function openDaumPostcode(postcode, address1, address2){
			new daum.Postcode({
				oncomplete: function(data){
					if(data.userSelectedType === 'R'){

						// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
						// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
						var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
						var extraRoadAddr = ''; // 도로명 조합형 주소 변수

						// 법정동명이 있을 경우 추가한다. (법정리는 제외)
						// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
						if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
							extraRoadAddr += data.bname;
						}
						// 건물명이 있고, 공동주택일 경우 추가한다.
						if(data.buildingName !== '' && data.apartment === 'Y'){
						   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
						}
						// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
						if(extraRoadAddr !== ''){
							extraRoadAddr = ' (' + extraRoadAddr + ')';
						}
						// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
						if(fullRoadAddr !== ''){
							fullRoadAddr += extraRoadAddr;
						}

						jQuery('#'+postcode).val(data.zonecode);
						jQuery('#'+address1).val(fullRoadAddr);
					}
					else{
						jQuery('#'+postcode).val(data.postcode1+"-"+data.postcode2);
						jQuery('#'+address1).val(data.jibunAddress);
					}

					jQuery('#'+address2).focus();
				}
			}).open();
		}
	</script> 
