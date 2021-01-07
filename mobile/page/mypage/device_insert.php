<?php
include_once ("../../../common.php");

if($device_name==""){
    alert("등록 기기명을 입력해주세요.");
}

if($device_address1 == "" || $device_address2 == "" || $device_address3 == "" || $device_address4 == ""){
    alert("등록기기 아이피주소를 입력해주세요.");
}

$device_address = base64_encode($device_address1."-".$device_address2."-".$device_address3."-".$device_address4);

$sql = "select * from `mydevice` where mb_id = '{$member["mb_id"]}'";
$device = sql_fetch($sql);

if($device==null){
    //인서트
    $sql = "insert into `mydevice` set mb_id = '{$member["mb_id"]}' , device_name = '{$device_name}' , device_ip = password('{$device_address}') ";
}else{
    //업데이트
    $sql = "update `mydevice` set device_name = '{$device_name}' , device_ip = '{$device_address}' where mb_id ='{$member["mb_id"]}'";
}
if(sql_query($sql)){
    alert("기기 등록이 완료 되었습니다.", G5_MOBILE_URL."/page/mypage/device_add.php");
}else{
    alert("기기등록이 실패하였습니다.");
}

?>