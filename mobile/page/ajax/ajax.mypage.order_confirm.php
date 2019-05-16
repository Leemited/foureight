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
$sql = "select *,o.mb_id as mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where p.mb_id = '{$member["mb_id"]}' and od_status = 1 and od_pay_status = 1 and od_step = 2 and od_fin_datetime <> ''  {$where} order by od_date desc, o.pd_id";
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
        ?>
        <div class="alarm_item orders_pd_id_<?php echo $cart[$i]["pd_id"];?>" id="item_<?php echo $cart[$i]["cid"];?>" >
            <?php if($cart[$i]["admin_status"]==1){?>
                <div class="ordering">
                    <span>정산 완료</span>
                </div>
            <?php }?>
            <?php if($cart[$i]["admin_status"]==0){?>
                <div class="ordering">
                    <span>정산 대기중</span>
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
                <?php if($cart[$i]["od_admin_status"]=="0"){?>
                <div class="controls pd_id_<?php echo $cart[$i]["pd_id"];?>">
                    <div class="btn_controls" >
                        <input type="button" value="관리자 문의" onclick="fnShow2('<?php echo $cart[$i]["mb_id"];?>','<?php echo $cart[$i]["pd_id"];?>')">
                        <input type="button" value="정산 요청" class="confirm" onclick="fnOrderConfirm('<?php echo $cart[$i]["cid"];?>','<?php echo $cart[$i]['pd_id'];?>');">
                    </div>
                </div>
                <?php }?>
            </div>
            <?php } ?>
        </div>
    </div>

<?php }else {
    echo "no-list//".$type1Count."//".$type2Count;
}
?>