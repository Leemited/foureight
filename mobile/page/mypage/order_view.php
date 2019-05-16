<?php
include_once ("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");

$sql = "select *,o.mb_id as mb_id, p.mb_id as pd_mb_id,p.pd_id as pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where od_id = '{$od_id}' ";
$order = sql_fetch($sql);

switch ($order["od_pay_status"]){
    case "1":
        $paystatus = "결제완료";
        break;
    case "0":
        $paystatus = "입금대기중";
        break;
    case "2":
        $paystatus = "결제취소";
        break;
}

switch ($order["od_pay_type"]){
    case "1":
        $paytype = "카드결제";
        break;
    case "3":
        $paytype = "계좌이체";
        break;
}

$back_url = G5_URL;

?>
    <div id="id0s" class="w3-modal w3-animate-opacity">
        <div class="w3-modal-content w3-card-4">
            <div class="w3-container">
                <div class="con">

                </div>
            </div>
        </div>
    </div>
    <div id="id02" class="w3-modal w3-animate-opacity no-view">
        <div class="w3-modal-content w3-card-4">
            <div class="w3-container">
                <input type="text" name="like_id" id="like_id" value="<?php echo $order["pd_id"];?>">
                <input type="hidden" name="view_pd_type" id="view_pd_type" value="">
                <h2>평가하기</h2>
                <div class="likes">
                    좋아요 <!--<img src="<?php /*echo G5_IMG_URL*/?>/view_like.svg" alt="" class="likeimg" >-->
                </div>
                <div>
                    <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnLikeUpdate();" >
                </div>
            </div>
        </div>
    </div>
    <div id="id00" class="w3-modal w3-animate-opacity no-view">
        <div class="w3-modal-content w3-card-4">
            <div class="w3-container">
                <input type="hidden" value="<?php echo $order["od_id"];?>" name="od_id" id="od_id">
                <h2>배송정보 입력</h2>
                <div>
                    <input type="text" name="delivery_name" id="delivery_name" required style="width:50%">
                    <select name="deli_sel" id="deli_sel" onchange="$('#delivery_name').val(this.value)" style="width:calc(50% - 8vw);text-align: center;background-color: #FFF;color: #000;position: relative;    margin: 4vw auto;padding: 2vw;font-size: 3.6vw;border-radius: 20vw;border: none;font-family: 'nsr', sans-serif;">
                        <option value="">택배사선택</option>
                        <option value="한진택배">한진택배</option>
                        <option value="우체국택배">우체국택배</option>
                        <option value="옐로우캡">옐로우캡</option>
                        <option value="로젠택배">로젠택배</option>
                        <option value="대한통운">대한통운</option>
                        <option value="경동택배">경동택배</option>
                        <option value="">직접입력</option>
                    </select>
                    <!--<input type="text" value="" name="delivery_name" id="delivery_name" placeholder="택배사" required >-->
                    <input type="text" value="" name="delivery_number" id="delivery_number" placeholder="운송장번호" required style="margin-top:0;">
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="배송정보 등록" onclick="fnConfirmDelivery();" style="width:auto;margin-left:1vw" >
                </div>
            </div>
        </div>
    </div>
    <div id="id08" class="w3-modal w3-animate-opacity no-view">
        <div class="w3-modal-content w3-card-4">
            <div class="w3-container">
                <form name="write_from" id="write_from" method="post" action="">
                    <h2>연락하기</h2>
                    <div class="contacts">
                        <ul>

                        </ul>
                    </div>
                    <div>
                        <input type="button" value="닫기" onclick="modalClose2()">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>거래정보</h2>
    </div>
    <div class="alert_list">
        <div class="list_con">
            <div class="product_img" onclick="fn_viewer('<?php echo $order["pd_id"];?>')">
                <?php
                if($order["pd_images"]!="") {
                    $img = explode(",", $order["pd_images"]);
                    $img1 = get_images(G5_DATA_PATH . "/product/" . $img[0], '', '');
                    if (is_file(G5_DATA_PATH . "/product/" . $img1)) {
                        $pro_img = G5_DATA_URL . "/product/" . $img1;
                    } else {
                        $pro_img = '';
                    }
                }else{
                    $pro_img = '';
                }
                ?>
                <?php if($pro_img){?>
                <div style="background-image:url('<?php echo $pro_img;?>');width:40vw;height:40vw;margin:3vw auto;background-size:cover;background-position: center;background-repeat:no-repeat;display:block;position: relative;border:2px solid #fff;"></div>
                <?php }?>
                <h2 style="text-align: center;font-size:4vw;"><?php echo $order["pd_tag"];?></h2>
            </div>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>주문정보</h2>
                <ul>
                    <li>주문자명 <span><?php echo $order["od_name"];?></span></li>
                    <li>배송지 <span><?php echo $order["od_zipcode"]." ".$order["od_addr1"]. " ".$order["od_addr2"];?></span></li>
                    <li>연락처 <span><?php echo hyphen_hp_number($order["od_tel"]);?></span></li>
                    <li>주문시 요청사항 <span><?php echo $order["od_content"];?></span></li>
                </ul>
            </div>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>결제정보</h2>
                <ul>
                    <li>결제 방식 <span><?php echo $paytype;?></span></li>
                    <li>결제 상태 <span><?php echo $paystatus;?></span></li>
                    <li>결제 금액 <span><?php echo number_format($order["od_price"]);?> 원</span></li>
                    <?php if($order["od_pay_type"]==2){?>
                    <li>가상계좌번호 <span><?php echo $order["vAccount"];?></span></li>
                    <li>은행 <span><?php echo $order["vAccountBankName"];?></span></li>
                    <li>입금 기간 <span><?php echo $order["vAccountDate"];?></span></li>
                    <?php }?>
                </ul>
            </div>
            <?php if($order["od_pay_status"]==1 && $order["od_pd_type"] == 1 && $order["od_status"] == 1){?>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>배송 정보</h2>
                <ul>
                    <li>택배사 <span id="deli_name"><?php echo ($order["delivery_name"])?$order["delivery_name"]:"배송전";?></span></li>
                    <li>운송장번호 <span id="deli_num"><?php echo ($order["delivery_number"])?$order["delivery_number"]:"배송전";?></span></li>
                    <li>배송 등록일 <span id="deli_date"><?php echo ($order["delivery_date"])?$order["delivery_date"]:"배송전";?></span></li>
                </ul>
            </div>
            <?php }?>
            <?php if($order["od_step"]!=2){?>
            <div class="order_view_btns">
                <?php if($order["od_status"]==1 && $order["od_pay_status"]==1 && $member["mb_id"]==$order["pd_mb_id"] ){?>
                    <?php if($order["delivery_name"]==""){?>
                        <input type="button" value="배송정보 입력" onclick="fnDeli()">
                    <?php }?>
                    <?php if($order["od_pd_type"]==1 && $order["delivery_name"]!=""){?>
                        <input type="button" value="배송/거래완료 요청" onclick="sendPush('<?php echo $order["mb_id"];?>','배송상태 및 거래완료 확인 요청입니다.','<?php echo $od_id;?>','<?php echo $order["pd_id"];?>')">
                    <?php }?>
                    <?php if($order["od_pd_type"]==2 && $order["od_step"] == 1){?>
                        <input type="button" value="능력 완료금 요청" onclick="sendPush('<?php echo $order["mb_id"];?>','능력 이행 완료금 처리 요청입니다.','<?php echo $od_id;?>','<?php echo $order["pd_id"];?>')">
                    <?php }?>
                <?php }else if($order["od_status"]==1 && $order["od_pay_status"]==1 && $member["mb_id"]!=$order["pd_mb_id"]){?>
                    <?php if($order["od_pd_type"]==2 && $order["od_step"] == 1){?>
                        <input type="button" value="능력 완료금 결제" onclick="fnPayment2('<?php echo $od_id;?>')">
                    <?php }?>
                    <?php if($order["delivery_name"]==""){?>
                        <input type="button" value="연락하기" onclick="fnShow('<?php echo $order["pd_mb_id"];?>')">
                        <input type="button" value="한번 더 배송 요청" onclick="sendPush('<?php echo $order["pd_mb_id"];?>','배송 요청입니다.','<?php echo $od_id;?>','<?php echo $order["pd_id"];?>')">
                        <input type="button" value="확인" onclick="location.href=g5_url">
                        <!--<input type="button" value="결제 취소 요청" onclick="orderCancel('<?php /*echo $od_id;*/?>');">-->
                    <?php }else{?>
                        <!--<input type="button" value="배송 추적" onclick="fnDelivery()">-->
                        <input type="button" value="거래 완료" class="fin_btn" onclick="fnOrderFin('<?php echo $od_id;?>','<?php echo $order["pd_mb_id"];?>')">
                    <?php }?>
                <?php }?>
            </div>
            <?php }?>
        </div>
    </div>
<script>
    function fnDeli(){
        $("#id00").css("display","block");
        $("html,body").css("height","100vh");
        $("html,body").css("overflow","hidden");
        location.hash = "modal";
    }
    function fnConfirmDelivery(){
        var od_id = $("#od_id").val();
        var delivery_name = $("#delivery_name").val();
        var delivery_number = $("#delivery_number").val();

        if(od_id == ""){
            alert("선택된 주문 정보가 없습니다.");
            return false;
        }
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.order_delivery_update.php",
            method:"post",
            data:{od_id:od_id,delivery_name:delivery_name,delivery_number:delivery_number},
            dataType:"json"
        }).done(function(data){
            console.log(data);
            if(data.result==1){
                alert("주문정보를 찾지 못했습니다. \n다시 시도해 주세요.")
            }else if(data.result == 2){
                alert("배송정보 입력 실패");
            }else{
                $("#deli_name").html(delivery_name);
                $("#deli_num").html(delivery_number);
                $("#deli_date").html(data.deli_date);
                modalClose();
                location.reload();
            }
        });
    }

    function fnOrderFin(od_id,mb_id){
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.order_update.php",
            method:"post",
            data:{od_id:od_id,mb_id:mb_id},
            dataType:"json"
        }).done(function(data){
            if(data.result==1){
               $(".fin_btn").remove();
            }
            alert(data.msg);
        });
    }

    /*function fnTalk(mb_id,pd_id){
        location.href=g5_url+"/mobile/page/talk/talk_view.php?type=payment&pd_id="+pd_id+"&mb_id="+mb_id;
    }*/

    function fnPayment2(){

    }

    function sendPush(mb_id,title,od_id,pd_id) {
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.send_push.php",
            method:"post",
            data:{mb_id:mb_id,content:title,od_id:od_id,pd_id:pd_id}
        }).done(function (data) {
            alert(data);
        })
    }

    function fnLikeUpdate(){
        var id = $("#like_id").val();
        var mb_id = "<?php echo $member["mb_id"];?>";
        var text = $("#like_content").val();
        $.ajax({
            url:g5_url+"/mobile/page/like_product.php",
            method:"post",
            dataType:"json",
            data:{pd_id:id,mb_id:mb_id,like_content:text}
        }).done(function(data){
            console.log(data);
            if(data.result=="1"){
                alert('이미 평가한 글입니다.');
            }else if(data.result=="2"){
                alert("평가가 정상 등록됬습니다.");
            }else{
                alert("잘못된 요청입니다.");
            }
            $(".pd_like span").html(data.count);
            modalClose();
        });
    }

    function fnShow(mb_id){
        // 연락자 정보 가져오기
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.get_member.php",
            method:"post",
            data:{mb_id:mb_id},
            dataType:"json"
        }).done(function(data){
            console.log(data);
            $("#id08 .contacts ul").html('');
            $("#id08 .contacts ul").append(data.obj);
            //$("#mb_"+id).toggleClass("active");
            $("#id08").css({"display":"block","z-index":"9002"});
            $("#id08").css("display","block");
            location.hash = "#modal";
        });
    }
</script>
<?php
include_once (G5_PATH."/tail.php");