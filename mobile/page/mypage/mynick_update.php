<?php
include_once("../../../common.php");

$mb_id = $member["mb_id"];

$sql = "update `g5_member` set `mb_nick` = '{$mynick}' where mb_id='{$mb_id}' ";

if(sql_query($sql)){
    $member = get_member($mb_id);
    alert("등록완료");
}else{
    alert("등록실패, 관리자에게 문의해 주세요.");
}
?>