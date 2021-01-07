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

    send_FCM($mb["regid"], "48 직거래 알림", cut_str($pd["pd_tag"],10,"...") . "의 직거래 요청입니다.\r\n직거래는 당사에서 책임을 지지 않습니다.\r\n구매자와 충분히 협의 후 진행바랍니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);

    echo "1";
}

?>