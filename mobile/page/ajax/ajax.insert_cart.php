<?php
include_once ("../../../common.php");

//$sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$sell_mb_id}', c_status = {$status}, c_price = {$price}, c_date = now()";
$sql = "select COUNT(*)as cnt,cid from `cart` where pd_id = {$pd_id} and mb_id = '{$member["mb_id"]}'";
$pro = sql_fetch($sql);
if($pro["cnt"] > 0){
    //이미 등록한 상태이고 진행중인지 파악
    $sql = "select count(*) from `cart` where pd_id = {$pd_id} and mb_id = '{$member["mb_id"]}' and c_status = 1 ";
    $c_set = sql_fetch($sql);
    if($c_set["cnt"] > 0){
        echo "7";
        return false;
    }

    $sql = "update `cart` set pd_id = {$pd_id} , mb_id = '{$member["mb_id"]}', c_status = {$status}, c_price = {$price}, c_date = now() where cid = {$pro["cid"]}";
}else {
    $sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$member["mb_id"]}', c_status = {$status}, c_price = {$price}, c_date = now()";
}

if(sql_query($sql)){
    if($pricing_id){
        $sql = "update `product_pricing` set status = 2 where pd_id = '{$pd_id}'";
        sql_query($sql);
        $sql = "update `product_pricing` set status = 1 where id = '{$pricing_id}'";
        if(sql_query($sql)){
            //성공시 상대방에게 푸시 알림 가기
            $sql = "select * from `product` where pd_id = '{$pd_id}'";
            $pd = sql_fetch($sql);
            if ($pd["pd_images"]) {
                $imgs = explode(",", $pd["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            $mb = get_member($pd["mb_id"]);
            //echo $mb["regid"]."//". $pd["pd_tag"]."//". $pd["pd_tag"] . "의 구매 요청입니다."."//". G5_MOBILE_URL . "/page/mypage/mypage.php?pd_id=" . $pd_id."//". 'pricing_set'."//". '제시/딜 알림'."//". $mb["mb_id"]."//". $pd_id."//". $img;
            send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매 요청입니다.", G5_MOBILE_URL . "/page/mypage/mypage.php?pd_id=" . $pd_id, 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);
            echo "3";
        }else{
            echo "2";
        }
    }else {
        $sql = "update `product_pricing` set status = 1 where pd_id = '{$pd_id}'";
        sql_query($sql);
        $cid = sql_insert_id();
        if($pd_type!=2) {
           //물건의 경우 게시글을 업데이트 한다.
            $sql = "update `product` set pd_status = {$status} where pd_id = {$pd_id}";

            if (sql_query($sql)) {
                //성공시 상대방에게 푸시 알림 가기
                $sql = "select * from `product` where pd_id = '{$pd_id}'";
                $pd = sql_fetch($sql);
                if ($pd["pd_images"]) {
                    $imgs = explode(",", $pd["pd_images"]);
                    $img = G5_DATA_URL . "/product/" . $imgs[0];
                }
                $mb = get_member($sell_mb_id);

                //구매예약인지 딜 승인인지 파악
                //승인
                if ($status == 1) {
                    send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매가 확정되었습니다.", G5_MOBILE_URL . "/page/mypage/cart.php", 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);
                }
                //예약
                if ($status == 0) {
                    $mb = get_member($pd["mb_id"]);
                    send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매 요청입니다.", G5_MOBILE_URL . "/page/mypage/mypage.php?pd_id=" . $pd_id, 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);
                }
                echo "3";
            } else {
                $sql = "delete from `cart` where cid = {$cid}";
                sql_query($sql);
                echo "2";
            }
        }else{
            //능력의 경우는 게시글을 업데이트 하지 않는다.
            //성공시 상대방에게 푸시 알림 가기
            $sql = "select * from `product` where pd_id = '{$pd_id}'";
            $pd = sql_fetch($sql);
            if ($pd["pd_images"]) {
                $imgs = explode(",", $pd["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            $mb = get_member($pd["mb_id"]);
            //요청알림
            send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매대기가 등록되었습니다.", G5_MOBILE_URL . "/page/mypage/cart.php", 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);

            echo "3";
        }
    }
}else{
    echo "1";
}

?>
