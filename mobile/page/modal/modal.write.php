<?php
include_once ("../../../common.php");
if(ca_id){
    $sql = "select * from `write_confirm` where mb_id = '{$member["mb_id"]}' and cate_id = '{$ca_id}'";
    $chk = sql_fetch($sql);
    if($chk==null){
        $sql = "insert into `write_confirm` set mb_id = '{$member["mb_id"]}', cate_id = '{$ca_id}' , confirm_date = now(), confirm_time = now() ";
        sql_query($sql);
    }
}
?>
<div id="id01" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_form" id="write_form" method="post" action="" onsubmit="return false">
                <input type="hidden" value="<?php if($set_type){echo $set_type;}else{echo 2;}?>" name="wr_type1" id="wr_type1">
                <input type="hidden" name="cate1" id="c" value="<?php echo $cate1;?>">
                <input type="hidden" name="cate2" id="sc" value="<?php echo $cate2;?>">
                <input type="hidden" name="pd_type2" id="pd_type2" value="<?php if($pd_type2){echo $pd_type2;}else{echo 8;}?>">
                <!--<input type="hidden" name="pd_price_type" id="pd_p_type" value="0">-->
                <div class="type2_box">
                    <label class="switch schtype2" >
                        <input type="checkbox" id="wr_type2" name="wr_type2" value="4">
                        <span class="slider round" >판매</span>
                    </label>
                </div>
                <h2>검색어</h2>
                <div>
                    <p class="write_help"><?php echo $catetag;?></p>
                    <input type="text" name="title" id="wr_title" placeholder="검색어 구분은 띄어쓰기로 가능합니다." required value="#" onkeyup="fnfilter(this.value,'wr_title')" <?php if($app2){?>onkeydown="fnInputs(event)"<?php }?>>
                    <input type="number" name="wr_price2" id="wr_price2" placeholder="계약금" required value="" onkeyup="number_only(this)" style="width:24%;margin-top:0;<?php if($type1=="2"){?>display:inline-block;<?php }else{?>display:none;<?php }?>;opacity: 0.6;">
                    <input type="number" name="wr_price" id="wr_price" placeholder="<?php if($type1=="1"){?>판매금액<?php }else{?>계약완료금<?php }?>" required value="" onkeyup="number_only(this)" style="<?php if($type1=="2"){?>width:40%;<?php }else{?>width:70%<?php }?>margin-right:5%;margin-top:0" <?php if($app2){?>onkeydown="fnInputsPrice(event)"<?php }?>>
                    <ul class="pd_price_type">
                        <li class="active" id="pd_price_type0">회당</li>
                        <li id="pd_price_type1">시간당</li>
                        <li id="pd_price_type2">하루당</li>
                    </ul>
                </div>
                <div class="price_box">
                    <input type="button" value="확인" style="background-color:yellow;margin-right:0" onclick="<?php if($app){ ?>fnOnCam();<?php }else if($app2){?>fnOnCamIos()<?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL."/page/write.php";?>');<?php }?>" class="types1">
                    <input type="button" value="간편등록" onclick="fnSimpleWrite();" class="types2" style="margin-right:1vw;">
                    <input type="button" value="상세등록" onclick="<?php if($app){ ?>fnOnCam();<?php }else if($app2){?>fnOnCamIos()<?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL."/page/write.php";?>');<?php }?>" class="types2">
                </div>
                <?php if($set_type=="2"){?>
                    <p class="write_help price_help">필요시 계약금을 설정할 수 있습니다.</p>
                <?php }?>
                <div class="modal_close" onclick="modalClose();">
                    <img src="<?php echo G5_IMG_URL?>/ic_modal_close.png" alt="">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    //능력 판매시 가격 타입
    $(".pd_price_type li").each(function(){
        $(this).click(function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $(".pd_price_type li").not($(this)).removeClass("active");
                var id = $(this).attr("id");
                var data = id.replace("pd_price_type","");
                $("#pd_p_type").val(data);
            }
        });
    });

    //검색어 등록시 판매 구매 선택
    $(".schtype2 .slider").click(function(){
        //console.log($("#wr_type1").val());
        if($(this).prev().prop("checked")==true){
            $(this).html('판매');
            $(this).css("text-align","right");
            //등록 버튼 수정
            $(".types1").css({"display":"inline-block","margin-right":"0"});
            $(".types2").css("display","none");
            $("#pd_type2").val("8");
            if($("#wr_type1").val()==1){ //물건 판매 / 판매금액필요
                $("#wr_price").attr("placeholder","판매금액");
                $("#wr_price").css("width","70%");
                $("#wr_price2").css("display","none");
                $(".pd_price_type").css("display","none");
                //$(".price_box .write_help.price_help").remove();
            }
            if($("#wr_type1").val()==2){ //능력 판매 / 계약금 / 계약완료금
                $("#wr_price").attr("placeholder","거래완료금");
                $("#wr_price2").attr("placeholder","계약금");
                $("#wr_price").css("width","40%");
                $("#wr_price2").css({"display":"inline-block","width":"24%"});
                $(".pd_price_type").css("display","block");
                //$(".price_box").append('<p class="write_help price_help">필요시 계약금을 설정할 수 있습니다.</p>');
            }
        }else{

            $(this).html('구매');
            $(this).css("text-align","left");
            //등록 버튼 수정
            $(".types1").css("display","none");
            $(".types2").css("display","inline-block");
            $("#pd_type2").val("4");
            if($("#wr_type1").val()==1){ //물건 구매 / 구매예상금
                $("#wr_price").attr("placeholder","구매예상금");
                $("#wr_price").css("width","70%");
                $("#wr_price2").css("display","none");
                $(".pd_price_type").css("display","none");
                //$(".price_box .write_help.price_help").remove();
            }
            if($("#wr_type1").val()==2){ //능력 구매 / 구매예상금
                $("#wr_price").attr("placeholder","구매예상금");
                $("#wr_price").css("width","70%");
                $("#wr_price2").css("display","none");
                $(".pd_price_type").css("display","block");
                //$(".price_box").append('<p class="write_help price_help">필요시 계약금을 설정할 수 있습니다.</p>');
            }
        }
    });

    //게시글 등록 엔터
    $("#wr_title").keyup(function (e) {
        var type2 = $("#wr_type2").val();
        var text = $(this).val();
        /*if(text==""){
         $(this).val("#");
         }*/
        <?php if(!$app){?>
        //키보드 32
        if (e.keyCode == 32) {
            text = text.replace(" ","#");
            $(this).val(text);
        }
        <?php }else{ ?>
        text = text.replace(" ","#");
        $(this).val(text);
        <?php }?>

        var chk = text.substr(0,1);
        if(chk != "#"){
            $(this).val("#"+text);
        }
        var cnt = text.split("#");
        if (cnt.length > 10) {
            if(confirm("검색어는 최대 10개까지 등록가능합니다. \r등록 하시겠습니까?")){
                $("#wr_price").focus();
            }else{
                return false;
            }
        }
        if (e.keyCode == 13) {
            if($("#wr_title").val()=="" || $("#wr_title").val()=="#"){
                alert("검색어를 입력해주세요.");
                return false;
            }
            if (type2 == 8) {
                //판매시
                <?php if($app){ ?>fnOnCam();
                <?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL . "/page/write.php";?>');<?php }?>
            } else {
                //구매시
                <?php if($app){ ?>fnOnCam();
                <?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL . "/page/write.php";?>');<?php }?>
            }
        }
    });
</script>