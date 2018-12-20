<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

?>
<div class="wrap">
    <div class="top_h">
        <div class="left" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/login_intro.php'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" ></div>
        <!--<div class="right"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_option.svg" alt=""></div>-->
    </div>

    <div id="mb_login" class="mbskin">
        <h1>비밀번호 찾기</h1>

        <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
            <div class="password_info">
                <p>"48" 아이디[이메일]을 입력해주세요.</p>
                <p>해당 아이디[이메일]로 변경을 위한 주소를 보내드릴 것입니다.</p>
            </div>
            <div id="login_frm single">

                <input type="text" id="mb_email" name="mb_email" placeholder="이메일주소(필수)" required class="frm_input">

            </div>

            <section>
                <div>
                    <input type="submit" value="이메일 발송" class="btn_submit">
                </div>
            </section>

             <div class="btn_confirm" style="margin-top:6vw">
                <a href="<?php echo G5_URL ?>/">메인으로 돌아가기</a>
            </div>

        </form>

    </div>
</div>
<!--<div id="find_info" class="new_win mbskin">
    <h1 id="win_title">아이디/비밀번호 찾기</h1>

    <form name="fpasswordlost" action="<?php /*echo $action_url */?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
    <fieldset id="info_fs">
        <p>
            회원가입 시 등록하신 이메일 주소를 입력해 주세요.<br>
            해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.
        </p>
        <input type="email" id="mb_email" name="mb_email" placeholder="이메일주소(필수)" required class="frm_input email">
    </fieldset>

    <?php /*echo captcha_html(); */?>

    <div class="win_btn">
        <input type="submit" class="btn_submit" value="확인">
        <button type="button" onclick="window.close();">창닫기</button>
    </div>
    </form>
</div>-->

<script>
function fpasswordlost_submit(f)
{
    return true;
}
/*
$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});*/
</script>
