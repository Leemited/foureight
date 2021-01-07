<?php
include_once ("../../../common.php");

$sql = "update `order` set od_fin_confirm = 1 where od_id = '{$od_id}'";
if(sql_query($sql)){
    $sql = "select *,o.mb_id,o.pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
    $pd = sql_fetch($sql);
    if ($pd["pd_images"]) {
        $imgs = explode(",", $pd["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    $mb = get_member($pd["mb_id"]);
    if($mb["regid"]){
        echo send_FCM($mb["regid"], "48 거래 알림", cut_str($pd["pd_tag"],10,"...") . "의 거래완료 요청입니다.\r\n구매완료시 최종 구매가 확정됩니다.\r\n4일간 거래미완료시 자동으로 완료 처리됩니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);
    }

    echo "1";
}else{
    echo "2";
}

?>