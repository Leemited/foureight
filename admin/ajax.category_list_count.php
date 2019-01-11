<?php
include_once ("_common.php");
$sql = "select cate_name from `categorys` where ca_id = '{$cate}'";
$ca_name = sql_fetch($sql);


if($type == "1"){
    $sql = "select * from `product` where pd_type = 1 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0 and pd_cate2 like '%{$ca_name["cate_name"]}%'";
}else{
    $sql = "select * from `product` where pd_type = 2 and pd_blind < 10 and pd_blind_status = 0 and pd_status = 0 and pd_cate2 like '%{$ca_name["cate_name"]}%'";
}
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cate1[] = $row;
}

echo count($cate1)-1;



