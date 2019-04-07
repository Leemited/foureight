<?php
include_once ("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");

$sql = "select * from `order` as o left join `product` as p on o.pd_id = p.pd_id where od_id = '{$od_id}' ";
$order = sql_fetch($sql);

switch ($order["od_pay_status"]){
    case "1":
        $paystatus = "결제완료";
        break;
    case "2":
        $paystatus = "";
        break;
    case "3":
        $paystatus = "";
        break;
    case "5":
        $paystatus = "입금확인중";
        break;
}

switch ($order["od_pay_type"]){
    case "1":
        $paytype = "카드결제";
        break;
    case "2":
        $paytype = "가상계좌";
        break;
    case "3":
        $paytype = "계좌이체";
        break;
    case "4":
        $paytype = "휴대폰";
        break;
}

$back_url = G5_URL;

?>
    <div class="sub_head">
        <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
        <h2>거래정보</h2>
    </div>
    <div class="alert_list">
        <div class="list_con">
            <div class="product_img">
                <?php
                if($order["pd_images"]!="") {
                    $img = explode(",", $order["pd_images"]);
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
                <h2 style="text-align: center;font-size:4vw;"><?php echo $order["pd_tag"];?></h2>
            </div>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>주문정보</h2>
                <ul>
                    <li>주문자명 <span><?php echo $order["od_name"];?></span></li>
                    <li>배송지 <span><?php echo $order["od_zipcode"]." ".$order["od_addr1"]. " ".$order["od_addr2"];?></span></li>
                    <li>연락처 <span><?php echo hyphen_hp_number($order["od_tel"]);?></span></li>
                    <li>주문시 요청사항 <span><?php echo $order["od_content"];?></span></li>
                </ul>
            </div>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>결제정보</h2>
                <ul>
                    <li>결제 방식 <span><?php echo $paytype;?></span></li>
                    <li>결제 상태 <span><?php echo $paystatus;?></span></li>
                    <li>결제 금액 <span><?php echo number_format($order["od_price"]);?> 원</span></li>
                    <?php if($order["od_pay_type"]==2){?>
                    <li>가상계좌번호 <span><?php echo $order["vAccount"];?></span></li>
                    <li>은행 <span><?php echo $order["vAccountBankName"];?></span></li>
                    <li>입금 기간 <span><?php echo $order["vAccountDate"];?></span></li>
                    <?php }?>
                </ul>
            </div>
            <?php if($order["od_pay_status"]==1 && $order["od_pd_type"] == 0){?>
            <div class="product_info" style="background-color:#fff;width: calc(100% - 8vw);padding: 2vw;margin: 2vw;">
                <h2>배송 정보</h2>
                <ul>
                    <li>택배사</li>
                    <li>운송장번호</li>
                </ul>
            </div>
            <?php }?>
            <div class="order_btns">
                <?php
                //결제 취소 조건
                //결제 완료 됬지만 배송정보가 없을 경우
                //가상 계좌 이체 기간에 이체 확인이 안될 경우

                ?>
                <input type="button" value="입금확인요청">
                <input type="button" value="취소">
                <input type="button" value="제품 확인">
                <input type="button" value="추가 결제">
                <input type="button" value="최종 승인">
            </div>
        </div>
    </div>
    <script>

    </script>
<?php
include_once (G5_PATH."/tail.php");