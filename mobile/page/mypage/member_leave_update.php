<?php
include_once ("../../../common.php");

if (!$member['mb_id'])
    alert('회원만 접근하실 수 있습니다.');

if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

if (!$_POST['password'] && $_POST['password'] != $_POST['password_confirm'])
    alert('입력한 비밀번호가 같지 않습니다.');

if (!($_POST['password'] && check_password($_POST['password'], $member['mb_password'])))
    alert('회원 정보와 비밀번호가 틀립니다.');

$mb_hp = hyphen_hp_number($_POST["mb_hp"]);

if (!$_POST['mb_hp'] && $mb_hp != $_POST['mb_hp'])
    alert('입력한 전화번호가 회원정보와 같지 않습니다.');

// 회원탈퇴일을 저장
$date = date("Ymd");
$sql = " update {$g5['member_table']} set mb_leave_date = '{$date}' where mb_id = '{$member['mb_id']}' ";
sql_query($sql);

//회원 게시물 삭제처리
$sql = "update `product` set pd_status = 10 where mb_id = '{$member["mb_id"]}'";
sql_query($sql);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

goto_url(G5_MOBILE_URL."/page/mypage/member_leave_complete.php");

?>