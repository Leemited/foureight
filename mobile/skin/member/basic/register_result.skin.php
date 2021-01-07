<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

/*if($member["mb_password"]==""){
    alert("카카오톡 회원가입 후 비밀번호 정보를 반드시 수정하여 사용 바랍니다.",G5_MOBILE_URL."/page/mypage/password_settings.php");
}*/

if($service == ""){
    include_once (G5_PATH."/mobile/skin/member/basic/register_result_confirm.php");
}else{
    include_once (G5_PATH."/mobile/skin/member/basic/register_result_sns.php");
}

?>

