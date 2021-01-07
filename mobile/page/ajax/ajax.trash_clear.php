<?php
include_once("../../../common.php");

$sql = "delete from `my_trash` where mb_id = '{$mb_id}'";

if(sql_query($sql)){
    echo "삭제되었습니다.";
}else{
    echo "잘못된 요청입니다.";
}