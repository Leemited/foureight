<?php
include_once ("../../../common.php");

$sql = "select count(*)as cnt,pd_id,mb_id from `order` where od_id = '{$od_id}'";
$chk = sql_fetch($sql);

if($chk["cnt"]==0){
    $result["result"]=1;
    echo json_encode($result);
    return false;
}

$sql = "update `order` set delivery_name='{$delivery_name}', delivery_number='{$delivery_number}', delivery_time = now(), delivery_date = now() where od_id ='{$od_id}'";

if(sql_query($sql)){
    $result["result"]=3;
    $date = date("Y-m-d");
    $result["deli_date"] = $date;

    $sql = "select * from `product` where pd_id ='{$chk["pd_id"]}'";
    $pd = sql_fetch($sql);
    $mb = get_member($chk["mb_id"]);

    if($mb["regid"]) {
        $img = "";
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        send_FCM($mb["regid"], '48 배송 정보 알림', cut_str($pd["pd_tag"],6,"...").'의 배송 정보가 등록되었습니다.', G5_MOBILE_URL.'/page/mypage/order_view.php?od_id='.$od_id, 'fcm_buy_channel', '구매일림', $mb['mb_id'], $pd_id, $img);
    }

}else{
    $result["result"]=2;
}

echo json_encode($result);

?>