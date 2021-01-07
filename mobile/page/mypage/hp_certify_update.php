<?php
include_once ("../../../common.php");
$sql = "update `g5_member` set mb_hp = '{$mb_hp}', mb_certify= '{$cert_type}' where mb_id = '{$member["mb_id"]}'";
if(sql_query($sql)){
    $member = get_member($member["mb_id"]);
    alert("인증 완료 되었습니다.",G5_MOBILE_URL."/page/mypage/settings.php");
}else{
    alert("잘못된 요청입니다.");
}