<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$back_url=G5_MOBILE_URL."/page/mypage/mypage.php";
add_javascript(G5_POSTCODE_JS, 0);

$sql = "select * from `company_info` where mb_id = '{$member["mb_id"]}'";
$view = sql_fetch($sql);
if($view["cp_id"]){
    $readonly = "readonly";
}
?>
<style>
    #settings .setting_wrap ul li{padding:2.88vw;}
</style>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>기업회원 신청</h2>
    <!-- <div class="sub_add">추가</div> -->
</div>
<div id="settings">
    <form action="<?php echo G5_MOBILE_URL?>/page/mypage/company_update.php" method="post" name="com_form" id="com_form" enctype="multipart/form-data">
        <input type="hidden" name="com_addr3" id="com_addr3">
        <input type="hidden" name="com_addr_jibeon" id="com_addr_jibeon">
        <div class="setting_wrap">
            <h2>사업자명</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_name" placeholder="사업자명" value="<?php echo $view["com_name"];?>" <?php echo $readonly;?> required></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>대표명</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_ceo" placeholder="대표명" value="<?php echo $view["com_ceo"];?>" <?php echo $readonly;?> required></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>사업자 번호</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_num" placeholder="사업자 번호(- 포함)" value="<?php echo $view["com_num"];?>" <?php echo $readonly;?> required></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>업종</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_type1" placeholder="업종" value="<?php echo $view["com_type1"];?>" <?php echo $readonly;?> ></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>업태</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_type2" placeholder="업태" value="<?php echo $view["com_type2"];?>" <?php echo $readonly;?> ></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>주소</h2>
            <ul>
                <li class="" style="position:relative">
                    <input type="text" class="setting_input" name="com_zip" id="com_zip" placeholder="우편번호" readonly value="<?php echo $view["com_zip"];?>" <?php echo $readonly;?> required>
                    <input type="button" value="검색" id="addr_btn" onclick="win_zip('com_form', 'com_zip', 'com_addr1', 'com_addr2', 'com_addr3', 'com_addr_jibeon');">
                </li>
                <li>
                    <input type="text" class="setting_input" name="com_addr1" id="com_addr1" readonly placeholder="기본주소" value="<?php echo $view["com_addr1"];?>" <?php echo $readonly;?> required>
                </li>
                <li>
                    <input type="text" class="setting_input" name="com_addr2" id="com_addr2" placeholder="상세주소" value="<?php echo $view["com_addr2"];?>" <?php echo $readonly;?> required>
                </li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>대표자 전화번호</h2>
            <ul>
                <li class="single"><input type="tel" class="setting_input" name="com_tel" placeholder="대표자 전화번호(- 생략)" value="<?php echo $view["com_tel"];?>" <?php echo $readonly;?> required maxlength="12"></li>
            </ul>
        </div>
        
        <div class="setting_wrap">
            <h2>사업자등록증 첨부</h2>
            <ul>
                <li class="single">
                    <?php if($view["cp_id"] == ""){?>
                    <label for="com_sign" id="addr_btn" style="top:3vw;font-weight:normal;">등록</label>
                    <input type="text" class="setting_input" name="file_name" id="file_name" readonly placeholder="사업자등록증 첨부">
                    <input type="file" class="setting_input" name="com_sign" id="com_sign" style="display:none;" accept="image/*" required onchange="$('#file_name').val(this.value)">
                    <?php }else{?>
                        <img src="<?php echo G5_DATA_URL?>/company/<?php echo $view["com_sign"];?>" alt="">
                    <?php }?>
                </li>
            </ul>
        </div>
        
        <?php if($view["cp_id"]==""){?>
        <div class="setting_wrap">
            <div><input type="checkbox" value="" name="agree" id="agree" required><label for="agree" style="font-size:3vw;margin-left:1vw;"><a href="<?php echo G5_MOBILE_URL?>/page/company/privacy.php" style="text-decoration: underline;">개인정보 취급방침</a>에 동의하십니까?</label></div>
        </div>
        <?php }?>
        <div class="setting_wrap">
            <div class="btn_group">
                <?php if($view["cp_id"] == ""){?>
                <input type="submit" value="등록" class="setting_btn" onclick="fnSubmit();">
                <?php }else{ ?>
                    심사중
                <?php }?>
            </div>
        </div>
    </form>
</div>
    <script>
        function fnSubmit(){
            document.com_form.submit();
        }
    </script>
<?php
include_once(G5_PATH."/tail.php");
?>