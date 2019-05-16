<?php
include_once ("../../../common.php");

if(!$mb_id){
    $result["result"] = 0;
    echo json_encode($result);
    return false;
}

$sql = "select count(*) as cnt from `my_card` where mb_id = '{$mb_id}' and card_status = 1";
$cardcnt = sql_fetch($sql);

if($cardcnt["cnt"] > 0){
    $result["result"] = 1;
}else{
    $result["result"] = 2;

    $sql = "select * from `my_card` where mb_id = '{$mb_id}'";
    $res = sql_query($sql);

    while($row = sql_fetch_array($res)){
        $card[] = $row;
    }
    $result["data"] = "<div class='w3-container'>";
    $result["data"] .= "<div class='modal_title'><h2>카드정보 입력</h2><div class='close'></div></div>";

    if(count($card) == 0){
        $result["data"] .= "<div class='modal_sel'><p>등록된 카드가 없습니다. 카드정보를 입력해주세요.</p></div>";
    }else{
        $result["data"] .= "<div class='modal_sel'><select name='cardsel' id='cardsel' onchange='fnCardSel(this.value)'>";
        for($i=0;$i<count($card);$i++){
            $cardinfo = $card[$i]["card_name"]."|".$card[$i]["card_year"].$card[$i]["card_month"]."|".base64_decode($card[$i]["card_num"]);
            $result["data"] .= "<option value='".$cardinfo."'>".$card[$i]["card_name"]."</option>";
        }
        $result["data"] .= "</select></div>";
    }

    $result["data"] .= "<div class='card_content'>";
    $result["data"] .= "<ul >";
    $result["data"] .= "<li><input type='text' class='write_input01' id='card_name' style='width:calc(50% - 3vw)' placeholder='카드명' onkeyup=\"$('#card_name').val(this.value)\"></li>";
    $result["data"] .= "<li><input type='text' class='write_input01' id='card_year' style='width:calc(25% - 3vw - 2px);margin-right:1.5vw;' placeholder='YY' maxlength='2' onkeyup=\"number_only(this);$('#od_expd').val(this.value+$('#card_month').val())\">";
    $result["data"] .= "<input type='text' class='write_input01' id='card_month' style='width:calc(25% - 3vw - 2px)' placeholder='MM' maxlength='2' onkeyup=\"number_only(this);$('#od_expd').val($('#card_year').val()+this.value)\">";
    $result["data"] .= "<li><input type='number' class='write_input01' id='card_num1' style='width:calc(22% - 3vw);margin-right:1.5vw;' placeholder='카드번호' onkeyup=\"number_only(this);$('#od_card_num').val(this.value+$('#card_num2').val()+$('#card_num3').val()+$('#card_num4').val())\" maxlength='4'>";
    $result["data"] .= "<input type='number' class='write_input01' id='card_num2' style='width:calc(22% - 3vw - 2px);margin-right:1.5vw;' placeholder='카드번호' onkeyup=\"number_only(this);$('#od_card_num').val($('#card_num1').val()+this.value+$('#card_num3').val()+$('#card_num4').val())\" maxlength='4'>";
    $result["data"] .= "<input type='number' class='write_input01' id='card_num3' style='width:calc(22% - 3vw - 2px);margin-right:1.5vw;' placeholder='카드번호' onkeyup=\"number_only(this);$('#od_card_num').val($('#card_num1').val()+$('#card_num2').val()+this.value+$('#card_num4').val())\" maxlength='4'>";
    $result["data"] .= "<input type='number' class='write_input01' id='card_num4' style='width:calc(22% - 3vw - 2px);' placeholder='카드번호' onkeyup=\"number_only(this);$('#od_card_num').val($('#card_num1').val()+$('#card_num2').val()+$('#card_num3').val()+this.value)\" maxlength='4'></li>";
    $result["data"] .= "</ul>";
    $result["data"] .= "</div>";
    $result["data"] .= "<div style='text-align: center'>";
    $result["data"] .= '<input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="결제" onclick="fnCardOrder();" style="margin-left:1vw;background-color:yellow" >';
    $result["data"] .= "</div>";


    $result["data"] .= "<div>";
}

echo json_encode($result);