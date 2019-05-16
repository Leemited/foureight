<?php
include_once("../../../common.php");

if($type1){
    $where = " and p.pd_type = '{$type1}'";
}

if($stx){
    $where .= " and p.pd_tag like '%{$stx}%'";
}


/*
$sql = "select *,c.mb_id as mb_id from `cart` as c left join `product` as p on c.pd_id = p.pd_id where p.mb_id = '{$member["mb_id"]}' and c_status != 2 and c_status != 3 and c_status!=10 order by c.c_date desc, c.pd_id";
$res =sql_query($sql);
$type1Count = 0;
$type2Count = 0;

while($row = sql_fetch_array($res)){
    if($row["pd_type"]==1){
        $type1Count++;
    }else{
        $type2Count++;
    }
}
*/
$sql = "select *,c.mb_id as mb_id,p.pd_id as pd_id,p.mb_id as pd_mb_id,c.cid as cid from `cart` as c left join `product` as p on c.pd_id = p.pd_id left join `order` as o on c.cid = o.cid  where (p.mb_id = '{$member["mb_id"]}' or c.mb_id = '{$member["mb_id"]}') and c_status != -1  {$where} order by c.c_date desc, c.pd_id";
$res =sql_query($sql);

while($row = sql_fetch_array($res)){
    $cart[] = $row;
}

if(count($cart) > 0){
?>
<input type="hidden" id="listCount1" value="<?php echo $type1Count;?>">
<input type="hidden" id="listCount2" value="<?php echo $type2Count;?>">
<div class="my_order_list" >
    <div class="list_con">
        <?php for($i=0;$i<count($cart);$i++){
            $mb = get_member($cart[$i]["mb_id"]);
            //대화방 있는지 찾기
            $sql = "select * from `product_chat` where send_mb_id = '{$cart[$i]["mb_id"]}' or send_mb_id = '{$cart[$i]["pd_mb_id"]}' limit 0, 1";
            $chat = sql_fetch($sql);
            $roomids = $chat["room_id"];
                ?>
            <div class="alarm_item orders_pd_id_<?php echo $cart[$i]["pd_id"];?>" id="item_<?php echo $cart[$i]["cid"];?>" >
                <?php if($cart[$i]["c_status"]==1){?>
                    <div class="ordering">
                        <span>결제대기중</span>
                    </div>
                <?php }?>
                <?php if($cart[$i]["od_status"]==1 && $cart[$i]["od_pay_status"]==1){?>
                    <div class="ordering">
                        <span>결제완료/배송대기중</span>
                    </div>
                <?php }?>
                <?php if($cart[$i]["c_status"]==3){?>
                    <div class="ordering">
                        <span>계약중</span>
                    </div>
                <?php }?>
                <?php if($cart[$i]["pd_images"]!=""){
                    $img = explode(",",$cart[$i]["pd_images"]);
                    $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
                    if(is_file(G5_DATA_PATH."/product/".$img1)){
                        ?>
                        <?php if($img1!=""){?>
                        <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;" onclick="fn_viewer('<?php echo $cart[$i]["pd_id"];?>');">
                            <?php }else{ ?>
                        <div class="item_images" style="background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg');background-repeat:no-repeat;background-size:cover;background-position:center;" onclick="fn_viewer('<?php echo $cart[$i]["pd_id"];?>');">
                            <?php }?>
                        </div>
                    <?php }else{
                        $tags = explode("/",$cart[$i]["pd_tag"]);
                        $rand = rand(1,13);
                        ?>
                        <div class="bg rand_bg<?php echo $rand;?> item_images" onclick="fn_viewer('<?php echo $cart[$i]["pd_id"];?>');">
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
                    $tags = explode("#",$cart[$i]["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" onclick="fn_viewer('<?php echo $cart[$i]["pd_id"];?>');">
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
                    <h2><?php echo $cart[$i]["pd_name"];?></h2>
                    <p>구매요청자 : <?php echo ($mb["mb_nick"])?$mb["mb_nick"]:"알수없음";?></p>
                    <div>
                        구매요청금액 : <?php echo number_format($cart[$i]["c_price"])." 원";?>
                    </div>
                </div>
                <div class="clear"></div>
                <?php if($cart[$i]["c_status"] == 0 && $member["mb_id"]==$cart[$i]["pd_mb_id"]){?>
                <div class="controls pd_id_<?php echo $cart[$i]["pd_id"];?>">
                    <div class="btn_controls" >
                        <input type="button" value="취소" onclick="fnOrderCancel('<?php echo $cart[$i]["cid"];?>','<?php echo $i;?>');">
                        <input type="button" value="연락하기" onclick="fnShow2('<?php echo $cart[$i]["mb_id"];?>','<?php echo $cart[$i]["pd_id"];?>','<?php echo $roomids;?>')">
                        <input type="button" value="승인" class="confirm" onclick="fnOrderConfirm('<?php echo $cart[$i]["cid"];?>','<?php echo $cart[$i]['pd_id'];?>');">
                    </div>
                </div>
                <?php }?>
                <?php if($cart[$i]["od_status"] == 1 && $cart[$i]["od_pay_status"] == 1 && $member["mb_id"]==$cart[$i]["pd_mb_id"]){?>
                    <div class="controls pd_id_<?php echo $cart[$i]["pd_id"];?>">
                        <div class="btn_controls" >
                            <input type="button" value="연락하기" onclick="fnShow2('<?php echo $cart[$i]["mb_id"];?>','<?php echo $cart[$i]["pd_id"];?>','<?php echo $roomids;?>')">
                            <input type="button" value="배송정보입력" class="confirm" onclick="fnDeli('<?php echo $cart[$i]["od_id"];?>')">
                        </div>
                    </div>
                <?php }?>
                <?php if($cart[$i]["od_status"] == 1 && $cart[$i]["od_pay_status"] == 1 && $member["mb_id"]!=$cart[$i]["pd_mb_id"]){?>
                    <div class="controls pd_id_<?php echo $cart[$i]["pd_id"];?>">
                        <div class="btn_controls" >
                            <input type="button" value="연락하기" onclick="fnShow2('<?php echo $cart[$i]["mb_id"];?>','<?php echo $cart[$i]["pd_id"];?>','<?php echo $roomids;?>')">
                            <input type="button" value="한번 더 배송 요청" class="confirm" onclick="sendPush('<?php echo $cart[$i]["pd_mb_id"];?>','배송 요청입니다.','<?php echo $cart[$i]["od_id"];?>','<?php echo $cart[$i]["pd_id"];?>')">
                        </div>
                    </div>
                <?php }?>
                <?php if($cart[$i]["c_status"] == 1 && $member["mb_id"]!=$cart[$i]["pd_mb_id"]){?>
                <div class="controls pd_id_<?php echo $cart[$i]["pd_id"];?>">
                    <div class="btn_controls" >
                        <!--<input type="button" value="취소" onclick="fnOrderCancel('<?php /*echo $cart[$i]["cid"];*/?>','<?php /*echo $i;*/?>');">-->
                        <input type="button" value="연락하기" onclick="fnShow2('<?php echo $cart[$i]["pd_mb_id"];?>','<?php echo $cart[$i]["pd_id"];?>','<?php echo $roomids;?>')">
                        <input type="button" value="결제하기" class="confirm" onclick="fnMypageOrder('<?php echo $cart[$i]["cid"];?>','<?php echo $cart[$i]['pd_id'];?>','<?php echo $cart[$i]["pd_type"];?>','<?php echo $cart[$i]["c_price"];?>','insert');">
                    </div>
                </div>
                <?php }?>
                <?php if($cart[$i]["c_status"] == 0 && $member["mb_id"]!=$cart[$i]["pd_mb_id"]){?>
                    <div class="controls pd_id_<?php echo $cart[$i]["pd_id"];?>">
                        <div class="btn_controls" >
                            <!--<input type="button" value="취소" onclick="fnOrderCancel('<?php /*echo $cart[$i]["cid"];*/?>','<?php /*echo $i;*/?>');">-->
                            <input type="button" value="연락하기" onclick="fnShow2('<?php echo $cart[$i]["pd_mb_id"];?>','<?php echo $cart[$i]["pd_id"];?>','<?php echo $roomids;?>')">
                        </div>
                    </div>
                <?php }?>
            </div>
        <?php } ?>
    </div>
</div>
    <script>
        var od_id = '';
        function fnMypageOrder(cid,pd_id,pd_type,price,type){
            location.href=g5_url+'/mobile/page/mypage/cart_update.php?pd_ids='+pd_id+'&cart_ids='+cid+'&pd_type='+pd_type+'&total_price='+price+'&prices='+price+'&type='+type;
        }
        function fnDeli(od_id){
            $("#deli_od_id").val(od_id);
            $("#id00").css("display","block");
            $("html,body").css("height","100vh");
            $("html,body").css("overflow","hidden");
            location.hash = "modal";
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
    </script>
    <?php }else {
        echo "no-list//".$type1Count."//".$type2Count;
    }
?>