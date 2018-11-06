<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_URL."/mobile/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$sql = "select * from `mysetting` where mb_id = '{$member[mb_id]}'";
$settings = sql_fetch($sql);

$back_url=G5_MOBILE_URL."/page/mypage/mypage.php";
$agent = $_SERVER["HTTP_USER_AGENT"];

if(strpos($agent,"foureight")!==false){
	$check = true;
}else{
	$check = false;
}

$sns_login = sql_fetch("select * from `g5_social_member` where mb_id = '{$member[mb_id]}'");

?>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>개인 정보 설정</h2>
</div>
<div id="settings" class="main">
	<div class="setting_wrap_top">
		<div class="profile">
            <form action="<?php echo G5_MOBILE_URL;?>/page/ajax/ajax.profile_upload.php" enctype="multipart/form-data" method="post" id="profile_form">
                <input type="hidden" name="mb_no" id="mb_no" value="<?php echo $member["mb_no"];?>">
			<label for="profile_img">
			<div class="choice_photo" onclick="<?php if($check){}else{}?>">	
				<img src="<?php echo G5_IMG_URL?>/ic_profile_camera.svg" alt="">
				<input type="file" name="profile_img" id="profile_img" style="display:none;" onchange="fnProfileUp();">
			</div>
			</label>
            </form>
            <div class="profile_thumb" style="<?php if($member["mb_profile"]){?>background-image:url('<?php echo $member["mb_profile"];?>')<?php }else{ ?>background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg')<?php }?>;background-size:cover;background-repeat:no-repeat;background-position:center;">
			<?php if($member["mb_profile"]){?>
				<img src="<?php echo $member["mb_profile"];?>" alt="" class="profile" id="profile_photo" style="display:none">
			<?php }else{ ?>
				<img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" id="profile_photo" style="display:none">
			<?php } ?>
            </div>
		</div>
	</div>
	<div class="setting_wrap">
		<h2>기본정보 설정</h2>
		<ul>
			<li>계정아이디 <?php if($sns_login["mb_id"]){?>
			<div class="sns <?php echo $sns_login["sm_service"];?>">
				<?php if($sns_login["sm_service"]=="naver"){?>
					<img src="<?php echo G5_IMG_URL?>/sns_login_naver.svg" alt="">
				<?php } if($sns_login["sm_service"]=="facebook"){?>
					<img src="<?php echo G5_IMG_URL?>/sns_login_facebook.svg" alt="">
				<?php } if($sns_login["sm_service"]=="kakao"){?>
					<img src="<?php echo G5_IMG_URL?>/sns_login_kakao.svg" alt="">
				<?php }?>
			</div>	
			<?php }?> <span><?php echo ($member["mb_id"])?$member["mb_id"]:"아이디를 표시할 수 없습니다.";?></span></li>
			<li onclick="fnEditNick();">닉네임 <span><?php echo ($member["mb_nick"])?$member["mb_nick"]:"닉네임이 설정되지 않았습니다.";?></span></li>
			<li onclick="fnEditTel('<?php echo $member["mb_hp"];?>');">전화번호 <span><?php if($member["mb_certify"]!="hp"){echo "미인증"; }else{echo "인증완료";}?>&nbsp;&nbsp;<?php echo ($member["mb_hp"])?$member["mb_hp"]:"연락처를 표시 할 수 없습니다.";?></span></li>
			<li class="set_sex">성별설정 <span><input type="radio" value="" name="mb_sex" id="no-sex" <?php if($member["mb_sex"]==""){?>checked<?php }?>><label for="no-sex">비공개</label><input type="radio" value="F" name="mb_sex" id="woman" <?php if($member["mb_sex"]=="F"){?>checked<?php }?>><label for="woman">여성</label> <input type="radio" value="M" name="mb_sex" id="man" <?php if($member["mb_sex"]=="M"){?>checked<?php }?>><label for="man">남성</label></span></li>
			<li onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/password_settings.php?id=<?php echo $settings['id'];?>'">비밀번호 변경</li>
			<li onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/address_settings.php?id=<?php echo $settings['id'];?>'">주소변경</li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>연락기능 설정</h2>
		<ul>
            <li>연락처 비공개 설정 <label class="switch2"><input type="checkbox" id="show_hp" name="show_hp" <?php if($settings["show_hp"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
            <li>전화가능 <label class="switch2"><input type="checkbox" id="hp_set" name="hp_set" <?php if($settings["hp_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>문자가능 <label class="switch2"><input type="checkbox" id="sms_set" name="sms_set" <?php if($settings["sms_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>대화가능 <label class="switch2"><input type="checkbox" id="chat_set" name="chat_set" <?php if($settings["chat_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>댓글가능 <label class="switch2"><input type="checkbox" id="comment_set" name="comment_set" <?php if($settings["comment_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>알림 설정</h2>
		<ul>
			<li class="single" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/push_settings.php?id=<?php echo $settings['id'];?>'">푸시 알림 설정</li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>게시판 설정</h2>
		<ul>
			<li>평가 허용 설정 <label class="switch2"><input type="checkbox" id="like_set" name="like_set" <?php if($settings["like_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
			<li>댓글 등록시 비공개 설정 <label class="switch2"><input type="checkbox" id="comment_secret_set" name="comment_secret_set" <?php if($settings["comment_secret_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
	<div class="setting_wrap">
		<h2>피드백 설정</h2>
		<ul>
			<li class="single">최근 6개월간의 피드백만 노출 <label class="switch2"><input type="checkbox" id="feed_set" name="feed_set" <?php if($settings["feed_set"]==1){echo "checked";}?>><span class="set_slider round"></span></label></li>
		</ul>
	</div>
    <div class="setting_wrap">
        <h2>결제설정</h2>
        <ul>
            <li onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/my_card.php?id=<?php echo $settings['id'];?>'">카드등록</li>
            <li onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/my_bank.php?id=<?php echo $settings['id'];?>'">계좌등록</li>
        </ul>
    </div>
	<div class="setting_wrap">
		<h2>간편 거래 설정</h2>
		<ul>
			<li onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/my_word.php?id=<?php echo $settings['id'];?>'">개인 문구 등록</li>
			<li onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/my_location.php?id=<?php echo $settings['id'];?>'">거래 위치 설정</li>
		</ul>
	</div>
    <div class="setting_wrap ">
        <h2>찬단목록</h2>
        <ul>
            <li class="single" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/member_block_list.php?id=<?php echo $settings['id'];?>'">차단회원 목록</li>
        </ul>
    </div>
    <div class="setting_wrap ">
        <h2>회원탈퇴</h2>
        <ul>
            <li class="single" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/member_leave.php?id=<?php echo $settings['id'];?>'">회원탈퇴</li>
        </ul>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>
<script>
var id = "<?php echo $settings['id'];?>";
$(function(){
	$("input[type=checkbox]").each(function(){
		$(this).click(function(){
			var type = $(this).attr("id");
			if(type == "hp_set" || type == "sms_set"){
			    //두개 체크 여부 확인
                var hp_chk = $("#hp_set").prop("checked");
                var sms_chk = $("#sms_set").prop("checked");
                if(hp_chk == sms_chk){
                    console.log("A");
                    if(hp_chk == false){
                        $("#show_hp").attr("checked",true);
                        fnSetUpdate("hp_set",0,id);
                        fnSetUpdate("sms_set",0,id);
                        //fnSetUpdate("show_hp",1,id);
                    }else{
                        $("#show_hp").attr("checked",false);
                        fnSetUpdate("hp_set",1,id);
                        fnSetUpdate("sms_set",1,id);
                        //fnSetUpdate("show_hp",0,id);
                    }
                }else{
                    if ($(this).is(":checked") == true) {
                        fnSetUpdate(type, 1, id);
                    } else {
                        fnSetUpdate(type, 0, id);
                    }
                }
            }else {
                console.log("B");
                if ($(this).is(":checked") == true) {
                    fnSetUpdate(type, 1, id);
                } else {
                    fnSetUpdate(type, 0, id);
                }
            }
		});
	});

	$(".set_sex label").click(function(){
	   var type = $(this).prev().attr("name");
	   var status = $(this).prev().val();
	   fnSetUpdate3(type,status);
    });
});

function fnSetUpdate(type,state){
    if(type=="show_hp" && state == 1){
        $("#hp_set").attr("checked",false);
        $("#sms_set").attr("checked",false);
        fnSetUpdate2("hp_set",0);
        fnSetUpdate2("sms_set",0);
    }else if(type=="show_hp" && state == 0){
        $("#hp_set").attr("checked",true);
        $("#sms_set").attr("checked",true);
        fnSetUpdate2("hp_set",1);
        fnSetUpdate2("sms_set",1);
    }
	$.ajax({
		url:g5_url+"/mobile/page/mypage/ajax.settings_update.php",
		data:{type:type,state:state,id:id},
		method:"POST"
	}).done(function(data){
		console.log(data);
	});
}

function fnSetUpdate2(type,state){
    $.ajax({
        url:g5_url+"/mobile/page/mypage/ajax.settings_update.php",
        data:{type:type,state:state,id:id},
        method:"POST"
    }).done(function(data){
        console.log(data);
    });
}

function fnSetUpdate3(type,state){
    $.ajax({
        url:g5_url+"/mobile/page/mypage/ajax.settings_update2.php",
        data:{type:type,state:state,id:id},
        method:"POST"
    }).done(function(data){
        console.log(data);
    });
}

function fnEditNick(){
	if(confirm("닉네임 변경시 이전 기록에 영향을 줄 수 있습니다.\n변경하시겠습니까?")){
		location.href=g5_url+'/mobile/page/mypage/mynick_setting.php';
	}else{
		return false;
	}
}

function fnEditTel(mb_tel){
    if(mb_tel==""){
       alert("등록된 번호가 없어 등록 페이지로 이동합니다.");
       location.href=g5_url+'/mobile/page/mypage/hp_certify.php';
    }else {
        /*if ('' != "hp") {

        }else{
            if(confirm("이미 등록된 번호가 있습니다. \r\n수정을 하실경우 제인증이 필요합니다.\r수정하시겠습니까?")){*/
                location.href=g5_url+'/mobile/page/mypage/hp_certify.php?w=r';
           /* }else{
                return false;
            }
        }*/
    }
}

function fnProfileUp(){
    /*var form = $("#profile_form")[0];
    var formData = new FormData(form);
    formData.append("profile_img",$("#profile_img")[0].files[0]);
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.profile_upload.php",
        contentType:false,
        data:formData,
        type:"POST"
    }).done(function(data){
        console.log(data);
    });*/
    var option = {
        dataType:'json',
        beforeSubmit:function (data,form,option){
            return true;
        },success:function(response,status){
            if(response.result == "1"){
                $(".profile_thumb").css("background-image","url('"+response.filename+"')");
                alert("프로필이 정상 수정되었습니다.");
            }else if(response.result == "2"){
                alert("회원정보가 잘못 요청되었습니다. 다시 시도해주세요.");
            }else if(response.result == "3"){
                alert("잘못된 요청입니다. 파일을 확인해주세요.");
            }
        },error:function(e){
            console.log(e);
        }
    };
    $("#profile_form").ajaxForm(option).submit();

}

</script>
<?php 
include_once(G5_PATH."/tail.php");
?>