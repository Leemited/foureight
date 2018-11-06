<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

include_once(G5_MOBILE_PATH."/head.login.php");

$agent = $_SERVER["HTTP_USER_AGENT"];

if(strpos($agent,"foureight")!==false){
    $check = true;
}else{
    $check = false;
}
?>
<div class="wrap">
	<div class="top_h">
		<div class="left" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/login_intro.php'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" ></div>
		<!--<div class="right"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_option.svg" alt=""></div>-->
	</div>

	<div id="mb_login" class="mbskin">
		<h1><?php echo $g5['title'] ?></h1>

		<form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
		<input type="hidden" name="url" value="<?php echo $login_url ?>">
            <input type="hidden" name="regid" id="regid" value="">

		<div id="login_frm">
            <?php
            // 소셜로그인 버튼
            include_once(G5_PLUGIN_PATH.'/oauth/login.skin.inc.php');
            ?>
			<input type="text" name="mb_id" id="login_id" placeholder="이메일" required class="frm_input" placeholder="" autocomplete="off" value="<?php echo $_REQUEST["mb_id"];?>">
			<input type="password" name="mb_password" id="login_pw" placeholder="비밀번호" required class="frm_input" autocomplete="off">
			<!-- <div>
				<input type="checkbox" name="auto_login" id="login_auto_login">
				<label for="login_auto_login">자동로그인</label>
			</div> -->
		</div>

		<section>
            <?php
            // 소셜로그인 버튼
            include_once(G5_PLUGIN_PATH.'/oauth/login.skin.inc.php');
            ?>
			<div class="login_link">
				<a href="<?php echo G5_BBS_URL ?>/password_lost.php" target="_self" id="login_password_lost" class="">비밀번호를 잊으셨나요?</a>
			</div>
			<div>
				<input type="submit" value="로그인" class="btn_submit">

			</div>
			<div class="login_link">
				계정이 없으신가요?<a href="./register_form.php" class="register_link"> 회원 가입 하기</a>
			</div>
		</section>

		<!-- <div class="btn_confirm">
			<a href="<?php echo G5_URL ?>/">메인으로 돌아가기</a>
		</div> -->

		</form>

	</div>
</div>

<script>
$(function(){
    //getRegid
    try{
        var regId = window.android.getRegid();
        $("#regid").val(regId);
    }catch(err){
        var regId = undefined;
        console.log(err);
    }
});
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });

	$("#login_id").focus();
    <?php if($check){?>
    window.android.Onkeyboard();
    <?php }?>
});

function flogin_submit(f)
{
    return true;
}
</script>



