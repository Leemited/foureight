<?php
include_once("../../../common.php");

if($cate1 && $cate2) {
    $sql = "select MAX(pd_price) as max, MIN(pd_price) as min from `product` where pd_cate = '{$cate1}' and pd_cate2 = '{$cate2}'";
    $cateminmax = sql_fetch($sql);
}else if($cate1 && !$cate2){
    $sql = "select MAX(pd_price) as max, MIN(pd_price) as min from `product` where pd_cate = '{$cate1}'";
    $cateminmax = sql_fetch($sql);
}else if(!$cate1 && !$cate2){
    $sql = "select MAX(pd_price) as max, MIN(pd_price) as min from `product` where pd_type = '{$pd_type}'";
    $cateminmax = sql_fetch($sql);
}
$cateminmax["sql"] = $sql;

echo json_encode($cateminmax);