<?php
include_once("../../../common.php");

if($type1){
    $where = " and p.pd_type = '{$type1}'";
}

if($stx){
    $where .= " and p.pd_tag like '%{$stx}%'";
}

//기본 판매로 뜨게
if($od_cate==""||$od_cate==1) {
    $od_cate = 1;
    $where .= " and p.mb_id = '{$member["mb_id"]}'";
}else{
    $where .= " and c.mb_id = '{$member["mb_id"]}'";
}

$sql = "select *,c.mb_id as mb_id,p.pd_id as pd_id,p.mb_id as pd_mb_id,c.cid as cid from `cart` as c left join `product` as p on c.pd_id = p.pd_id left join `order` as o on c.cid = o.cid  where o.od_fin_status = 1  {$where} order by c.c_date desc, c.pd_id";
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
        $mb2 = get_member($cart[$i]["pd_mb_id"]);
        ?>
        <div class="alarm_item orders_pd_id_<?php echo $cart[$i]["pd_id"];?>" id="item_<?php echo $cart[$i]["cid"];?>" >
            <?php if($cart[$i]["pd_mb_id"]==$member["mb_id"]){?>
            <?php if($cart[$i]["od_direct_status"]==0){?>
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
            <?php }else{?>
                <div class="ordering">
                    <span>직거래 완료</span>
                </div>
            <?php }?>
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
                    <p><?php if($cart[$i]["pd_mb_id"]==$member["mb_id"]){?>구매요청자 : <?php echo ($mb["mb_nick"])?$mb["mb_nick"]:"알수없음";?><?php }else{?>판매자 : <?php echo ($mb2["mb_nick"])?$mb2["mb_nick"]:"알수없음";?><?php }?></p>
                    <div>
                        구매요청금액 : <?php echo number_format($cart[$i]["c_price"])." 원";?>
                    </div>
                </div>
                <div class="clear"></div>
                <?php if($cart[$i]["od_admin_status"]=="0"){?>
                <div class="controls pd_id_<?php echo $cart[$i]["pd_id"];?>">
                    <div class="btn_controls" >
                        <input type="button" value="문의하기" onclick="fnShow2('<?php echo $cart[$i]["mb_id"];?>','<?php echo $cart[$i]["pd_id"];?>')">
                        <input type="button" value="상세보기" onclick="location.href=g5_url+'/mobile/page/mypage/order_view.php?od_id=<?php echo $cart[$i]["od_id"];?>'">
                        <?php if($cart[$i]["pd_mb_id"]==$member["mb_id"]) {
                            if ($cart[$i]["od_direct_status"] != 1) {
                                ?>
                                <input type="button" value="정산요청" class="confirm" onclick="fnOrderPayConfirm('<?php echo $cart[$i]["od_id"]; ?>','<?php echo $cart[$i]['pd_id']; ?>');">
                            <?php }
                        }else {

                            if ($cart[$i]["pd_type"] == 1) {
                                $sql = "select count(*)as cnt from `product_like` where mb_id = '{$member["mb_id"]}' and pd_id ";
                                $cnt = sql_fetch($sql);
                                if($cnt["cnt"]==0){
                                ?>
                                <input type="button" value="평가하기" class="confirm" onclick="fnRank('<?php echo $cart[$i]["pd_id"];?>','<?php echo $cart[$i]["pd_type"];?>','<?php echo $cart[$i]["od_id"];?>')">
                                <?php }?>
                            <?php }else{?>
                                <input type="button" value="후기작성" class="confirm" onclick="">
                            <?php }
                                ?>
                        <?php }?>
                    </div>
                </div>
                <?php }?>
            </div>
            <?php } ?>
        </div>
    </div>

<?php }else {
    echo "no-list";
}
?>