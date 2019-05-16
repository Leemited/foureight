<?php
include_once ("./_common.php");

$sql = "update `categorys` set cate_status = '{$status}' where ca_id = '{$ca_id}'";

if(sql_query($sql)){
    alert("수정되었습니다.");
}else{
    alert("잘못된 요청입니다.");
}