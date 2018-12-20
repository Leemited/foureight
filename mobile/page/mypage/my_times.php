<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}
$sql = "select * from `mysetting` where id= {$id} and mb_id = '{$member["mb_id"]}'";
$settings = sql_fetch($sql);

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
            <div>
                <ul>
                    <li class="single" style="text-align:right">
                        <select name="pd_timeFrom" id="pd_timeFrom" class="write_input3" style="width:18vw">
                            <?php for($i = 0; $i< 24; $i++){
                                $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                ?>
                                <option value="<?php echo $time;?>" <?php if($settings["pd_timeFrom"]==$time){?>selected<?php }?>><?php echo $time;?></option>
                            <?php }?>
                        </select> 시부터
                        ~
                        <select name="pd_timeTo" id="pd_timeTo" class="write_input3" style="width:18vw">
                            <?php for($i = 0; $i< 24; $i++){
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
</script>
<?php
include_once(G5_PATH."/tail.php");
?>