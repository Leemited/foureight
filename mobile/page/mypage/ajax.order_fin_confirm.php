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
        echo send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 거래완료 요청입니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);
    }

    echo "1";
}else{
    echo "2";
}

?>