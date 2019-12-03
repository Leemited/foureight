<?php
include_once ("../../../common.php");

$sql = "select * from `order` where od_id = '{$od_id}'";
$od = sql_fetch($sql);

$sql = "select mb_id,pd_type,pd_tag,pd_images from `product` where pd_id = '{$od["pd_id"]}'";
$pd = sql_fetch($sql);

$mb = get_member($pd["mb_id"]);
if($mb["regid"]){
    $sql = "update `order` set od_direct_status = 1 where od_id = '{$od_id}'";
    sql_query($sql);

    if ($pd["pd_images"]) {
        $imgs = explode(",", $pd["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }

    send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "직거래 요청입니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);

    echo "1";
}

?>