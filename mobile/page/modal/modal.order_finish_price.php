<?php
include_once ("../../../common.php");
$pd = sql_fetch("select * from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'");
switch ($pd["pd_price_type"]){
    case "0":
        $price_type = "회당";
        break;
    case "1":
        $price_type = "시간당";
        break;
    case "2":
        $price_type = "일당";
        break;
}
?>
<div id="id05" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2 style="width:65vw;margin-bottom:3vw;">거래완료금 입력</h2>
            <form action="<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order_fin_request.php" method="post" name="fin_update">
                <input type="hidden" name="od_id" value="<?php echo $od_id;?>">
            <div style="display: inline-block;margin: 0 auto;width:100%">
                <ul style="display:inline-block;margin:0 auto;">
                    <li style="background-color:rgba(255,255,255,0.6);padding:2vw;font-size:3vw;width:45vw;-webkit-border-radius: 5vw;-moz-border-radius: 5vw;border-radius: 5vw;">
                        참고 : <?php echo $price_type." ".number_format($pd["pd_price"])." 원"; ;?>
                    </li>
                </ul>
            </div>
            <div>
                <input type="hidden" name="od_price" id="od_price" value="<?php echo $pd["pd_price"];?>" onchange="number_only(this)">
                <input type="number" name="od_price_text" id="od_price_text" onkeyup="$('#od_price').val(this.value);" required style="margin:4vw auto;width:65vw" placeholder="거래완료금 : 현재 <?php echo $pd["pd_price"];?> 원">
            </div>
            <div>
                <input type="text" name="od_fin_content" id="od_fin_content" style="margin:0 auto 4vw auto;width:65vw;" placeholder="최종 요청금액에 대해 설명을 입력해주세요.">
            </div>
            <p style="font-size:3vw;color:#fff;font-weight:bold;width:100%;text-align: center;margin-bottom:4vw;">예) 시간당 500일경우 총 6시간 3,000 원 결제바랍니다.</p>
            <div>
                <input type="button" value="취소" onclick="modalClose()">
                <input type="button" value="잔금요청" onclick="fnOrderFinUpdate()" style="width: auto">
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    function fnOrderFinUpdate(){
        var od_price = $("#od_price").val();
        if(od_price=="") {
            alert("최종 거래 완료금을 입력해주세요.");
            return false;
        }

        if(confirm("구매자와 협의된 금액이 맞으신가요? \r\n해당 금액으로 최종 결제 요청 하시겠습니까?")) {
            document.fin_update.submit();
        }
    }
</script>

