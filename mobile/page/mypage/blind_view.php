<?php
include_once ("../../../common.php");

if(!$pd_id){
    alert("조회할 게시물을 찾을수 없습니다..");
    return false;
}

$sql = "update `product` set pd_blind_userchk = 1 where pd_id = '{$pd_id}'";
sql_query($sql);

$sql = "select * from `product_blind` where pd_id = '{$pd_id}' order by blind_date desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}

include_once (G5_MOBILE_PATH."/head.login.php");

?>
<!--<div id="id10" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>관리자문의</h2>
            <div>
                <input type="text" id="admin_content" placeholder="문의 내용(50자내외)을 입력해주세요." required maxlength="50">
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)">
                <input type="button" value="문의하기" onclick="fnAdminWriteUp('<?php /*echo $pd_id;*/?>');" style="width:auto">
            </div>
        </div>
    </div>
</div>-->
<div class="sub_head">
    <div class="sub_back" onclick="<?php if($type!="modal"){?>location.href='<?php echo $backurl;?>'<?php }else{?>blindClose()<?php }?>"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>신고유형 선택</h2>
</div>
<div class="blind_write" id="settings">
    <div class="setting_wrap">
        <ul>
            <?php for($i=0;$i<count($list);$i++){
                if($list[$i]["mb_id"])
                    $mb = get_member($list[$i]["mb_id"]);
                ?>
            <li><?php echo ($list[$i]["mb_id"])?preg_replace('/(?<=.{1})./u','*',$mb["mb_nick"]):"정보없음";?> / 사유 : <?php echo $list[$i]["blind_content"];?><span><?php echo $list[$i]["blind_date"];?></span></li>
            <?php }
            if(count($list)==0) {
            ?>
            <li style="-webkit-border-radius: 2vw;-moz-border-radius: 2vw;border-radius: 2vw;">관리자 권한 처리</li>
                <?php
            }
            ?>
        </ul>
    </div>
    <div class="btn_group" style="display:inline-block;width:100%;">
        <input type="button" value="관리자문의" class="setting_btn" onclick="fnAdminWrite('<?php echo $pd_id;?>')">
    </div>
    <div class="btn_group" style="display:inline-block;width:100%;">
        <input type="button" value="마이페이지" class="setting_btn" style="width:calc(50% - 4vw);display: inline-block;background-color:#000;color:#fff" onclick="location.href=g5_url+'/mobile/page/mypage/mypage.php'">
        <input type="button" value="홈" class="setting_btn" style="width:calc(50% - 4vw);display: inline-block" onclick="location.href=g5_url">
    </div>
</div>
<script>
    $(function(){
        $(".blind_write ul li").click(function(){
            $(this).children().find("input[type='radio']").prop(":checked");
        });
        $(".border-bottom").focus(function(){
            $(this).find("input[type='radio']").attr("checked",true);
        });
    });
    function chkSubmit(){
        var cnt = 0;
        var blind_type = "";
        $("input[type=radio]").each(function(){
            if($(this).is(":checked") == true){
                cnt++;
                blind_type = $(this).val();

            }
        });
        if(cnt == 0){
            alert("신고유형을 선택해주세요");
            return false;
        }
        if(blind_type=="기타"){
            if($("#blind_con_txt") == ""){
                alert("기타 내용을 입력해주세요");
                return false;
            }
        }
    }
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.php");
?>
