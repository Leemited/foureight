<?php
include_once ("./_common.php");

$sql = "delete from `product_blind` where pd_id = '{$pd_id}'";
sql_query($sql);

$sql = "update `product` set pd_blind = 0, pd_blind_status = 0 where pd_id = {$pd_id}";
if(sql_query($sql)){
    alert("정상 처리되었습니다.", G5_URL."/admin/qa_view.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&qa_id=".$qa_id);
}else{
    alert("잘못된 요청입니다.");
}