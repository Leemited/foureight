<?php
include_once ("../../../common.php");

$sql = "select * from `product` where pd_id = '{$pd_id}'";
$pro = sql_fetch($sql);

$sql = "insert into `order` set pd_id='{$pd_id}',mb_id = '{$member["mb_id"]}', pd_price = '{$od_price}',od_price = '{$pro["pd_price"]}',od_price2 = '{$pro["pd_price2"]}',od_delivery_type = '{$pro["pd_delivery_use"]}', od_status = 1, od_reser_date = now(), od_reser_time = now(),od_pd_type='{$pro["pd_type"]}'";
if(sql_query($sql)){
    $od_id = sql_insert_id();
    goto_url(G5_MOBILE_URL."/page/mypage/orders.php?od_id=".$od_id);
}else{
    alert("결제오류 입니다.");
}
?>