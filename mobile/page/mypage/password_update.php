<?php 
include_once("../../../common.php");

$mb_id = $_REQUEST["mb_id"];
$pass = $_REQUEST["password"];

if($mb_id != $member["mb_id"]){
	alert("비정상 접근입니다. 아이디를 확인해 주세요.");
}

$mb = get_member($mb_id);

// 가입된 회원이 아니다. 비밀번호가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 비밀번호를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 비밀번호가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
/*if (!check_password($pass, $mb['mb_password'])) {
    alert('비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
}*/

if ($pass){
	$sql_password = " `mb_password` = '".get_encrypt_string($pass)."' ";
}

$sql = "update `g5_member` set {$sql_password} where `mb_id` = '{$mb_id}' ";

if(sql_query($sql)){
    if($type!="register") {
        $msg = "변경이 완료 되었습니다.\\r\\n개인정보 변경으로 인해 재로그인 후 이용 가능합니다.";
        alert($msg, G5_BBS_URL . "/logout.php");
    }else{
        alert("회원가입이 완료 되었습니다.",G5_URL);
    }
}else{
	alert("잘못된 접근입니다. 관리자에게 문의 바랍니다.");
}
?>