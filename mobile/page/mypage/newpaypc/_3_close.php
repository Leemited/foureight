<?php
include_once ("../../../../common.php");
include_once (G5_PATH."/head.sub.php");
	/********************************************************************************	
	  결제창 종료시에 PG사에서 호출하는 페이지 입니다.
	  상점에서 필요한 로직 추가	
	********************************************************************************/
session_start();
$od_id = $_SESSION["od_id"];

$return = G5_MOBILE_URL."/page/mypage/orders.php?od_id=".$od_id;
?>
<script src="https://api-std.mainpay.co.kr/js/mainpay.pc-1.0.js"></script>
<script>
	//parent.parent.colsePayment();
	// 또는 아래와 같이 원하는 URL로 리다이렉트 가능
    top.location="<?php echo $return;?>";
</script>
종료페이지