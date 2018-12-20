<?php
include_once ("../../../common.php");

if($id==""){
    echo "1";
    return false;
}
//직접삭제
//$sql = "delete from `my_alarms` where id= {$id}";
//삭제처리
$sql = "update `my_alarms` set alarm_status = 3  where id= {$id}";
if(sql_query($sql)){
    echo "2";
}else{
    echo "3";
}
?>