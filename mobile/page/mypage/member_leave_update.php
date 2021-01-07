<?php
include_once ("../../../common.php");

if (!$member['mb_id'])
    alert('회원만 접근하실 수 있습니다.');

if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

//SNS 로그인 체크해서 비밀번호 무시
if (!$_POST['password'] && $_POST['password'] != $_POST['password_confirm'])
    alert('입력한 비밀번호가 같지 않습니다.');

if (!($_POST['password'] && check_password($_POST['password'], $member['mb_password'])))
    alert('회원 정보와 비밀번호가 틀립니다.');

$mb_hp = hyphen_hp_number($_POST["mb_hp"]);

if (!$_POST['mb_hp'] && $mb_hp != $_POST['mb_hp'])
    alert('입력한 전화번호가 회원정보와 같지 않습니다.');

$sql = "select count(*) as cnt from `order` as o left join `product` as p on p.pd_id = o.pd_id left join `g5_member` as m on m.mb_id = p.mb_id where m.mb_id = '{$mb_id}' and o.od_status = 1 and o.od_cancel_status = 0 and o.od_fin_status = 0 and o.pay_oid <> ''";
$order = sql_fetch(sql);
if($order["cnt"] > 0){
    alert("현재 거래중인 상품,능력이 있어 탈퇴처리 하지 못하였습니다.\\r\\n거래를 완료 후 탈퇴 바랍니다.");
}
// 회원탈퇴일을 저장
$date = date("Ymd");
$sql = " update {$g5['member_table']} set mb_leave_date = '{$date}' where mb_id = '{$member['mb_id']}' ";
sql_query($sql);

//회원 게시물 삭제처리
$sql = "update `product` set pd_status = 10 where mb_id = '{$member["mb_id"]}'";
sql_query($sql);
//거래진행중 취소


// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

goto_url(G5_MOBILE_URL."/page/mypage/member_leave_complete.php");

?>