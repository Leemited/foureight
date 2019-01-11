<?php
include_once ("../../../common.php");

if(!$cid){
    echo "1";
    return false;
}
$sql = "select * from `cart` where cid = '{$cid}'";
$chkid = sql_fetch($sql);

$sql = "update `cart` set c_status = 3 where cid = '{$cid}'";
if(sql_query($sql)){
    $mb = get_member($chkid["mb_id"]);
    $pro = sql_fetch("select * from `product` where pd_id = '{$chkid["pd_id"]}'");
    if($pro["pd_images"]) {
        $imgs = explode(",",$pro["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    //취소 알림
    send_FCM($mb["regid"], $pro["pd_tag"], $pro["pd_tag"]."구매 예약이 취소 되었습니다.", G5_MOBILE_URL . "/page/mypage/cart.php", "pay_reser_set", "구매예약",$mb["mb_id"], $pro["pd_id"], $img);

    echo "2";
}else{
    echo "3";
}

?>