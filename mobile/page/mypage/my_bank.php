<?php
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
    alert("로그인이 필요합니다.", G5_URL."/mobile/page/login.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}

$sql = "select * from `my_bank` where mb_id = '{$member["mb_id"]}'";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $bank_list[] = $row;
    if($row["bank_status"] == 1) {
        $basic = $row;
    }
}

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
<div id="id01" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_bank_update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <input type="hidden" name="mode" value="insert">
            <div class="setting_wrap">
                <h2>계좌 추가</h2>
                <ul>
                    <li>
                        <input type="text" class="setting_input" name="bank_nick" placeholder="계좌별명" value="" required style="width:calc(80% - 4vw)">
                    </li>
                    <li>
                        <input type="text" class="setting_input" id="bank_name" name="bank_name" placeholder="직접입력" value="" required style="width:calc(80% - 4vw);display:none;">
                        <select name="bank_name_set" id="bank_name_set" class="setting_input" required style="width:80%" onchange="fnbankSel(this.value);">
                            <option value='SC제일은행'>SC제일은행</option>
                            <option value='경남은행'>경남은행</option>
                            <option value='광주은행'>광주은행</option>
                            <option value='국민은행'>국민은행</option>
                            <option value='굿모닝신한증권'>굿모닝신한증권</option>
                            <option value='기업은행'>기업은행</option>
                            <option value='농협중앙회'>농협중앙회</option>
                            <option value='농협회원조합'>농협회원조합</option>
                            <option value='대구은행'>대구은행</option>
                            <option value='대신증권'>대신증권</option>
                            <option value='대우증권'>대우증권</option>
                            <option value='동부증권'>동부증권</option>
                            <option value='동양종합금융증권'>동양종합금융증권</option>
                            <option value='메리츠증권'>메리츠증권</option>
                            <option value='미래에셋증권'>미래에셋증권</option>
                            <option value='뱅크오브아메리카(BOA)'>뱅크오브아메리카(BOA)</option>
                            <option value='부국증권'>부국증권</option>
                            <option value='부산은행'>부산은행</option>
                            <option value='산림조합중앙회'>산림조합중앙회</option>
                            <option value='산업은행'>산업은행</option>
                            <option value='삼성증권'>삼성증권</option>
                            <option value='상호신용금고'>상호신용금고</option>
                            <option value='새마을금고'>새마을금고</option>
                            <option value='수출입은행'>수출입은행</option>
                            <option value='수협중앙회'>수협중앙회</option>
                            <option value='신영증권'>신영증권</option>
                            <option value='신한은행'>신한은행</option>
                            <option value='신협중앙회'>신협중앙회</option>
                            <option value='에스케이증권'>에스케이증권</option>
                            <option value='에이치엠씨투자증권'>에이치엠씨투자증권</option>
                            <option value='엔에이치투자증권'>엔에이치투자증권</option>
                            <option value='엘아이지투자증권'>엘아이지투자증권</option>
                            <option value='외환은행'>외환은행</option>
                            <option value='우리은행'>우리은행</option>
                            <option value='우리투자증권'>우리투자증권</option>
                            <option value='우체국'>우체국</option>
                            <option value='유진투자증권'>유진투자증권</option>
                            <option value='전북은행'>전북은행</option>
                            <option value='제주은행'>제주은행</option>
                            <option value='키움증권'>키움증권</option>
                            <option value='하나대투증권'>하나대투증권</option>
                            <option value='하나은행'>하나은행</option>
                            <option value='하이투자증권'>하이투자증권</option>
                            <option value='한국씨티은행'>한국씨티은행</option>
                            <option value='한국투자증권'>한국투자증권</option>
                            <option value='한화증권'>한화증권</option>
                            <option value='현대증권'>현대증권</option>
                            <option value='홍콩상하이은행'>홍콩상하이은행</option>
                            <option value='direct'>직접 입력</option>
                        </select>
                    </li>
                    <li>
                        <input type="text" class="setting_input" style="width:calc(30% - 4vw);display: inline-block;" name="account_name" placeholder="계좌소유주" value="" required>

                        <input type="number" class="setting_input" name="bank_number" placeholder="계좌번호 '-' 포함" value="" required style="width:calc(50% - 4vw);">
                    </li>
                </ul>
                <div class="btn_group">
                    <input type="button" value="취소" class="setting_btn" onclick="$('#id01').attr('style','display:none')">
                    <input type="submit" value="등록" class="setting_btn">
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
    <style>
        #settings .setting_wrap ul li{padding:2.88vw;}
    </style>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>계좌정보 등록</h2>
        <!-- <div class="sub_add">추가</div> -->
    </div>
    <div id="settings">
        <?php if(count($basic)>0){
            ?>
            <div class="setting_wrap">
                <h2>기본 계좌 정보</h2>
                <ul>
                    <li>계좌별명<span><?php echo ($basic["bank_nick"])?$basic["bank_nick"]:$basic["bank_name"];?></span></li>
                    <li>계좌소유주<span><?php echo $basic["account_name"];?></span></li>
                    <li>은행명 <span><?php echo $basic["bank_name"];?></span></li>
                    <li>계좌번호 <span><?php echo base64_decode($basic["bank_number"]);?></span></li>
                </ul>
            </div>
        <?php }?>
        <?php if(count($bank_list)>0){?>
            <div class="setting_wrap">
                <h2>등록계좌 목록</h2>
                <ul>
                    <?php for($i=0;$i<count($bank_list);$i++){?>
                        <li <?php if(count($bank_list) == 1){?>class="single" <?php }?> style="height:7vw;line-height:6vw;"><?php echo $bank_list[$i]["bank_name"];?><?php if($bank_list[$i]["bank_status"]!=1){?><span class="owner" onclick="fnOwnerCard('<?php echo $bank_list[$i]["id"];?>','owner')">기본계좌 등록</span><?php }else{echo " [기본 계좌]";}?><span class="del" onclick="fnOwnerCard('<?php echo $bank_list[$i]["id"];?>','del')">삭제</span></li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
        <div class="setting_wrap">
            <div class="btn_group">
                <input type="submit" value="계좌추가" class="setting_btn" onclick="$('#id01').attr('style','display:block')">
            </div>
        </div>
        <!--<form action="<?php /*echo G5_MOBILE_URL*/?>/page/mypage/my_bank_update.php" method="post">
            <input type="hidden" name="id" value="<?php /*echo $id;*/?>">
            <input type="hidden" name="mode" value="insert">
            <div class="setting_wrap">
                <h2>계좌 추가</h2>
                <ul>
                    <li><input type="text" class="setting_input" name="account_name" placeholder="계좌이름" value="" required></li>
                    <li>
                        <select name="bank_name" id="bank_name" class="setting_input" required >
                            <option value="">은행명</option>
                            <option value="국민은행">국민은행</option>
                            <option value="신한은행">신한은행</option>
                        </select>
                    </li>
                    <li><input type="number" class="setting_input" name="bank_number" placeholder="계좌번호 '-' 포함" value="" required></li>
                </ul>
                <div class="btn_group">
                    <input type="submit" value="등록" class="setting_btn">
                </div>
            </div>
        </form>-->
    </div>
    <script>
        function fnOwnerCard(id,mode){
            if(mode=="owner") {
                if (confirm("현재 등록되어 있는 계좌가 변경 됩니다.")) {
                    location.href = g5_url + "/mobile/page/mypage/my_bank_update.php?mode=" + mode + "&id=" + id;
                }
            }else{
                if (confirm("삭제 하시겠습니까?")) {
                    location.href = g5_url + "/mobile/page/mypage/my_bank_update.php?mode=" + mode + "&id=" + id;
                }
            }
        }
        function fnbankSel(bank){
            /*if(bank=="direct"){
                $("#bank_name").show();
                $("#bank_name").val('');
            }else{*/
                $("#bank_name").hide();
                $("#bank_name").val(bank);
            //}
        }
    </script>
<?php
include_once(G5_PATH."/tail.php");
?>