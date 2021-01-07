<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>개인 정보 설정</h2>
</div>
<div id="settings">
    <div >
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/password_update.php" method="post" onsubmit="fnsubmit();">
            <input type="hidden" name="mb_id" value="<?php echo $member["mb_id"];?>">
            <!-- <div class="setting_wrap">
                <h2>현재 비밀 번호</h2>
                <ul>
                    <li class="single"><input type="password" class="setting_input" name="ori_password" required placeholder="현재 비밀번호 입력"></li>
                </ul>
            </div> -->
            <div class="setting_wrap">
                <h2>비밀 번호 설정</h2>
                <ul>
                    <li><input type="password" class="frm_input" name="password" minlength="4" maxlength="15" required placeholder="변경 비밀번호 입력(최소 4자 최대 15자, 영문 숫자 혼합)"></li>
                    <li><input type="password" class="frm_input" name="password_re" minlength="4" maxlength="15" required placeholder="현재 비밀번호 확인(최소 4자 최대 15자, 영문 숫자 혼합)"></li>
                </ul>
            </div>
            <div class="setting_wrap">
                <h2>휴대폰 인증</h2>
                <ul>
                    <li class="single"><input type="mb_hp" class="frm_input" name="mb_hp" minlength="20" maxlength="20" required placeholder="'-'생략"><input type="button" id="vaild_mb_hp" value="인증하기"></li>
                </ul>
                <div class="btn_group">
                    <input type="submit" value="확인" class="setting_btn">
                </div>
            </div>
        </form>
    </div>
</div>