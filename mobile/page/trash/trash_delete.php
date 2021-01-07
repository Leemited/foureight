<?php
include_once ("../../../common.php");

$sql = "delete from `my_trash` where mb_id = '{$mb_id}'";

if(sql_query($sql)){
    alert("삭제되었습니다.");
}else{
    alert("잘못된 요청입니다.");
}