<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");

$app = false;
if(stripos($_SERVER["HTTP_USER_AGENT"],"foureight")){
    $app = true;
}

if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_URL."/mobile/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$sql = "select * from `mysetting` where id= {$id} and mb_id = '{$member["mb_id"]}'";
$settings = sql_fetch($sql);

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";



?>
<style>
#settings .setting_wrap ul li.select_time{display:inline-block;width:calc(100% - 8vw);}
#settings .setting_wrap ul li.select_time select{font-size:4vw;position:absolute;right:2.8vw;top:2.4vw;width:initial;width:20%}
#settings .setting_wrap ul li.select_time select:after{content:"";}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>푸시 알림 설정</h2>
</div>
<div id="settings">
    <?php if($member["sdkVersion"] && $member["sdkVersion"] < 26 ){?>
    <div class="setting_wrap">
        <h2>소리/진동 설정</h2>
        <ul>
            <li>소리 <label class="switch2"><input type="checkbox" id="sound_set" name="sound_set" <?php if($settings["sound_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
            <li>진동 <label class="switch2"><input type="checkbox" id="vibrate_set" name="vibrate_set" <?php if($settings["vibrate_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
        </ul>
    </div>
    <?php }else{?>
    <div class="setting_wrap">
        <h2>소리/진동 설정</h2>
        <ul>
            <li class="single" onclick="<?php if($app){?>fnAppSet();<?php }else{?>alert('앱에서 설정할 수 있습니다.');<?php }?>">앱 설정 바로 가기 <span>안드로이드 오레오 버전 이상</span></li>
        </ul>
    </div>
    <?php }?>
	<div class="setting_wrap">
		<h2>기본 설정</h2>
		<ul>
			<li class="single">전체 푸시알림 켜짐 <label class="switch2" for="push_set"><input type="checkbox" id="push_set" name="push_set" <?php if($settings["push_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>알림 종류</h2>
		<ul>
			<li>기본 알림 <label class="switch2" for="notice_alarm_set"><input type="checkbox" id="notice_alarm_set" name="notice_alarm_set" <?php if($settings["notice_alarm_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>구매관련 알림<label class="switch2" for="pay_reser_set"><input type="checkbox" id="pay_reser_set" name="pay_reser_set" <?php if($settings["pay_reser_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>딜/제시하기 알림<label class="switch2" for="pricing_set"><input type="checkbox" id="pricing_set" name="pricing_set" <?php if($settings["pricing_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>채팅 알림 <label class="switch2" for="chat_alarm_set"><input type="checkbox" id="chat_alarm_set" name="chat_alarm_set" <?php if($settings["chat_alarm_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>댓글 알림 <label class="switch2" for="comment_alarm_set"><input type="checkbox" id="comment_alarm_set" name="comment_alarm_set" <?php if($settings["comment_alarm_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>답변 알림 <label class="switch2" for="recomment_alarm_set"><input type="checkbox" id="recomment_alarm_set" name="recomment_alarm_set" <?php if($settings["recomment_alarm_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>검색 알림 <label class="switch2" for="search_alarm_set"><input type="checkbox" id="search_alarm_set" name="search_alarm_set" <?php if($settings["search_alarm_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>시간 설정</h2>
		<ul>
			<li>에티켓 시간 설정 <label class="switch2" for="etiquette_set"><input type="checkbox" id="etiquette_set" name="etiquette_set" <?php if($settings["etiquette_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li class="select_time">시작시간 <select id="etiquette_time_start" class="setting_input" name="etiquette_time_start"><?php for($i=0;$i<24;$i++){?><option value="<?php $time = (count($i)<0)?"0".$i:$i; echo $time?>" <?php if($settings["etiquette_time_start"]==$time){?>selected<?php }?>><?php echo $time;?>시</option><?php }?></select></li>
			<li class="select_time">종료시간 <select id="etiquette_time_end" class="setting_input" name="etiquette_time_end"><?php for($i=0;$i<24;$i++){?><option value="<?php $time2 = (count($i)<0)?"0".$i:$i; echo $time2?>" <?php if($settings["etiquette_time_end"]==$time2){?>selected<?php }?>><?php echo $time2;?>시</option><?php }?></select></li>
		</ul>
	</div>
</div>
<script>
$(function(){

    $("#push_set").click(function(){
        if($(this).prop("checked")==true){
            //전체 온
            $("#etiquette_set,#comment_alarm_set,#notice_alarm_set,#pricing_set,#pay_reser_set,#chat_alarm_set,#recomment_alarm_set,#search_alarm_set").attr("checked",true);
        }else{
            //전체 오프
            $("#etiquette_set,#comment_alarm_set,#notice_alarm_set,#pricing_set,#pay_reser_set,#chat_alarm_set,#recomment_alarm_set,#search_alarm_set").attr("checked",false);
        }
    })

	$("input[type=checkbox]").each(function(){
		$(this).click(function(){
			var type = $(this).attr("id");
			if($(this).is(":checked")==true){
                fnSetUpdate(type, 1);
			}else{
				fnSetUpdate(type,0);
			}
		});
	});

	$("#etiquette_time_start").change(function(){
	    var type = $(this).attr("id");
	    var state = $(this).val();
        fnSetUpdate(type,state);
    });
    $("#etiquette_time_end ").change(function(){
        var type = $(this).attr("id");
        var state = $(this).val();
        fnSetUpdate(type,state);
    });
});
function fnAppSet(){
    window.android.settingOn();
}
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