<?php
include_once("../../../common.php");

$mb_id = $member["mb_id"];

$sql = "update `mysetting` set pd_timeFrom = '{$pd_timeFrom}' , pd_timeTo = '{$pd_timeTo}', pd_timeType = '{$pd_timeType}'  where mb_id='{$mb_id}' ";

if(sql_query($sql)){
    alert("등록완료");
}else{
    alert("등록실패, 관리자에게 문의해 주세요.");
}

?>