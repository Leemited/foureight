<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");

$back_url = G5_MOBILE_URL."/page/mypage/settings.php";
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>회원 탈퇴</h2>
    <!-- <div class="sub_add">추가</div> -->
</div>
<div id="settings">
    <div class="setting_wrap">
        <h2>회원탈퇴 안내</h2>
        <ul>
            <li class="single">
                <p>* 회원 탈퇴 시 고객님의 정보는 전자상거래상 소비자 보호에 관합 법률에 의거한 48 고객정보 정책에 따라 관리됩니다.</p>
                <p>* 한 한번 탈퇴한 아이디는 다시 사용할 수 없으니 신중하게 선택 바랍니다.</p>
                <p>* 재가입 요청은 문의 게시판을 이용해 주세요.</p>
            </li>
        </ul>
    </div>
    <div class="setting_wrap">
        <div class="btn_group">
            <input type="button" value="카카오 회원탈퇴" class="setting_btn kakao_btn" onclick="fnSnsLeave();">
        </div>
    </div>
    <form action="<?php echo G5_MOBILE_URL?>/page/mypage/member_leave_update.php" method="post" name="leave_form">
    <div class="setting_wrap">
        <h2>일반회원 탈퇴</h2>
        <ul>
            <li>아이디 <span><?php echo $member["mb_id"];?></span></li>
            <li><input type="password" class="setting_input" name="password" placeholder="현재 비밀번호" required></li>
            <li><input type="password" class="setting_input" name="password_confirm" placeholder="비밀번호 확인" required></li>
            <li><input type="tel" class="setting_input" name="mb_hp" placeholder="휴대폰번호(-생략)" maxlength="12" required></li>
        </ul>
        <div class="btn_group">
            <input type="submit" value="회원탈퇴" class="setting_btn">
        </div>
    </div>
    </form>
</div>
<script>
function fnSnsLeave(){
    if(confirm("회원탈퇴 하시겠습니까?")){
        location.href=g5_url+'/mobile/page/mypage/member_sns_leave.php';
    }else{
        return false;
    }
}
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.php");
