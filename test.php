<?php
include_once ("./common.php");
$mb_id = "sun001126@hanmail.net";
//$mb_id = "tecooni@cyworld.com";
$mb = get_member($mb_id);
print_r2($mb);
if($mb["regid"]) {
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);
    $img = "";
    if($pd["pd_images"]) {
        $imgs = explode(",",$pd["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    echo send_FCM($mb["regid"], "48 배송 요청 알림", $pd["pd_tag"]."의 ".$content, G5_MOBILE_URL . '/page/mypage/order_view.php?od_id=' . $od_id, 'chat_alarm_set', "채팅알림", $mb_id, $pd_id, $img,'215_501021270');
}

?>