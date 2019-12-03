<?php
include_once ("../../../common.php");

$sql = "select * from `order` where od_id = '{$od_id}' and od_cancel_confirm = 1";
$od = sql_fetch($sql);

if($od == null){
    $sql = "update `order` set od_cancel_confirm = 1 where od_id = '{$od_id}'";
    if(sql_query($sql)){
        $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
        $mb = get_member($pro["mb_id"]);
        $img="";
        if($pro["pd_images"]) {
            $imgs = explode(",",$pro["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }

        if($mb["regid"]) {
            send_FCM($mb["regid"], "환불 알림", $pro["pd_tag"] . "의 구매 환불 요청 되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
        }
        
        echo "2";
    }else{
        echo "3";
    }
}else{
    echo "1";
}