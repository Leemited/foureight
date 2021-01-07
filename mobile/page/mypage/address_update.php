<?php
include_once("../../../common.php");

$mb_id = $member["mb_id"];
$mb_zip1 = $_REQUEST["mb_zip1"];
$mb_addr1 = $_REQUEST["mb_addr1"];
$mb_addr2 = $_REQUEST["mb_addr2"];

$sql = "update `g5_member` set `mb_zip1` = '{$mb_zip1}', `mb_addr1` = '{$mb_addr1}', `mb_addr2` = '{$mb_addr2}' where `mb_id` = '{$mb_id}' ";

if(sql_query($sql)){
	alert("등록완료");
}else{
	alert("등록실패, 관리자에게 문의해 주세요.");
}
?>