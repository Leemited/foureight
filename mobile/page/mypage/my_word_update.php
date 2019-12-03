<?php
include_once("../../../common.php");
if($mb_id=="") {
    $mb_id = $member["mb_id"];
}
if($_REQUEST["myword1"]){
	$words = implode("!@~",$_REQUEST["myword1"]);
}

if($_REQUEST["myword2"]){
	$words2 = implode("!@~",$_REQUEST["myword2"]);
}

if($_REQUEST["myword3"]){
    $words3 = implode("!@~",$_REQUEST["myword3"]);
}

$myword = $words . ":@!" . $words2 . ":@!" . $words3;

$sql = "update `mysetting` set `my_word` = '{$myword}' where id = {$id} and mb_id='{$mb_id}' ";

if(sql_query($sql)){
	alert("등록완료");
}else{
	alert("등록실패, 관리자에게 문의해 주세요.");
}
?>