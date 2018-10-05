<?php
include_once ("./_common.php");
$sql = "select count(*) as cnt from `g5_qa_content` where `qa_parent` = '{$qa_parent}' and qa_type = 1";
$cnt = sql_fetch($sql);
if($cnt[cnt] == 1){
    $sql = "update `g5_qa_content` set qa_status = 0 where qa_id = '{$qa_parent}'";
    sql_query($sql);
}

$sql = "delete from `g5_qa_content` where qa_id = '{$qa_id}' ";
if(sql_query($sql)){
    alert('처리되었습니다.');
}else{
    alert("잘못된 요청입니다.");
}