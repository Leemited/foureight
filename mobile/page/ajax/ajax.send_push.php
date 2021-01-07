<?php
include_once ("../../../common.php");

$mb = get_member($mb_id);

if($mb["regid"]) {
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);
    $img = "";
    if($pd["pd_images"]) {
        $imgs = explode(",",$pd["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    if($mb["mb_id"]==$pd["mb_id"]){
        $od_cate = 1;
    }else{
        $od_cate = 2;
    }

    if($type="delivery"){
        $title = "48 배송 요청 알림";
    }
    if($type=="od_fin"){
        $title = "48 거래 완료 요청";
    }
    if($type=="od_fin"){
        $title = "48 잔금 결제 요청";
    }

    $today = date("Y-m-d");
    $tohour = date("Y-m-d H:i:s");
    $hour = date("Y-m-d H:i:s",strtotime("+1 hours"));

    $sql = "select count(*) as cnt from `my_alarms_history` where mb_id = '{$member["mb_id"]}' and od_id = '{$od_id}' and send_type ='{$type}' and send_date = '{$today}' and '{$tohour}' BETWEEN send_datetime and DATE_ADD(send_datetime, Interval 1 hour)";

    $cnt = sql_fetch($sql);
    if($cnt["cnt"]>0){
        echo "같은 요청의 알림은 한시간에 한번씩 가능합니다.";
    }else {
        //이전 데이터 삭제
        $sql = "delete from `my_alarms_history` where mb_id = '{$member["mb_id"]}' and od_id = '{$od_id}' and send_type ='{$type}'";
        sql_query($sql);

        $sql = "insert into `my_alarms_history` set mb_id = '{$member["mb_id"]}' , od_id = '{$od_id}', send_type = '{$type}', send_date = now(), send_time = now(),send_datetime = now()";
        sql_query($sql);

        send_FCM($mb["regid"], $title, $pd["pd_tag"] . "의 " . $content, G5_MOBILE_URL . '/page/mypage/mypage_order.php?od_cate=' . $od_cate . "&pd_type=" . $pd["pd_type"], 'fcm_buy_channel', "구매일림", $mb_id, $pd_id, $img);

        echo "알림을 보냈습니다.\n전화나 문자를 통해 한번 더 요청 바랍니다.";
    }
}else{
    echo "푸시알람을 보내지 못 했습니다.";
}

?>