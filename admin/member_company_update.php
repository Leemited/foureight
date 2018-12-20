<?php
include_once ("./_common.php");

$sql = "select * from `company_info` where cp_id = '{$cp_id}'";
$com = sql_fetch($sql);

$sql = "update `company_info` set status = {$status} where cp_id = '{$cp_id}'";
if($status == 1){
    $level = 4;
}else {
    $level = 2;
}
if(sql_query($sql)) {
    $sql = "update `g5_member` set mb_level = {$level} where mb_id = '{$com["mb_id"]}'";
    if (sql_query($sql)) {

        $mb = get_member($com["mb_id"]);

        send_FCM($mb["regid"],"48알림","기업신청이 승인되었습니다.",G5_MOBILE_URL."/page/mypage/alarm.php","notice_alarm_set","기본알람",$mb["mb_id"],"","");

        alert("사업자회원등급으로 수정되었습니다.");
    } else {
        alert("잘못된 요청입니다. 다시 시도해 주세요.");
    }
}else{
    alert("신청정보오류 입니다. 다시 시도해 주세요.");
}