<?php
include_once ("./common.php");

//주문목록에서 배송정보 입력건을 가져온다
$sql = "select *,o.mb_id as mb_id from `order` as o left join `g5_member` as m on o.mb_id = m.mb_id where od_status = 1 and od_pay_status = 1 and od_pd_type = 1 and od_cancel_status <> 2 and delivery_name <> '' and od_fin_status = 0";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
    $sql = "select *,o.mb_id,o.pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$row["od_id"]}'";
    $pd = sql_fetch($sql);
    if ($pd["pd_images"]) {
        $imgs = explode(",", $pd["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    //판매자가 배송정보 입력 하였고 환불요청이 아닌 경우
    $finish_date = date("Y-m-d", strtotime("+ 4 day", strtotime($row["od_date"])));//결제 완료일에서 4일후의 날짜 (완료처리일)
    $today = date("Y-m-d");
    $tomorrow = date("Y-m-d",strtotime("+ 1 day",strtotime($finish_date)));
    //echo "주문일 : ".$row["od_date"]."//주문확정예정일 + 4 : ".$finish_date ."// 주문완료후 다음날 : ".$tomorrow."// 오늘 : ".$today."<br>";
    if ($finish_date == $today) {//오늘이 4일이 지난 후인 경우 알림 보냄
        $mb = get_member($row["mb_id"]);
        if($mb["regid"]){
            echo send_FCM($mb["regid"], "48 알림", $pd["pd_name"] . "의 거래가 [{$tomorrow}]에 자동구매확정됩니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);
        }
    }
    if($tomorrow == $today){
        //자동구매확정
        $sql = "update `order` set od_fin_status = 1 where od_id = '{$row["od_id"]}'";
        sql_query($sql);
        $mb = get_member($row["mb_id"]);
        if($mb["regid"]){
            echo send_FCM($mb["regid"], "48 알림", $pd["pd_name"] . "의 거래가 자동구매확정되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);
        }
    }
}

?>