<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_BBS_URL."/login.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$sql = "select * from `mysetting` where mb_id = '{$member[mb_id]}'";
$settings = sql_fetch($sql);

$mywords = explode(",",$settings["my_words"]);

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
    <style>
        #settings .setting_wrap ul li{padding:2.88vw;}
    </style>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>성별설정</h2>
        <!-- <div class="sub_add">추가</div> -->
    </div>
    <div id="settings">
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_sex_update.php" method="post">
            <div class="setting_wrap">
                <h2>성별 선택</h2>
                <ul>
                    <li class="single"><input type="text" class="setting_input" name="mynick" placeholder="등록할 닉네임을 입력해 주세요." value="<?php echo ($member["mb_nick"])?$member["mb_nick"]:"";?>"></li>
                </ul>
                <div class="btn_group">
                    <input type="submit" value="변경" class="setting_btn">
                </div>
            </div>
        </form>
    </div>
    <script>

    </script>
<?php
include_once(G5_PATH."/tail.php");
?>