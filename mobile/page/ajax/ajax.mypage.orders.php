<?php
include_once("../../../common.php");

$where = " od_fin_status = 0 and od_cancel_status < 2";

if($type1){
    $where .= " and p.pd_type = '{$type1}'";
}
if($stx){
    $where .= " and p.pd_tag like '%{$stx}%'";
}
//기본 판매로 뜨게
if($od_cate==""||$od_cate==1) {
    $od_cate = 1;
    $where .= " and p.mb_id = '{$member["mb_id"]}'";
}else{
    $where .= " and o.mb_id = '{$member["mb_id"]}'";
}

$sql = "select *,o.mb_id as mb_id,p.pd_id as pd_id,p.mb_id as pd_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where {$where} order by od_reser_date desc, od_reser_date desc";
$res =sql_query($sql);
while($row = sql_fetch_array($res)){
    if($row["pd_status"] < 10) {
        $order[] = $row;
    }
}
?>
<?php
if(count($order) > 0){
?>
<input type="hidden" id="listCount1" value="<?php echo $type1Count;?>">
<input type="hidden" id="listCount2" value="<?php echo $type2Count;?>">
<div class="my_order_list" >
    <div class="list_con">
        <?php for($i=0;$i<count($order);$i++){
            if($od_cate==1||$od_cate=="") {
                $mb = get_member($order[$i]["mb_id"]);
            }else{
                $mb = get_member($order[$i]["pd_mb_id"]);
            }
            //대화방 있는지 찾기
            $sql = "select * from `product_chat` where send_mb_id = '{$order[$i]["mb_id"]}' or send_mb_id = '{$order[$i]["pd_mb_id"]}' limit 0, 1";
            $chat = sql_fetch($sql);
            $roomids = $chat["room_id"];
            switch ($order[$i]["delivery_name"]){
                case "":
                    $search_url = "";
                    break;
            }
                ?>
            <div class="alarm_item orders_pd_id_<?php echo $order[$i]["pd_id"];?>" id="item_<?php echo $order[$i]["od_id"];?>" >
                <?php if($order[$i]["od_pd_type"]==1){?>
                    <?php if($order[$i]["od_status"]==0){?>
                        <div class="ordering">
                            <span>구매예약요청</span>
                        </div>
                    <?php }else if($order[$i]["od_status"]==1){?>
                        <?php if($order[$i]["od_direct_status"]==1 && $order[$i]["od_pay_status"]==0){?>
                            <div class="ordering">
                                <span>직거래요청</span>
                            </div>    
                        <?php }?>
                        <?php if($order[$i]["od_direct_status"]==2  && $order[$i]["od_pay_status"]==1){?>
                            <div class="ordering">
                                <span>직거래중</span>
                            </div>
                        <?php }?>
                        <?php if($order[$i]["od_pay_status"]=="1" && $order[$i]["od_direct_status"] == 0){?>
                            <?php if($order[$i]["delivery_name"]==""){?>
                                <?php if($order[$i]["od_cancel_status"]==1){?>
                                    <div class="ordering">
                                        <span>취소 요청</span>
                                    </div>
                                <?php }else{?>
                                    <div class="ordering">
                                        <span>배송준비중</span>
                                    </div>
                                <?php }?>
                            <?php }?>
                            <?php if($order[$i]["delivery_name"]!=""){?>
                                <?php if($order[$i]["od_refund_status"]==1){?>
                                    <div class="ordering">
                                        <span>환불요청중</span>
                                    </div>
                                <?php }else if($order[$i]["od_refund_status"]==2){?>
                                    <div class="ordering">
                                        <span>환불중</span>
                                    </div>
                                <?php }else{?>
                                    <div class="ordering">
                                        <span>배송중</span>
                                    </div>
                                <?php }?>
                            <?php }?>
                        <?php }?>
                    <?php }?>
                <?php }else{?>
                    <?php if($order[$i]["od_status"]==0){?>
                        <div class="ordering">
                            <span>예약요청</span>
                        </div>
                    <?php }else if($order[$i]["od_status"]==1){?>
                        <?php if($order[$i]["od_pay_status"]==0){?>

                            <?php if($order[$i]["od_direct_status"]==0){?>
                                <div class="ordering">
                                    <span>결제대기중</span>
                                </div>
                            <?php }else if($order[$i]["od_direct_status"]==1){?>
                                <div class="ordering">
                                    <span>직거래요청</span>
                                </div>
                            <?php }else{ ?>
                                <div class="ordering">
                                    <span>결제대기중</span>
                                </div>
                            <?php } ?>
                        <?php }else if($order[$i]["od_pay_status"]==1){?>
                            <?php if($order[$i]["pd_price"]==0){?>
                                <div class="ordering">
                                    <span>계약중</span>
                                </div>
                            <?php }else{?>
                                <?php if($order[$i]["od_direct_status"]==2){?>
                                    <div class="ordering">
                                        <span>직거래중</span>
                                    </div>
                                <?php }?>
                                <?php if($order[$i]["od_direct_status"]!=2){?>
                                    <?php if($order[$i]["od_cancel_status"]==1){?>
                                        <div class="ordering">
                                            <span>취소요청중</span>
                                        </div>
                                    <?php }else{?>
                                        <?php if($order[$i]["pd_delivery_use"]==0){//배송없음?>
                                            <?php if($order[$i]["pay_oid"] && $order[$i]["od_step"]==1){//결제함?>
                                                <?php if($order[$i]["od_refund_status"]!=1){?>
                                                    <div class="ordering">
                                                        <span>계약중</span>
                                                    </div>
                                                <?php }else if($order[$i]["od_refund_status"]==1){?>
                                                    <div class="ordering">
                                                        <span>환불요청중</span>
                                                    </div>
                                                <?php }?>
                                            <?php }?>
                                        <?php }else if($order[$i]["pd_delivery_use"]==1){//있음?>
                                            <?php if($order[$i]["delivery_name"]==""){?>
                                                <div class="ordering">
                                                    <span>배송대기중</span>
                                                </div>
                                            <?php }else{?>
                                                <?php if($order[$i]["od_fin_pay_status"]==1){?>
                                                    <div class="ordering">
                                                        <span>잔금요청중</span>
                                                    </div>
                                                <?php }else if($order[$i]["od_fin_pay_status"]==0){
                                                    if($order[$i]["od_refund_status"]==2){?>
                                                        <div class="ordering">
                                                            <span>환불처리중</span>
                                                        </div>
                                                    <?php }else if($order[$i]["od_refund_status"]==1){?>
                                                        <div class="ordering">
                                                            <span>환불요청중</span>
                                                        </div>
                                                    <?php }else if($order[$i]["od_refund_status"]==-1){?>
                                                        <div class="ordering">
                                                            <span>배송중</span>
                                                        </div>
                                                    <?php }?>
                                                <?php }else if($order[$i]["od_fin_pay_status"]==2){?>
                                                    <?php if($order[$i]["od_step"]==1){?>
                                                        <div class="ordering">
                                                            <span>결제진행중</span>
                                                        </div>
                                                    <?php }else if($order[$i]["od_step"]==2){?>
                                                        <div class="ordering">
                                                            <span>구매완료처리중</span>
                                                        </div>
                                                    <?php }?>
                                                <?php }?>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        <?php }?>
                    <?php }?>
                <?php }?>
                <?php                ?>
                <?php if($order[$i]["pd_images"]!=""){
                    $img = explode(",",$order[$i]["pd_images"]);
                    $img1 = get_images(G5_DATA_PATH."/product/".$img[0],400,'');
                    if(is_file(G5_DATA_PATH."/product/".$img1)){
                        ?>
                        <?php if($img1!=""){?>
                        <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;" onclick="fn_viewer('<?php echo $order[$i]["pd_id"];?>');">
                            <?php }else{ ?>
                        <div class="item_images" style="background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg');background-repeat:no-repeat;background-size:cover;background-position:center;" onclick="fn_viewer('<?php echo $order[$i]["pd_id"];?>');">
                            <?php }?>
                        </div>
                    <?php }else{
                        $tags = explode("/",$order[$i]["pd_tag"]);
                        $rand = rand(1,13);
                        ?>
                        <div class="bg rand_bg<?php echo $rand;?> item_images" onclick="fn_viewer('<?php echo $order[$i]["pd_id"];?>');">
                            <div class="tags">
                                <?php //for($k=0;$k<count($tags);$k++){
                                    $rand_font = rand(3,6);
                                    ?>
                                    <div class="rand_size<?php echo $rand_font;?>"><?php echo $order[$i]["pd_tag"];?></div>
                                <?php //}?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php }?>
                <?php }else{
                    $tags = explode("#",$order[$i]["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" onclick="fn_viewer('<?php echo $order[$i]["pd_id"];?>');">
                        <div class="tags">
                            <?php //for($k=0;$k<count($tags);$k++){
                                $rand_font = rand(3,6);
                                ?>
                                <div class="rand_size<?php echo $rand_font;?>"><?php echo $order[$i]["pd_tag"];?></div>
                            <?php //}?>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
                <div class="item_text cart">
                    <h2><?php echo $order[$i]["pd_name"];?></h2>
                    <p><?php if($od_cate==""||$od_cate=="1"){?>구매요청자 : <?php echo ($mb["mb_nick"])?$mb["mb_nick"]:"알수없음";?><?php }else{?>판매자 : <?php echo ($mb["mb_nick"])?$mb["mb_nick"]:"알수없음";?><?php }?> </p>
                    <div>
                        <?php if($order[$i]["pd_type"]==1){?>
                            <?php if($order[$i]["od_price"]!=0){?>
                                결제금액 : <?php echo $order[$i]["od_price"];?> 원
                            <?php }else{?>
                                무료나눔
                            <?php }?>
                        <?php }else if($order[$i]["pd_type"]==2){?>
                            <?php if($order[$i]["od_price2"]!=0){//계약금있음?>
                                <?php if($order[$i]["od_step"]==0){?>
                                    계약금 : <?php echo number_format($order[$i]["od_price2"]);?> 원
                                <?php }else if($order[$i]["od_step"]==1){?>
                                    거래완료예상금 : <?php echo number_format($order[$i]["od_price"]);?> 원
                                <?php }?>
                            <?php }else{//계약금 없음?>
                                <?php if($order[$i]["od_price"]!=0){?>
                                    결제금액 : <?php echo number_format($order[$i]["od_price"]);?> 원
                                <?php }else{?>
                                    무료나눔
                                <?php }?>
                            <?php }?>
                        <?php }?>
                    </div>
                </div>
                <div class="clear"></div>
                <?php //if($order[$i]["c_status"]==0){ ?>
                <?php if($order[$i]["pd_type"]==1){?>
                <div class="controls pd_id_<?php echo $order[$i]["od_id"];?>">
                    <div class="btn_controls" >
                        <input type="button" value="연락하기" onclick="fnShow2('<?php echo $mb["mb_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $roomids;?>','mypage','<?php echo $order[$i]["od_pd_type"];?>')">
                        <?php if($member["mb_id"]==$order[$i]["pd_mb_id"]){//판매자 ?>
                            <?php if($order[$i]["od_status"] == 0 ){?><!--최초 구매요청-->
                                <input type="button" value="승인" class="confirm" onclick="fnOrderConfirm('<?php echo $order[$i]['od_id'];?>',1);">
                                <input type="button" value="취소" class="confirm2" onclick="fnOrderCancel('<?php echo $order[$i]["od_id"];?>',1);">
                            <?php }?>
                            <?php if($order[$i]["od_status"] == 1 && $order[$i]["od_pay_status"] == 1 && $order[$i]["delivery_name"]==""){?><!--결제 완료 // 배송입력전-->
                                <?php if($order[$i]["od_direct_status"]!=2 && $order[$i]["od_fin_status"]==0){?>
                                    <!-- 직거래 중 -->
                                    <!-- 결제 완료중 -->

                                    <?php if($order[$i]["delivery_name"]==""){?>
                                        <?php if($order[$i]["od_cancel_status"]==1){?>
                                            <input type="button" value="결제취소승인" class="confirm" onclick="fnOrderRefundConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pay_oid"];?>',0)">
                                            <input type="button" value="결제취소거절" class="confirm2" onclick="fnOrderRefundCancel('<?php echo $order[$i]["od_id"];?>')">
                                        <?php }else{?>
                                            <input type="button" value="배송정보입력" class="confirm" onclick="fnDeli('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_type"];?>')">
                                            <?php if($order[$i]["delivery_extend"]==0){?>
                                                <input type="button" value="배송정보연장" class="confirm deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','0')">
                                            <?php }?>
                                            <input type="button" value="거래취소" class="confirm2" onclick="fnOrderRefundConfirm2('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pay_oid"];?>',0);">
                                        <?php }?>
                                    <?php }else{?>
                                        <input type="button" value="배송정보수정" class="confirm" onclick="fnDeli('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_type"];?>')">
                                        <input type="button" value="거래완료 요청" class="confirm" onclick="fnDirectConfirm('<?php echo $order[$i]["od_id"];?>')">
                                    <?php }?>
                                <?php }else if($order[$i]["od_direct_status"]==2 && $order[$i]["od_fin_status"]==0){?>
                                    <input type="button" value="직거래취소" class="confirm2" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=3&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 판매자가 취소-->
                                <?php }?>
                                <!--<input type="button" value="상세보기" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/order_view.php?od_id=<?php /*echo $order[$i]["od_id"];*/?>'">-->
                            <?php }?>
                            <?php if($order[$i]["od_status"] == 1 && $order[$i]["od_pay_status"] == 1 && $order[$i]["delivery_name"]!=""){?><!--결제 완료 // 배송입력후-->
                                <?php if($order[$i]["od_refund_status"]==1){?>
                                    <input type="button" value="환불승인" class="confirm"  onclick="fnOrderReturnConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["mb_id"];?>');">
                                    <input type="button" value="환불거절" class="confirm2"  onclick="fnOrderReturnCancel(0,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                <?php }else if($order[$i]["od_refund_status"]==2){?>
                                    <?php if($order[$i]["delivery_name_cancel"]!=""){?>
                                        <input type="button" value="배송정보" class="confirm" onclick="fnDeliDetail('<?php echo $order[$i]['od_id'];?>','<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order.php','1','1');">
                                        <input type="button" value="배송조회" class="confirm" onclick="fnDeliSearch('<?php echo $order[$i]["delivery_name_cancel"];?>','<?php echo $order[$i]["delivery_number_cancel"];?>');">
                                        <input type="button" value="환불완료" class="confirm" onclick="fnOrderRefundConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pay_oid"];?>',1)">
                                    <?php }else{?>
                                        <input type="button" value="한번 더 배송 요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["mb_id"];?>','배송 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>'.'delivery')"">
                                    <?php }?>
                                <?php }else{ ?>
                                    <input type="button" value="거래완료 요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["mb_id"];?>','거래 완료요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','od_fin')">
                                <?php }?>
                                <!--<input type="button" value="상세보기" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/order_view.php?od_id=<?php /*echo $order[$i]["od_id"];*/?>'">-->
                            <?php }?>
                            <?php if($order[$i]["od_direct_status"]==1){?>
                                <input type="button" value="직거래승인" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=1&od_id=<?php echo $order[$i]["od_id"];?>'">
                                <input type="button" value="직거래취소" class="confirm2" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=0&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 판매자가 승인 취소-->
                            <?php }?>
                            <?php if($order[$i]["od_status"]==1 && $order[$i]["od_pay_status"]==0 && ($order[$i]["od_direct_status"]==0||$order[$i]["od_direct_status"] == -1)){?>
                                <input type="button" value="가격변경" class="confirm2" onclick="fnPriceUpdate('<?php echo $order[$i]["od_id"];?>')">
                                <input type="button" value="판매취소" class="confirm2" onclick="fnOrderCancel('<?php echo $order[$i]["od_id"];?>',1)">
                            <?php }?>
                        <?php }?>
                        <?php if($member["mb_id"]!=$order[$i]["pd_mb_id"]){//구매자?>
                            <?php if($order[$i]["od_status"]==0){?>
                                <input type="button" value="예약취소" class="confirm2" onclick="fnOrderCancel('<?php echo $order[$i]["od_id"];?>',2)">
                            <?php }?>
                            <?php if($order[$i]["od_status"] == 1 && $order[$i]["od_pay_status"] == 1 && $order[$i]["delivery_name"]==""){?><!--구매자 결제 완료 // 배송입력전-->
                                    <?php if($order[$i]["od_fin_confirm"]==0){?>
                                        <?php if($order[$i]["od_direct_status"]==2){?>
                                            <input type="button" value="직거래완료" class="confirm" onclick="fnOrderfin('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',1,'<?php echo $order[$i]["pd_mb_id"];?>','direct')"><!-- 구매자가 완료 -->
                                            <input type="button" value="직거래취소" class="confirm2" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=2&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 구매자가 취소 -->
                                        <?php }else{?>
                                            <?php /*if($order[$i]["od_cancel_status"]==0){*/?><!--
                                            <input type="button" value="결제취소요청" class="confirm" onclick="fnOrderRefund('<?php /*echo $order[$i]["od_id"];*/?>','<?php /*echo $order[$i]["pay_oid"];*/?>')">
                                            --><?php /*}*/?>
                                            <?php if($order[$i]["delivery_name"]==''){
                                                if($order[$i]["delivery_extend"]==1){?>
                                                    <input type="button" value="배송연장승인" class="confirm deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','1')">
                                                    <input type="button" value="배송연장거절" class="confirm2 deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','2')">
                                                <?php }else{?>
                                                    <input type="button" value="한번 더 배송 요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["pd_mb_id"];?>','배송 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','delivery')">
                                                <?php }?>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                    <!--<input type="button" value="상세보기" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/order_view.php?od_id=<?php /*echo $order[$i]["od_id"];*/?>'">-->
                            <?php }?>
                            <?php if($order[$i]["od_status"] == 1 && $order[$i]["od_pay_status"] == 1 && $order[$i]["delivery_name"]!=""){?><!--구매자 결제 완료 // 배송입력후-->
                                    <!--<input type="button" value="거래완료" class="confirm" onclick="sendPush('<?php /*echo $mb["mb_id"];*/?>','배송 요청입니다.','<?php /*echo $order[$i]["od_id"];*/?>','<?php /*echo $order[$i]["pd_id"];*/?>')">-->
                                    <?php if($order[$i]["od_cancel_confirm"]!=2){?>
                                        <?php if($order[$i]["od_refund_status"]!=2){?>
                                        <input type="button" value="배송정보" class="confirm" onclick="fnDeliDetail('<?php echo $order[$i]['od_id'];?>','<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order.php','1','2');">
                                        <input type="button" value="배송조회" class="confirm" onclick="fnDeliSearch('<?php echo $order[$i]["delivery_name"];?>','<?php echo $order[$i]["delivery_number"];?>');">
                                        <?php }?>
                                        <!--<input type="button" value="배송확인" class="confirm" onclick="fnDeliConfirm('<?php /*echo $order[$i]["od_id"];*/?>');">-->
                                        <?php if($order[$i]["od_refund_status"]==0||$order[$i]["od_refund_status"]==-1){?>
                                        <input type="button" value="거래완료/평가" class="confirm" onclick="fnOrderfin('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',1,'<?php echo $order[$i]["pd_mb_id"];?>')">
                                        <input type="button" value="환불요청" class="confirm" onclick="fnOrderReturn('<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                        <?php }?>
                                        <?php if($order[$i]["od_refund_status"]==1){?>
                                            <input type="button" value="환불요청취소" class="confirm2" onclick="fnOrderReturnCancel(1,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                        <?php }?>
                                        <?php if($order[$i]["od_refund_status"]==2){?>
                                            <?php if($order[$i]["delivery_name_cancel"]==""){?>
                                                <input type="button" value="배송정보입력" class="confirm" onclick="fnDeli('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_type"];?>','cancel')">
                                            <?php }else{?>
                                                <input type="button" value="환불완료요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["pd_mb_id"];?>','환불완료 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','refund')">
                                            <?php }?>
                                        <?php }?>
                                    <?php }else{?>
                                    <!--<input type="button" value="환불배송지" class="confirm" onclick="">-->
                                        <?php if($order[$i]["delivery_name_cancel"]==""){?>
                                            <input type="button" value="환불배송정보입력" class="confirm" onclick="fnOrderCancelDelivery('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["pd_id"];?>',0,'cancel')">
                                        <?php }else{?>
                                            <!--<input type="button" value="환불배송정보수정" class="confirm" onclick="fnOrderCancelDelivery('<?php /*echo $order[$i]["od_id"];*/?>','<?php /*echo $order[$i]["pd_type"];*/?>','<?php /*echo $order[$i]["pd_id"];*/?>',1)">-->
                                        <?php }?>
                                    <?php }?>
                                    <?php if($order[$i]["delivery_name"]!="" && $order[$i]["od_cancel_confirm"]==0){?>

                                    <?php }?>
                                    <!--<input type="button" value="상세보기" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/order_view.php?od_id=<?php /*echo $order[$i]["od_id"];*/?>'">-->
                            <?php }?>
                            <?php if($order[$i]["od_status"] == 1 && $order[$i]["od_pay_status"] == 0 && ($order[$i]["od_direct_status"]==0 || $order[$i]["od_direct_status"]==-1)) {?>
                                    <input type="button" value="결제하기" class="confirm" onclick="fnMypageOrder('<?php echo $order[$i]['od_id']; ?>','<?php echo $order[$i]["pd_type"]; ?>','<?php echo $order[$i]["od_price"]; ?>','insert');">
                                    <input type="button" value="구매취소" class="confirm2" onclick="fnOrderCancel('<?php echo $order[$i]["od_id"];?>',2)">
                                <?php }else if($order[$i]["od_status"] == 1 && $member["mb_id"] != $order[$i]["pd_mb_id"] && $order[$i]["od_direct_status"]==1){?>
                                    <input type="button" value="직거래취소" class="confirm2" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=2&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 구매자가 취소-->
                                <?php }?>
                            <?php }?>
                        </div>
                    </div>
                <?php }?>

                <?php if($order[$i]["pd_type"]==2){?>
                    <div class="controls pd_id_<?php echo $order[$i]["od_id"];?>">
                        <div class="btn_controls" >
                            <input type="button" value="연락하기" onclick="fnShow2('<?php echo $mb["mb_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $roomids;?>','mypage','<?php echo $order[$i]["od_pd_type"];?>')">
                            <?php if($member["mb_id"]==$order[$i]["pd_mb_id"]){//판매자?>
                                <?php if($order[$i]["od_status"]==0){//주문예약상태?>
                                    <input type="button" value="예약승인" class="confirm" onclick="fnOrderConfirm('<?php echo $order[$i]["od_id"];?>',2)">
                                    <input type="button" value="예약취소" class="confirm2" onclick="fnOrderCancel2('<?php echo $order[$i]["od_id"];?>',1)">
                                <?php }?>
                                <?php if($order[$i]["od_status"]==1){//계약중?>
                                    <?php if($order[$i]["od_pay_status"]==0){?>
                                        <?php if($order[$i]["od_direct_status"]==1){?>
                                            <!--<input type="button" value="직거래승인" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=1&od_id=<?php /*echo $order[$i]["od_id"];*/?>'">-->
                                            <input type="button" value="직거래승인" class="confirm" onclick="fnDirectOrders(1,'<?php echo $order[$i]["od_id"];?>');">
                                            <input type="button" value="직거래취소" class="confirm2" onclick="fnDirectOrders(0,'<?php echo $order[$i]["od_id"];?>');"><!-- 판매자가 승인 취소-->
                                        <?php }else if($order[$i]["od_direct_status"]==2){?>
                                            <input type="button" value="직거래취소" class="confirm2" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=3&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 판매자가 취소-->
                                        <?php }else if($order[$i]["od_direct_status"]==0){?>
                                            <input type="button" value="가격변경" class="confirm2" onclick="fnPriceUpdate('<?php echo $order[$i]["od_id"];?>')">
                                            <input type="button" value="거래취소" class="confirm2" onclick="fnOrderCancel2('<?php echo $order[$i]["od_id"];?>',2)">
                                        <?php }?>
                                    <?php }else if($order[$i]["od_pay_status"]==1){?>
                                        <?php if($order[$i]["pd_delivery_use"]==1){?>
                                            <?php if($order[$i]["od_step"]==1){//1차결제(계약금)?>
                                                <?php if($order[$i]["od_price"]==0){?>
                                                    <?php if($order[$i]["od_name"]==""){?>
                                                        <input type="button" value="거래취소" class="confirm2" onclick="fnOrderCancel2('<?php echo $order[$i]["od_id"];?>',2)">
                                                    <?php }?>
                                                    <?php if($order[$i]["od_direct_status"]==2){//직거래승인?>
                                                        <input type="button" value="직거래완료요청" class="confirm" onclick="fnDirectConfirm('<?php echo $order[$i]["od_id"];?>')">
                                                    <?php }else{//직거래 아님?>
                                                        <?php if($order[$i]["delivery_name"]==""){?>
                                                            <input type="button" value="배송정보입력" class="confirm" onclick="fnDeli('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',2)">
                                                            <?php if($order[$i]["delivery_extend"]==0){?>
                                                                <input type="button" value="배송정보연장" class="confirm deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','0')">
                                                            <?php }?>
                                                        <?php }else{?>
                                                            <?php if($order[$i]["od_refund_status"]==1){?>
                                                                <input type="button" value="환불승인" class="confirm" onclick="fnOrderReturnConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["mb_id"];?>');">
                                                                <input type="button" value="환불거절" class="confirm" onclick="fnOrderReturnCancel(0,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                            <?php }?>
                                                            <input type="button" value="거래완료요청" class="confirm" onclick="fnDirectConfirm('<?php echo $order[$i]["od_id"];?>')">
                                                        <?php }?>
                                                    <?php }?>
                                                <?php }else{?>
                                                    <?php if($order[$i]["pd_price2"]!=0){//잔금있음?>
                                                        <?php if($order[$i]["od_direct_status"]==2){?>
                                                            <input type="button" value="직거래완료요청" class="confirm" onclick="fnDirectConfirm('<?php echo $order[$i]["od_id"];?>')">
                                                        <?php }else{?>
                                                            <?php if($order[$i]["delivery_name"]==""){?>
                                                                <input type="button" value="배송정보입력" class="confirm" onclick="fnDeli('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',2)">
                                                                <?php if($order[$i]["delivery_extend"]==0){?>
                                                                <input type="button" value="배송정보연장" class="confirm deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','0')">
                                                                <?php }?>
                                                            <?php }else if($order[$i]["delivery_name"]!=""){//배송입력완료?>
                                                                <?php if($order[$i]["od_fin_pay_status"]==0 && ($order[$i]["od_direct_status"]==0 || $order[$i]["od_direct_status"]==-1)){//거래완료요청금?>
                                                                <input type="button" value="잔금요청" class="confirm" onclick="fnMypageOrderStep2('<?php echo $order[$i]["od_id"];?>');">
                                                                <?php }?>
                                                                <?php if($order[$i]["od_fin_pay_status"]==2){//승인완료?>
                                                                    <input type="button" value="잔금결제요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["mb_id"];?>','잔금결제 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','fin_payment')">
                                                                <?php }?>
                                                                <?php if($order[$i]["od_refund_status"]==1){?>
                                                                    <input type="button" value="환불승인" class="confirm" onclick="fnOrderReturnConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["mb_id"];?>');">
                                                                    <input type="button" value="환불거절" class="confirm" onclick="fnOrderReturnCancel(0,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                                <?php }?>
                                                            <?php }?>
                                                        <?php }?>
                                                    <?php }else{//잔금없음?>
                                                        <?php if($order[$i]["od_direct_status"]==2){?>
                                                            <input type="button" value="직거래완료요청" class="confirm" onclick="fnDirectConfirm('<?php echo $order[$i]["od_id"];?>')">
                                                        <?php }else{?>
                                                            <?php if($order[$i]["delivery_name"]==""){?>
                                                                <input type="button" value="배송정보입력" class="confirm" onclick="fnDeli('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',2)">
                                                                <?php if($order[$i]["delivery_extend"]==0){?>
                                                                    <input type="button" value="배송정보연장" class="confirm deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','0')">
                                                                <?php }?>
                                                            <?php }else if($order[$i]["delivery_name"]!=""){//배송입력완료?>
                                                                <?php if($order[$i]["od_refund_status"]==0 || $order[$i]["od_refund_status"]==-1){?>
                                                                <input type="button" value="거래완료요청" class="confirm" onclick="fnDirectConfirm('<?php echo $order[$i]["od_id"];?>')">
                                                                <?php }else if($order[$i]["od_refund_status"]==1){?>
                                                                    <input type="button" value="반품승인" class="confirm" onclick="fnOrderReturnConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["mb_id"];?>');">
                                                                    <input type="button" value="반품거절" class="confirm" onclick="fnOrderReturnCancel(0,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                                <?php }else{?>
                                                                    <?php if($order[$i]["delivery_name_cancel"]==""){?>
                                                                        <input type="button" value="한번 더 배송요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["mb_id"];?>','배송 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','delivery')">
                                                                    <?php }else{?>
                                                                        <input type="button" value="배송정보" class="confirm" onclick="fnDeliDetail('<?php echo $order[$i]['od_id'];?>','<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order.php','2','1');">
                                                                        <input type="button" value="배송조회" class="confirm" onclick="fnDeliSearch('<?php echo $order[$i]["delivery_name"];?>','<?php echo $order[$i]["delivery_number"];?>');">
                                                                        <input type="button" value="반품완료" class="confirm" onclick="fnOrderCancel3('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pay_oid"];?>')">
                                                                    <?php }?>
                                                                <?php }?>
                                                            <?php }?>
                                                        <?php }?>
                                                    <?php }?>
                                                <?php }?>
                                            <?php }?>
                                        <?php }else{?>
                                            <?php if($order[$i]["od_step"]==1){?>
                                                <?php if($order[$i]["pd_price"]!=0){?>
                                                    <?php if($order[$i]["od_refund_status"]!=1){?>
                                                    <?php if(($order[$i]["od_fin_pay_status"]==0 || $order[$i]["od_fin_pay_status"]==1) && ($order[$i]["od_direct_status"]==0 || $order[$i]["od_direct_status"]==-1)){?>
                                                        <?php if($order[$i]["od_price2"]){?>
                                                        <input type="button" value="잔금요청" class="confirm" onclick="fnMypageOrderStep2('<?php echo $order[$i]["od_id"];?>');">
                                                        <input type="button" value="거래취소" class="confirm" onclick="fnOrderCancel4('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pay_oid"];?>');">
                                                        <?php }else{?>
                                                        <input type="button" value="거래취소" class="confirm" onclick="fnOrderCancel4('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pay_oid"];?>');">
                                                        <?php }?>
                                                    <?php }if($order[$i]["od_fin_pay_status"]==2){?>
                                                        <input type="button" value="잔금결제요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["mb_id"];?>','잔금결제 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','fin_payment')">
                                                    <?php }?>
                                                    <?php if(($order[$i]["od_fin_pay_status"]==0 || $order[$i]["od_fin_pay_status"]==1) && ($order[$i]["od_direct_status"]==1 || $order[$i]["od_direct_status"]==2)){?>
                                                        <input type="button" value="직거래취소" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=3&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 판매자가 취소-->
                                                    <?php }?>
                                                <?php }?>
                                                <?php if($order[$i]["od_refund_status"]==1){?>
                                                    <input type="button" value="환불승인" class="confirm" onclick="fnOrderReturnConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["mb_id"];?>');">
                                                    <input type="button" value="환불거절" class="confirm2" onclick="fnOrderReturnCancel(0,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                <?php }?>
                                                <?php /*if($order[$i]["delivery_name"]==""){*/?><!--
                                                    <input type="button" value="배송정보입력" class="confirm" onclick="fnDeli('<?php /*echo $order[$i]["od_id"];*/?>','<?php /*echo $order[$i]["pd_id"];*/?>',2)">
                                                <?php /*}else{*/?>
                                                    <input type="button" value="잔금 요청" class="confirm" onclick="fnMypageOrderStep2('<?php /*echo $order[$i]["od_id"];*/?>');">
                                                --><?php /*}*/?>
                                                <?php }else{?>
                                                    <?php if($order[$i]["od_refund_status"]==0 || $order[$i]["od_refund_status"]==-1){?>
                                                    <input type="button" value="거래완료요청" class="confirm" onclick="fnDirectConfirm('<?php echo $order[$i]["od_id"];?>')">
                                                    <?php }else if($order[$i]["od_refund_status"]==1){?>
                                                    <input type="button" value="반품승인" class="confirm" onclick="fnOrderReturnConfirm('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["mb_id"];?>');">
                                                    <input type="button" value="반품거절" class="confirm" onclick="fnOrderReturnCancel(0,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                    <?php }else{?>
                                                        <input type="button" value="한번 더 배송요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["pd_mb_id"];?>','배송 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','delivery')">
                                                    <?php }?>
                                                <?php }?>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                            <?php }else if($member["mb_id"]==$order[$i]["mb_id"]){ //구매자?>
                                <?php if($order[$i]["od_status"]==0){//예약상태?>
                                    <input type="button" value="요청취소" class="confirm" onclick="fnOrderCancel2('<?php echo $order[$i]["od_id"];?>',0)">
                                <?php }?>
                                <?php if($order[$i]["od_status"]==1){//거래 승인?>
                                    <?php if($order[$i]["od_pay_status"]==0){//결제안함?>
                                        <?php if($order[$i]["od_direct_status"]==0 || $order[$i]["od_direct_status"]==-1){?>
                                            <input type="button" value="결제하기" class="confirm" onclick="fnMypageOrder2('<?php echo $order[$i]['od_id']; ?>','<?php echo $order[$i]["pd_type"]; ?>','<?php echo $order[$i]["od_price"]; ?>',0,'insert')">
                                            <input type="button" value="거래취소" class="confirm2" onclick="fnOrderCancel2('<?php echo $order[$i]["od_id"];?>',0)">
                                        <?php }else if($order[$i]["od_direct_status"]==1){?>
                                            <input type="button" value="직거래 요청취소" class="confirm2" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=3&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 구매자가 취소-->
                                        <?php }?>
                                    <?php }else{//결제완료?>
                                        <?php if($order[$i]["od_direct_status"]==2){//직거래?>
                                            <input type="button" value="직거래취소" class="confirm" onclick="location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type=2&od_id=<?php echo $order[$i]["od_id"];?>'"><!-- 구매자가 취소-->
                                            <input type="button" value="직거래완료" class="confirm" onclick="fnOrderfin('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',1,'<?php echo $order[$i]["pd_mb_id"];?>','direct')"><!-- 구매자가 완료-->
                                        <?php }else{//일반결제?>
                                            <?php if($order[$i]["od_step"]==1){//계약중?>
                                                <?php if($order[$i]["pd_delivery_use"]==1){//배송있음?>
                                                    <?php if($order[$i]["delivery_name"]==""){//아직 배송정보 입력전?>
                                                        <input type="button" value="한번 더 배송요청" class="confirm" onclick="sendPush('<?php echo $order[$i]["pd_mb_id"];?>','배송 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','delivery')">
                                                        <?php if($order[$i]["delivery_extend"]==1){//연장요청?>
                                                            <input type="button" value="배송연장승인" class="confirm deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','1')">
                                                            <input type="button" value="배송연장거절" class="confirm2 deliextends<?php echo $order[$i]["od_id"];?>" onclick="fnDeliExtend('<?php echo $order[$i]["od_id"];?>','2')">
                                                        <?php }?>
                                                    <?php }else{//배송정보 입력 후?>
                                                        <?php if($order[$i]["pd_price2"]!=0){?>
                                                            <?php if($order[$i]["od_fin_pay_status"]==0){?>
                                                                <?php if($order[$i]["od_refund_status"]==0){?>
                                                                    <input type="button" value="배송정보" class="confirm" onclick="fnDeliDetail('<?php echo $order[$i]['od_id'];?>','<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order.php','2','2');">
                                                                    <input type="button" value="배송조회" class="confirm" onclick="fnDeliSearch('<?php echo $order[$i]["delivery_name"];?>','<?php echo $order[$i]["delivery_number"];?>');">
                                                                    <?php if($order[$i]["pd_price"]==0){?>
                                                                        <input type="button" value="거래완료/평가" class="confirm" onclick="fnOrderfin('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',2,'<?php echo $order[$i]["pd_mb_id"];?>')">
                                                                        <input type="button" value="반품요청" class="confirm" onclick="fnOrderReturn('<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                                    <?php }else{?>
                                                                        <input type="button" value="환불요청" class="confirm" onclick="fnOrderReturn('<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                                    <?php }?>
                                                                <?php }?>
                                                            <?php }else if($order[$i]["od_fin_pay_status"]==1){//결제요청?>
                                                                <input type="button" value="요청내용확인" class="confirm" onclick="fnFinPayConfirm('<?php echo $order[$i]["od_id"];?>',0)">
                                                                <input type="button" value="잔금요청승인" class="confirm" onclick="fnFinPayConfirm('<?php echo $order[$i]["od_id"];?>',1)">
                                                                <input type="button" value="잔금요청거절" class="confirm2" onclick="fnFinPayConfirm('<?php echo $order[$i]["od_id"];?>',2)">
                                                            <?php }else{?>
                                                                <input type="button" value="잔금결제/거래완료" class="confirm" onclick="fnMypageOrder('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["od_price"];?>','')">
                                                            <?php }?>
                                                        <?php }else{?>
                                                            <?php if($order[$i]["od_refund_status"]==0 || $order[$i]["od_refund_status"]==-1){?>
                                                                <input type="button" value="배송정보" class="confirm" onclick="fnDeliDetail('<?php echo $order[$i]['od_id'];?>','<?php echo G5_MOBILE_URL;?>/page/mypage/mypage_order.php','2','2');">
                                                                <input type="button" value="배송조회" class="confirm" onclick="fnDeliSearch('<?php echo $order[$i]["delivery_name"];?>','<?php echo $order[$i]["delivery_number"];?>');">
                                                                <input type="button" value="거래완료/평가" class="confirm" onclick="fnOrderfin('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',2,'<?php echo $order[$i]["pd_mb_id"];?>')">
                                                                <?php if($order[$i]["od_refund_status"]==0){?>
                                                                    <input type="button" value="반품요청" class="confirm2" onclick="fnOrderReturn('<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                                <?php }?>
                                                            <?php }else if($order[$i]["od_refund_status"]==1){?>
                                                                <input type="button" value="반품요청취소" class="confirm2" onclick="fnOrderReturnCancel(1,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                            <?php }else{?>
                                                                <?php if($order[$i]["delivery_name_cancel"]==""){?>
                                                                    <input type="button" value="배송정보입력" class="confirm2" onclick="fnDeli('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_type"];?>','cancel')">
                                                                    <input type="button" value="반품취소/거래완료" class="confirm2" onclick="fnOrderfin('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',2,'<?php echo $order[$i]["pd_mb_id"];?>')">
                                                                <?php }else{?>
                                                                    <input type="button" value="반품완료요청" class="confirm2" onclick="sendPush('<?php echo $order[$i]["pd_mb_id"];?>','배송 요청입니다.','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','refurnd')">
                                                                <?php }?>
                                                            <?php }?>
                                                        <?php }?>
                                                    <?php }?>
                                                <?php }else{//배송없음?>
                                                    <?php if($order[$i]["od_price2"]>0){//계약금 있음?>
                                                        <?php if($order[$i]["od_fin_pay_status"]==0 || $order[$i]["od_fin_pay_status"]==-1){?>
                                                            <?php if($order[$i]["od_refund_status"]==0){?>
                                                                <input type="button" value="환불요청" class="confirm2" onclick="fnOrderReturn('<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                            <?php }else if($order[$i]["od_refund_status"]==1){?>
                                                                <input type="button" value="환불요청취소" class="confirm2" onclick="fnOrderReturnCancel(1,'<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>','<?php echo $order[$i]["pd_price"];?>')">
                                                            <?php }?>
                                                        <?php }?>
                                                        <?php if($order[$i]["od_fin_pay_status"]==1){//결제요청?>
                                                            <input type="button" value="요청내용확인" class="confirm" onclick="fnFinPayConfirm('<?php echo $order[$i]["od_id"];?>',0)">
                                                            <input type="button" value="잔금결제/거래완료" class="confirm" onclick="fnMypageOrder('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_type"];?>','<?php echo $order[$i]["od_price"];?>','fin_order')">
                                                            <input type="button" value="잔금요청거절" class="confirm2" onclick="fnFinPayConfirm('<?php echo $order[$i]["od_id"];?>',2)">
                                                        <?php }?>
                                                    <?php }else{//계약금없음?>
                                                        <input type="button" value="거래완료/평가" class="confirm" onclick="fnOrderfin('<?php echo $order[$i]["od_id"];?>','<?php echo $order[$i]["pd_id"];?>',2,'<?php echo $order[$i]["pd_mb_id"];?>')">
                                                    <?php }?>
                                                <?php }?>
                                            <?php }else if($order[$i]["od_step"]==0){?>
                                                <?php if($order[$i]["od_price"]==0){?>
                                                    <?php if($order[$i]["pd_delivery_use"]==1){?>

                                                    <?php }else{?>
                                                        <input type="button" value="주문정보입력" class="confirm" onclick="fnMypageOrder2('<?php echo $order[$i]['od_id']; ?>','<?php echo $order[$i]["pd_type"]; ?>','<?php echo $order[$i]["od_price"]; ?>',0,'insert')">
                                                        <input type="button" value="거래취소" class="confirm2" onclick="fnOrderCancel2('<?php echo $order[$i]["od_id"];?>',0)">
                                                    <?php }?>
                                                <?php }?>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </div>
                    </div>
                <?php }//pd_type2?>
            </div>
        <?php } //for?>
    </div>
</div>
    <script>
        var od_id = '';
        function fnMypageOrder(od_id,pd_type,price,type){
            location.href=g5_url+'/mobile/page/mypage/orders.php?od_id='+od_id+'&pd_type='+pd_type+'&total_price='+price+'&prices='+price+'&type='+type;
        }

        function fnMypageOrder2(od_id,pd_type,price,price2,type){
            var total = price + price2;
            location.href=g5_url+'/mobile/page/mypage/orders.php?od_id='+od_id+'&pd_type='+pd_type+'&total_price='+total+'&prices='+price+'&prices2='+price2+'&type='+type;
        }

        function fnDeli(od_id,pd_id,pd_type,status){
            /*$("#deli_od_id").val(od_id);
            $("#deli_pd_id").val(pd_id);*/
            /*$("#id00").css("display","block");*/
            $.ajax({
                url:g5_url+'/mobile/page/modal/modal.deliveryadd.php',
                method:"post",
                data:{od_id:od_id,pd_id:pd_id,pd_type:pd_type,status:status}
            }).done(function(data){
                $(".modal").html(data).addClass("active");
                $("html,body").css("height","100vh");
                $("html,body").css("overflow","hidden");
                location.hash = "modal";
            });
        }

        function fnDeliDetail(od_id,back_url,pd_type,od_cate){
            location.href=g5_url+'/mobile/page/mypage/order_view.php?od_cate='+od_cate+"&pd_type="+pd_type+"&od_id="+od_id+"&back_url="+back_url;
        }

        function fnDeliExtend(od_id,step) {
            if(od_id==""){
                alert("선택된 주문정보가 없습니다.");
                return false;
            }
            if(step==0) {
                if (confirm("해당 주문의 배송 연장요청을 하시겠습니까?\r\n배송 연장요청은 구매자가 승인할 경우 3일 연장됩니다.\r\n추가 3일이 지나도 배송을 시작하지 않으면, 거래는 자동 취소됩니다.")) {
                    $.ajax({
                        url: g5_url + '/mobile/page/mypage/mypage_deliextend.php',
                        method: "post",
                        data: {od_id: od_id,step:step}
                    }).done(function (data) {
                        if (data == "1") {
                            alert("배송정보 연장요청이 완료되었습니다.");
                            $(".deliextends"+od_id).remove();
                        } else {
                            alert("배송정보 연장요청에 실패하였습니다.");
                        }
                    });
                }
            }else if(step==1){
                if (confirm("해당 주문의 배송 연장요청을 승인하시겠습니까?")) {
                    $.ajax({
                        url: g5_url + '/mobile/page/mypage/mypage_deliextend.php',
                        method: "post",
                        data: {od_id: od_id,step:step}
                    }).done(function (data) {
                        if (data == "1") {
                            alert("배송정보 연장요청승인이 완료되었습니다.");
                            $(".deliextends"+od_id).remove();
                        } else {
                            alert("배송정보 연장요청승인이 실패하였습니다.");
                        }
                    });
                }
            }
            else if(step==2){
                if (confirm("해당 주문의 배송 연장요청을 거절하시겠습니까?")) {
                    $.ajax({
                        url: g5_url + '/mobile/page/mypage/mypage_deliextend.php',
                        method: "post",
                        data: {od_id: od_id,step:step}
                    }).done(function (data) {
                        if (data == "1") {
                            alert("배송정보 연장요청거절이 완료되었습니다.");
                            $(".deliextends"+od_id).remove();
                        } else {
                            alert("배송정보 연장요청거절에 실패하였습니다.");
                        }
                    });
                }
            }
        }

        function sendPush(mb_id,title,od_id,pd_id,type) {
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.send_push.php",
                method:"post",
                data:{mb_id:mb_id,content:title,od_id:od_id,pd_id:pd_id,type:type}
            }).done(function (data) {
                alert(data);
            })
        }

        function fnDeliSearch(deli,deli_num){
            var t = document.createElement("textarea");
            document.body.appendChild(t);
            t.value = deli_num;
            t.select();
            document.execCommand('copy');
            document.body.removeChild(t);
            <?php if($app2){?>
            var dataString = {
                url : deli_num
            };
            webkit.messageHandlers.copyLink.postMessage(dataString);
            <?php }?>
            if(confirm("운송장번호가 복사되었습니다. 네이버 운송장 번호란에 붙여넣기 하세요.")){
            <?php if($app || $app2){?>
                location.href='https://search.naver.com/search.naver?sm=top_sly.hst&fbm=1&ie=utf8&query='+deli;
            <?php }else{?>
                window.open('https://search.naver.com/search.naver?sm=top_sly.hst&fbm=1&ie=utf8&query='+deli,"_blank");
            <?php }?>
            }
        }

        function fnDirectConfirm(od_id){
            if(od_id==""){
                alert("선택된 주문정보가 없습니다.");
                return false;
            }
            $.ajax({
                url:g5_url+'/mobile/page/mypage/ajax.order_fin_confirm.php',
                method:"post",
                data:{od_id:od_id}
            }).done(function(data){
                alert("요청되었습니다.");
            });
        }

        function fnDirectOrders(type,od_id){
            //var msg = "능력 직거래승인시 해당 거래는 주문완료로 변경됩니다.\r\n서비스 이용간 발상하는 상황은 책임지지 않습니다.";
            var msg = "직거래 요청을 승인하시겠습니까?\r\n서비스 이용간 발상하는 상황은 책임지지 않습니다.";
            if(type==0){
                msg = "직거래 요청을 거절하시겠습니까?";
            }
            if(confirm(msg)){
                location.href=g5_url+'/mobile/page/mypage/ajax.pay_direct_confirm.php?type='+type+'&od_id='+od_id;
            }
        }

        function fnDirectConfirm2(od_id,cid){
            $.ajax({
                url:g5_url+'/mobile/page/modal/modal.orderpayment2.php',
                method:"post",
                data:{od_id:od_id,cid:cid}
            }).done(function(data){
                $(".modal").html(data).addClass("active");
                $("html,body").css({"overflow":"hidden","height":"100%"});
                location.hash = "#modal";
            })
            /*$("#od_id_step2").val(od_id);
            $("#cid_step2").val(cid);*/
            //$("#id10").show();
        }

        function fnFinPayConfirm(od_id,type){
            if(type==0) {
                $.ajax({
                    url:g5_url+'/mobile/page/modal/modal.order_fin_view.php',
                    type:"post",
                    data:{od_id:od_id}
                }).done(function (data) {
                    $(".modal").html(data).addClass("active");
                    $("html,body").css({"overflow":"hidden","height":"100%"});
                    location.hash = "#modal";
                });
            }else{
                if(type==1){
                    if(confirm('해당 금액으로 최종 결제 하시겠습니까?')){
                        location.href=g5_url+'/mobile/page/mypage/mypage_fin_status.php?od_id='+od_id+"&type="+type;
                    }
                }else if(type==2){
                    if(confirm('해당 금액으로 잔금 결제를 거절 하시겠습니까?\r\n반드시 판매자와 협의 후 진행 바랍니다.')){
                        location.href=g5_url+'/mobile/page/mypage/mypage_fin_status.php?od_id='+od_id+"&type="+type;
                    }
                }
            }
        }
    </script>
    <?php }else {
        echo "no-list".$sql;
    }
?>