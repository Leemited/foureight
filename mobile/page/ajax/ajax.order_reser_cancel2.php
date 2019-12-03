<?php
include_once ("../../../common.php");
if(!$cid){
    echo "1";
    return false;
}

if(!$pd_id){
    echo "2";
    return false;
}

$sql = "delete from `cart` where cid = '{$cid}'";
sql_query($sql);

$sql = "update `product` set pd_status = 0 where pd_id = '{$pd_id}'";
sql_query($sql);

$sql = "select *,p.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where pd_id = '{$pd_id}'";
$pro = sql_fetch($sql);
$img="";
if($pro["pd_images"]) {
    $imgs = explode(",",$pro["pd_images"]);
    $img = G5_DATA_URL."/product/".$imgs[0];
}

if($pro["regid"]) {
    send_FCM($pro["regid"], $pro["pd_tag"], $pro["pd_tag"] . "의 구매가 취소 되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매예약", $pro["mb_id"], $pro["pd_id"], $img);
}