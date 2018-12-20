<?php
include_once("../../../common.php");

$sql = "select *,c.mb_id as mb_id from `cart` as c left join `product` as p on c.pd_id = p.pd_id where p.mb_id = '{$member["mb_id"]}' and c_status = 1 and p.pd_type = {$type1} order by c.c_date desc, c.pd_id";
$res =sql_query($sql);
while($row = sql_fetch_array($res)){
    $cart[] = $row;
}

?>
<div class="my_order_list" >
    <div class="list_con">
        <?php for($i=0;$i<count($cart);$i++){
            $mb = get_member($cart[$i]["mb_id"]);
                ?>
            <div class="alarm_item" id="item_<?php echo $cart[$i]["pd_id"];?>" onclick="fn_viewer('<?php echo $cart[$i]["pd_id"];?>');">
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
                        $tags = explode("/",$cart[$i]["pd_tag"]);
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
                    $tags = explode("#",$cart[$i]["pd_tag"]);
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
                    <h2><?php echo $cart[$i]["pd_name"];?></h2>
                    <p>구매요청자 : <?php echo ($mb["mb_nick"])?$mb["mb_nick"]:"알수없음";?></p>
                    <div>
                        구매요청금액 : <?php echo number_format($cart[$i]["c_price"])." 원";?>
                    </div>
                </div>
                <div class="clear"></div>
              <!--  <div class="controls" >
                    <div class="btn_controls" >
                        <input type="button" value="예약취소">
                        <input type="button" value="예약승인" class="confirm">
                    </div>
                </div>-->
            </div>
        <?php } ?>
        <?php if(count($cart)==0){?>
            <div class="no-list">
                <div>장바구니가 비었습니다.</div>
            </div>
        <?php }?>
    </div>
</div>