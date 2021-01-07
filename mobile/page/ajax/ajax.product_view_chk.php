<?php
include_once ("../../../common.php");

$sql = "select * from `product` where pd_id = '{$pd_id}'";
$pro = sql_fetch($sql);

if($pro["pd_status"] < 10){
    echo "1";
}else{
    echo "0";
}

?>