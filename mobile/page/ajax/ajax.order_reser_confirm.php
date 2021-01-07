<?php
include_once ("../../../common.php");

if(!$od_id){
    /*$result["msg"] = "1";
    echo json_encode($result);*/
    alert("주문정보가 없습니다.");
    //return false;
}
$sql = "select * from `order` where od_id = '{$od_id}'";
$chkid = sql_fetch($sql);
$pd_id = $chkid["pd_id"];

$sql = "update `order` set od_status = 1, od_reser_confirm_date = now() where od_id = '{$od_id}'";
if(sql_query($sql)){
    $mb = get_member($chkid["mb_id"]);
    $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
    if($pro["pd_images"]) {
        $imgs = explode(",",$pro["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }

    if($pro["pd_type"]==2 && $chkid["od_price"]==0){
        $sql = "update `order` set od_pay_status = 1 where od_id = '{$od_id}'";
        sql_query($sql);
    }

    //승인자
    if($mb["regid"]) {
        //승인 알림
         send_FCM($mb["regid"], "48 구매 알림", cut_str($pro["pd_tag"],10,"...") . "구매 예약이 승인 되었습니다.\r\n빠른 거래진행은 간편대화를 통해 진행해보세요!\r\n마이페이지 > 거래진행중 > 연락하기를 통해 가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
    }

    //나머지 취소
    if($pro["pd_type"]==1){ //물건일 경우 업데이트
        /*$sql = "update `order` set od_status = 0 where pd_id = '{$pd_id}'";
        sql_query($sql);
        $sql = "select * from `cart` where pd_id = '{$pd_id}' and c_status = -1";
        $res = sql_query($sql);
        while($row = sql_fetch_array($res)){
            $cancel[] = $row;
            $result["cid"][] = $row["cid"];
        }*/
        $sql = "select * from `order` where pd_id = '{$pd_id}' and od_id != '{$od_id}'";
        $res = sql_query($sql);
        $i=0;
        while($row = sql_fetch_array($res)){
            $result["od_id"][] = $row["od_id"];
            $mb = get_member($row["mb_id"]);
            if($mb["regid"]) {
                $regid[$i] = $mb["regid"];
                //취소 알림
            }

            $sql = "delete from `order` where od_id = '{$row["od_id"]}'";
            sql_query($sql);
            send_FCM($regid[$i], "48 취소 알림", cut_str($pro["pd_tag"],10,"...") . "구매 예약이 취소 되었습니다.\r\n더 좋은 거래를 원하시면 [삽니다]등록을 해보세요\r\n원하는 상품을 판매자가 직접 알려드려요!", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img,'','');
            $i++;
        }

        $sql = "update `product` set pd_status = 1 where pd_id = '{$pd_id}'";
        sql_query($sql);
    }
    //$result["pd_type"]=$pro["pd_type"];
    if($pro["pd_type"]==1){
        alert("구매예약이 승인되었습니다.");
    }else{
        alert("거래예약이 승인되었습니다.");
    }
}else{
    alert("처리 오류입니다.\\r다시 시도해 주세요.");
}


?>