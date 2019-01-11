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
        session_unset(); // 모든 세션변수를 언레지스터 시켜줌
        session_destroy(); // 세션해제함

        $date = date("Ymd");
        $sql = " update {$g5['member_table']} set mb_leave_date = '{$date}', regid = '' where mb_id = '{$member['mb_id']}' ";
        if(sql_query($sql)){
            $sql = "update `product` set pd_status = 10 where mb_id = '{$member["mb_id"]}'";
            sql_query($sql);
            unset($member);
            goto_url(G5_MOBILE_URL."/page/mypage/member_leave_complete.php");
        }else{
            alert("잘못 된 요청입니다.",G5_MOBILE_URL."/page/mypage/member_leave.php");
        }
    //}else{

    //}
}
