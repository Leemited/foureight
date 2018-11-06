<?php
include_once ("../../../common.php");

//소셜 가입체크
$sql = "select * from `g5_social_member` where mb_id ='{$member["mb_id"]}'";
$sns_id = sql_fetch($sql);

if($sns_id==""){
    alert("카카오톡 가입회원이 아닙니다.",G5_MOBILE_URL."/page/mypage/member_leave.php");
}else{
    //$sql = "delete from `g5_social_member` where mb_id ='{$member["mb_id"]}'";
    //if(sql_query($sql)) {
        $date = date("Ymd");
        $sql = " update {$g5['member_table']} set mb_leave_date = '{$date}' where mb_id = '{$member['mb_id']}' ";
        if(sql_query($sql)){
            goto_url(G5_MOBILE_URL."/page/mypage/member_leave_complete.php");
        }else{
            alert("잘못 된 요청입니다.",G5_MOBILE_URL."/page/mypage/member_leave.php");
        }
    //}else{

    //}
}