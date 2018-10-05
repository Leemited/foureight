<?php 
include_once("../common.php");
include_once("./admin.head.php");

$sql = "select * from `homepage_info` where `home_id` = 1";
$write = sql_fetch($sql);
?>
<div id="wrap">
	<section>
		<header class="admin_title">
			<h1>홈페이지 정보 관리</h1>
		</header>
		<div class="write_form">
			<form action="<?php echo G5_URL?>/admin/home_info_update.php" method="post">
			<table>
				<colgroup>
					<col width="10%">
					<col width="90%">
				</colgroup>
				<tr>
					<th>상호</th>
					<td><input type="text" name="company_name" id="company_name" value="<?php echo $write["company_name"];?>" required class="write_input01 "></td>
				</tr>
				<tr>
					<th>대표</th>
					<td><input type="text" name="ceo" id="ceo" value="<?php echo $write["ceo"];?>" required class="write_input01"></td>
				</tr>
				<tr>
					<th>주소</th>
					<td>
						<input type="text" name="zipcode" id="sample3_postcode" value="<?php echo $write["zipcode"];?>" required class="write_input01"><input type="button" value="주소검색" class="map_button" onclick="sample3_execDaumPostcode()">
						<div id="wrap_map" style="display:none;border:1px solid;width:500px;height:300px;margin:5px 0;position:relative">
<img src="//t1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
</div>
						<input type="text" name="addr1" id="sample3_address" value="<?php echo $write["addr1"];?>" required class="write_input01 grid_80">
						<input type="text" name="addr2" id="detail" value="<?php echo $write["addr2"];?>" required class="write_input01 grid_80">
					</td>
				</tr>
				<tr>
					<th>사업자등록번호</th>
					<td><input type="text" name="company_number" id="company_number" value="<?php echo $write["company_number"];?>" required class="write_input01"></td>
				</tr>
				<tr>
					<th>중계업번호</th>
					<td><input type="text" name="company_number2" id="company_number2" value="<?php echo $write["company_number2"];?>" required class="write_input01"></td>
				</tr>
				<tr>
					<th>전화번호</th>
					<td><input type="text" name="tel" id="tel" value="<?php echo $write["tel"];?>" required class="write_input01"></td>
				</tr>
				<tr>
					<th>팩스</th>
					<td><input type="text" name="fax" id="fax" value="<?php echo $write["fax"];?>" required class="write_input01"></td>
				</tr>
				<tr>
					<th>이메일</th>
					<td><input type="text" name="email" id="email" value="<?php echo $write["email"];?>" required class="write_input01 grid_80"></td>
				</tr>
			</table>	
			<div class="write_gr">
				<input type="button" class="cancel_btn" value="취소" onclick="location.href='<?php echo G5_URL?>/admin/home_info_write.php'">
				<input type="submit" class="submit_btn" value="수정">
			</div>
			</form>
		</div>
	</section>
</div>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<script>
// 우편번호 찾기 찾기 화면을 넣을 element
    var element_wrap = document.getElementById('wrap_map');

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
include_once("./admin.tail.php");
?>