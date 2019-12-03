<?php
include_once ("../../../common.php");

$mb = get_member($mb_id);

if($mb["regid"]) {
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);
    $img = "";
    if($pd["pd_images"]) {
        $imgs = explode(",",$pd["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    if($mb["mb_id"]==$pd["mb_id"]){
        $od_cate = 1;
    }else{
        $od_cate = 2;
    }

    send_FCM($mb["regid"], "48 배송 요청 알림", $pd["pd_tag"]."의 ".$content, G5_MOBILE_URL . '/page/mypage/mypage_order.php?od_cate='.$od_cate."&pd_type=".$pd["pd_type"], 'fcm_buy_channel', "구매일림", $mb_id, $pd_id, $img);

    echo "알림을 보냈습니다.\n상대방인 수신거부시 알림이 제대로 전달되지 않을 수 있습니다.\n전화나 문자를 통해 한번 더 요청 바랍니다.";
}else{
    echo "푸시알람을 보내지 못 했습니다.";
}

?>