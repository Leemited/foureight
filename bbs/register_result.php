<?php
include_once('./_common.php');

if (isset($_SESSION['ss_mb_reg']))
    $mb = get_member($_SESSION['ss_mb_reg']);

// 회원정보가 없다면 초기 페이지로 이동
if (!$mb['mb_id'])
    goto_url(G5_URL);

if ($mb["mb_password"] == "")
    goto_url(G5_MOBILE_URL."/page/mypage/password_settings.php?type=register");

goto_url(G5_URL);
/*
$g5['title'] = '회원가입이 완료되었습니다.';
include_once(G5_PATH.'/mobile/head.login.php');
include_once($member_skin_path.'/register_result.skin.php');
include_once('./_tail.php');*/
?>