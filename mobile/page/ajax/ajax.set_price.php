<?php
include_once ("../../../common.php");
?>
<div id="id05" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>가격 상세 설정</h2>
            <div>
                <ul>
                    <li>
                        <input type="text" name="toPrice" id="toPrice" placeholder="최저금액" style="margin-bottom:0">
                    </li>
                    <li>
                        <input type="text" name="fromPrice" id="fromPrice" placeholder="최고금액">
                    </li>
                </ul>
                <div>
                    <input type="button" value="취소" onclick="modalClose()">
                    <input type="button" value="가격설정" onclick="setPrice()" style="width: auto">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function setPrice(){
    var toPrice = Number($("#toPrice").val());
    var fromPrice = Number($("#fromPrice").val());
    $("#slider-range" ).slider({
        range: true,
        min:0,
        max:fromPrice,
        value:[0,fromPrice],
        step:1000,
        slide: function( event, ui ) {
            $("#sc_priceTo").val(ui.values[1]);
            $("#sc_priceFrom").val(ui.values[0]);
            $("#schp").text( number_format(ui.values[0])+" ~ "+number_format(ui.values[1]));
        }
    });

    $("#sc_priceTo").val(toPrice);
    $("#sc_priceFrom").val(fromPrice);
    $("#schp").text( number_format(toPrice)+" ~ "+number_format(fromPrice));

    modalClose();
}
</script>
