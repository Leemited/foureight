<?php
include_once("../../../common.php");

if($type1){
    $where = " and p.pd_type = '{$type1}'";
    /*if($type1==1){
        $where .= " and p.pd_status = 10 ";
    }*/
}

if($stx){
    $where .= " and p.pd_tag like '%{$stx}%' ";
}

//기본 판매로 뜨게
if($od_cate==""||$od_cate==1) {
    $od_cate = 1;
    $where .= " and p.mb_id = '{$member["mb_id"]}' and od_del_status2 = 0 ";
}else{
    $where .= " and o.mb_id = '{$member["mb_id"]}' and od_del_status1 = 0 ";
}
$sql = "select *,o.mb_id as mb_id,p.mb_id as pd_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id left join `g5_member` as m on m.mb_id = p.mb_id where od_fin_status = 1 {$where} order by od_date desc";
$res =sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}
if(count($list) > 0){
    ?>
    <input type="hidden" id="listCount1" value="<?php echo $type1Count;?>">
    <input type="hidden" id="listCount2" value="<?php echo $type2Count;?>">
    <div class="my_order_list" >
    <div class="list_con">
        <?php for($i=0;$i<count($list);$i++){
            $mb = get_member($list[$i]["mb_id"]);
            $mb2 = get_member($list[$i]["pd_mb_id"]);
            //판매자 설정
            $mb2Set = sql_fetch("select * from `mysetting` where mb_id = '{$mb2["mb_id"]}'");
        ?>
        <div class="alarm_item orders_pd_id_<?php echo $list[$i]["pd_id"];?>" id="item_<?php echo $list[$i]["cid"];?>" >
            <?php if($list[$i]["pd_mb_id"]==$member["mb_id"]){?>
                <?php if($list[$i]["od_direct_status"]==0){?>
                    <?php if($list[$i]["od_price"]==0){?>
                        <div class="ordering">
                            <span>무료나눔</span>
                        </div>
                    <?php }else{?>
                        <?php if($list[$i]["od_admin_status"]==2){?>
                            <div class="ordering">
                                <span>정산 완료</span>
                            </div>
                        <?php }?>
                        <?php if($list[$i]["od_admin_status"]==0){?>
                            <div class="ordering">
                                <span>정산 대기중</span>
                            </div>
                        <?php }?>
                    <?php }?>
                <?php }else{?>
                    <div class="ordering">
                        <span>직거래 완료</span>
                    </div>
                <?php }?>
                <?php }else{?>
                    <?php if($list[$i]["pd_type"]==1){?>
                    <div class="ordering">
                        <span>구매 완료</span>
                    </div>
                    <?php }else{?>
                    <div class="ordering">
                        <span>거래 완료</span>
                    </div>
                    <?php }?>
                <?php }?>
            <?php if($list[$i]["pd_images"]!=""){
            $img = explode(",",$list[$i]["pd_images"]);
            $img1 = get_images(G5_DATA_PATH."/product/".$img[0],400,'');
            if(is_file(G5_DATA_PATH."/product/".$img1)){
                if($img1!=""){?>
            <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;" onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>');">
                <?php }else{ ?>
                <div class="item_images" style="background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg');background-repeat:no-repeat;background-size:cover;background-position:center;" onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>');">
                    <?php }?>
                </div>
                <?php }else{
                    $tags = explode("/",$list[$i]["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>');">
                        <div class="tags">
                            <?php //for($k=0;$k<count($tags);$k++){
                            $rand_font = rand(3,6);
                            ?>
                            <div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
                            <?php //}?>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
                <?php }else{
                    $tags = explode("#",$list[$i]["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>');">
                        <div class="tags">
                            <?php //for($k=0;$k<count($tags);$k++){
                            $rand_font = rand(3,6);
                            ?>
                            <div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
                            <?php //}?>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
                <div class="item_text cart">
                    <h2><?php echo $list[$i]["pd_name"]."|".$list[$i]["od_id"];?></h2>
                    <p><?php if($list[$i]["pd_mb_id"]==$member["mb_id"]){?>구매요청자 : <?php echo ($mb["mb_nick"])?$mb["mb_nick"]:"알수없음";?><?php }else{?>판매자 : <?php echo ($mb2["mb_nick"])?$mb2["mb_nick"]:"알수없음";?><?php }?></p>
                    <div>
                        <?php if($list[$i]["pd_price"]==0){?>
                            무료나눔
                        <?php }else{?>
                        구매완료금액 : <?php echo number_format(($list[$i]["od_price"]+$list[$i]["od_price2"]))." 원";?>
                        <?php }?>
                    </div>
                </div>
                <div class="clear"></div>
                <?php if($list[$i]["od_admin_status"]=="0"){?>
                <div class="controls pd_id_<?php echo $list[$i]["pd_id"];?>">
                    <div class="btn_controls" >
                        <input type="button" value="문의하기" onclick="fnShow2('<?php echo $list[$i]["mb_id"];?>','<?php echo $list[$i]["pd_id"];?>','order_fin','<?php echo $list[$i]["pd_type"];?>')">
                        <input type="button" value="상세보기" onclick="location.href=g5_url+'/mobile/page/mypage/order_view.php?od_id=<?php echo $list[$i]["od_id"];?>&back_url=<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order_complete.php?od_cate=<?php if($member["mb_id"]==$list[$i]["pd_mb_id"]){?>1<?php }else{?>2<?php }?>'">
                        <?php if($list[$i]["pd_mb_id"]==$member["mb_id"]) {
                            if ($list[$i]["pay_oid"]) {
                                ?>
                                <!--<input type="button" value="정산요청" class="confirm" onclick="fnOrderPayConfirm('<?php /*echo $list[$i]["od_id"]; */?>','<?php /*echo $list[$i]['pd_id']; */?>');">-->
                            <?php }
                        }else {

                            if ($list[$i]["pd_type"] == 1) {
                                if($mb2Set["like_set"]==1){
                                    $sql = "select count(*)as cnt from `product_like` where mb_id = '{$member["mb_id"]}' and pd_id = '{$list[$i]["pd_id"]}' and od_id = '{$list[$i]["od_id"]}'";
                                    $cnt = sql_fetch($sql);
                                    if($cnt["cnt"]==0){
                                    ?>
                                    <!--<input type="button" value="평가하기" class="confirm" id="item_<?php /*echo $list[$i]["od_id"];*/?>" onclick="fnRank('<?php /*echo $list[$i]["pd_id"];*/?>','<?php /*echo $list[$i]["pd_type"];*/?>','<?php /*echo $list[$i]["od_id"];*/?>')">-->
                                    <input type="button" value="평가하기" class="confirm" id="item_<?php echo $list[$i]["od_id"];?>" onclick="fnOrderfin('<?php echo $list[$i]["od_id"];?>','<?php echo $list[$i]["pd_id"];?>','<?php echo $list[$i]["pd_type"];?>','<?php echo $list[$i]["pd_mb_id"];?>');">
                                    <?php }?>
                                <?php }?>
                            <?php }else {?>
                                <input type="button" value="재구매" class="confirm" onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>')">
                                <?php if ($mb2Set["like_set"] == 1) {
                                    $sql = "select count(*)as cnt from `product_like` where mb_id = '{$member["mb_id"]}' and od_id = '{$list[$i]["od_id"]}' and pd_id = '{$list[$i]["pd_id"]}'";
                                    $cnt = sql_fetch($sql);
                                    if($cnt["cnt"]==0){
                                    ?>
                                    <input type="button" value="후기작성" class="confirm" id="item_<?php echo $list[$i]["od_id"];?>" onclick="fnOrderfin2('<?php echo $list[$i]["od_id"];?>','<?php echo $list[$i]["pd_id"];?>','<?php echo $list[$i]["pd_type"];?>');">
                            <?php   }
                                    }
                                }
                            ?>
                        <?php }?>
                        <input type="button" value="목록삭제" class="confirm2" onclick="fnDeleteOrderList('<?php echo $list[$i]["od_id"];?>')">
                    </div>
                </div>
                <?php }?>
            </div>
            <?php } ?>
        </div>
    </div>
    <script>
        function fnDeleteOrderList(od_id){
            if(od_id==""){
                alert("삭제할 목록을 선택해주세요.");
                return false;
            }
            if(confirm('해당 거래완료를 목록에서 삭제 하시겠습니까?')){
                location.href=g5_url+'/mobile/page/mypage/mypage.order_complete_delete.php?od_id='+od_id;
            }
        }
    </script>
<?php }else {
    echo "no-list";
}
?>