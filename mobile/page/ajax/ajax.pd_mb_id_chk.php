<?php
include_once ("../../../common.php");

$sql = "select * from `product` where pd_id = '{$pd_id}'";
$pd = sql_fetch($sql);

if($pd["mb_id"]==$member["mb_id"]){
    echo "0";
}else{
    echo "1";
}


?>