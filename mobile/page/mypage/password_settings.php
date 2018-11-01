<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
<style>
#settings .setting_wrap ul li{padding:2vw;}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>비밀번호 변경</h2>
	<!-- <div class="sub_add">추가</div> -->
</div>
<div id="settings">
	<form action="<?php echo G5_MOBILE_URL?>/page/mypage/password_update.php" method="post" onsubmit="fnsubmit();">
	<input type="hidden" name="mb_id" value="<?php echo $member["mb_id"];?>">
		<!-- <div class="setting_wrap">
			<h2>현재 비밀 번호</h2>
			<ul>
				<li class="single"><input type="password" class="setting_input" name="ori_password" required placeholder="현재 비밀번호 입력"></li>
			</ul>
		</div> -->
		<div class="setting_wrap">
			<h2>변경 비밀 번호</h2>
			<ul>
				<li><input type="password" class="setting_input" name="password" minlength="4" maxlength="15" required placeholder="변경 비밀번호 입력(최소 4자 최대 15자, 영문 숫자 혼합)"></li>
				<li><input type="password" class="setting_input" name="password_re" minlength="4" maxlength="15" required placeholder="현재 비밀번호 확인(최소 4자 최대 15자, 영문 숫자 혼합)"></li>
			</ul>
			<div class="btn_group">
				<input type="submit" value="변경" class="setting_btn">
			</div>
		</div>
	</form>
</div>
<script>
function fnsubmit(){

}
</script>
<?php 
include_once(G5_PATH."/tail.php");?>