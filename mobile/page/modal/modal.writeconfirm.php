<?php
include_once ("../../../common.php");
?>
<div id="id04" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <h2>게시글 등록 안내</h2>
                <div class="con">
                    <div class="like_list" style="font-size:3.4vw;text-align: left">
                        <?php echo $infotext;?>
                    </div>
                </div>
                <div>
                    <input type="button" value="확인" onclick="fnWriteConfirm()"><input type="button" value="다시보지않기" onclick="fnWriteConfirmChk('<?php echo $ca_id;?>');" style="width: auto">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function fnWriteConfirm(){
        $.ajax({
            url:g5_url+'/mobile/page/modal/modal.write.php',
            method:"post",
            data:{set_type:"<?php echo $set_type;?>",cate1:"<?php echo $cate1;?>",cate2:"<?php echo $cate2;?>",pd_type2:"<?php echo $pd_type2;?>",app:"<?php echo $app;?>",app2:"<?php echo $app2;?>",catetag:"<?php echo $catetag;?>"}
        }).done(function(data){
            $(".modal").html(data).addClass("active");
            if ("<?php echo $set_type;?>" == "1") {
                $("#wr_price").attr("placeholder", "판매금액");
                $("#wr_price2").css("display", "none");
                $("#wr_price").css("width", "70%");
                $("#wr_price2").css({"display": "none"});
                $(".pd_price_type").css("display", "none");
                $(".price_box .write_help.price_help").html('');
            }
            if ("<?php echo $set_type;?>" == "2") {
                $(".pd_price_type").css("display", "block");
                if ($("#id01 .write_help.price_help").length == 0) {
                    $(".price_box").append('<p class="write_help price_help">필요시 계약금을 설정할 수 있습니다.</p>');
                } else {
                    $("#id01 .write_help.price_help").html("필요시 계약금을 설정할 수 있습니다.");
                }
                $("#wr_price").attr("placeholder", "거래완료금");
                $("#wr_price2").attr("placeholder", "계약금");
                $("#wr_price").css("width", "40%");
                $("#wr_price2").css({"display": "inline-block", "width": "24%"});
                $("#wr_price2").css("display", "inline-block");
            }
            <?php if($app){?>
            $("#id01 #wr_title").focus();
            $("#id01 #wr_title").selectRange(2, 2);
            window.android.Onkeyboard();
            <?php }if($app2){?>
            setTimeout(function () {
                $("#id01 #wr_title").focus();
                $("#id01 #wr_title").selectRange(2, 2);
            }, 500);
            <?php }?>
            location.hash = '#writes';
        });
    }
    function fnWriteConfirmChk(ca_id){
        $.ajax({
            url:g5_url+'/mobile/page/modal/modal.write.php',
            method:"post",
            data:{set_type:"<?php echo $set_type;?>",cate1:"<?php echo $cate1;?>",cate2:"<?php echo $cate2;?>",pd_type2:"<?php echo $pd_type2;?>",app:"<?php echo $app;?>",app2:"<?php echo $app2;?>",catetag:"<?php echo $catetag;?>",ca_id:ca_id}
        }).done(function(data){
            $(".modal").html(data).addClass("active");
            if ("<?php echo $set_type;?>" == "1") {
                $("#wr_price").attr("placeholder", "판매금액");
                $("#wr_price2").css("display", "none");
                $("#wr_price").css("width", "70%");
                $("#wr_price2").css({"display": "none"});
                $(".pd_price_type").css("display", "none");
                $(".price_box .write_help.price_help").html('');
            }
            if ("<?php echo $set_type;?>" == "2") {
                $(".pd_price_type").css("display", "block");
                if ($("#id01 .write_help.price_help").length == 0) {
                    $(".price_box").append('<p class="write_help price_help">필요시 계약금을 설정할 수 있습니다.</p>');
                } else {
                    $("#id01 .write_help.price_help").html("필요시 계약금을 설정할 수 있습니다.");
                }
                $("#wr_price").attr("placeholder", "거래완료금");
                $("#wr_price2").attr("placeholder", "계약금");
                $("#wr_price").css("width", "40%");
                $("#wr_price2").css({"display": "inline-block", "width": "24%"});
                $("#wr_price2").css("display", "inline-block");
            }
            <?php if($app){?>
            $("#id01 #wr_title").focus();
            $("#id01 #wr_title").selectRange(2, 2);
            window.android.Onkeyboard();
            <?php }if($app2){?>
            setTimeout(function () {
                $("#id01 #wr_title").focus();
                $("#id01 #wr_title").selectRange(2, 2);
            }, 500);
            <?php }?>
            location.hash = '#writes';
        });
    }
</script>
