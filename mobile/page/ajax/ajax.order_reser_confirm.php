<?php
include_once ("../../../common.php");

if(!$od_id){
    $result["msg"] = "1";
    echo json_encode($result);
    return false;
}
$sql = "select * from `order` where od_id = '{$od_id}'";
$chkid = sql_fetch($sql);
$pd_id = $chkid["pd_id"];

$sql = "update `order` set od_status = 1 where od_id = '{$od_id}'";
if(sql_query($sql)){
    $mb = get_member($chkid["mb_id"]);
    $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
    if($pro["pd_images"]) {
        $imgs = explode(",",$pro["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }

    //승인자
    if($mb["regid"]) {
        //승인 알림
        send_FCM($mb["regid"], $pro["pd_tag"], $pro["pd_tag"] . "구매 예약이 승인 되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
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
            send_FCM($regid[$i], $pro["pd_tag"], $pro["pd_tag"] . "구매 예약이 취소 되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=2&od_cate=2&pd_type=".$pro["pd_type"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img,'','');
            $i++;
        }

        $sql = "update `product` set pd_status = 1 where pd_id = '{$pd_id}'";
        sql_query($sql);
    }
    $result["pd_type"]=$pro["pd_type"];
    $result["msg"] = "2";
}else{
    $result["msg"] = "1";
}
echo json_encode($result);

?>