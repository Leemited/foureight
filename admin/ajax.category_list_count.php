<?php
include_once ("_common.php");
$type = $_REQUEST["ad_type"];

if($type == "1"){
    $sql = "select * from `product` where pd_type = 1 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0";
}else{
    $sql = "select * from `product` where pd_type = 2 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0";
}

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cate1[] = $row;
}

echo count($cate1);



