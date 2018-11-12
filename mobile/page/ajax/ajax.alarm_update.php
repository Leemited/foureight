<?php
include_once ("../../../common.php");

if($id==""){
    echo "3";
}
$sql = "update `my_alarms` set alarm_satus = 1 where id = {$id}";
if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}

?>