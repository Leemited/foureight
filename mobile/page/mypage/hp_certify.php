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
    <script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
    <style>
        #settings .setting_wrap ul li{padding:2.88vw;}
    </style>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>전화번호 수정</h2>
        <!-- <div class="sub_add">추가</div> -->
    </div>
    <div id="settings">
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/hp_certify_update.php" method="post" name="fregisterform">
            <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>" id="cert_type">
            <input type="hidden" name="cert_no" id="cert_no" value="">
            <div class="setting_wrap">
                <h2>현재 번호</h2>
                <ul>
                    <li class="single">
                        <?php echo ($member["mb_hp"])?$member["mb_hp"]:"등록된 핸드폰 번호가 없습니다.";?>
                    </li>
                </ul>
            </div>
            <div class="setting_wrap">
                <h2>변경 번호</h2>
                <ul>
                    <li class="single">
                        <input type="text" class="setting_input" name="mb_hp" id="mb_hp" placeholder="" value="">
                        <input type="button" value="인증하기" id="win_hp_cert" class="addr_btn">
                    </li>
                </ul>
                <div class="btn_group">
                    <input type="submit" value="수정" class="setting_btn">
                </div>
            </div>
        </form>
    </div>
<script>
    // 휴대폰인증
    $("#win_hp_cert").click(function() {
        if(!cert_confirm())
            return false;

        <?php
        switch($config['cf_cert_hp']) {
            case 'kcb':
                $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                $cert_type = 'kcb-hp';
                break;
            case 'kcp':
                $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                $cert_type = 'kcp-hp';
                break;
            case 'lg':
                $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                $cert_type = 'lg-hp';
                break;
            default:
                echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                echo 'return false;';
                break;
        }
        ?>

        certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
        return;
    });

    // 인증체크
    function cert_confirm()
    {
        var val = document.fregisterform.cert_type.value;
        var type;

        switch(val) {
            case "ipin":
                type = "아이핀";
                break;
            case "hp":
                type = "휴대폰";
                break;
            default:
                return true;
        }

        if(confirm("이미 "+type+"으로 본인확인을 완료하셨습니다.\n\n이전 인증을 취소하고 다시 인증하시겠습니까?"))
            return true;
        else
            return false;
    }

    function fnSubmit(){
        if($("#mb_hp").val()==""){
            alert("변경할 번호를 입력해주세요.");
            return false;
        }else if($("#cert_no").val()==""){
            alert("휴대폰 인증을 해주세요.");
            return false;
        }
        var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            $("#mb_hp").focus();
            return false;
        }

        document.fregisterform.submit();
    }
</script>
<?php
include_once(G5_PATH."/tail.php");
?>