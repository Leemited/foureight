<?php
include_once ("../../../common.php");

$sql = "update `order` set od_del_status = 1 where od_id = '{$od_id}'";

if(sql_query($sql)){
    alert("삭제되었습니다.");
}else{
    alert("삭제 실패 하였습니다.");
}

?>