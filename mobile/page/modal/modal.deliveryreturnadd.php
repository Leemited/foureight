<?php
include_once ("../../../common.php");
$order = sql_fetch("select * from `order` where od_id = '{$od_id}'");
?>
<div id="id11" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" value="<?php echo $od_id;?>" name="od_id" id="od_id">
            <h2>환불배송 <?php if($type==0){?>입력<?php }else{?>수정<?php }?></h2>
            <div>
                <input type="hidden" id="return_deli_od_id" value="<?php echo $od_id;?>">
                <input type="hidden" id="return_deli_pd_type" value="<?php echo $pd_type;?>">
                <input type="hidden" id="return_deli_pd_id" value="<?php echo $pd_id;?>">
                <input type="hidden" name="return_delivery_name" id="return_delivery_name" required style="width:50%" readonly placeholder="택배사를 선택해주세요.">
                <select name="deli_sel" id="deli_sel" onchange="$('#return_delivery_name').val(this.value)" style="width:75%;text-align: center;background-color: #FFF;color: #000;position: relative;    margin: 4vw auto;padding: 2vw;font-size: 3.6vw;border-radius: 20vw;border: none;font-family: 'nsr', sans-serif;" >
                    <option <?php if($order["delivery_name_cancel"]==""){?>selected<?php }?> value="">택배사선택</option>
                    <option <?php if($order["delivery_name_cancel"]=="한진택배"){?>selected<?php }?> value="한진택배">한진택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="우체국택배"){?>selected<?php }?> value="우체국택배">우체국택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="로젠택배"){?>selected<?php }?> value="로젠택배">로젠택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="대한통운"){?>selected<?php }?> value="대한통운">대한통운</option>
                    <option <?php if($order["delivery_name_cancel"]=="경동택배"){?>selected<?php }?> value="경동택배">경동택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="DHL"){?>selected<?php }?> value="DHL">DHL</option>
                    <option <?php if($order["delivery_name_cancel"]=="천일택배"){?>selected<?php }?> value="천일택배">천일택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="CU편의점택배"){?>selected<?php }?> value="CU편의점택배">CU편의점택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="GSpostbox택배"){?>selected<?php }?> value="GSpostbox택배">GSpostbox택배</option
                    <option <?php if($order["delivery_name_cancel"]=="대신택배"){?>selected<?php }?> value="대신택배">대신택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="한의사랑택배"){?>selected<?php }?> value="한의사랑택배">한의사랑택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="합동택배"){?>selected<?php }?> value="합동택배">합동택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="홈픽"){?>selected<?php }?> value="홈픽">홈픽</option>
                    <option <?php if($order["delivery_name_cancel"]=="한서호남택배"){?>selected<?php }?> value="한서호남택배">한서호남택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="일양로지스"){?>selected<?php }?> value="일양로지스">일양로지스</option>
                    <option <?php if($order["delivery_name_cancel"]=="건영택배"){?>selected<?php }?> value="건영택배">건영택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="롯데택배"){?>selected<?php }?> value="롯데택배">롯데택배</option>
                    <option <?php if($order["delivery_name_cancel"]=="SLX"){?>selected<?php }?> value="SLX">SLX</option>
                    <option <?php if($order["delivery_name_cancel"]=="TNT"){?>selected<?php }?> value="TNT">TNT</option>
                    <option <?php if($order["delivery_name_cancel"]=="EMS"){?>selected<?php }?> value="EMS">EMS</option>
                    <option <?php if($order["delivery_name_cancel"]=="Fedex"){?>selected<?php }?> value="Fedex">Fedex</option>
                    <option <?php if($order["delivery_name_cancel"]=="UPS"){?>selected<?php }?> value="UPS">UPS</option>
                    <option <?php if($order["delivery_name_cancel"]=="USPS"){?>selected<?php }?> value="USPS">USPS</option>
                </select>
                <!--<input type="text" value="" name="delivery_name" id="delivery_name" placeholder="택배사" required >-->
                <input type="text" value="<?php echo $order["delivery_number_cancel"];?>" name="return_delivery_number" id="return_delivery_number" placeholder="운송장번호" required style="margin-top:0;">
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="환불배송 <?php if($type==0){?>등록<?php }else{?>수정<?php }?>" onclick="fnConfirmDeliveryReturn('<?php echo $type;?>');" style="width:auto;margin-left:1vw" >
            </div>
        </div>
    </div>
</div>
