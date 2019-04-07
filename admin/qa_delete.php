<?php
include_once ("./_common.php");

if(!$qa_id){
    alert("게시물 정보를 읽을 수 없습니다.");
    return false;
}

$sql = "update `g5_qa_content` set qa_status = 3 where qa_id = '{$qa_id}'";
if(sql_query($sql)){
    alert("삭제되었습니다.");
}else{
    alert("게시물이 없거나 잘못된 요청입니다.");
}