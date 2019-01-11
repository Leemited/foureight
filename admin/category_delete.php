<?php
include_once("./_common.php");

$ca_id = $_REQUEST["ca_id"];

$sql = "delete from `categorys` where ca_id = '{$ca_id}'";
if(sql_query($sql)){
	$sql = "delete from `categorys` where parent_ca_id = '{$ca_id}'";
	sql_query($sql);
	alert("카테고리가 삭제되었습니다.");
}else{
	alert("잘못된 요청입니다.");
}
?>