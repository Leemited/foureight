<?php
include_once ("../../common.php");

if($pd_id=="" || !$pd_id){
    alert("잘못된 요청입니다.");
    return false;
}

$sql = "select * from `product` where pd_id = '{$pd_id}'";
//file
$file = sql_fetch($sql);
$path = G5_DATA_URL."/product/";
if($file["pd_type"]==1){
    $sql = "select * from `order` where pd_id = '{$pd_id}'";
    $orders = sql_fetch($sql);
    if(($orders!=null && $orders["od_fin_status"]==0) || $file["pd_status"]==1){
        alert("해당 게시글은 거래중으로 삭제할 수 없습니다.");
    }
}else{
    //능력
    $sql = "select * from `order` where pd_id = '{$pd_id}' and od_status = 1 and od_fin_status = 0 and od_pay_status = 1 and od_cancel_status = 0";
    $res = sql_query($sql);
    while($row=sql_fetch_array($res)) {
        $orders[] = $row;
    }
    if(count($orders)!=0){
        alert("해당 게시글은 거래중으로 삭제할 수 없습니다.");
    }
}

$sql = "update `product` set pd_status = 10 where pd_id = '{$pd_id}'";
if($return_url){
    $link = $return_url;
}else{
    $link = G5_URL;
}

if(stripos($link,"index.php")){
    $link = G5_URL;
}

if(sql_query($sql)){
    //남아있는 경우
    //예약 요청 건은 모두 취소처리 or 승인했지만 입금 전 취소처리
    $sql = "select * from `order` where pd_id = '{$pd_id}' and (od_status = 0 or (od_status = 1 and od_pay_status = 0))";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $sql = "update `order` set od_cancel_status = 2, od_cancel_date = now(), od_cancel_time = now() where od_id = '{$row["od_id"]}'";
        sql_query($sql);

        $mb = get_member($row["mb_id"]);
        if($mb["regid"]){
            $img = "";
            if ($file["pd_images"]) {
                $imgs = explode(",", $file["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            send_FCM($mb["regid"],"48 취소 알림","[".cut_str($file["pd_tag"],10,"...")."]이 삭제되어 자동 취소 되었습니다.\r\n재구매 요청은 판매자와 상의 바랍니다.",G5_MOBILE_URL."/page/mypage/alarm.php","fcm_buy_channel","구매일림",$row["mb_id"],$pd_id,$img,'','');
        }
    }
    alert("삭제되었습니다.",$link);
}else {
    alert("잘못된 요청입니다.");
}