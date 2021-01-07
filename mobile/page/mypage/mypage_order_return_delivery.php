<?php
include_once ("../../../common.php");

$sql = "update `order` set delivery_name_cancel='{$delivery_name}', delivery_number_cancel='{$delivery_number}', delivery_cancel_time = now(), delivery_cancel_date = now() where od_id ='{$od_id}'";
if(sql_query($sql)){
    $result["result"]=3;
    $date = date("Y-m-d");
    $result["deli_date"] = $date;

    $sql = "select * from `product` where pd_id ='{$pd_id}'";
    $pd = sql_fetch($sql);
    $mb = get_member($pd["mb_id"]);

    if($mb["regid"]) {
        $img = "";
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        send_FCM($mb["regid"], '환불 배송 알림', cut_str($pd["pd_tag"],6,"...")."의 환불 배송 정보가 등록되었습니다.\r\n환불배송 확인 후 환불완료 처리바랍니다.\r\n배송정보는 마이페이지 > 거래진행중에서 확인 가능합니다.", G5_MOBILE_URL.'/page/mypage/mypage_order.php?od_cate=1&pd_type='.$pd["pd_type"], 'fcm_buy_channel', '구매일림', $mb['mb_id'], $pd_id, $img);
    }
    alert("환불 배송지 정보가 정상 등록되었습니다.",G5_MOBILE_URL.'/page/mypage/mypage_order.php?od_cate=2&pd_type='.$pd_type);
}else{
    alert("환불 배송지 정보등록에 실패 하였습니다.");
}


?>