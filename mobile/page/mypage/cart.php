<?php
include_once ("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

if(!$pd_type){
    $pd_type = 1;
}

if($group_id){
    $sql = "delete from `order_temp` where group_id = '{$group_id}'";
    sql_query($sql);
    $sql = "update `cart` set c_status = 1 where cid in ({$cart_id})";
    sql_query($sql);
}

if($pd_type==1){
    $where = ' and c.c_status != 10';
}else{
    $where = ' and c.c_status != 10';
}

$sql = "select *,p.mb_id as pd_mb_id from `cart` as c left join `product` as p on c.pd_id = p.pd_id where c.mb_id = '{$mb_id}' and p.pd_type = {$pd_type} {$where} order by c_status desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cart[] = $row;
    //if($row["c_status"]==1) {
        $total += (int)$row["c_price"];
        $all_pd_id[] = $row["pd_id"];
        $all_cart_id[] = $row["cid"];
        $all_pd_price[] = $row["c_price"];
    //}
}
if($all_pd_id) {
    $pd_ids = implode(",", $all_pd_id);
    $cart_ids = implode(",", $all_cart_id);
    $pd_prices = implode(",", $all_pd_price);
}

$back_url = G5_URL;

?>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>장바구니</h2>
</div>
<div class="mycart_tab">
    <ul>
        <li <?php if($pd_type==1){?>class="active"<?php }?> onclick="fnCartList(1);">물건</li>
        <li <?php if($pd_type==2){?>class="active"<?php }?> onclick="fnCartList(2);">능력</li>
    </ul>
</div>
<div class="alert_list" style="height:calc(100vh - 39vw);">
    <div class="list_con">
        <?php for($i=0;$i<count($cart);$i++){
            $mb = get_member($cart[$i]["pd_mb_id"]);
            if($cart[$i]["c_status"]==0 && $chk != true){
            ?>
            <p style="margin:2vw 2vw 2vw 2vw;">구매승인대기</p>
            <div style="width:calc(100% - 4vw);height:1px;background-color:#000;margin:2vw;border:1px inset #eee;opacity:0.5"></div>
            <?php $chk = true; } ?>
        <div class="alarm_item <?php if($cart[$i]["c_status"]==0){?>no_order<?php }else{?> noactive <?php }?>" id="item_<?php echo $cart[$i]["cid"];?>" <?php if($cart[$i]["c_status"]!=10 && $cart[$i]["c_status"]!=0){?>onclick="addOrder('<?php echo $cart[$i]["pd_id"];?>','<?php echo $cart[$i]["c_price"];?>','<?php echo $cart[$i]['cid'];?>');" <?php }?>>
            <?php if($cart[$i]["pd_images"]!=""){
                $img = explode(",",$cart[$i]["pd_images"]);
                $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
                if(is_file(G5_DATA_PATH."/product/".$img1)){
                    ?>
                        <?php if($img1!=""){?>
                    <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;">
                        <?php }else{ ?>
                    <div class="item_images" style="background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg');background-repeat:no-repeat;background-size:cover;background-position:center;">
                        <?php }?>
                    </div>
                <?php }else{
                    //$tags = explode("/",$cart[$i]["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" >
                        <div class="tags">
                            <?php //for($k=0;$k<count($tags);$k++){
                                $rand_font = rand(3,6);
                                ?>
                                <div class="rand_size<?php echo $rand_font;?>"><?php echo $cart[$i]["pd_tag"];?></div>
                            <?php //}?>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
            <?php }else{
                //$tags = explode("#",$cart[$i]["pd_tag"]);
                $rand = rand(1,13);
                ?>
                <div class="bg rand_bg<?php echo $rand;?> item_images" >
                    <div class="tags">
                        <?php //for($k=0;$k<count($tags);$k++){
                            $rand_font = rand(3,6);
                            ?>
                            <div class="rand_size<?php echo $rand_font;?>"><?php echo $cart[$i]["pd_tag"];?></div>
                        <?php //}?>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php }?>
            <div class="item_text cart">
                <?php if($cart[$i]["c_status"]==0){?>
                <div class="no_order">구매예약</div>
                <?php }?>
                <?php if($cart[$i]["c_status"]==1){
                    if($pd_type==1){
                    ?>
                    <div class="no_order">구매승인</div>
                <?php }else{?>
                    <div class="no_order">계약대기</div>
                <?php }?>
                <?php if($cart[$i]["c_status"]==2){?>
                <div class="no_order">구매취소</div>
                <?php }?>
                <?php }?>
                <?php if($cart[$i]["c_status"]==3){?>
                <div class="no_order">예약거절</div>
                <?php }?>
                <h2><?php echo $cart[$i]["pd_name"];?></h2>
                <p>판매자 : <?php echo $mb["mb_nick"];?> </p>
                <div>
                    <?php
                        if($pd_type==2){
                            if($cart[$i]["pd_price2"]){
                                echo number_format($cart[$i]["pd_price2"])." 원";
                            }else {
                                echo number_format($cart[$i]["c_price"]) . " 원";
                            }
                        }else{
                             echo number_format($cart[$i]["c_price"])." 원";
                        }
                    ?>
                </div>
            </div>
            <div class="clear"></div>
            <?php if($cart[$i]["c_status"]==3){?>
            <div class="controls">
                <div class="btn_controls">
                    <input type="button" value="삭제하기" onclick="fnCartDelItem('<?php echo $cart[$i]['cid'];?>')">
                </div>
            </div>
            <?php } ?>

        </div>
        <?php } ?>
        <?php if(count($cart)==0){?>
        <div class="no-list">
            <div>장바구니가 비었습니다.</div>
        </div>
        <?php }?>
    </div>
    <?php if($total > 0){?>
    <div class="cart_btns">
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/cart_update.php" name="cartAll" method="post">
            <input type="hidden" name="pd_ids" value="<?php echo $pd_ids;?>">
            <input type="hidden" name="pd_type" value="<?php echo $pd_type;?>">
            <input type="hidden" name="cart_ids" value="<?php echo $cart_ids;?>">
            <input type="hidden" name="prices"value="<?php echo $pd_prices;?>">
            <input type="hidden" name="total_price" value="<?php echo $total;?>">
            <input type="hidden" name="type" id="type1" value="insert">
        </form>
        <form action="<?php echo G5_MOBILE_URL?>/page/mypage/cart_update.php" method="post" name="cartform" >
            <input type="hidden" name="pd_ids" id="pd_ids" value="">
            <input type="hidden" name="pd_type" value="<?php echo $pd_type;?>">
            <input type="hidden" name="cart_ids" id="cart_ids" value="">
            <input type="hidden" name="prices" id="prices" value="">
            <input type="hidden" name="total_price" id="total_price" value="">
            <input type="hidden" name="type" id="type2" value="insert">
        <div class="cart_info">
            <div class="cart_div">
                <div class="left">총상품 금액</div>
                <div class="right"><?php echo number_format(0);?> 원</div>
            </div>
            <div class="cart_div">
                <div class="left">총 합계금액</div>
                <div class="right"><?php echo number_format(0);?> 원</div>
            </div>
            <div class="btns">
                <input type="button" value="선택 구매취소" onclick="fnCartDel()">
                <input type="button" value="선택 주문" onclick="fnCartOrder()">
                <input type="button" value="전체 주문" class="all" onclick="fnOrderAll();">
            </div>
        </div>
        </form>
    </div>
    <?php }?>
</div>
<script>
function fnCartList(pd_type){
    location.href=g5_url+"/mobile/page/mypage/cart.php?pd_type="+pd_type
}
function addOrder(pd_id,price,cid){
    var pd_ids = $("#pd_ids").val();
    var cart_ids = $("#cart_ids").val();
    var prices = $("#prices").val();
    if($("#item_"+cid).hasClass("active")) {
        // 빼기
        $("#item_"+cid).removeClass("active");
        $("#item_"+cid).addClass("noactive");
        var to_price = $(".cart_div:first-child .right").text();
        //to_price = to_price.replace(" 원", "");
        var tot_price = to_price.replace(/[^0-9]/g,"");
        var end_price = Number(tot_price) - Number(price);
        $("#total_price").val(end_price);
        end_price = end_price.numberFormat();
        $(".cart_div .right").html(end_price + " 원");
        if(pd_ids.indexOf(pd_id)!=-1){
            var chk1 = pd_ids.split(",");
            var chk2 = cart_ids.split(",");
            var chk3 = prices.split(",");
            var re_pd_ids="",re_cart_ids="",re_prices="";
            for(var i = 0 ;i<chk1.length; i++){
                if(chk1[i] != pd_id){
                    if(re_pd_ids=="") {
                        re_pd_ids = chk1[i];
                        re_cart_ids = chk2[i];
                        re_prices = chk3[i];
                    }else{
                        re_pd_ids = ","+chk1[i];
                        re_cart_ids = ","+chk2[i];
                        re_prices = ","+chk3[i];
                    }
                }
            }
            $("#pd_ids").val(re_pd_ids);
            $("#cart_ids").val(re_cart_ids);
            $("#prices").val(re_prices);
        }
    }else{
        //더하기
        $("#item_"+cid).addClass("active");
        $("#item_"+cid).removeClass("noactive");
        var to_price = $(".cart_div:first-child .right").text();
        var tot_price = to_price.replace(/[^0-9]/g,"");
        var end_price = Number(tot_price) + Number(price);
        $("#total_price").val(end_price);
        end_price = end_price.numberFormat();
        $(".cart_div .right").html(end_price + " 원");
        if(!pd_ids.indexOf(pd_id)!=-1){
            if(pd_ids==""){
                console.log(pd_ids);
                pd_ids = pd_id;
                cart_ids = cid;
                prices = price;
            }else{
                console.log(pd_ids);
                pd_ids += ","+pd_id;
                cart_ids += ","+cid;
                prices += ","+price;
            }
            $("#pd_ids").val(pd_ids);
            $("#cart_ids").val(cart_ids);
            $("#prices").val(prices);
        }
    }
}
function fnOrderAll(){
    document.cartAll.submit();
}

function fnCartOrder(){
    if($("#cart_ids").val()==""){
        alert("선택된 물건/능력이 없습니다.");
        return false;
    }
    document.cartform.submit();
}

function fnCartDel(){
    $("#type2").val("del");
    document.cartform.submit();
}

function fnCartDelItem(cid){
    $.ajax({
       url:g5_url+"/mobile/page/ajax/ajax.cart_delete.php",
        method:"POST",
        data:{cid:cid}
    }).done(function (data) {
        console.log(data);
        if(data=="1"){
            alert("삭제할 아이템이 없습니다.");
        }
        if(data=="3"){
            alert("삭제하지 못하였습니다.\r다시 시도해 주세요.");
        }
        if(data == "2"){
            $("#item_"+cid).remove();
        }
    });
}

// 숫자 타입에서 쓸 수 있도록 format() 함수 추가
Number.prototype.numberFormat = function(){
    if(this==0) return 0;

    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');

    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

    return n;
};
</script>
<?php
include_once (G5_PATH."/tail.php");