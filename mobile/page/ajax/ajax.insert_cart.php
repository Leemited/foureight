<?php
include_once ("../../../common.php");

//$sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$sell_mb_id}', c_status = {$status}, c_price = {$price}, c_date = now()";
$sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$sell_mb_id}', c_status = 1, c_price = {$price}, c_date = now()";
if(sql_query($sql)){
    $cid = sql_insert_id();
    $sql = "update `product` set pd_status = {$status} where pd_id = {$pd_id}";
    if(sql_query($sql)){
        //성공시 상대방에게 푸시 알림 가기

        echo "3";
    }else{
        $sql = "delete from `cart` where cid = {$cid}";
        sql_query($sql);
        echo "2";
    }
}else{
    echo "1";
}

?>