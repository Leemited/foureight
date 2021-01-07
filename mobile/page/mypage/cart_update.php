<?php
include_once ("../../../common.php");

if(!$od_id || !$prices){
    alert("잘못된 요청입니다.");
    return false;
}

if($type=="insert") {
    //주문정보 불러오기
    $sql = "select c.*,p.*,c.pd_id as pd_id from `cart` as c left join `product` as p on c.pd_id = p.pd_id where c.cid in ({$_REQUEST['cart_ids']}) and p.pd_type = 1";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        if($row["c_status"]==0){
            alert("승인되지 않은 상품이 있습니다.");
        }
    }

    $groupid = "od_" . strtotime(date("Ymdhis"));
    $step = 0;

    $flag = true;
    for ($i = 0; $i < count($pd_ids); $i++) {
        $sql = "select * from `product` where pd_id = {$pd_ids[$i]}";
        $pro = sql_fetch($sql);
        if ($pro["pd_type"] == 2 && $pro["pd_price2"] > 0) {
            $step = 1;
        }

        if($pro["pd_type"]==1){
            $od_delivery_status = 1;
        }else{
            if($pro["pd_delivery_use"]==1){
                $od_delivery_status = 1;
            }else{
                $od_delivery_status = 0;
            }
        }
        
        //신규인지 중복인지 체크
        $sql = "select * from `order_temp` where cid = '{$_REQUEST['cart_ids']}'";
        $chkCart = sql_fetch($sql);

        //todo:2단계결제 진입시??

        if($chkCart==null) {//신규
            $sql = "";
            $sql = "insert into `order_temp` set cid = '{$cart_ids[$i]}', pd_id = '{$pd_ids[$i]}', mb_id = '{$member["mb_id"]}', pd_price = '{$pro["pd_price"]}',od_price = '{$prices[$i]}', od_status = 0, od_date = now() , od_pd_type = {$pd_type}, od_step={$step}, group_id = '{$groupid}', od_delivery_status = '{$od_delivery_status}'";
        }else{//중복 중복일경우 패스?
            $flag = true;
            /*$groupid = $chkCart["group_id"];
            $sql = "update `order_temp` set cid = '{$cart_ids[$i]}', pd_id = '{$pd_ids[$i]}', mb_id = '{$member["mb_id"]}', pd_price = '{$pro["pd_price"]}',od_price = '{$prices[$i]}', od_status = 0, od_date = now() , od_pd_type = {$pd_type}, od_step={$step} where cid = '{$_REQUEST['cart_ids']}'";*/
        }
        if($flag==true) {
            if (sql_query($sql)) {
                //$sql = "update `cart` set c_status = 2 where cid = {$cart_ids[$i]}";
                //sql_query($sql);
                //$od_temp_id = sql_insert_id();
            } else {
                $flag = false;
            }
        }
    }

    if ($flag == false) {
        $sql = "delete from `order_temp` where group_id = '{$groupid}'";
        sql_query($sql);
        alert("등록 오류 입니다.\r다시 시도해 주세요.");
    } else {
        //alert("주문 페이지로 이동합니다.", G5_MOBILE_URL . "/page/mypage/orders.php?group_id=" . $groupid);
        goto_url( G5_MOBILE_URL . "/page/mypage/orders.php?group_id=" . $groupid);
    }
}else if($type=="del"){
    $cart_ids = implode(",",$cart_ids);
    $sql = "update `cart` set c_status = 10 where cid in ({$cart_ids})";

    if(sql_query($sql)){
        //취소 알림
        //물건일 경우 상태 해당 상태 변경
        $pdids = implode(",",$pd_ids);
        $sql = "update `product` set pd_status = 0 where pd_id in ($pdids) and pd_status > 0";
        sql_query($sql);

        $sql = "select * from `product` where pd_id in ($pd_ids)";
        $res = sql_query($sql);
        while($row=sql_fetch_array($res)){
            $mb = get_member($row["mb_id"]);
            if ($row["pd_images"]) {
                $imgs = explode(",", $row["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            send_FCM($mb["regid"], $row["pd_tag"], $row["pd_tag"] . "의 구매가 취소되었습니다.", G5_MOBILE_URL . "/page/mypage_order.php?type=2&od_cate=2&pd_type=".$row["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $row["pd_id"], $img);
        }
        alert("삭제 되었습니다.");
    }else{
        alert("이미 삭제 되었거나 잘못된 요청입니다.");
    }
}
?>