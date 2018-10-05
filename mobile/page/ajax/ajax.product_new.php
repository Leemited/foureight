<?php
include_once ("../../../common.php");

$sql = "select * from `product` where pd_id = '{$pd_id}'";
$item = sql_fetch($sql);

$uptime = (strtotime(date("Y-m-d H:i:s")) - strtotime($item["pd_update"])) / 3600;

if($item["pd_update_cnt"] >= 5){
    $msg = 'upcnt';
    echo $msg;
    return false;
}

if($uptime < 1){
    $msg = "time";
    echo $msg;
    return false;
}

$sql = "update `product` set pd_update = now(), pd_update_cnt = pd_update_cnt + 1 where pd_id = '{$pd_id}'";
if(sql_query($sql)){
    $msg = "success";
}else{
    $msg = "failed";
}

echo $msg;