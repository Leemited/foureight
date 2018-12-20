<?php
include_once("../../../common.php");

$sql = "delete from `my_trash` where mb_id = '{$mb_id}' and pd_id = {$pd_id}";

if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}