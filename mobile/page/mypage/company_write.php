<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$back_url=G5_MOBILE_URL."/page/mypage/mypage.php";
add_javascript(G5_POSTCODE_JS, 0);
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
                <li class="single"><input type="text" class="setting_input" name="com_name" placeholder="사업자명" value="" required></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>대표명</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_ceo" placeholder="대표명" value="" required></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>사업자 번호</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_num" placeholder="사업자 번호(- 포함)" value="" required></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>업종</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_type1" placeholder="업종" value="" ></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>업태</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_type2" placeholder="업태" value="" ></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>주소</h2>
            <ul>
                <li class="" style="position:relative">
                    <input type="text" class="setting_input" name="com_zip" id="com_zip" placeholder="우편번호" readonly value="" required>
                    <input type="button" value="검색" id="addr_btn" onclick="win_zip('com_form', 'com_zip', 'com_addr1', 'com_addr2', 'com_addr3', 'com_addr_jibeon');">
                </li>
                <li>
                    <input type="text" class="setting_input" name="com_addr1" id="com_addr1" readonly placeholder="기본주소" value="" required>
                </li>
                <li>
                    <input type="text" class="setting_input" name="com_addr2" id="com_addr2" placeholder="상세주소" value="" required>
                </li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>대표자 전화번호</h2>
            <ul>
                <li class="single"><input type="text" class="setting_input" name="com_tel" placeholder="대표자 전화번호(- 생략)" value="" required maxlength="12"></li>
            </ul>
        </div>
        <div class="setting_wrap">
            <h2>사업자등록증 첨부</h2>
            <ul>
                <li class="single">
                    <label for="com_sign" id="addr_btn" style="top:3vw;font-weight:normal;">등록</label>
                    <input type="text" class="setting_input" name="file_name" id="file_name" readonly placeholder="사업자등록증 첨부" for="com_sign">
                    <input type="file" class="setting_input" name="com_sign" id="com_sign" style="display:none;" accept="image/*" required onchange="$('#file_name').val(this.value)">
                </li>
            </ul>
        </div>
        <div class="setting_wrap">
            <div><input type="checkbox" value="" name="agree" id="agree" required><label for="agree" style="font-size:3vw;margin-left:1vw;"><a href="<?php echo G5_MOBILE_URL?>/page/guide/privacy.php" style="text-decoration: underline;">개인정보 취급방침</a>에 동의하십니까?</label></div>
        </div>
        <div class="setting_wrap">
            <div class="btn_group">
                <input type="submit" value="등록" class="setting_btn">
            </div>
        </div>
    </form>
</div>
<script>

</script>
<?php
include_once(G5_PATH."/tail.php");
?>