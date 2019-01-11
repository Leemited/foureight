<?php
include_once("../../../common.php");

if($type1){
    $where = " and p.pd_type = '{$type1}'";
}

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

$sql = "select *,c.mb_id as mb_id from `cart` as c left join `product` as p on c.pd_id = p.pd_id where p.mb_id = '{$member["mb_id"]}' and c_status != 2 and c_status != 3 and c_status!=10  {$where} order by c.c_date desc, c.pd_id";
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
            <div class="alarm_item" id="item_<?php echo $cart[$i]["cid"];?>" >
                <?php if($cart[$i]["c_status"]==1){?>
                    <div class="ordering">
                        <span>입금대기중</span>
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
                <?php if($cart[$i]["c_status"]==0){?>
                <div class="controls">
                    <div class="btn_controls" >
                        <input type="button" value="취소" onclick="fnOrderCancel('<?php echo $cart[$i]["cid"];?>','<?php echo $i;?>');">
                        <input type="button" value="승인" class="confirm" onclick="fnOrderConfirm('<?php echo $cart[$i]["cid"];?>','<?php echo $cart[$i]['pd_id'];?>');">
                    </div>
                </div>
                <?php }?>
            </div>
        <?php } ?>
    </div>
</div>
<script>
function fnOrderCancel(cid){
    var type = 1;
    if($(".sub_ul li.active").attr("id") == "avil"){
        type = 2;
    }
    if(confirm("해당 유저의 요청을 취소 하시겠습니까?")){
        $.ajax({
           url:g5_url+"/mobile/page/ajax/ajax.order_reser_cancel.php",
            method:"POST",
            data:{cid:cid}
        }).done(function(data){
            console.log(data);
            if(data=="1"){
                alert("해당 구매요청을 찾을 수 없습니다.");
            }
            if(data == "3"){
                alert("처리 오류입니다.\r다시 시도해 주세요.");
            }
            if(data == "2"){
                $("#item_"+cid).remove();
                if(type == 1 ){
                    var count = $("#mul label").text();
                    count = count.replace(",","");
                    count = Number(count) - 1;
                    count = count.numberFormat();
                    $("#mul label").html(count);
                    var topcount = $(".order_tab span").text();
                    topcount = topcount.replace(",","");
                    topcount = Number(topcount) -1;
                    topcount = topcount.numberFormat();
                    $(".order_tab span").html(topcount);
                }else{
                    var count = $("#avil label").text();
                    count = count.replace(",","");
                    count = Number(count) - 1;
                    count = count.numberFormat();
                    $("#avil label").html(count);
                    var topcount = $(".order_tab span").text();
                    topcount = topcount.replace(",","");
                    topcount = Number(topcount) -1;
                    topcount = topcount.numberFormat();
                    $(".order_tab span").html(topcount);
                }
            }
        });
    }else{
        return false;
    }
}
function fnOrderConfirm(cid,pd_id){
    if(confirm("구매 승인시 해당 물품은 거래중으로 변경됩니다.")) {
        var type = 1;
        if ($(".sub_ul li.active").attr("id") == "avil") {
            type = 2;
        }
        $.ajax({
            url: g5_url + "/mobile/page/ajax/ajax.order_reser_confirm.php",
            mothod: "POST",
            dataType: "json",
            data: {cid: cid, pd_id: pd_id}
        }).done(function (data) {
            if (data.msg == "1") {
                alert("해당 구매요청을 찾을 수 없습니다.");
            }
            if (data.msg == "3") {
                alert("처리 오류입니다.\r다시 시도해 주세요.");
            }
            if (data.msg == "2") {
                $("#item_" + cid + " .controls").remove();
                for (var i = 0; i < data.cid.length; i++) {
                    $("#item_" + data.cid[i]).remove();
                }
                if (type == 1) {
                    var count = $("#mul label").text();
                    count = count.replace(",", "");
                    count = Number(count) - data.cid.length;
                    count = count.numberFormat();
                    $("#mul label").html(count);
                    var topcount = $(".order_tab span").text();
                    topcount = topcount.replace(",", "");
                    topcount = Number(topcount) - data.cid.length;
                    topcount = topcount.numberFormat();
                    $(".order_tab span").html(topcount);
                } else {
                    var count = $("#avil label").text();
                    count = count.replace(",", "");
                    count = Number(count) - data.cid.length;
                    count = count.numberFormat();
                    $("#avil label").html(count);
                    var topcount = $(".order_tab span").text();
                    topcount = topcount.replace(",", "");
                    topcount = Number(topcount) - data.cid.length;
                    topcount = topcount.numberFormat();
                    $(".order_tab span").html(topcount);
                }
            }
        });
    }
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
    <?php }else {
        echo "no-list//".$type1Count."//".$type2Count;
    }
?>