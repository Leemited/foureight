<?php
include_once("../../../common.php");

$mb_id = $member["mb_id"];
if($_REQUEST["mylocation"]){
	$mylocations = implode(",",$_REQUEST["mylocation"]);
}
if($_REQUEST["mylat"]){
    $mylats = implode(",",$_REQUEST["mylat"]);
}
if($_REQUEST["mylng"]){
    $mylngs = implode(",",$_REQUEST["mylng"]);
}

$sql = "update `mysetting` set `my_locations` = '{$mylocations}', `location_lat` = '{$mylats}', `location_lng` = '{$mylngs}' where mb_id='{$mb_id}' ";

if(sql_query($sql)){
	alert("등록완료");
}else{
	alert("등록실패, 관리자에게 문의해 주세요.");
}
?>