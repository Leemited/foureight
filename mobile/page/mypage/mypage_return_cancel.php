<?php
include_once ("../../../common.php");

if($type==0){
    $sql = "update `order` set od_cancel_confirm = -1 where od_id = '{$od_id}'";
    sql_query($sql);

    $od = sql_fetch("select mb_id from `order` where od_id = '{$od_id}'");

    $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
    $mb = get_member($od["mb_id"]);
    $img = "";
    if($pro["pd_images"]) {
        $imgs = explode(",",$pro["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    if($mb["regid"])
        send_FCM($mb["regid"],"환불 알림",$pro['pd_tag'].'의 환불요청이 취소되었습니다.',G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"],'fcm_buy_channel',"구매알림",$mb["mb_id"],$pd_id,$img);

    alert("거절 완료되었습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=1&pd_type=".$pro["pd_type"]);
}else{
    $sql = "update `order` set od_cancel_confirm = 0 where od_id = '{$od_id}'";
    sql_query($sql);

    $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
    $mb = get_member($pro["mb_id"]);
    $img = "";
    if($pro["pd_images"]) {
        $imgs = explode(",",$pro["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    if($mb["regid"])
        send_FCM($mb["regid"],"환불 알림",$pro['pd_tag'].'의 환불요청이 취소되었습니다.',G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"],'fcm_buy_channel',"구매알림",$mb["mb_id"],$pd_id,$img);

    alert("환불요청취소가 완료되었습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"]);
}
?>