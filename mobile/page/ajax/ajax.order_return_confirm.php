<?php
include_once ("../../../common.php");

$sql = "update `order` set od_cancel_confirm = 2 where od_id = '{$od_id}'";
if(sql_query($sql)){
    //구매자에게 승인 알림
    $mb = get_member($mb_id);
    $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
    $img = '';
    if ($pro["pd_images"]) {
        $imgs = explode(",", $pro["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }

    //취소 알림
    if($mb["regid"])
        send_FCM($mb["regid"], "환불 알림", $pro["pd_tag"]."의 환불요청이 승인 되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매알림", $mb["mb_id"], $pro["pd_id"], $img);

    echo "1";
}else{
    echo "3";
}
?>
