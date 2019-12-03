<?php
include_once ("../../../common.php");

$sql = "update `order` set od_step = 2, od_fin_datetime = now() where od_id = '{$od_id}'";
if(sql_query($sql)){
    $sql = "select *,p.pd_id as pd_id,o.mb_id,p.mb_id as sell_mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
    $pd = sql_fetch($sql);

    $mb = get_member($pd["sell_mb_id"]);
    if($mb["regid"]) {
        $img = "";
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        send_FCM($mb["regid"],"48 결제 확인 알림",$pd["pd_tag"]."의 판매가 완료 되었습니다.",G5_MOBILE_URL.'/page/mypage/order_view.php?od_id='.$od_id,'fcm_buy_channel','구매알림',$mb_id,$pd["pd_id"],$img);
    }
    $result["msg"] = "최종 거래가 완료 되었습니다.\n이용해 주셔서 감사합니다.";
    $result["result"]=1;
}else{
    $result["msg"] = "거래 완료 요청이 실패하였습니다.";
    $result["result"]=2;
}

echo json_encode($result);