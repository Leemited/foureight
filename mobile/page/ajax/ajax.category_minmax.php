<?php
include_once("../../../common.php");

if($cate1 && $cate2) {
    $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_cate = '{$cate1}' and pd_cate2 = '{$cate2}' and pd_status = 0";
    $cateminmax = sql_fetch($sql);
}else if($cate1 && !$cate2){
    $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_cate = '{$cate1}' and pd_status = 0";
    $cateminmax = sql_fetch($sql);
}else if(!$cate1 && !$cate2){
    $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_type = '{$pd_type}' and pd_status = 0";
    $cateminmax = sql_fetch($sql);
}
set_session("priceFrom",$cateminmax["min"]);
set_session("priceTo",$cateminmax["max"]);
echo json_encode($cateminmax);