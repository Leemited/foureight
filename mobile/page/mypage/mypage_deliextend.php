<?php
include_once ("../../../common.php");
if($step==0) {
    $sql = "update `order` set delivery_extend = '1' where od_id = '{$od_id}'";
}else if($step==1){
    $sql = "update `order` set delivery_extend = '2' where od_id = '{$od_id}'";
}else if($step==2){
    $sql = "update `order` set delivery_extend = '-1' where od_id = '{$od_id}'";
}else{
    echo "2";
    return false;
}

if(sql_query($sql)){
    $sql = "select *,o.mb_id as mb_id, p.mb_id as pd_mb_id from `order` as o left join `product` as p ON o.pd_id = p.pd_id where od_id = '{$od_id}'";
    $od = sql_fetch($sql);
    $img = "";
    if ($od["pd_images"]) {
        $imgs = explode(",", $od["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    if($step==0 ){//판매자가 구매자에게
        $mb = get_member($od["mb_id"]);
        if ($mb["regid"]) {
            send_FCM($mb["regid"], "배송 연장 요청", cut_str($od["pd_tag"],10,"...") . "의 배송연장 요청입니다.\r\n요청 승인시 배송입력 기간이 3일간 연장됩니다.\r\n안전한 거래를 위해 상호 충분한 협의 후 진행바랍니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=' . $od["pd_type"], 'fcm_buy_channel', '구매일림', $mb["mb_id"], $od['pd_id'], $img);
        }
    }else{//구매자가 판매자에게
        $mb = get_member($od["pd_mb_id"]);
        if($mb["regid"]) {
            if($step==1) {
                send_FCM($mb["regid"], "배송 연장 요청", cut_str($od["pd_tag"],10,"...") . "의 배송연장 요청이 승인되었습니다.\r\n기본 입력기간 7일이후 3일이 연장되었습니다.\r\n10일이 지나면 자동 취소됩니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=' . $od["pd_type"], 'fcm_buy_channel', '구매일림', $mb["mb_id"], $od['pd_id'], $img);
            }else if($step==2){
                send_FCM($mb["regid"], "배송 연장 요청", cut_str($od["pd_tag"],10,"...") . "의 배송연장 요청이 거절되었습니다.\r\n배송 입력기간 7일이내에 반드시 배송정보를 입력바랍니다.\r\n자세한 정보는 이용가이드를 참고 하시기 바랍니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=' . $od["pd_type"], 'fcm_buy_channel', '구매일림', $mb["mb_id"], $od['pd_id'], $img);
            }
        }
    }
    echo "1";
}else{
    echo "2";
}
?>