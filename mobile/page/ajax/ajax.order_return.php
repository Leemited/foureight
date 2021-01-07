<?php
include_once ("../../../common.php");

$sql = "select * from `order` where od_id = '{$od_id}' and od_refund_status = 1";
$od = sql_fetch($sql);

if($od == null){
    $sql = "update `order` set od_refund_status = 1, od_refund_date = now(),od_refund_time = now() where od_id = '{$od_id}'";
    if(sql_query($sql)){
        $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
        $mb = get_member($pro["mb_id"]);
        $img="";
        if($pro["pd_images"]) {
            $imgs = explode(",",$pro["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        $today = date("Y-m-d",strtotime(" + 5 days"));
        $result["day"] = $today;
        if($mb["regid"]) {
            send_FCM($mb["regid"], "48 환불 알림", cut_str($pro["pd_tag"],10,"...") . "의 구매 환불이 요청 되었습니다.\r\n환불 승인 여부에 대해 구매자님과 상의하신 후 승인 또는 거절 처리해 주세요.\r\n환불 요청에 선택하지 않으시면, [{$today}] 정오에 자동 환불 승인 처리됩니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
        }
        
        $result["msg"] = "2";
    }else{
        $result["msg"] = "3";
    }
}else{
    $result["msg"] = "1";
}

echo json_encode($result);