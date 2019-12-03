<?php
include_once("../../../common.php");

$mb_id = $member["mb_id"];
$nick = strtoupper($mynick);
$sql = "select count(*) as cnt from g5_member where UPPER(mb_nick) = '{$mynick}' and mb_id != '{$mb_id}'";
$cnt = sql_fetch($sql);

if($cnt["cnt"]>0){
    alert("중복된 닉네임이 있습니다.\\닉네임은 대소문자 구분을 하지 않습니다.");
}

$sql = "update `g5_member` set `mb_nick` = '{$mynick}' where mb_id='{$mb_id}' ";

if(sql_query($sql)){
    $member = get_member($mb_id);
    alert("등록완료");
}else{
    alert("등록실패, 관리자에게 문의해 주세요.");
}
?>