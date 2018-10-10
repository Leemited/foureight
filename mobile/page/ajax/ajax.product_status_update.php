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

$sql = "update `product` set pd_status = {$st} where pd_id = {$pd_id}";
if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}