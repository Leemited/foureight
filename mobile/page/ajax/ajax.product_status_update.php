<?php
include_once ("../../../common.php");

switch ($status){
    case "판매중":
        $st = 0;
        break;
    case "거래중":
        $st = 1;
        break;
    case "판매보류":
        $st = 2;
        break;
    case "판매완료":
        $st = 3;
        break;
}
//거래중/판매완료 인지 체크
$sql = "select count(*)as cnt from `cart` where pd_id='{$pd_id}' and c_status = 1";
$chkCart = sql_fetch($sql);
if($chkCart["cnt"] > 0){
    echo "3";
    return false;
}

$sql = "select count(*)as cnt from `order` where pd_id='{$pd_id}' and od_status = 1";
$chkOrder = sql_fetch($sql);
if($chkOrder["cnt"] > 0){
    //판매완료로 변경
    $sql = "update `porduct` set pd_status = 3 where pd_id = '{$pd_id}'";
    sql_query($sql);
    echo "4";
    return false;
}

$sql = "update `product` set pd_status = {$st} where pd_id = {$pd_id}";
if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}