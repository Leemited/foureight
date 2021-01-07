<?php
include_once ("../../../common.php");

$pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
if ($pro["pd_images"]) {
    $imgs = explode(",", $pd["pd_images"]);
    $img = G5_DATA_URL . "/product/" . $imgs[0];
}
$total = $pro["pd_price"] + $pro["pd_price2"];

//예약요청건 확인
$sql = "select count(*) as cnt from `order` where pd_id = '{$pd_id}' and mb_id = '{$member["mb_id"]}'";
$chk = sql_fetch($sql);
if($chk["cnt"] > 0){//예약건 있음
    //물건일경우
    if($pro["pd_type"]==1 && $pro["pd_status"]!=0){
        //중복건 안됨
        echo "7";
        return false;
    }else{
        $total = $pro["pd_price"]+$pro["pd_price2"];

        //if($pro["pd_price2"]==0){
        $sql = "insert into `order` set pd_id='{$pd_id}',mb_id = '{$member["mb_id"]}', pd_price = '{$total}',od_price = '{$pro["pd_price"]}',od_price2 = '{$pro["pd_price2"]}',od_delivery_type = '{$pro["pd_delivery_use"]}', od_status = 0, od_reser_date = now(), od_reser_time = now(),od_pd_type='{$pro["pd_type"]}'";
        if(sql_query($sql)) {
            $mb = get_member($pro["mb_id"]);
            if($mb["regid"]) {
                if ($pro["pd_type"] == 1) {
                    send_FCM($mb["regid"], "48 거래 알림", cut_str($pro["pd_tag"], 10, "...") . "의 구매 요청입니다.\r\n거래 확인 후 진행 바랍니다.\r\n마이페이지 > 거래진행중에서 확인가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=" . $pro["pd_type"] . "&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img, '', '');
                } else {
                    send_FCM($mb["regid"], "48 거래 알림", cut_str($pro["pd_tag"], 10, "...") . "의 능력거래 요청입니다.\r\n거래 확인 후 진행 바랍니다.\r\n마이페이지 > 거래진행중에서 확인가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=" . $pro["pd_type"] . "&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img, '', '');
                }
            }

            echo 3;
        }
        return false;
    }
    //능력일경우
    if($pro["pd_type"]==2){
        $sql = "select * from `order` where pd_id = '".$pd_id."' and mb_id = '".$member["mb_id"]."'";
        $res = sql_query($sql);
        $orderchk = false;
        while($row = sql_fetch_array($res)){
            if($row["od_fin_status"]==0){
                $orderchk = true;
            }
        }
        if($orderchk==true) {//거래중이고 끝난게 아닐때
            //중복건 안됨
            echo "7";
            return false;
        }else{
            $total = $pro["pd_price"]+$pro["pd_price2"];

            //if($pro["pd_price2"]==0){
            $sql = "insert into `order` set pd_id='{$pd_id}',mb_id = '{$member["mb_id"]}', pd_price = '{$total}',od_price = '{$pro["pd_price"]}',od_price2 = '{$pro["pd_price2"]}',od_delivery_type = '{$pro["pd_delivery_use"]}', od_status = 0, od_reser_date = now(), od_reser_time = now(),od_pd_type='{$pro["pd_type"]}'";
            if(sql_query($sql)) {
                $mb = get_member($pro["mb_id"]);
                send_FCM($mb["regid"], "48 거래 알림", cut_str($pro["pd_tag"],10,"...") . "의 능력거래 요청입니다.\r\n거래 확인 후 진행 바랍니다.\r\n마이페이지 > 거래진행중에서 확인가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pro["pd_type"]."&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img,'','');
                echo 3;
            }
        }
    }
}else{
    //예약건 없음, 계약확인후 등록하기
    //물건일경우는 바로등록
    if($pro["pd_type"]==1){
        //딜요청에서 승인일경우
        if($pricing_id){
            $sql = "update `product` set pd_status = 2 where pd_id = '{$my_pd_id}'";
            sql_query($sql);

            $sql = "insert into `order` set pd_id='{$pd_id}',mb_id = '{$member["mb_id"]}', pd_price = '{$price}',od_price = '{$price}',od_delivery_type = 1, od_status = 0, od_reser_date = now(), od_reser_time = now(),od_pd_type='{$pro["pd_type"]}'";
            if(sql_query($sql)){
                $mb = get_member($pro["mb_id"]);
                send_FCM($mb["regid"], "48 거래 알림", cut_str($pro["pd_tag"],10,"...") . "의 구매 요청입니다.\r\n거래 확인 후 진행 바랍니다.\r\n마이페이지 > 거래진행중에서 확인가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pro["pd_type"]."&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img,'','');
                echo 3;
            }
        }else{//일반 예약일경우
            if($addtype=="confirm"){
                $sql = "update `product` set pd_status = 1 where pd_id = '{$pd_id}'";
                sql_query($sql);

                $sql = "insert into `order` set pd_id='{$pd_id}',mb_id = '{$sell_mb_id}', pd_price = '{$total}',od_price = '{$price}',od_delivery_type = 1, od_status = 1, od_reser_date = now(), od_reser_time = now(),od_pd_type='{$pro["pd_type"]}'";
                if(sql_query($sql)){
                    $mb = get_member($sell_mb_id);//구매자에게
                    if($mb["regid"]) {
                        send_FCM($mb["regid"], "48 거래 알림", cut_str($pro["pd_tag"], 10, "...") . "의 판매자님께서 결제를 요청하셨습니다.\r\n거래를 원하시면 마이페이지->거래진행중에서 결제를 진행해 주세요.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=" . $pro["pd_type"] . "&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img, '', '');
                    }
                    echo 3;
                }
            }else {
                $sql = "insert into `order` set pd_id='{$pd_id}',mb_id = '{$member["mb_id"]}', pd_price = '{$total}',od_price = '{$total}',od_delivery_type = 1, od_status = 0, od_reser_date = now(), od_reser_time = now(),od_pd_type='{$pro["pd_type"]}'";

                if(sql_query($sql)){
                    $mb = get_member($pro["mb_id"]);
                    send_FCM($mb["regid"], "48 거래 알림", cut_str($pro["pd_tag"],10,"...") . "의 구매 요청입니다.\r\n거래 확인 후 진행 바랍니다.\r\n마이페이지 > 거래진행중에서 확인가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pro["pd_type"]."&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img,'','');
                    echo 3;
                }
            }

        }
    }
    //능력
    if($pro["pd_type"]==2){
        $total = $pro["pd_price"]+$pro["pd_price2"];

        //if($pro["pd_price2"]==0){
        $sql = "insert into `order` set pd_id='{$pd_id}',mb_id = '{$member["mb_id"]}', pd_price = '{$total}',od_price = '{$pro["pd_price"]}',od_price2 = '{$pro["pd_price2"]}',od_delivery_type = '{$pro["pd_delivery_use"]}', od_status = 0, od_reser_date = now(), od_reser_time = now(),od_pd_type='{$pro["pd_type"]}'";
        if(sql_query($sql)) {
            $mb = get_member($pro["mb_id"]);
            send_FCM($mb["regid"], "48 거래 알림", cut_str($pro["pd_tag"],10,"...") . "의 능력거래 요청입니다.\r\n거래 확인 후 진행 바랍니다.\r\n마이페이지 > 거래진행중에서 확인가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pro["pd_type"]."&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img,'','');
            echo 3;
        }
        //}
    }

}

//$sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$sell_mb_id}', c_status = {$status}, c_price = {$price}, c_date = now()";
/*
$sql = "select COUNT(*)as cnt,cid,mb_id from `cart` where pd_id = {$pd_id} and mb_id = '{$member["mb_id"]}'";
$pro = sql_fetch($sql);
if($pro["cnt"] > 0){
    if($pro["pd_type"]==1) {
        //이미 등록한 상태이고 진행중인지 파악 물건일 경우만
        $sql = "select count(*)as cnt from `cart` where pd_id = {$pd_id} and mb_id = '{$member["mb_id"]}' and c_status = 1 ";
        $c_set = sql_fetch($sql);
        if ($c_set["cnt"] > 0) {
            echo "7";
            return false;
        }
    }
    if($price2){
        $where = " , c_price = '{$price2}', c_price2 = '{$price}'";
    }else{
        $where = " , c_price = '{$price}'";
    }

    $sql = "update `cart` set pd_id = {$pd_id} , mb_id = '{$member["mb_id"]}', c_status = {$status}, c_date = now() {$where} where cid = {$pro["cid"]}";
}else {

    if($price2){
        $where = " , c_price = '{$price2}', c_price2 = '{$price}'";
    }else{
        $where = " , c_price = '{$price}'";
    }

    $sql = "insert into `cart` set pd_id = {$pd_id} , mb_id = '{$member["mb_id"]}', c_status = {$status}, c_date = now() {$where}";
}

if(sql_query($sql)){
    //성공시 상대방에게 푸시 알림 가기
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);
    if ($pd["pd_images"]) {
        $imgs = explode(",", $pd["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }

    if($pd["pd_type"]==1) {
        if ($pricing_id) {
            $sql = "update `product_pricing` set status = 2 where pd_id = '{$pd_id}'";
            sql_query($sql);
            $sql = "update `product_pricing` set status = 1 where id = '{$pricing_id}'";
            if (sql_query($sql)) {

                $mb = get_member($pd["mb_id"]);
                //echo $mb["regid"]."//". $pd["pd_tag"]."//". $pd["pd_tag"] . "의 구매 요청입니다."."//". G5_MOBILE_URL . "/page/mypage/mypage.php?pd_id=" . $pd_id."//". 'pricing_set'."//". '제시/딜 알림'."//". $mb["mb_id"]."//". $pd_id."//". $img;
                send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매 요청입니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=" . $pd["pd_type"], 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img);
                echo "3";
            } else {
                echo "2";
            }
        } else {
            $sql = "update `product_pricing` set status = 1 where pd_id = '{$pd_id}'";
            sql_query($sql);
            $cid = sql_insert_id();
            //물건의 경우 게시글을 업데이트 한다.
            $sql = "update `product` set pd_status = {$status} where pd_id = {$pd_id}";

            if (sql_query($sql)) {
                //구매예약인지 딜 승인인지 파악
                //승인
                if ($status == 1) {
                    //구매자에게 푸시
                    $mb = get_member($pro["mb_id"]);
                    send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매가 확정되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img);
                }
                //예약
                if ($status == 0) {
                    //판매자에게 푸시
                    $mb = get_member($pd["mb_id"]);
                    send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매 요청입니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pd["pd_type"]."&pd_id=" . $pd_id, 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img,'','');
                }
                echo "3";
            } else {
                $sql = "delete from `cart` where cid = {$cid}";
                sql_query($sql);
                echo "2";
            }
        }
    }else if($pd["pd_type"]==2){
        if ($pricing_id) {

        }else{
            //능력의 경우는 게시글을 업데이트 하지 않는다.

            $mb = get_member($pd["mb_id"]);
            //요청알림
            send_FCM($mb["regid"], $pd["pd_tag"], $pd["pd_tag"] . "의 구매대기가 등록되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=".$pd["pd_type"], 'fcm_buy_channel', '구매예약', $mb["mb_id"], $pd_id, $img,'','');

            echo "3";
        }
    }
}else{
    echo "1";
}*/

?>
