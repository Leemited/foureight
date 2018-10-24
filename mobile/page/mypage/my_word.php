<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_URL."/mobile/page/login.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$sql = "select * from `mysetting` where id= {$id} and mb_id = '{$member["mb_id"]}'";
$settings = sql_fetch($sql);

$mywords = explode(":@!",$settings["my_word"]);
for($i=0;$i<count($mywords);$i++){
    $mywordss[] = explode("!@~",$mywords[$i]);
}

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
<style>
#settings .setting_wrap ul li{padding:2.88vw;}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>개인 문구 설정</h2>
	<!-- <div class="sub_add">추가</div> -->
</div>
<div id="settings">
	<div class="setting_wrap">
		<h2>개인 문구 활성화</h2>
		<ul>
			<li class="single" style="padding:4vw;">개인문구 설정 <label class="switch2"><input type="checkbox" id="my_word_set" name="my_word_set" <?php if($settings["my_word_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_word_update.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id;?>">
		<div class="setting_wrap">
			<h2>구매 시</h2>
			<ul>
				<li><input type="text" class="setting_input" name="myword1[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[0][0])?$mywordss[0][0]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword1[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[0][1])?$mywordss[0][1]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword1[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[0][2])?$mywordss[0][2]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword1[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[0][3])?$mywordss[0][3]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword1[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[0][4])?$mywordss[0][4]:"";?>"></li>
			</ul>
		</div>
		<div class="setting_wrap">
			<h2>판매 시</h2>
			<ul>
				<li><input type="text" class="setting_input" name="myword2[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[1][0])?$mywordss[1][0]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword2[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[1][1])?$mywordss[1][1]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword2[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[1][2])?$mywordss[1][2]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword2[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[1][3])?$mywordss[1][3]:"";?>"></li>
				<li><input type="text" class="setting_input" name="myword2[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[1][4])?$mywordss[1][4]:"";?>"></li>
			</ul>
		</div>
        <div class="setting_wrap">
            <h2>대화 시</h2>
            <ul>
                <li><input type="text" class="setting_input" name="myword3[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[2][0])?$mywordss[2][0]:"";?>"></li>
                <li><input type="text" class="setting_input" name="myword3[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[2][1])?$mywordss[2][1]:"";?>"></li>
                <li><input type="text" class="setting_input" name="myword3[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[2][2])?$mywordss[2][2]:"";?>"></li>
                <li><input type="text" class="setting_input" name="myword3[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[2][3])?$mywordss[2][3]:"";?>"></li>
                <li><input type="text" class="setting_input" name="myword3[]" placeholder="예) 강남역 1번 출구" value="<?php echo ($mywordss[2][4])?$mywordss[2][4]:"";?>"></li>
            </ul>
            <div class="btn_group">
                <input type="submit" value="수정" class="setting_btn">
            </div>
        </div>
	</form>
</div>
<script>
$(function(){
	$("input[type=checkbox]").each(function(){
		$(this).click(function(){
			var type = $(this).attr("id");
			if($(this).is(":checked")==true){
				fnSetUpdate(type,1);	
			}else{
				fnSetUpdate(type,0);
			}
		});
	});
});
function fnSetUpdate(type,state){
	$.ajax({
		url:g5_url+"/mobile/page/mypage/ajax.settings_update.php",
		data:{type:type,state:state},
		method:"POST"
	}).done(function(data){
		console.log(data);
	});
}
</script>
<?php 
include_once(G5_PATH."/tail.php");
?>