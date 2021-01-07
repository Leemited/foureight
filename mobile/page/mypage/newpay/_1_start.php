<?php
include_once ("../../../../common.php");
header('Content-Type: text/html; charset=utf-8');

$_SESSION["readyParameters"] = "";
$_SESSION["apiKey"] = "";
$_SESSION["aid"] = "";
$_SESSION["API_BASE"] = "";
$_SESSION["od_id"] = "";
$_SESSION["oid"] = "";
$_SESSION["od_type"] = "";

$sql = "select * from `order` as o left join `product` as p on p.pd_id = o.pd_id where o.od_id = '{$od_id}'";
$od = sql_fetch($sql);
if($od["od_pay_status"]==1 && $od["pd_type"]==1){//일단 상품
    alert("이미 결제한 주문건입니다.");
}
$od_tel = $tel1.$tel2.$tel3;
date_default_timezone_set("Asia/Seoul");
$timestamp = date('YmdHmi');

// 구매자 명
$buyerName = $od_name;
// 구매자 휴대폰 번호
$buyerMobile = $od_tel;
// 구매자 Email
$buyerEmail = "";
// 상품명
$productName = $od_item_name;
//결제 금액
$salesPrice = $od_price;

$READY_API_URL = G5_URL."/mobile/page/mypage/newpay/_2_ready.php";

switch ($od_type){
    case 1:
        $pays = "CARD";
        break;
    case 3:
        $pays = "ACCT";
        break;
    default:
        $pays = "CARD";
        break;
}

$oid = $timestamp."_".$od_id;

//임시저장
if($od["pay_status"]==1 && $od["pay_step"]==1){
    $sql = "update `order` set od_tel = '{$od_tel}', od_zipcode = '{$od_zipcode}', od_addr1 = '{$od_address1}', od_addr2 = '{$od_address2}', od_content = '{$od_content}', od_pay_type2 = '{$od_type}', od_step = '2', pay_oid2 = '{$oid}' where od_id = '{$od_id}'";
    sql_query($sql);
}else {
    $sql = "update `order` set od_name = '{$od_name}', od_tel = '{$od_tel}', od_zipcode = '{$od_zipcode}', od_addr1 = '{$od_address1}', od_addr2 = '{$od_address2}', od_content = '{$od_content}', od_pay_type = '{$od_type}', od_pd_type = '{$od_pd_type}', od_step = '{$od_step}', pay_oid = '{$oid}' where od_id = '{$od_id}'";
    sql_query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, user-scalable=no">
<script src="https://api-std.mainpay.co.kr/js/mainpay.mobile-1.0.js"></script>
<script type='text/javascript'> 
	var READY_API_URL = "<?=$READY_API_URL?>";
	function payment() {
		var request = mainpay_ready(READY_API_URL);
		request.done(function(response) {
			if (response.resultCode == '200') {
				/* 결제창 호출 */
				location.href = response.data.nextMobileUrl; // *주의* PC와 Mobile은 URL이 상이합니다.
				return false;
			}
			alert("ERROR : "+JSON.stringify(response));
		});		
	}
</script>  
</head>
<body onload="payment()">
<!--<body >-->
	<div>
		<!-- id 고정 -->
		<form id="MAINPAY_FORM" style="opacity: 0">
			주문자 <input type="text" name="oid" value="<?php echo $oid;?>"> <br>
			주문자 <input type="text" name="od_id" value="<?php echo $od_id;?>"> <br>
			주문자 <input type="text" name="buyername" value="<?php echo $buyerName;?>"> <br>
			금액 <input type="text" name="od_price" value="<?php echo $salesPrice;?>"> <br>
			주문자연락처 <input type="text" name="buyermobile" value="<?php echo $buyerMobile;?>"> <br>
			주문자이메일 <input type="text" name="buyeremail" value="<?php echo $buyerEmail;?>"> <br>
			지불수단 <input type="text" name="paymethod" value="<?php echo $pays;?>"> <br>
			지불수단 <input type="text" name="od_type" value="<?php echo $od_type;?>"> <br>
			<!--상품코드 <input type="text" name="goodsCode" value="<?php /*echo $oid;*/?>"> <br>-->
			상품명칭 <input type="text" name="goodsName" value="<?php echo str_replace("#"," ",$productName);?>"> <br><br>
		</form>
		<!--<button type="button" class="btn_submit" onclick="payment()">결제요청</button>-->
	</div>
	<div>
		<!-- id 고정 -->
		<IFRAME id="MAINPAY_IFRAME" width="100%" height="100%" frameborder="0" scrolling="no" allowtransparency="true"></IFRAME>
	</div>
</body>
</html>

