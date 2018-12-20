<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_URL."/mobile/page/login.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$sql = "select * from `my_card` where mb_id = '{$member["mb_id"]}'";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $card_list[] = $row;
    if($row["card_status"] == 1) {
        $owner = $row;
    }
}

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
    <style>
        #settings .setting_wrap ul li{padding:2.88vw;}
    </style>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>카드정보 등록</h2>
        <!-- <div class="sub_add">추가</div> -->
    </div>
    <div id="settings">
        <div class="setting_wrap">
            <p>현재 결제모듈 연동중입니다. </p>
        </div>

            <?php if(count($owner)>0){
                $card_num = explode("-",$owner["card_number"]);
                ?>
            <div class="setting_wrap">
                <h2>기본 카드 정보</h2>
                <ul>
                    <li>카드이름<span><?php echo $owner["card_name"];?></span></li>
                    <li>카드회사 <span><?php echo $owner["card_company_name"];?></span></li>
                    <li>카드유효기간 <span><?php echo $owner["card_year"];?> 년 / <?php echo $owner["card_month"];?> 월</span></li>
                    <li>카드번호 <span><?php echo $card_num[0]."-".$card_num[1]."-****-****";?></span></li>
                </ul>
            </div>
            <?php }?>
            <?php if(count($card_list)>0){?>
            <div class="setting_wrap">
                <h2>등록카드 목록</h2>
                <ul>
                    <?php for($i=0;$i<count($card_list);$i++){?>
                    <li <?php if(count($card_list) == 1){?>class="single" <?php }?> style="height:7vw;line-height:6vw;"><?php echo $card_list[$i]["card_name"];?><?php if($card_list[$i]["card_status"]!=1){?><span class="owner" onclick="fnOwnerCard('<?php echo $card_list[$i]["id"];?>','owner')">기본카드 등록</span><?php }?><span class="del" onclick="fnOwnerCard('<?php echo $card_list[$i]["id"];?>','del')">삭제</span></li>
                    <?php }?>
                </ul>
            </div>
            <?php }?>
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_card_update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <input type="hidden" name="mode" value="insert">
            <div class="setting_wrap">
                <h2>카드 추가</h2>
                <ul>
                    <li><input type="text" class="setting_input" name="card_name" placeholder="카드이름" value="" required></li>
                    <li>
                        <select name="card_company" id="card_company" class="setting_input" required>
                            <option value="">카드회사</option>
                            <option value="01/국민카드">국민카드</option>
                            <option value="02/신한카드">신한카드</option>
                        </select>
                    </li>
                    <li><input type="number" class="setting_input" name="card_year" placeholder="유효년" value="" style="width:10vw" required> 년 / <input type="number" class="setting_input" name="card_month" placeholder="유효월" value="" style="width:10vw" required> 월</li>
                    <li>
                        <input type="number" class="setting_input" name="card_number[]" placeholder="카드번호" value="" style="width:15vw" maxlength="4" required> -
                        <input type="password" class="setting_input" name="card_number[]" placeholder="카드번호" value="" style="width:15vw" maxlength="4" required> -
                        <input type="password" class="setting_input" name="card_number[]" placeholder="카드번호" value="" style="width:15vw" maxlength="4" required> -
                        <input type="number" class="setting_input" name="card_number[]" placeholder="카드번호" value="" style="width:15vw" maxlength="4" required>
                    </li>
                </ul>
                <div class="btn_group">
                    <input type="submit" value="등록" class="setting_btn">
                </div>
            </div>
        </form>
    </div>
<script>
function fnOwnerCard(id,mode){
    if(mode=="owner") {
        if (confirm("현재 등록되어 있는 카드가 변경 됩니다.")) {
            location.href = g5_url + "/mobile/page/mypage/my_card_update.php?mode=" + mode + "&id=" + id;
        }
    }else{
        if (confirm("삭제 하시겠습니까?")) {
            location.href = g5_url + "/mobile/page/mypage/my_card_update.php?mode=" + mode + "&id=" + id;
        }
    }
}
</script>
<?php
include_once(G5_PATH."/tail.php");
?>