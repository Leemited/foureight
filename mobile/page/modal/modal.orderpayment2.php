<?php
include_once ("../../../common.php");

$od = sql_fetch("select * from `order` where od_id = '{$od_id}'");

?>
<div id="id10" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>잔금요청</h2>
            <div>
                <input type="hidden" name="od_step2_price" id="od_step2_price" value="<?php echo $od["od_price2"];?>">
                <input type="number" name="od_step2_price" id="od_step2_price" value="" placeholder="기본설정값 <?php echo $od["od_price2"];?>" onchange="$('#od_step2_price').val(this.value);">
                <input type="hidden" name="od_id_step2" id="od_id_step2" value="<?php echo $od_id;?>" >
                <input type="hidden" name="cid_step2" id="cid_step2" value="<?php echo $cid;?>" >
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="요청하기" onclick="fnPaymentConfirm();" style="width:auto">
            </div>
        </div>
    </div>
</div>
