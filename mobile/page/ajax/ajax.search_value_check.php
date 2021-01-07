<?php
include_once ("../../../common.php");

$sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
$search = sql_fetch($sql);

if($stx != $search["sc_tag"]){
    if($set) $set .=",";
    $set .= " sc_tag= '{$stx}' ";
}

if($type2 != $search["sc_type2"]){
    if($set) $set .=",";
    $set .= "sc_type2='{$type2}'";
}

if($cate1 != $search["sc_cate1"]){
    if($set) $set .= ",";
    $set .= "sc_cate1='{$cate1}'";
}

if($cate2 != $search["sc_cate2"]){
    if($set) $set .= ",";
    $set .= "sc_cate2 = '{$cate2}'";
}

if($priceFrom != $search["sc_priceFrom"]){
    if($set) $set .= ",";
    $set .= "sc_priceFrom = '{$priceFrom}'";
}

if($priceTo != $search["sc_priceTo"]){
    if($set) $set .= ",";
    $set .= "sc_priceTo = '{$priceTo}'";
}

if($orderactive != $search["sc_align_active"]) {
    if($set) $set .= ",";
    $set .= "sc_align_active = '{$orderactive}', sc_align='{$align}'";
}

if($typecompany=="on"){
    $typecompany = 0;
}else{
    $typecompany = -1;
}

if($typecompany != $search["sc_level"]){
    if($set) $set .= ",";
    $set .= "sc_level = '{$typecompany}'";
}

if($pd_price_type1 == "off"){
    $pd_price_type1 = -1;
}
if($pd_price_type2 == "off"){
    $pd_price_type2 = -1;
}
if($pd_price_type3 == "off"){
    $pd_price_type3 = -1;
}

if($pd_price_type1 != $search["sc_price_type1"]){
    if($set) $set .= ",";
    $set .= "sc_price_type1 = '{$pd_price_type1}'";
}
if($pd_price_type2 != $search["sc_price_type2"]){
    if($set) $set .= ",";
    $set .= "sc_price_type2 = '{$pd_price_type2}'";
}
if($pd_price_type3 != $search["sc_price_type3"]){
    if($set) $set .= ",";
    $set .= "sc_price_type3 = '{$pd_price_type3}'";
}

echo $set;
?>