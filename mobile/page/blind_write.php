<?php
include_once ("../../common.php");

if(!$pd_id){
    alert("신고할 게시물이 없거나 삭제되었습니다.");
    return false;
}

if($member["mb_id"]=="" || !$is_member){
    $mb_id = session_id();
}

if($type != "modal") {
    include_once (G5_MOBILE_PATH."/head.login.php");
}
?>
<div class="sub_head">
    <div class="sub_back" onclick="<?php if($type!="modal"){?>location.href='<?php echo $backurl;?>'<?php }else{?>blindClose()<?php }?>"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>신고유형 선택</h2>
</div>
<div class="blind_write" id="settings">
    <form action="<?php echo G5_MOBILE_URL?>/page/blind_write_update.php" method="post">
        <input type="hidden" value="<?php echo $pd_id;?>" name="pd_id">
        <input type="hidden" value="<?php echo $mb_id;?>" name="mb_id">
    <div class="setting_wrap">
        <ul>
            <li><input type="radio" name="blind_con" id="type1" value="거래금지품목" ><label for="type1"><span class="outside"><span class="inside"></span></span>거래금지품목 <p>(주류,담배,장물 등)</p></label></li>
            <li><input type="radio" name="blind_con" id="type2" value="상품정보 부정확" ><label for="type2"><span class="outside"><span class="inside"></span></span>상품정보 부정확 <p>(카테고리, 가격, 사진)</p></label></li>
            <li><input type="radio" name="blind_con" id="type3" value="성인/도박 등 불법광고 및 스팸활동" ><label for="type3"><span class="outside"><span class="inside"></span></span>성인/도박 등 불법광고 및 스팸활동</label></li>
            <li><input type="radio" name="blind_con" id="type4" value="언어폭력" ><label for="type4"><span class="outside"><span class="inside"></span></span>언어폭력 <p>(비방, 욕설, 성희롱)</p></label></li>
            <li><input type="radio" name="blind_con" id="type5" value="사기의심" ><label for="type5"><span class="outside"><span class="inside"></span></span>사기의심 </label></li>
            <li><input type="radio" name="blind_con" id="type6" value="기타" ><label for="type6"><span class="outside"><span class="inside"></span></span>기타
                <input type="text" class="setting_input border-bottom " placeholder="150자 내외 입력" name="blind_con_txt" id="blind_con_txt" style="width:80%" ></label>
            </li>
        </ul>
    </div>
    <div class="btn_group">
        <input type="submit" value="신고하기" class="setting_btn" onclick="return chkSubmit();">
    </div>
    </form>
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
if($type != "modal") {
    include_once (G5_MOBILE_PATH."/tail.php");
}
?>
