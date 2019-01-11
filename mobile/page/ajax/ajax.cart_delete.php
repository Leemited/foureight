<?php
include_once ("../../../common.php");

if(!$cid){
    echo "1";
    return false;
}

$sql = "update `cart` set c_status = 2 where cid = '{$cid}'";
if(sql_query($sql)){
    echo "2";
}else{
    echo "3";
}

?>