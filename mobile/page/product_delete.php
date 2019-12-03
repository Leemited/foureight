<?php
include_once ("../../common.php");

if($pd_id=="" || !$pd_id){
    alert("잘못된 요청입니다.");
    return false;
}

$sql = "select * from `product` where pd_id = '{$pd_id}'";
//file
$file = sql_fetch($sql);
$path = G5_DATA_URL."/product/";
if($file["pd_type"]==1){
    $sql = "select * from `orders` where pd_id = '{$pd_id}'";
    $orders = sql_fetch($sql);
    if(($orders!=null && $orders["od_fin_status"]==0) || $file["pd_status"]==1){
        alert("해당 게시글은 거래중으로 삭제할 수 없습니다.");
    }
}else{
    $sql = "select * from `cart` where pd_id = '{$pd_id}'";
    $res = sql_query($sql);
    while($row=sql_fetch_array($res)) {
        $orders[] = $row;
    }
    if(count($orders)!=0){
        alert("해당 게시글은 거래중으로 삭제할 수 없습니다.");
    }
}


$sql = "update `product` set pd_status = 10 where pd_id = '{$pd_id}'";

if($return_url){
    $link = $return_url;
}else{
    $link = G5_URL;
}

if(sql_query($sql)){
    alert("삭제되었습니다.",$link);
}else {
    alert("잘못된 요청입니다.");
}