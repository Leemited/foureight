<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_BBS_URL."/login.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$sql = "select * from `mysetting` where mb_id = '{$member[mb_id]}'";
$settings = sql_fetch($sql);

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
<style>
#settings{height:calc(100vh - 20vw);}
#settings .setting_wrap ul li.select_time{display:inline-block;width:calc(100% - 8vw);}
#settings .setting_wrap ul li.select_time select{font-size:4vw;position:absolute;right:2.8vw;top:2.4vw;width:initial;width:20%}
#settings .setting_wrap ul li.select_time select:after{content:"";}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>푸시 알림 설정</h2>
</div>
<div id="settings">
	<div class="setting_wrap">
		<h2>기본 설정</h2>
		<ul>
			<li class="single">전체 푸시알림 켜짐 <label class="switch2"><input type="checkbox" id="push_set" name="push_set" <?php if($settings["push_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>알림 종류</h2>
		<ul>
			<li>안전 결제 진행 <label class="switch2"><input type="checkbox" id="safe_pay_set" name="safe_pay_set" <?php if($settings["safe_pay_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>채팅 대화 알림 <label class="switch2"><input type="checkbox" id="chat_alarm_set" name="chat_alarm_set" <?php if($settings["chat_alram_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>댓글 알림 설정</h2>
		<ul>
			<li>댓글, 답변, 대화 알림 <label class="switch2"><input type="checkbox" id="comment_alram_set" name="comment_alram_set" <?php if($settings["comment_alram_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>기본 알림 (게시글, 검색) <label class="switch2"><input type="checkbox" id="notice_alram_set" name="notice_alram_set" <?php if($settings["notice_alram_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>시간 설정</h2>
		<ul>
			<li>에티켓 시간 설정 <label class="switch2"><input type="checkbox" id="etiquette_set" name="etiquette_set" <?php if($settings["etiquette_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li class="select_time">시작시간 <select id="etiquette_time_start" class="setting_input" name="etiquette_time_start"><?php for($i=0;$i<24;$i++){?><option value="<?php $time = (count($i)<0)?"0".$i:$i; echo $time?>" <?php if($settings["etiquette_time_start"]==$time){?>selected<?php }?>><?php echo $time;?>시</option><?php }?></select></li>
			<li class="select_time">종료시간 <select id="etiquette_time_end" class="setting_input" name="etiquette_time_end"><?php for($i=0;$i<24;$i++){?><option value="<?php $time2 = (count($i)<0)?"0".$i:$i;?>"  <?php if($settings["etiquette_time_end"]==$time2){?>selected<?php }?>><?php echo $time2;?>시</option><?php }?></select></li>
		</ul>
	</div>
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

	$("#etiquette_time_end , #etiquette_time_start").change(function(){
	    var type = $(this).attr("id");
	    var state = $(this).val();
        fnSetUpdate(type,state);
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