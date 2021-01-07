<?php
include_once("../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]){
	goto_url(G5_URL);
}
?>
<div class="wrap">
	<div class="top_h">
		<div class="left" onclick="location.href='<?php echo G5_URL?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_close.svg" alt="" ></div>
<!--		<div class="right"><img src="--><?php //echo G5_IMG_URL?><!--/ic_menu_option.svg" alt=""></div>-->
	</div>
	<section class="login-intro">
		<div class="login-logo">
			<img src="<?php echo G5_IMG_URL?>/login_logo2.svg" alt="">
		</div>
		<div class="login-title">
			<div class="left"></div>
			<p class="wide-line">SNS 계정 로그인</p>
			<div class="right"></div>
		</div>
		<?php
		// 소셜로그인 버튼
		include_once(G5_PLUGIN_PATH.'/oauth/login.skin.inc.php');
		?>
		<div class="login-title">
			<p class="wide-line">로그인 / 회원가입</p>
			<div class="left"></div>
			<div class="right"></div>
			<div class="logins">
				<ul>
					<li class="first" onclick="location.href='<?php echo G5_BBS_URL?>/register_form.php'">회원가입</li>
					<li onclick="location.href='<?php echo G5_BBS_URL?>/login.php'" >로그인</li>
				</ul>
			</div>
		</div>
		<div class="login-info">
			<p>로그인한 회원님은 저희 사용 약관 및<br>개인정보사용정책에 동의하시게 됩니다.</p>
		</div>
	</section>
</div>

<script type="text/javascript">
    AppleID.auth.init({
        clientId : 'kr.co.484848',
        scope : 'name email',
        redirectURI : g5_url+'/plugin/oauth/apple/callback.php',
        usePopup : false //or false defaults to false
    });
</script>

<?php 
include_once(G5_PATH."/tail.sub.php");
?>
