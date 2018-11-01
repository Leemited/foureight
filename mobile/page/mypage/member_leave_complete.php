<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");
$back_url = G5_URL;
?>
<div class="sub_head">
    <h2>회원 탈퇴 완료</h2>
</div>
<div id="settings">
    <div class="leave_logo">
        <img src="<?php echo G5_IMG_URL?>/logo.svg" alt="">
    </div>
    <div class="setting_wrap">
        <div class="leave_info">
            <p>그동안 48을 이용해 주셔서 감사합니다.</p>
            <p>더욱 좋은 서비스를 위해 노력하겠습니다.</p>
            <p>감사합니다.</p>
        </div>
        <div class="btn_group" style="margin-top:3vh;">
            <input type="button" value="메인으로" class="setting_btn" onclick="location.href=g5_url" style="width:60%">
        </div>
    </div>

</div>
<?php
include_once (G5_MOBILE_PATH."/tail.php");
?>
