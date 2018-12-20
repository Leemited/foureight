<?php
include_once("../../../common.php");

$mb_id = $member["mb_id"];


$sql = "update `mysetting` set `my_locations` = '', `location_lat` = '', `location_lng` = '' where mb_id='{$mb_id}' ";

if(sql_query($sql)){
    alert("리셋완료");
}else{
    alert("등록실패, 관리자에게 문의해 주세요.");
}
?>