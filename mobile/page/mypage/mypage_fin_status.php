<?php
include_once ("../../../common.php");
//구매자가 판매자에게 잔금요청을 승인 or 거절
if($type==1){//승인 판매자 알림
    $pay_status = 2;
    $msg = cut_str($pro["pd_tag"],6,"...")."의 잔금요청이 승인되었습니다.\r\n거래 완료는 구매자가 최종 승인후 처리됩니다.";
    $msg2 = '잔금요청이 승인되었습니다.';
}else if($type==2){//거절
    $pay_status = 0;
    $msg = cut_str($pro["pd_tag"],6,"...")."의 잔금요청이 거절되었습니다.\r\n구매자와 충분히 협의 후 진행 바랍니다.";
    $msg2 = '잔금요청이 거절되었습니다.';
}

$sql = "update `order` set od_fin_pay_status = '{$pay_status}' where od_id = '{$od_id}'";

if(sql_query($sql)){
    //판매자에게 알림
    $pro = sql_fetch("select *,o.mb_id as mb_id , p.mb_id as pd_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'");
    $mb = get_member($pro["pd_mb_id"]);

    if($mb["regid"]){
        $img="";
        if($pro["pd_images"]) {
            $imgs = explode(",",$pro["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }

        send_FCM($mb["regid"], '48 잔금 요청 알림', $msg, G5_MOBILE_URL.'/page/mypage/mypage_order.php?od_cate=1&pd_type='.$pro["pd_type"], 'fcm_buy_channel', '구매일림', $mb['mb_id'], $pro["pd_id"], $img,'','');
    }
    alert($msg2);
}

?>