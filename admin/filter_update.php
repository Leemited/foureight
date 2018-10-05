<?php
include_once("./_common.php");

$cf_filter = $_REQUEST["cf_filter"];

$sql = "update `g5_config` set cf_filter = '{$cf_filter}'";

if(sql_query($sql)){
	alert("수정되었습니다.");
}else{
	alert("잘못된 접근입니다.\r\n다시시도해 주세요");
}