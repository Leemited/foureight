<?php
include_once ("../../../common.php");

if(!$od_id){
    alert("주문 상품이 없거나 잘못된 요청입니다.");
    return false;
}

if($od_id){
    $sql = "select *,p.pd_id,p.mb_id as mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
}
$order = sql_fetch($sql);
if($order["od_pd_type"]==1) {
    $total = $order["od_price"] + ($order["od_price"]* 0.035);
    $od_prices = $order["od_price"];

}else{
    if($order["od_price2"]!=0) {
        if ($order["od_step"] == 0) {
            $total = $order["od_price2"] + ($order["od_price2"]* 0.035);
            $od_prices = $order["od_price2"];
        } else if ($order["od_step"] == 1) {
            $total = $order["od_price"] + ($order["od_price"]* 0.035);
            $od_prices = $order["od_price"];
        }
    }else{
        $total = $order["od_price"] + ($order["od_price"]* 0.035);
        $od_prices = $order["od_price"];
    }
}
$od_pd_type = $order["od_pd_type"];
$od_delivery_type = $order["od_delivery_type"];
$order_item_name = $order["pd_tag"];
$set_type = $od_pd_type;
$back_url = G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type={$set_type}";

include_once (G5_MOBILE_PATH."/head.login.php");
$sql = "select * from `my_address` where mb_id = '{$member["mb_id"]}' order by addr_default desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $my_addres[] = $row;
}

$my_addres_li = count($my_addres);

$mb_hp = explode("-",$member["mb_hp"]);


$sql = "select * from `my_card` where mb_id='{$member["mb_id"]}' and card_status = 1 ";
$mycard = sql_fetch($sql);

$mycard_num = base64_decode($mycard["card_number"]);

$mbs = get_member($order["mb_id"]);

if($type="fin_order"){
    $sql = "";
}

$mAgent = array("iPhone","iPod","Android","Blackberry", "Opera Mini", "Windows ce", "Nokia", "sony" ,"iPad");

//echo $_SERVER['HTTP_USER_AGENT'];

$is_mobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
    if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
        $is_mobile = true;
        break;
    }
}

if(is_mobile() || $is_mobile){
    $pay_url = G5_MOBILE_URL."/page/mypage/newpay/_1_start.php";
}else{
    $pay_url = G5_MOBILE_URL."/page/mypage/newpaypc/_1_start.php";
}
?>
<div id="id00" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id" id="mb_id">
            <h2>배송지저장</h2>
            <div>
                <input type="text" value="" name="addr_name" id="addr_name" placeholder="배송지 이름" required>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="저장" onclick="fnAddrSave(1);" ><input type="button" value="기본배송지로저장" onclick="fnAddrSave(2);" style="width:auto;margin-left:1vw" >
            </div>
        </div>
    </div>
</div>
<div id="idcard" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">

        </div>
    </div>
</div>
<div id="id01" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4" style="height:auto;-webkit-transform: translateY(-50%);-moz-transform: translateY(-50%);-ms-transform: translateY(-50%);-o-transform: translateY(-50%);transform: translateY(-50%);margin-top:0">
        <div class="w3-container" style="text-align: center">
            <div>
                <ul class="modal_sel">
                    <li style="font-size:3vw;width:60%">판매자 정보 : <span class="cheat_info"><?php echo $mbs["mb_hp"];?></span></li>
                </ul>
            </div>
            <iframe src="https://api.thecheat.co.kr/web/widget.php?url=http://484848.co.kr" width="281" height="118" frameborder="0" border="0" framespacing="0" marginheight="0" marginwidth="0" scrolling="no" noresize></iframe>
            <div style="margin-top:2vw;">
                <input type="button" value="닫기" onclick="modalClose(this)" style="font-size:3vw">
            </div>
        </div>
    </div>
</div>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>주문/결제하기</h2>
</div>
<div class="orders">
    <div class="order_items">
        <div class="item_top">
            <?php if($order["pd_images"]!=""){
                $img = explode(",",$order["pd_images"]);
                $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
                if(is_file(G5_DATA_PATH."/product/".$img1)){
                    ?>
                    <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;">
                        <?php if($img1!=""){?>
                            <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" style="opacity:0">
                        <?php }else{ ?>
                            <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" style="opacity:0">
                        <?php }?>
                    </div>
                <?php }else{
                    $tags = explode("/",$order["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" >
                        <div class="tags" style="display:table">
                            <?php //for($k=0;$k<count($tags);$k++){
                                $rand_font = rand(3,6);
                                ?>
                                <div class="rand_size<?php echo $rand_font;?>" style="display:table-cell;vertical-align: middle;text-align: center"><?php echo iconv_substr($order["pd_tag"],0,20,'UTF-8');?></div>
                            <?php //}?>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
            <?php }else{
                $tags = explode("#",$order["pd_tag"]);
                $rand = rand(1,13);
                ?>
                <div class="bg rand_bg<?php echo $rand;?> item_images" >
                    <div class="tags" style="display:table;width:100%;height:100%;">
                        <?php //for($k=0;$k<count($tags);$k++){
                            $rand_font = rand(3,6);
                            ?>
                            <div class="rand_size<?php echo $rand_font;?>" style="display:table-cell;vertical-align: middle;text-align: center"><?php echo iconv_substr($order["pd_tag"],0,20,'UTF-8');?></div>
                        <?php //}?>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php }?>
            <div class="item_info">
                <h2><?php echo $order["pd_tag"];?></h2>
                <div>
                    <?php
                    if((int)$total > 0){
                        if((int)$order["od_step"]==0 && (int)$order["od_pd_type"]==2){?>
                            계약 금액 <span><?php echo number_format($od_prices)." 원"; ?></span>
                        <?php }else if((int)$order["od_step"]==1 && (int)$order["od_pd_type"]==2){?>
                            최종결제액 <span><?php echo number_format($od_prices)." 원"; ?></span>
                        <?php }else if((int)$order["od_step"]==0 && (int)$order["od_pd_type"]==1){?>
                            주문 금액 <span><?php echo number_format($od_prices);?> 원</span>
                        <?php }?>
                    <?php }if((int)$total == 0){?>
                        무료 나눔
                    <?php }?>
                </div>
                <div class="vat">
                    카드결제 수수료(3.5%) = <?php echo number_format(ceil($od_prices * 0.035))." 원";?>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="item_vatinto" style="font-size:2.6vw;">
            수수료는 결제대행사의 몫이며, 48은 수수료가 없습니다. ^^
        </div>
        <div class="item_bottom">
            <div>
                <h2>총금액 <span><?php if((int)$total > 0){ echo number_format($total);?> 원 <?php } else { echo "무료나눔";}?></span></h2>
            </div>
        </div>
    </div>
    <?php if($order["pd_delivery_use"]==1){?>
    <div class="address_tab" <?php if($od_id){?>style="display:none"<?php }?>>
        <ul>
            <?php if(count($my_addres) == 0){?>
            <li class="active" style="width:20%;">기본배송지</li>
            <?php }else{
                for($i=0;$i<count($my_addres);$i++){
                ?>
                    <li style="width:20%;float:left" id='<?php echo $my_addres[$i]["id"];?>' <?php if($my_addres[$i]["addr_default"] == 1){?>class="active"<?php }?> ><?php if($my_addres[$i]["addr_default"]==1){?>기본배송지<?php }else{ if($my_addres[$i]["addr_name"]){echo $my_addres[$i]["addr_name"];}else{echo "이름없음";}}?></li>
            <?php }
                }?>
        </ul>
        <div class="clear"></div>
    </div>
    <?php }?>
    <!--form action="<?php echo G5_MOBILE_URL?>/page/mypage/order_update.php" name="order_form_update"-->
    <!--<form action="<?php /*echo G5_MOBILE_URL*/?>/page/mypage/stdpay/requestPay.php" name="order_form_update" method="post" >-->
    <form action="<?php echo $pay_url;?>" name="order_form_update" method="post" >
    <div class="order_write">
        <input type="hidden" name="od_id" id="od_id" value="<?php echo $od_id;?>">
        <input type="hidden" name="od_item_name" id="od_item_name" value="<?php echo $order_item_name;?>">
        <input type="hidden" name="od_total" id="od_total" value="<?php echo ceil($total);?>">
        <input type="hidden" name="od_price" id="od_price" value="<?php echo ceil($total);?>">
        <input type="hidden" name="od_price_gnr" id="od_price_gnr" value="<?php echo $od_prices;?>">
        <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>">
        <input type="hidden" name="ca_id" id="ca_id" value="<?php echo $cart_idss;?>">
        <input type="hidden" name="pd_id" id="pd_id" value="<?php echo $pd_idss;?>">
        <input type="hidden" name="od_pd_type" id="od_pd_type" value="<?php echo $od_pd_type;?>">
        <input type="hidden" name="od_expd" id="od_expd" value="<?php echo str_pad($mycard["card_year"],"0",STR_PAD_LEFT).$mycard["card_month"];?>">
        <input type="hidden" name="od_card_num" id="od_card_num" value="<?php echo str_replace("-","",$mycard_num);?>">
        <input type="hidden" name="card_name" id="card_name" value="<?php echo $mycard["card_name"];?>">
        <input type="hidden" name="od_delivery_type" id="od_delivery_type" value="<?php echo "";?>">
        <input type="hidden" name="card_add" id="card_add" value="">
        <input type="hidden" name="od_temp_id" id="od_temp_id" value="<?php echo $od_temp_id;?>">
        <input type="hidden" name="app_mb_id" id="app_mb_id" value="<?php echo $member["mb_id"];?>">
        <input type="hidden" name="pay_oid" id="pay_oid" value="<?php echo $order["pay_oid"];?>"> <!-- 2번째 결제인지 파악하기위함 -->
        <input type="hidden" name="od_step" id="od_step" value="<?php echo $order["od_step"];?>"> <!-- 2번째 결제인지 파악하기위함 -->
        <?php if($order["pd_delivery_use"]==0){//배송없음?>
            <input type="hidden" name="od_name" value="<?php echo $member['mb_name'];?>">
            <input type="hidden" name="tel1" value="<?php echo $mb_hp[0];?>">
            <input type="hidden" name="tel2" value="<?php echo $mb_hp[1];?>">
            <input type="hidden" name="tel3" value="<?php echo $mb_hp[2];?>">
        <?php }?>
        <?php if($order["pd_delivery_use"]==1){?>
        <div class="write_form" >
            <div class="row">
                <div class="cell title"><?php if($od_pd_type==2){?>요청자<?php }else{?>받는분<?php }?></div>
                <div class="cell inputs">
                    <input type="text" name="od_name" id="od_name" value="<?php echo $member["mb_name"];?>" class="order_input02" required>
                    <input type="button" value="배송지저장" class="order_addr" onclick="fnAddr();">
                </div>
            </div>
            <div class="row">
                <div class="cell title">우편번호</div>
                <div class="cell inputs">
                    <input type="text" name="od_zipcode" id="od_zipcode" value="<?php echo $member["mb_zip1"];?>" class="order_input02" required readonly>
                    <input type="button" value="우편번호 검색" class="order_addr" onclick="sample2_execDaumPostcode()">
                    <div id="wraps" style="display:none;position:fixed;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;">
                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="cell title">기본주소</div>
                <div class="cell inputs">
                    <input type="text" name="od_address1" id="od_address1" value="<?php echo $member["mb_addr1"];?>" class="order_input" required readonly>
                </div>
            </div>
            <div class="row">
                <div class="cell title">상세주소</div>
                <div class="cell inputs">
                    <input type="text" name="od_address2" id="od_address2" value="<?php echo $member["mb_addr2"];?>" class="order_input" required>
                </div>
            </div>
            <div class="row">
                <div class="cell title">연락처</div>
                <div class="cell inputs">
                    <select name="tel1" id="tel1" class="hps" required>
                        <option value="010">010</option>
                        <option value="019">019</option>
                        <option value="018">018</option>
                        <option value="017">017</option>
                        <option value="070">070</option>
                        <option value="02">02</option>
                        <option value="031">031</option>
                        <option value="032">032</option>
                        <option value="033">033</option>
                        <option value="041">041</option>
                        <option value="042">042</option>
                        <option value="043">043</option>
                        <option value="044">044</option>
                        <option value="051">051</option>
                        <option value="052">052</option>
                        <option value="053">053</option>
                        <option value="054">054</option>
                        <option value="055">055</option>
                        <option value="061">061</option>
                        <option value="062">062</option>
                        <option value="063">063</option>
                        <option value="064">064</option>
                    </select> -
                    <input type="text" name="tel2" id="tel2" maxlength="4" value="<?php echo $mb_hp[1];?>" class="hps" required> - <input type="text" name="tel3" id="tel3" maxlength="4" value="<?php echo $mb_hp[2];?>" class="hps" required>
                </div>
            </div>
        </div>
        <?php }?>
        <?php if($order["od_step"]==0){?>
        <div class="msgs" >
            <div class="cell full">
                <div>
                    <input type="text" name="od_content" id="od_content" class="order_input" placeholder="판매자에게 남기는 말(50자 이내);">
                </div>
            </div>
        </div>
        <?php }?>
        <?php if($total > 0){?>
        <div class="write_form">
            <input type="hidden" name="od_type" value="1" id="od_type">
            <div class="row">
                <div class="cell title">결제수단</div>
                <div class="cell inputs">
                    <ul class="paysel">
                        <li onclick="fnOderType('1')" class="active" >카드결제</li>
                        <li onclick="fnOderType('3')" >계좌이체</li>
                        <!--<li onclick="fnOderType('4')" >핸드폰</li>-->
                    </ul>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
    <div class="order_info">
        <h2>사기조회 / 안전개래 안내</h2>
        <div>
            <!--48에서는 직접적인 거래 사기와 피해를 보장해 주지 않습니다.<br>
            따라서 결제전 신중하게 판단 하시기 바랍니다.-->
            <div class="order_info_link" style="position: relative;width:100%;text-align: center;margin:0 0 6vw;display: inline-block">
                <div style="background-color:#0c0c94;width:calc(50% - 7vw);padding:2vw 3vw;font-size:3vw;color:#fff;margin:1vw auto;text-align: center;-webkit-border-radius: 4vw;-moz-border-radius: 4vw;border-radius: 4vw;" onclick="fnTheCheat()">
                    <img src="<?php echo G5_IMG_URL;?>/logo1.png" alt="" style="width:36%"> 사기조회 하기
                </div><!--
                <div style="background-color:#2584c6;width:calc(50% - 7vw);padding:2vw 3vw;font-size:3vw;margin-left:1vw;color:#FFF;margin-top:2vw;text-align: center;-webkit-border-radius: 4vw;-moz-border-radius: 4vw;border-radius: 4vw;float:left" onclick="location.href='https://www.unicro.co.kr/index.jsp'">
                    <img src="<?php /*echo G5_IMG_URL;*/?>/unicro_logo.jpg" alt="" style="width:36%;margin-top: -1vw;"> 안전거래 하기
                </div>-->
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="order_btns">
        <?php if($order["od_direct_status"]==0 && $order["od_step"]==0){?>
        <input type="button" value="직거래" class="order_btn" onclick="orderDirect('<?php echo $od_id;?>')">
        <?php }?>
        <input type="button" value="즉시결재" class="order_btn2" onclick="orderUpdate()">
    </div>
    </form>
</div>

<script>
function fnTheCheat(){
    $("#id01").css({"display":"block","z-index":"9002"});
    $("#id01").css("display","block");
    location.hash = "#modal";
}

function fnOderType(type) {
    var total = Number($("#od_total").val());
    var price = Number($("#od_price").val());
    var info = "";
    if(type==1){//카드
        total = Math.ceil(Number($("#od_price_gnr").val()) + (Number($("#od_price_gnr").val())  * 0.035));
        price = Math.ceil(Number($("#od_price_gnr").val()) + (Number($("#od_price_gnr").val())  * 0.035));
        info = "카드결제 수수료(3.5%) = "+ number_format(Math.ceil(Number($("#od_price_gnr").val()) * 0.035)) +" 원";
    }else{//계좌이체
        total = Math.ceil(Number($("#od_price_gnr").val()) + (Number($("#od_price_gnr").val())  * 0.02));
        price = Math.ceil(Number($("#od_price_gnr").val()) + (Number($("#od_price_gnr").val())  * 0.02));
        info = "계좌이체 수수료(2%) = " + number_format(Math.ceil(Number($("#od_price_gnr").val()) * 0.02)) +" 원";
    }
    $("#od_total").val(total);
    $("#od_price").val(price);
    $(".vat").html(info);
    $(".item_bottom h2 span").html(number_format(total)+" 원 ");
    $("#od_type").val(type);
}

function orderUpdate(){
    <?php if($od_id==""){?>
    if($("#od_name").val() == ""){
        alert("받는분 성함을 입력해주세요.");
        return false;
    }
    <?php }?>

    <?php if($order["pd_delivery_use"]==1 && $order["od_step"]==0){?>
        if($("#od_zipcode").val()=="" || $("#od_address1").val()=="" || $("#od_address2").val()==""){
            alert("배송지 정보를 입력해주세요.");
            return false;
        }
        if($("#tel2").val()=="" || $("#tel3").val()==""){
            alert("연락처 정보를 입력해주세요.");
            return false;
        }
    <?php }?>

    if(confirm("해당 주문건에 대한 결제를 하시겠습니까?")){
        /*var od_name = $("#od_name").val();
        var od_tel = $("#tel1").val()+"-"+$("#tel2").val()+"-"+$("#tel3").val();
        var od_address = $("#od_address1").val()+" "+$("#od_address2").val();
        var od_zipcode = $("#od_zipcode").val();
        var mt_id = $("#group_id").val();
        var ordertype = $("#od_type").val();
        if(ordertype == 1) {
            $.ajax({
                url: g5_url + "/mobile/page/ajax/ajax.my_card.php",
                method: "post",
                data: {mb_id: "<--?php echo //$member["mb_id"];?>"},
                dataType: "json"
            }).done(function (data) {
                if (data.result == 1) {
                    if(confirm('등록된 카드정보로 결제 하시겠습니까?')){
                        document.order_form_update.submit();
                    }
                } else {
                    $("#card_add").val('add');
                    $("#idcard").css({"display":"block","z-index":"9999999"});
                    $("#idcard .w3-modal-content").html(data.data);
                    $("#idcard .w3-modal-content").css({"height":"80vw","margin-top":"-40vw"});
                    $("html, body").css("overflow","hidden");
                    $("html, body").css("height","100vh");
                    location.hash="#modal";
                }
            });
        }else{*/
            //document.order_form_update.action = g5_url+"/mobile/page/mypage/stdpay/requestPay.php";
        if($("#od_price").val()>0) {
            document.order_form_update.submit();
        }else{
            //무료는 바로 결제 완료 상태로 변경
            document.order_form_update.action = g5_url+"/mobile/page/mypage/order_update.php";
            document.order_form_update.submit();
        }
        //}
    }else{
        return false;
    }
}
function orderDirect(od_id){
    if(confirm("직거래 이용시 판매자의 동의가 필요하며, 장소와 시간은 반드시 협의 후 진행 바랍니다.")){
        //판매자에게 동의 확인 알림 확인 후 처리
        $.ajax({
            url:g5_url+"/mobile/page/mypage/ajax.pay_direct.php",
            method:"post",
            data:{od_id:od_id}
        }).done(function(data){
            console.log(data);
            if(data=="1") {
                alert("직거래 요청을 보냈습니다.\r좀더 빠른대응을 위해 판매자와 1:1을 대화를 해보세요.");
                $(".order_btn").remove();
            }
        });
    }else{
        return false;
    }
}

function fnAddr(){
    var zipcode = $("#od_zipcode").val();
    var addr1 = $("#od_address1").val();
    var addr2 = $("#od_address2").val();
    var addr_mbname = $("#od_name").val();
    if(zipcode=="" && addr1 == "" && addr2=="" && addr_mbname==""){
        alert("주소정보를 입력해 주세요.");
        return false;
    }
    $("#id00").css("display","block");
    $("html,body").css("height","100vh");
    $("html,body").css("overflow","hidden");
    location.hash = "modal";
}

function fnAddrSave(type){
    var zipcode = $("#od_zipcode").val();
    var addr1 = $("#od_address1").val();
    var addr2 = $("#od_address2").val();
    var addr_name = $("#addr_name").val();
    var addr_mbname = $("#od_name").val();
    if(addr_name==""){
        alert("배송지명을 입력해 주세요.");
        return false;
    }
    $.ajax({
        url:g5_url+"/mobile/page/mypage/ajax.add_address.php",
        method:"post",
        data:{zipcode:zipcode,addr1:addr1,addr2:addr2,type:type,addr_name:addr_name,addr_mbname:addr_mbname},
        dataType:"json"
    }).done(function(data){
        console.log(data);
        if(data.result == 0){
            alert("배송지저장 갯수를 초과 했습니다.");
        }
        if(data.result==2){
            alert("등록되었습니다.");
            if(type==2){
                var li = "<li id='"+data.id+"' class='active' style='width:20%;float:left'>"+addr_name+"</li>";
                $(".address_tab li").removeClass("active");
                $(".address_tab ul").prepend(li);
            }else{
                var li = "<li id='"+data.id+"' style='width:20%;float:left'>"+addr_name+"</li>";
                $(".address_tab ul").append(li);
            }

        }if(data.result==1){
            alert("중복된 정보 입니다.");
        }if(data.result==3){
            alert("입력 정보 오류로 인해 등록이 취소 되었습니다.");
        }
        modalClose();
    });
}
$(function(){
    fnOderType(1);

   $(document).on("click",".address_tab li",function(){
      if(!$(this).hasClass("active")){
          var id = $(this).attr("id");
          $(this).addClass("active");
          $(".address_tab li").not($(this)).removeClass("active");
          $.ajax({
              url:g5_url+"/mobile/page/mypage/ajax.select_address.php",
              method:"post",
              data:{id:id},
              dataType:"json"
          }).done(function(data){
              console.log(data);
              $("#od_name").val(data.addr_mbname);
              $("#od_zipcode").val(data.addr_zipcode);
              $("#od_address1").val(data.addr_address1);
              $("#od_address2").val(data.addr_address2);
          })
      }
   });
    $(document).on("click",".paysel li",function(){
        if(!$(this).hasClass("active")){
            $(this).addClass("active");
            $(".paysel li").not($(this)).removeClass("active");
        }
    });
});
</script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    // 우편번호 찾기 화면을 넣을 element
    var element_layer = document.getElementById('wraps');

    function closeDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_layer.style.display = 'none';
    }

    function sample2_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                console.log(data);
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                /*if(data.userSelectedType === 'R'){
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if(data.buildingName !== '' && data.apartment === 'Y'){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if(extraAddr !== ''){
                        extraAddr = ' (' + extraAddr + ')';
                    }
                    // 조합된 참고항목을 해당 필드에 넣는다.
                    document.getElementById("sample2_extraAddress").value = extraAddr;

                } else {
                    document.getElementById("sample2_extraAddress").value = '';
                }*/

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('od_zipcode').value = data.zonecode;
                document.getElementById("od_address1").value = addr;
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("od_address2").focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_layer.style.display = 'none';
            },
            width : '100%',
            height : '100%',
            maxSuggestItems : 5
        }).embed(element_layer);

        // iframe을 넣은 element를 보이게 한다.
        element_layer.style.display = 'block';

        // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
        initLayerPosition();
    }

    // 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
    // resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
    // 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
    function initLayerPosition(){
        var width = 300; //우편번호서비스가 들어갈 element의 width
        var height = 400; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 5; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        element_layer.style.width = width + 'px';
        element_layer.style.height = height + 'px';
        element_layer.style.border = borderWidth + 'px solid';
        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
        element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
    }

    function backKey(){
        location.href='<?php echo $backUrl;?>';
    }
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.orders.php");
