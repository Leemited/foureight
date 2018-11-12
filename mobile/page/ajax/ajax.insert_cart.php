<?php
include_once ("../../../common.php");

//$sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$sell_mb_id}', c_status = {$status}, c_price = {$price}, c_date = now()";
$sql = "select COUNT(*)as cnt,cid from `cart` where pd_id = {$pd_id} and mb_id = '{$sell_mb_id}' and c_status = 1";
$pro = sql_fetch($sql);
if($pro["cnt"]>0){
    $sql = "update `cart` set pd_id = {$pd_id} , mb_id = '{$sell_mb_id}', c_status = {$status}, c_price = {$price}, c_date = now() where cid = {$pro["cid"]}";
}else {
    $sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$sell_mb_id}', c_status = {$status}, c_price = {$price}, c_date = now()";
}
if(sql_query($sql)){
    $cid = sql_insert_id();
    $sql = "update `product` set pd_status = {$status} where pd_id = {$pd_id}";
    if(sql_query($sql)){
        //성공시 상대방에게 푸시 알림 가기
        $sql = "select * from `product` where pd_id = '{$pd_id}'";
        $pd = sql_fetch($sql);
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        $mb = get_member($sell_mb_id);
        
        //구매예약인지 딜 승인인지 파악
        
        //구매자
        if($status==1) {
            send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매가 확정되었습니다.", G5_MOBILE_URL . "/page/mypage/cart.php", 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);
        }
        //판매자
        if($status==0) {
            $mb = get_member($pd["mb_id"]);
            send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매 요청입니다.", G5_MOBILE_URL . "/page/mypage/mypage.php?pd_id=" . $pd_id, 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);
        }
        echo "3";
    }else{
        $sql = "delete from `cart` where cid = {$cid}";
        sql_query($sql);
        echo "2";
    }
}else{
    echo "1";
}

?>
