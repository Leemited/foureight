<?php
include_once ("../../../common.php");
if($od_id==""){
    echo "1";
    return false;
}

$sql = "select *,o.mb_id as mb_id , p.mb_id as pd_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id left join `g5_member` as m on p.mb_id = m.mb_id where o.od_id = '{$od_id}'";
$pro = sql_fetch($sql);
$img="";
if($pro["pd_images"]) {
    $imgs = explode(",",$pro["pd_images"]);
    $img = G5_DATA_URL."/product/".$imgs[0];
}
$sql = "delete from `order` where od_id = '{$od_id}'";
if(sql_query($sql)) {
    if($type==0){//구매자 취소
        $od_cate = 2;
        $mb = get_member($pro["pd_mb_id"]);
    }else if($type==1 || $type==2){//판매자 취소
        $od_cate = 1;
        $mb = get_member($pro["mb_id"]);
    }
    if ($mb["regid"]) {
        send_FCM($mb["regid"], "48 취소 알림", cut_str($pro["pd_tag"],10,"...") . "의 예약/구매가 취소 되었습니다.\r\n아쉽지만 다른 거래를 찾아봐야 겠네요..\r\n바로 새로운 거래를 찾아보세요!", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=".$od_cate."&pd_type=" . $pro["pd_type"], "fcm_buy_channel", "구매알림", $pro["mb_id"], $pro["pd_id"], $img);
    }
    echo "2";
}