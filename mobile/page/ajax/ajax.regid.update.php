<?php
include_once ("../../../common.php");

$sql = "update `g5_member` set regid= '{$regid}' where mb_id = '{$mb_id}'";
if(sql_query($sql)){
   echo "1";
}else{
    echo "2";
}