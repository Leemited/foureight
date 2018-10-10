<?php
include_once ("./_common.php");

$sql = "update `product` set pd_blind = 10, pd_blind_status = 1 where pd_id = {$pd_id}";
if(sql_query($sql)){
    alert("정상 처리되었습니다.");
}else{
    alert("잘못된 요청입니다.");
}