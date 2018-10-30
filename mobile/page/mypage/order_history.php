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

$sql = "select *,p.mb_id as pd_mb_id from `order` as c left join `product` as p on c.pd_id = p.pd_id where c.mb_id = '{$mb_id}' and c.od_status = 1 and c.od_pay_status = 1 order by od_date desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cart[] = $row;
    if($row["c_status"]==1) {
        $total += (int)$row["c_price"];
        $all_pd_id[] = $row["pd_id"];
        $all_cart_id[] = $row["cid"];
        $all_pd_price[] = $row["c_price"];
    }
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
    <h2>거래내역</h2>
</div>
<div class="mycart_tab">
    <ul>
        <li class="active">거래내역관리</li>
        <li onclick="location.href=g5_url+'/mobile/page/mypage/order_history_bank.php'">계좌등록/변경</li>
    </ul>
</div>
<div class="alert_list">
    <div class="serch_box">
        <div>

        </div>
    </div>
    <div class="list_con">
        <?php for($i=0;$i<count($cart);$i++){
            $mb = get_member($cart["pd_mb_id"]);

                ?>
            <div class="alarm_item" id="item_<?php echo $cart[$i]["pd_id"];?>">
                <?php if($cart[$i]["pd_images"]!=""){
                    $img = explode(",",$cart[$i]["pd_images"]);
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
                        $tags = explode("/",$cart[$i]["pd_tag"]);
                        $rand = rand(1,13);
                        ?>
                        <div class="bg rand_bg<?php echo $rand;?> item_images" >
                            <div class="tags">
                                <?php for($k=0;$k<count($tags);$k++){
                                    $rand_font = rand(3,6);
                                    ?>
                                    <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                <?php }?>
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
                            <?php for($k=0;$k<count($tags);$k++){
                                $rand_font = rand(3,6);
                                ?>
                                <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                            <?php }?>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
                <div class="item_text cart">
<!--                    <?php /*if($cart[$i]["od_status"]==1 && $cart[$i]["od_step"]==0){*/?>
                        <div class="no_order">구매종료</div>
                    <?php /*}*/?>
                    <?php /*if($cart[$i]["od_status"]==1 && $cart[$i]["od_step"]==1){*/?>
                        <div class="no_order">계약완료</div>
                    --><?php /*}*/?>
                    <h2><?php echo $cart[$i]["pd_name"];?></h2>
                    <p>판매자 : <?php echo $mb["mb_nick"];?> </p>
                    <div>
                        <?php echo number_format($cart[$i]["od_price"])." 원";?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>
        <?php if(count($cart)==0){?>
            <div class="no-list">
                <div>주문내역이 비었습니다.</div>
            </div>
        <?php }?>
    </div>
</div>
<script>

</script>
<?php
include_once (G5_PATH."/tail.php");