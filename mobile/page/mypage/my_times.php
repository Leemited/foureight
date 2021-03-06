<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}
$sql = "select * from `mysetting` where id= {$id} and mb_id = '{$member["mb_id"]}'";
$settings = sql_fetch($sql);

if($settings["pd_timeFrom"]==""){
    $settings["pd_timeFrom"] = "09";
}
if($settings["pd_timeTo"]==""){
    $settings["pd_timeTo"] = "21";
}

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";
?>
    <style>
        body{overflow: hidden}
        #settings{height:calc(100vh - 30vw);overflow-y:scroll;position:relative}
        #settings .setting_wrap ul li{padding:2.88vw;}
    </style>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>거래 시간 설정</h2>
    </div>
    <div id="settings">
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_time_update.php" method="post" >
        <div class="setting_wrap">
            <h2>거래 시간 설정</h2>
            <div class="my_times">
                <ul>
                    <li class="single" style="text-align:right">
                        <select name="pd_timeFrom" id="pd_timeFrom" class="write_input3" style="width:16vw">
                            <?php for($i = 1; $i< 25; $i++){
                                $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                ?>
                                <option value="<?php echo $time;?>" <?php if($settings["pd_timeFrom"]==$time){?>selected<?php }?>><?php echo $time;?></option>
                            <?php }?>
                        </select> 시부터
                        ~
                        <input type="checkbox" value="1" name="pd_timeType" id="pd_timetype" <?php if($settings["pd_timeType"]==1){?>checked<?php }?> style="display: none"><label for="pd_timetype"><img src="<?php echo G5_IMG_URL?>/ic_write_check.svg" alt=""> 익일 </label>
                        <select name="pd_timeTo" id="pd_timeTo" class="write_input3" style="width:16vw;margin-left:1vw">
                            <?php for($i = 1; $i< 25; $i++){
                                $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                ?>
                                <option value="<?php echo $time;?>" <?php if($settings["pd_timeTo"]==$time){?>selected<?php }?>><?php echo $time;?></option>
                            <?php }?>
                        </select> 시사이
                    </li>
                </ul>
            </div>
            <div class="btn_group">
                <input type="submit" value="등록" class="setting_btn" >
            </div>
        </div>
        </form>
    </div>
<script>
$(document).on("change","#pd_timeFrom",function(){
    var time = $(this).val();
    setCookie("pd_timeFrom",$(this).val(),'1');
    $("#pd_timeTo option").each(function(e){
        if(Number($(this).val()) < Number(time)){
            $(this).attr("disabled",true);
        }else{
            $(this).attr("disabled",false);
        }
        if(Number(time)+1 == e){
            console.log(e+1);
            $(this).attr("selected",true);
        }
    })
});


$(document).on("click","#pd_timetype",function(){
    if($(this).prop("checked")==true){
        $("#pd_timeTo option").each(function(e) {
            $(this).attr("disabled",false);
        });
        setCookie("pd_timetype", 1);
    }else{
        var time = $("#pd_timeFrom").val();

        $("#pd_timeTo option").each(function (e) {
            if (Number($(this).val()) < Number(time)) {
                $(this).attr("disabled", true);
            } else {
                $(this).attr("disabled", false);
            }
            if (Number(time) + 1 == e) {
                $(this).attr("selected", true);
            }
        })
        setCookie("pd_timetype", 0);
    }
});
</script>
<?php
include_once(G5_PATH."/tail.php");
?>