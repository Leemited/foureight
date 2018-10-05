<?php
include_once ("./_common.php");
/*
$sql = "select * from `company_info` where cp_id = '{$cp_id}'";
$file = sql_fetch($sql);

if($file["com_sign"]!=""){
    $path = G5_DATA_PATH."/company/";

    @unlink($path.$file["com_sign"]);
}*/

$sql = "update `company_info` set status = 3 where cp_id = '{$cp_id}'";

if(sql_query($sql)){
    alert("정상 처리되었습니다.");
}else{
    alert("잘못된 요청입니다. 다시 시도해 주세요.");
}