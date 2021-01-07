<?php
include_once ("../../../common.php");

if($od_price2){
    $set = ", od_price2 = '{$od_price2}'";
}

$sql = "update `order` set od_price = '{$od_price}' {$set} where od_id = '{$od_id}'";

if(sql_query($sql)){

    $sql = "select * from `order` as o left join `g5_member` as m on o.mb_id = m.mb_id where od_id = '{$od_id}'";
    $order = sql_fetch($sql);

    $sql = "select * from `product` where pd_id = '{$order["pd_id"]}'";
    $pd = sql_fetch($sql);

    if($pd["pd_images"]) {
        $imgs = explode(",",$pd["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    if($order["regid"]) {
        send_FCM($order["regid"],"48 알림", "[".cut_str($pd["pd_tag"],10,"...")."]의 결제 가격이 변경되었습니다.\r\n결제가격 확인 후 결제 바랍니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?mypage_order.php?type=2&od_cate=2&pd_type={$pd["pd_type"]}","fcm_buy_channel", "결제알림",$order["mb_id"],$pd["pd_id"],$img,"","");
    }
    alert("변경 가격이 적용되었습니다.");

}else{
    alert("가격 변경에 실패하였습니다.");
}

?>