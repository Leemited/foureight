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
    <style>
        #settings .setting_wrap ul li{padding:2.88vw;}
    </style>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>계좌정보 등록</h2>
        <!-- <div class="sub_add">추가</div> -->
    </div>
    <div id="settings">
        <div class="setting_wrap">
            <p>현재 결제모듈 연동중입니다. </p>
        </div>

        <?php if(count($basic)>0){
            ?>
            <div class="setting_wrap">
                <h2>기본 계좌 정보</h2>
                <ul>
                    <li>계좌명<span><?php echo $basic["account_name"];?></span></li>
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
                        <li <?php if(count($bank_list) == 1){?>class="single" <?php }?> style="height:7vw;line-height:6vw;"><?php echo $bank_list[$i]["bank_name"];?><?php if($bank_list[$i]["bank_status"]!=1){?><span class="owner" onclick="fnOwnerCard('<?php echo $bank_list[$i]["id"];?>','owner')">기본계좌 등록</span><?php }?><span class="del" onclick="fnOwnerCard('<?php echo $bank_list[$i]["id"];?>','del')">삭제</span></li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_bank_update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id;?>">
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
        </form>
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
    </script>
<?php
include_once(G5_PATH."/tail.php");
?>