<?php
include_once ("../../../common.php");
?>
<div id="id00" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <!--<input type="hidden" value="<?php /*echo $od_id;*/?>" name="od_id" id="deli_od_id">
            <input type="hidden" value="<?php /*echo $pd_id;*/?>" name="pd_id" id="deli_pd_id">-->
            <h2>배송정보 입력</h2>
            <div>
                <input type="hidden" id="deli_od_id" value="">
                <input type="hidden" name="delivery_name" id="delivery_name" required style="width:50%" readonly placeholder="택배사를 선택해주세요.">
                <select name="deli_sel" id="deli_sel" onchange="$('#delivery_name').val(this.value)"  style="width:75%;text-align: center;background-color: #FFF;color: #000;position: relative;    margin: 4vw auto;padding: 2vw;font-size: 3.6vw;border-radius: 20vw;border: none;font-family: 'nsr', sans-serif;">
                    <option value="">택배사선택</option>
                    <option value="한진택배">한진택배</option>
                    <option value="우체국택배">우체국택배</option>
                    <option value="로젠택배">로젠택배</option>
                    <option value="대한통운">대한통운</option>
                    <option value="경동택배">경동택배</option>
                    <option value="DHL">DHL</option>
                    <option value="천일택배">천일택배</option>
                    <option value="CU편의점택배">CU편의점택배</option>
                    <option value="GSpostbox택배">GSpostbox택배</option>
                    <option value="대신택배">대신택배</option>
                    <option value="한의사랑택배">한의사랑택배</option>
                    <option value="합동택배">합동택배</option>
                    <option value="홈픽">홈픽</option>
                    <option value="한서호남택배">한서호남택배</option>
                    <option value="일양로지스">일양로지스</option>
                    <option value="건영택배">건영택배</option>
                    <option value="롯데택배">롯데택배</option>
                    <option value="SLX">SLX</option>
                    <option value="TNT">TNT</option>
                    <option value="EMS">EMS</option>
                    <option value="Fedex">Fedex</option>
                    <option value="UPS">UPS</option>
                    <option value="USPS">USPS</option>
                </select>
                <!--<input type="text" value="" name="delivery_name" id="delivery_name" placeholder="택배사" required >-->
                <input type="text" value="" name="delivery_number" id="delivery_number" placeholder="운송장번호" required style="margin-top:0;">
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="배송정보 등록" onclick="fnConfirmDelivery('<?php echo $od_id;?>','<?php echo $pd_id;?>','<?php echo $pd_type;?>');" style="width:auto;margin-left:1vw" >
            </div>
        </div>
    </div>
</div>
