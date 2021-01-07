<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}
$sql = "select * from `mydevice` where mb_id = '{$member["mb_id"]}'";
$device = sql_fetch($sql);
$userAddress = $_SERVER["REMOTE_ADDR"];
$back_url = G5_MOBILE_URL."/page/mypage/settings.php";
?>
<style>
    body{overflow: hidden}
    #settings{height:calc(100vh - 28vw);overflow-y:scroll;position:relative}
    #settings .setting_wrap ul li{padding:2.88vw;}
</style>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>연결기기 등록</h2>
    <div class="all_clear" onclick="fnLocationReset();"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash_all.svg" alt=""></div>
</div>
<div id="settings">
    <form action="<?php echo G5_MOBILE_URL?>/page/mypage/device_insert.php" method="post" name="deviceForm" onsubmit="return false;">
        <div class="setting_wrap">
            <h2>기기 등록 / 변경</h2>
            <ul>
                <li>
                    <input type="text" class="setting_input" id="device_name" name="device_name" placeholder="등록 기기명" value="" required>
                </li>
                <li>
                    <input type="text" class="setting_input" id="device_address1" name="device_address1" placeholder="XXX" maxlength="3" value="" style="width:20%;text-align: center" required> -
                    <input type="text" class="setting_input" id="device_address2" name="device_address2" placeholder="XXX" maxlength="3" value="" style="width:20%;text-align: center" required> -
                    <input type="password" class="setting_input" id="device_address3" name="device_address3" placeholder="XXX" maxlength="3" value="" style="width:20%;text-align: center" required> -
                    <input type="password" class="setting_input" id="device_address4" name="device_address4" placeholder="XXX" maxlength="3" value="" style="width:20%;text-align: center" required>
                </li>
            </ul>
            <div class="btn_group">
                <input type="button" value="<?php if($device){echo "수정";}else{echo "등록";}?>" class="setting_btn" onclick="fnSubmit()" >
            </div>
        </div>
        <?php if($device){?>
        <div class="setting_wrap">
            <h2>현재 연결 기기 등록</h2>
            <ul>
                <li class="single">
                    <h3 style="display: inline-block;vertical-align: middle">
                        <?php echo $device["device_name"]." [<div style='display: inline-block;vertical-align: middle;color:#888;'>".base64_decode($device["device_ip"])."</div>]";?>
                    </h3>
                    <span style="display: inline-block;vertical-align: middle;height:6vw;" onclick="location.href=g5_url+'/mobile/page/mypage/device_delete.php?did=<?php echo $device["did"];?>'">삭제</span>
                </li>
            </ul>
        </div>
        <?php }?>
    </form>
</div>
<script>
    function fnSubmit(){
        document.deviceForm.submit();
    }
</script>
<?php
include_once(G5_PATH."/tail.php");
?>