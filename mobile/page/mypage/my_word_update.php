<?php
include_once("../../../common.php");

$mb_id = $member["mb_id"];
if($_REQUEST["myword"]){
	$words = implode(",",$_REQUEST["myword"]);
}

$sql = "update `mysetting` set `my_words` = '{$words}' where mb_id='{$mb_id}' ";

sql_query($sql);

if($_REQUEST["myword2"]){
	$words2 = implode(",",$_REQUEST["myword2"]);
}

$sql = "update `mysetting` set `my_word` = '{$words2}' where mb_id='{$mb_id}' ";

if(sql_query($sql)){
	alert("등록완료");
}else{
	alert("등록실패, 관리자에게 문의해 주세요.");
}
?>