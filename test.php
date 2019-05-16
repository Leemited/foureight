<?php
include_once ("./common.php");
$mb_id = $member["mb_id"];
$mb = get_member($mb_id);

if($mb["regid"]) {
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);
    $img = "";
    if($pd["pd_images"]) {
        $imgs = explode(",",$pd["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    send_FCM($mb["regid"], "48 배송 요청 알림", $pd["pd_tag"]."의 ".$content, G5_MOBILE_URL . '/page/mypage/order_view.php?od_id=' . $od_id, 'fcm_buy_channel', "구매일림", $mb_id, $pd_id, $img);

    //echo "알림을 보냈습니다.\n상대방인 수신거부시 알림이 제대로 전달되지 않을 수 있습니다.\n전화나 문자를 통해 한번 더 요청 바랍니다.";
}

?>