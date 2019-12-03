<?php
include_once ("_common.php");
$sql = "select cate_name from `categorys` where ca_id = '{$cate}'";
$ca_name = sql_fetch($sql);


if($type == "1"){
    $sql = "select pd_id from `product` where pd_type = 1 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0 and pd_cate2 like '%{$ca_name["cate_name"]}%' and pd_type2 = 8";
}else{
    $sql = "select pd_id from `product` where pd_type = 2 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0 and pd_cate2 like '%{$ca_name["cate_name"]}%' and pd_type2 = 8";
}
$result["sql1"]=$sql;
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cate1[] = $row;
}

$result["cnt1"] = count($cate1);

if($type == "1"){
    $sql = "select pd_id from `product` where pd_type = 1 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0 and pd_cate2 like '%{$ca_name["cate_name"]}%' and pd_type2 = 4";
}else{
    $sql = "select pd_id from `product` where pd_type = 2 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0 and pd_cate2 like '%{$ca_name["cate_name"]}%' and pd_type2 = 4";
}
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cate2[] = $row;
}
$result["cnt2"] = count($cate2);

echo json_encode($result);



