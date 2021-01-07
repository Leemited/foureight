<?php
include_once ("../../../common.php");

if($type==0){//판매지취소
    $sql = "update `order` set od_refund_status = -1 where od_id = '{$od_id}'";
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
        send_FCM($mb["regid"],"48 환불 알림",cut_str($pro['pd_tag'],10,"...")."의 환불요청이 취소되었습니다.\r\n타당한 사유가 있다면 판매자와 상의후 진행 해보세요.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"],'fcm_buy_channel',"구매알림",$mb["mb_id"],$pd_id,$img);

    alert("거절 완료되었습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=1&pd_type=".$pro["pd_type"]);
}else{//구매자취소
    $sql = "update `order` set od_refund_status = -1 where od_id = '{$od_id}'";
    sql_query($sql);

    $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
    $mb = get_member($pro["mb_id"]);
    $img = "";
    if($pro["pd_images"]) {
        $imgs = explode(",",$pro["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    if($mb["regid"])
        send_FCM($mb["regid"],"48 환불 알림",cut_str($pro['pd_tag'],10,"...")."의 환불요청이 취소되었습니다.\r\n환불이 필요한 경우 꼭 판매자와 상의후 진행 해주세요.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"],'fcm_buy_channel',"구매알림",$mb["mb_id"],$pd_id,$img);

    alert("환불요청취소가 완료되었습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"]);
}
?>