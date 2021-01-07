<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
<style>
#settings{height:calc(100vh - 20vw);}
#settings .setting_wrap ul li{padding:2.8vw;}
.small {width:20%;}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>주소변경</h2>
</div>
<div id="settings">
	<form action="<?php echo G5_MOBILE_URL?>/page/mypage/address_update.php" method="post">
	<input type="hidden" name="mb_id" value="<?php echo $member["mb_id"];?>">
		<div class="setting_wrap"> 
			<h2>현재 주소</h2>
			<ul>
				<?php if($member["mb_zip1"]){?>
				<li style="padding:4vw">우편번호 <span><?php echo $member["mb_zip1"];?></span></li>
				<li style="padding:4vw">기본주소 <span><?php echo $member["mb_addr1"];?></span></li>
				<li style="padding:4vw">상세주소 <span><?php echo $member["mb_addr2"];?></span></li>
				<?php }else{ ?>
				<li class="single" style="padding:4vw">등록된 주소가 없습니다. </li>
				<?php }?>
			</ul>
		</div>
		<div class="setting_wrap">
			<h2>변경 주소</h2>
			<ul>
				<li><input type="text" class="setting_input small" name="mb_zip1" id="sample3_postcode" required readonly placeholder="우편번호" onclick="sample3_execDaumPostcode()"><input type="button" value="검색" id="addr_btn" onclick="sample3_execDaumPostcode()">
				<div id="wrap" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
<img src="//t1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
</div>
				</li>
				<li><input type="text" class="setting_input" name="mb_addr1" id="sample3_address" required readonly placeholder="기본주소" onclick="sample3_execDaumPostcode()"></li>
				<li><input type="text" class="setting_input" name="mb_addr2" id="sample3_address2" required placeholder="상세주소"></li>
			</ul>
			<div class="btn_group">
				<input type="submit" value="변경" class="setting_btn">
			</div>
		</div>
	</form>
</div>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    // 우편번호 찾기 찾기 화면을 넣을 element
    var element_wrap = document.getElementById('wrap');

    function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    function sample3_execDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('sample3_postcode').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('sample3_address').value = fullAddr;

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }
</script>
<?php 
include_once(G5_PATH."/tail.php");
?>