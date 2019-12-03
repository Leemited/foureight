<?php
include_once ("../../../common.php");

//주문,제품정보
$sql = "select *,o.mb_id as mb_id,p.mb_id as sell_mb_id,o.pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
$order = sql_fetch($sql);
$pd_id = $order["pd_id"];
if($type==0){//판매자 요청 취소
    $sql = "update `order` set od_direct_status = 0,od_pay_type = 0, od_pay_status = 0 where od_id = '{$od_id}'";
    sql_query($sql);

    $mb = get_member($order["mb_id"]);//구매자정보
    if ($order["pd_images"]) {
        $imgs = explode(",", $order["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    if($mb["regid"]){//기기정보 있을때
        send_FCM($mb["regid"], $order["pd_tag"], $order["pd_tag"] . "의 직거래 요청이 취소되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$order["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img,'','');
    }
    alert("직거래 요청을 거절하였습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2&od_cate=1");
}else if($type==1){//판매자 요청 승인
    if($order["od_pd_type"]==2){
        $where = " , od_step = 1";
    }

    $sql = "update `order` set od_pay_type=5,od_pay_status=1,od_direct_status=2 {$where} where od_id = '{$od_id}'";
    if(sql_query($sql)){
        $mb = get_member($order["mb_id"]);//구매자정보
        if ($order["pd_images"]) {
            $imgs = explode(",", $order["pd_images"]);
            $img = G5_DATA_URL . "/product/" . $imgs[0];
        }
        if($mb["regid"]){//기기정보 있을때
            send_FCM($mb["regid"], $order["pd_tag"], $order["pd_tag"] . "의 직거래 요청이 승인되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$order["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);
        }

        alert("직거래 요청을 승인하였습니다.\\r구매자와 직거래장소를 최종 확인 바랍니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2&od_cate=1");
    }else{
        alert("주문정보 등록 오류입니다.\\r재시도 바랍니다.");
    }
}else if($type==3){//판매자가 취소
    $sql = "update `order` set od_direct_status = 0,od_pay_type = 0, od_pay_status = 0 where od_id = '{$od_id}'";
    sql_query($sql);

    $mb = get_member($order["mb_id"]);//구매자정보
    if ($order["pd_images"]) {
        $imgs = explode(",", $order["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    if($mb["regid"]){//기기정보 있을때
        send_FCM($mb["regid"], $order["pd_tag"], $order["pd_tag"] . "의 직거래가 취소되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=".$order["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);
    }
    alert("직거래를 취소하였습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type={$order["pd_type"]}");
}else if($type==2){//구매자가 취소
    $sql = "update `order` set od_direct_status = 0,od_pay_type = 0, od_pay_status = 0 where od_id = '{$od_id}'";
    sql_query($sql);

    $mb = get_member($order["sell_mb_id"]);//판매자정보
    if ($order["pd_images"]) {
        $imgs = explode(",", $order["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    if($mb["regid"]){//기기정보 있을때
        send_FCM($mb["regid"], $order["pd_tag"], $order["pd_tag"] . "의 직거래가 취소되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=".$order["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img);
    }
    alert("직거래를 취소하였습니다.",G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type={$order["pd_type"]}");
}else if($type==4){//구매자가 직거래 완료
    //물건/능력 ?
    /*if($order["od_pd_type"]==1){
        $sql = "update `order` set od_fin_status = 1,od_fin_confirm = 1, od_admin_status = 2,od_fin_datetime = now() where od_id = '{$od_id}'";
        if(sql_query($sql)){
            //물건 판매완료 처리
            sql_query("update `product` set pd_status = 10 where pd_id = '{$order["pd_id"]}'");

            $mb = get_member($order["sell_mb_id"]);
            if ($order["pd_images"]) {
                $imgs = explode(",", $order["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            if($mb["regid"]){//기기정보 있을때
                send_FCM($mb["regid"], $order["pd_tag"], $order["pd_tag"] . "의 직거래가 완료되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=".$order["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img,'','');
            }

            alert("직거래가 완료 되었습니다.");
        }else{
            alert("직거래 완료처리를 실패하였습니다. 다시 시도해 주세요.");
        }
    }else{

    }*/
}
?>