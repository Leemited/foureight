<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$view = sql_fetch("select * from `order` as o left join `product` as p on o.pd_id = p.pd_id where od_id = '{$od_id}'");

if($view["pd_images"]) {
    $imgs = explode(",",$view["pd_images"]);
    $img = G5_DATA_URL."/product/".$imgs[0];
}

switch ($view["od_pay_type"]){
    case "1":
        $od_type = "카드결제";
        break;
    case "3":
        $od_type = "계좌이체";
        break;
}

switch ($view["od_pay_status"]){
    case "1":
        $paystatus = "결제완료";
        break;
    case "0":
        $paystatus = "입금대기중";
        break;
    case "2":
        $paystatus = "결제취소";
        break;
}

$mb = get_member($view["mb_id"]);

$subject="거래내역 상세보기";

?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1><?php echo $subject;?></h1>
        </header>
        <article>
            <div class="model_list">
                <div class="etc_gr"></div>
                <h2>주문정보</h2>
                <table class="view_tbl">
                    <colgroup>
                        <col width="12%">
                        <col width="23%">
                        <col width="12%">
                        <col width="23%">
                    </colgroup>
                    <tbody>
                    <tr>
                        <td colspan="4">
                            <img src="<?php echo $img;?>" alt="">
                        </td>
                    </tr>
                    <tr>
                        <th>상품명</th>
                        <td colspan="3" class="subject"><?php echo $view["pd_tag"];?></td>
                    </tr>
                    <tr>
                        <th>주문자명</th>
                        <td ><?php echo $view["od_name"];?></td>
                        <th>연락처</th>
                        <td ><?php echo $view["od_tel"];?></td>
                    </tr>
                    <tr>
                        <th>배송지</th>
                        <td ><?php echo "[".$view["od_zipcode"]."] ".$view["od_addr1"]." ".$view["od_addr2"];?></td>
                        <th>주문시 요청사항</th>
                        <td ><?php echo ($view["od_content"])?nl2br($view["od_content"]):"없음";?></td>
                    </tr>
                    </tbody>
                </table>
                <h2 style="margin-top:30px;">결제 정보</h2>
                <table class="view_tbl">
                    <colgroup>
                        <col width="12%">
                        <col width="23%">
                        <col width="12%">
                        <col width="23%">
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>결제번호</th>
                        <td ><?php echo $view["pay_oid"];?></td>
                        <th>결제상태</th>
                        <td ><?php echo $paystatus;?></td>
                    </tr>
                    <tr>
                        <th>결제방식</th>
                        <td ><?php echo $od_type;?></td>
                        <th>결제금액</th>
                        <td><?php echo number_format($view["od_price"]);?> 원</td>
                    </tr>
                    </tbody>
                </table>
                <h2 style="margin-top:30px;">배송 정보</h2>
                <table class="view_tbl">
                    <colgroup>
                        <col width="12%">
                        <col width="23%">
                        <col width="12%">
                        <col width="23%">
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>택배사</th>
                        <td ><?php echo ($view["delivery_name"])?$view["delivery_name"]:"배송준비중";?></td>
                        <th>운송장번호</th>
                        <td ><?php echo ($view["delivery_number"])?$view["delivery_number"]:"배송준비중";?></td>
                    </tr>
                    <tr>
                        <th>배송등록일</th>
                        <td colspan="3"><?php echo ($view["delivery_date"])?$view["delivery_date"]:"배송준비중";?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="submit_gr no_print">
                <a href="<?php echo G5_URL."/admin/order_list.php?stx=".$stx."&page=".$page."&sfl=".$sfl; ?>" class="adm-btn01">목록</a>
                <!--<a href="<?php /*echo G5_URL."/admin/board_write.php?bo_table=".$bo_table."&wr_id=".$wr_id."&ca_name".$view["ca_name"]; */?>" class="adm-btn01">수정</a>-->
            </div>
        </article>
    </section>
</div>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
