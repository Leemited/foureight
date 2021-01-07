<?php
include_once ("../../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");
/********************************************************************************
결제가 완료된 경우 상점에서 호출하는 페이지 입니다.
상점에서 필요한 로직 추가
 ********************************************************************************/

$sql = "select *,o.mb_id as mb_id,p.mb_id as pd_mb_id,p.pd_id as pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
$pro = sql_fetch($sql);
$app_mb_id = $pro["pd_mb_id"];

if($pro["od_pay_status"]==1&&$pro["od_step"]==1){
    $sql = "select * from `order_pay_info` where od_id = '{$od_id}' and  mbrRefNo = '{$pro["pay_oid2"]}'";
}else {
    $sql = "select * from `order_pay_info` where od_id = '{$od_id}' and  mbrRefNo = '{$pro["pay_oid"]}'";
}
$od_info = sql_fetch($sql);
switch ($od_info["paymethod"]){
    case "1":
        $payType = "카드";
        break;
    case "3":
        $payType = "계좌이체";
        break;
}

if($payType=="카드") {
    $sql = "select * from `pay_card_code` where card_code = '{$od_info["issueCompanyNo"]}'";
    $cardName = sql_fetch($sql);
}
?>
<script src="https://api-std.mainpay.co.kr/js/mainpay.pc-1.0.js"></script>

<style>
    table{width:100%;padding:2vw;}
    table th{padding:2vw;text-align: center;font-size:1em;background-color: #fff;font-weight:normal;width:20%}
    table td{padding:2vw;text-align: left;font-size:1em;background-color:#fff;width:80%}
</style>
<div class="sub_head">
    <h2>결제 완료 정보</h2>
</div>

<div class="alert_list" >
    <div class="list_con" style="height:auto;padding:0">
        <div class="product_img">
            <?php
            if($pro["pd_images"]!="") {
                $img = explode(",", $pro["pd_images"]);
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
            <h2 style="text-align: center;font-size:4vw;margin:3vw auto;"><?php echo $pro["pd_name"];?></h2>
        </div>
    </div>
    <table>
        <colgroup>
            <col width="40%">
            <col width="*">
        </colgroup>

        <!--<tr>
            <th>결과 메세지</th>
            <td><?php /*echo $rstMsg; */?></td>
        </tr>
        <tr>
            <th>가맹점아이디</th>
            <td><?php /*echo $mbrId; */?></td>
        </tr>-->
        <tr>
            <th>결제금액</th>
            <td><?php echo $pro["od_price"]; ?></td>
        </tr>
        <tr>
            <th>주문번호</th>
            <td><?php echo $pro["pay_oid"]; ?></td>
        </tr>
    </table>
    <table>
        <colgroup>
            <col width="40%">
            <col width="*">
        </colgroup>
        <tr>
            <th colspan="2">결제정보</th>
        </tr>
        <tr>
            <th>결제 타입</th>
            <td><?php echo $payType; ?></td>
        </tr>
        <tr>
            <th>카드 거래번호</th>
            <td><?php echo $od_info["mbrRefNo"]; ?></td>
        </tr>
        <tr>
            <th>카드 승인일</th>
            <td><?php echo $od_info["tranDate"]; ?></td>
        </tr>
        <tr>
            <th>카드 승인시각</th>
            <td><?php echo $od_info["tranTime"]; ?></td>
        </tr>
        <!--<tr>
            <th>카드명</th>
            <td><?php /*echo $cardName; */?></td>
        </tr>
        <tr>
            <th>카드코드</th>
            <td><?php /*echo $cardCode; */?></td>
        </tr>-->
        <?php if($payType=="카드"){?>
        <tr>
            <th>할부개월</th>
            <td><?php echo $od_info["installment"]; ?></td>
        </tr>
        <tr>
            <th>결제카드사</th>
            <td><?php echo $cardName["card_name"]; ?></td>
        </tr>
        <?php }?>
        <?php if($payType=="계좌이체"){?>
            <tr>
                <th>결제은행</th>
                <td><?php echo $od_info["bankName"];?></td>
            </tr>
        <?php }?>
    </table>
    <div class="btns" style="width:100%;padding:6vw 0;background-color:#fff;text-align: center;position: absolute;bottom:0;left:0;">
        <input type="button" value="<?php if($pro["od_step"]==1||$pro["od_step"]==0){?>결제 확인<?php }else{?>거래완료/평가<?php }?>" class="input_btn" onclick="<?php if($pro["od_step"]==1||$pro["od_step"]==0){?>location.replace(g5_url+'/mobile/page/mypage/mypage_order.php?od_cate=2&pd_type=<?php echo $pro["pd_type"];?>&od_id=<?php echo $od_id;?>&app_mb_id=<?php echo $pro["mb_id"];?>')<?php }else{?>fnOrderfin('<?php echo $od_id;?>','<?php echo $pro["pd_id"];?>','','<?php echo $pro["mb_id"];?>')<?php }?>" style="width:50%;background-color:#595959;color:#fff;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;font-size:3.5vw;padding:2.2vw 0;border:none;font-family: 'nsr', sans-serif;">
        <?php if($pro["pd_type"]==2 && $pro["od_step"]==2){
            $set = sql_fetch("select * from `mysetting` where mb_id = '{$pro["pd_mb_id"]}'");
            if($set["like_set"]==1){
                $sql = "select count(*)as cnt from `product_like` where mb_id = '{$member["mb_id"]}' and od_id = '{$od_id}' and pd_id = '{$pro["pd_id"]}'";
                $cnt = sql_fetch($sql);
                if ($cnt["cnt"] == 0) {
                    $alert = true;
                    ?>
                    <!--<input type="button" value="후기남기기" onclick="fnReviewWrite('<?php /*echo $member["mb_id"];*/?>','<?php /*echo $pro["pd_type"];*/?>')">-->
                <?php }?>
            <?php }?>
        <?php }?>
        <!--<input type="button" value="새로고침" onclick="location.reload();">-->
    </div>
</div>

<script>
    $(function(){
        <?php if($alert){?>
        fnOrderfin('<?php echo $od_id;?>','<?php echo $pro["pd_id"];?>','','<?php echo $pro["mb_id"];?>')
        <?php }?>
    });
    function fnFinLikeUpdate(pd_id,od_id,fin_type){
        var mb_id = $("#fin_mb_id").val();
        var text = $("#like_content").val();
        var likeup = $("#fin_likeup").val();

        $.ajax({
            url:g5_url+"/mobile/page/like_product.php",
            method:"post",
            dataType:"json",
            data:{od_id:od_id,pd_id:pd_id,mb_id:mb_id,like_content:text,likeup:likeup,fin_type:fin_type},
            async:false
        }).done(function(data){
            if(data.result=="1"){
                alert('이미 평가한 글입니다.');
            }else if(data.result=="2"){
                alert("거래완료 및 평가가 정상 등록됬습니다.");
            }else{
                alert("잘못된 요청입니다.");
            }
            location.href= g5_url+'/mobile/page/mypage/order_complete.php?pd_id='+pd_id+'&od_id='+od_id+"&pd_type="+fin_type;
        });
    }
</script>
<?php
include_once (G5_PATH."/tail.php");
?>
