<?php
include_once ("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");

$sql = "select *,o.mb_id as mb_id,p.pd_id as pd_id,p.mb_id as pd_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where od_status != -1 and o.od_id = '{$od_id}'";
$order = sql_fetch($sql);
$mb = get_member($order["mb_id"]);
if($order["od_direct_status"]!=1) {
    switch ($order["od_pay_status"]) {
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
}

if($order["od_direct_status"]==2){
    $order["od_name"] = $mb["mb_name"];
    $order["od_addr1"] = "직거래";
    $order["od_tel"] = $mb["mb_hp"];
    $paytype = "직거래";
}
/*$sql = "select * from order_payment_card_info where pay_oid = '{$order["pay_oid"]}'";
$cardinfo = sql_fetch($sql);*/

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
    <div id="id01s" class="w3-modal w3-animate-opacity">
        <div class="w3-modal-content w3-card-4">
            <div class="w3-container">
                <div class="con">

                </div>
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
                <h2 style="text-align: center;font-size:4vw;margin:3vw auto;"><?php echo $order["pd_tag"];?></h2>
            </div>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>주문정보</h2>
                <ul>
                    <li>주문자명 <span><?php echo $order["od_name"];?></span></li>
                    <li>배송지 <span><?php echo $order["od_zipcode"]." ".$order["od_addr1"]. " ".$order["od_addr2"];?></span></li>
                    <li>연락처 <span><?php echo hyphen_hp_number($order["od_tel"]);?></span></li>
                    <li>주문시 요청사항 <span><?php echo ($order["od_content"])?$order["od_content"]:"없음";?></span></li>
                </ul>
            </div>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>결제정보</h2>
                <ul>
                    <li>결제 방식 <span><?php echo $paytype;?></span></li>
                    <?php if($order["od_direct_status"]!=2){?>
                        <li>결제 상태 <span><?php echo $paystatus;?></span></li>
                        <li>결제 금액 <span><?php echo number_format($order["od_price"]);?> 원</span></li>
                        <?php if($order["od_pay_type"]==2){?>
                        <li>가상계좌번호 <span><?php echo $order["vAccount"];?></span></li>
                        <li>은행 <span><?php echo $order["vAccountBankName"];?></span></li>
                        <li>입금 기간 <span><?php echo $order["vAccountDate"];?></span></li>
                        <?php }?>
                    <?php }?>
                </ul>
            </div>
            <?php if($order["od_pay_status"]==1 && $order["od_pd_type"] == 1 && $order["od_status"] == 1 && $order["od_direct_status"]!=2){?>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>배송 정보</h2>
                <ul>
                    <li>택배사 <span id="deli_name"><?php echo ($order["delivery_name"])?$order["delivery_name"]:"배송전";?></span></li>
                    <li>운송장번호 <span id="deli_num"><?php echo ($order["delivery_number"])?$order["delivery_number"]:"배송전";?></span></li>
                    <li>배송 등록일 <span id="deli_date"><?php echo ($order["delivery_date"])?$order["delivery_date"]:"배송전";?></span></li>
                </ul>
            </div>
            <?php }?>
            <div class="order_view_btns">
                <input type="button" value="확인" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php?type=2'">
            </div>
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
           url:g5_url+'/mobile/page/modal/modal.contact.php',
            method:"post",
            data:{},
            async:false
        }).done(function(data){
            $(".modal").html(data).addClass("active");
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
        });

    }

    function fnBlind(pd_id,cm_id){
        $.ajax({
            url:g5_url+"/mobile/page/blind_write.php",
            method:"post",
            data:{pd_id:pd_id,type:"modal",cm_id:cm_id}
        }).done(function(data){
            $("#id01s").css({"display":"block","z-index":"9002"});
            $("#id01s .con").html('');
            $("#id01s .con").append(data);
            $("html, body").css("overflow","hidden");
            $("html, body").css("height","100vh");
            location.hash = "#blind";
        });
        /*$.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.blind_view.php",
            method:"post",
            data:{pd_id:pd_id}
        }).done(function(data){
            console.log(data);
        });
        $("#id06").css("display","block");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");*/
    }
    <?php if($alert=="true"){?>
    $(function(){
        alert("판매자가 배송정보를 입력하였습니다. 해당 거래는 5일 후 정오에 자동구매확정 됩니다. 문제가 있을 시에는 그 전에 판매자와 상의하시기 바랍니다.");
    });
    <?php }?>
</script>
<?php
include_once (G5_PATH."/tail.php");