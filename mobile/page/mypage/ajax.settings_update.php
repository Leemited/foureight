<?php
include_once("../../../common.php");

$type = $_REQUEST["type"];
$state = $_REQUEST["state"];
$mb_id = $_REQUSET["mb_id"];
if(!$mb_id){
	$mb_id = $member["mb_id"];
}
$sql = "update `mysetting` set `{$type}` = '{$state}' where mb_id = '{$mb_id}'";
echo $sql;
if(sql_query($sql)){
	echo "A";
}else{
	echo "B";
}
?>