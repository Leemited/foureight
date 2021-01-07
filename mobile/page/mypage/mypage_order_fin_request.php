<?php
include_once ("../../../common.php");

$sql = "update `order` set od_fin_content = '{$od_fin_content}', od_price = '{$od_price}', od_fin_pay_status = 1 where od_id = '{$od_id}'";

if(sql_query($sql)){
    $sql = "select *,o.mb_id as mb_id, p.mb_id as pd_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
    $pro = sql_fetch($sql);
    $mb = get_member($pro["mb_id"]);
    if($mb["regid"]) {
        $img = "";
        if ($pd["pd_images"]) {
            $imgs = explode(",", $pro["pd_images"]);
            $img = G5_DATA_URL . "/product/" . $imgs[0];
        }
        send_FCM($mb["regid"], '48 결제 요청 알림', cut_str($pro["pd_tag"], 10, "...") . "의 거래완료 결제 요청입니다.\r\n최종 결재금액을 확인 바랍니다.\r\n승인 시 해당 결제금액으로 결제가 진행됩니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?od_cate=2&pd_type='.$pro["pd_type"], 'fcm_buy_channel', '구매일림', $mb['mb_id'], $pd_id, $img, '', '');
    }
    alert("최종 결제 금액을 요청하였습니다.\\r\\n문제가 있을 시에는 그 전에 구매자와 상의하시기 바랍니다.");
}else{
    alert("요청 오류 입니다.");
}

?>