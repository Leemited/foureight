<?php
include_once("../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]){
    goto_url(G5_URL);
}
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

?>
<div class="wrap">
    <div class="top_h">
        <div class="left" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/login_intro.php'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" ></div>
        <!--<div class="right"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_option.svg" alt=""></div>-->
    </div>

    <div id="mb_login" class="mbskin">
        <h1>비밀번호 찾기</h1>

        <form name="flogin" action="<?php echo G5_MOBILE_URL?>/page/find_password_update.php" onsubmit="" method="post">
            <div>
                <p>"48" 아이디[이메일]을 입력해주세요.</p>
                <p>해당 아이디[이메일]로 변경을 위한 주소를 보내드릴 것입니다.</p>
            </div>
            <div id="login_frm single">

                <input type="text" name="mb_id" id="login_id" placeholder="이메일" required class="frm_input" autocomplete="off" value="<?php echo $_REQUEST["mb_id"];?>">

            </div>

            <section>
                <div>
                    <input type="submit" value="이메일 발송" class="btn_submit">
                </div>
            </section>

            <!-- <div class="btn_confirm">
			<a href="<?php echo G5_URL ?>/">메인으로 돌아가기</a>
		</div> -->

        </form>

    </div>
</div>
<?php
include_once(G5_PATH."/tail.sub.php");
?>
