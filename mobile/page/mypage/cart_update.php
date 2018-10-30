<?php
include_once ("../../../common.php");
if(!$pd_ids || !$cart_ids || !$prices){
    alert("잘못된 요청입니다.");
    return false;
}

if($type=="insert") {

    $pd_ids = explode(",", $_REQUEST["pd_ids"]);
    $cart_ids = explode(",", $_REQUEST["cart_ids"]);
    $prices = explode(",", $_REQUEST["prices"]);
    $groupid = "od_" . strtotime(date("Ymdhis"));
    $step = 0;

    $flag = true;
    for ($i = 0; $i < count($pd_ids); $i++) {
        $sql = "select * from `product` where pd_id = {$pd_ids[$i]}";
        $pro = sql_fetch($sql);
        if ($pro["pd_type2"] == 2) {
            $step = 1;
        }
        $sql = "insert into `order_temp` set cid = '{$cart_ids[$i]}', pd_id = '{$pd_ids[$i]}', mb_id = '{$member["mb_id"]}', od_price = '{$prices[$i]}', od_status = 0, od_date = now() , od_pd_type = {$pro["pd_type2"]}, od_step=0, group_id = '{$groupid}'";
        if (sql_query($sql)) {
            $sql = "update `cart` set c_status = 2 where cid = {$cart_ids[$i]}";
            sql_query($sql);
        } else {
            $flag = false;
        }
    }

    if ($flag == false) {
        $sql = "delete from `order_temp` where group_id = '{$groupid}'";
        sql_query($sql);
        alert("등록 오류 입니다.\r다시 시도해 주세요.");
    } else {
        alert("주문 페이지로 이동합니다.", G5_MOBILE_URL . "/page/mypage/orders.php?group_id=" . $groupid);
    }
}else if($type=="del"){
    $sql = "delete from `cart` where cid in ({$cart_ids})";
    if(sql_query($sql)){
        alert("삭제 되었습니다.");
    }else{
        alert("이미 삭제 되었거나 잘못된 요청입니다.");
    }
}
?>