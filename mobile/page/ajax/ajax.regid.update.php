<?php
include_once ("../../../common.php");

$sql = "update `g5_member` set regid= '{$regid}', sdkVersion = '{$sdkVersion}' where mb_id = '{$mb_id}'";
echo $sql;
if(sql_query($sql)){
   echo "1";
}else{
    echo "2";
}