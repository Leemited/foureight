<?php
include_once ("../../../common.php");
$sql = "select * from `order` where od_id ='{$od_id}'";
$od = sql_fetch($sql);
?>
<div id="id00" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form action="<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order_price_update.php" method="post" name="update_price">
                <input type="hidden" value="<?php echo $od_id;?>" name="od_id">
                <h2>결제 가격 수정</h2>
                <div style="position: relative">
                    <?php if($od["od_price2"]){?>
                    <label for="od_price2" style="position:absolute;left:7vw;top:0;z-index:9">변경 계약금</label>
                    <input type="text" value="<?php echo $od["od_price2"];?>" name="od_price2" id="od_price2" placeholder="변경 계약금" required style="margin-left:10vw;text-align: right" onkeyup="number_only(this)" >
                    <label for="od_price2" style="position:absolute;left:7vw;bottom:0;z-index:9">변경 거래완료금</label>
                    <input type="text" value="<?php echo $od["od_price"];?>" name="od_price" id="od_price" placeholder="변경 거래완료금" required style="margin-top:0;margin-left:10vw;text-align: right" onkeyup="number_only(this)" >
                    <?php }else{?>
                    <!--<label for="od_price2">변경가격 입력</label>-->
                    <input type="text" value="<?php echo $od["od_price"];?>" name="od_price" id="od_price" placeholder="변경가격 입력" required style="" onkeyup="number_only(this)" >
                    <?php }?>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="가격변경" onclick="fnPriceUpdateConfirm();" style="width:auto;margin-left:1vw" >
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function fnPriceUpdateConfirm(){
        if(confirm("해당 금액으로 변경하시겠습니까?")){
            document.update_price.submit();
        }
    }
</script>