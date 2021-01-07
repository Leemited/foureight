<?php
include_once ("../../../common.php");

$set = sql_fetch("select * from `mysetting` where mb_id = '{$mb_id}'");
if($set["like_set"]==1){
    echo "1";
}else{
    echo "0";
}