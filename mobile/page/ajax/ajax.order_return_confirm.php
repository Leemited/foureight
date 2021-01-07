<?php
include_once ("../../../common.php");

$sql = "update `order` set od_refund_status = 2, od_refund_confirm_date = now() where od_id = '{$od_id}'";
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
        send_FCM($mb["regid"], "48 환불 알림", cut_str($pro["pd_tag"],10,"...")."의 환불/반품요청이 승인되었습니다.\r\n물건이 있는경우 판매자에게 반품후 최종 환불처리됩니다.\r\n환불 배송 기한은 요청일로부터 4일입니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매알림", $mb["mb_id"], $pro["pd_id"], $img);

    echo "1";
}else{
    echo "3";
}
?>
