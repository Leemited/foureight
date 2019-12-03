<?php
include_once ("../../../common.php");

if(!$od_id){
    echo "1";
    return false;
}

$sql = "select * from `order` where od_id = '{$od_id}'";
$chkid = sql_fetch($sql);
$pd_id = $chkid["pd_id"];
//$sql = "update `order` set od_status = -1 where od_id = '{$od_id}'";
$sql = "delete from `order` where od_id = '{$od_id}'";
if(sql_query($sql)){
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $pro = sql_fetch($sql);
    if ($pro["pd_images"]) {
        $imgs = explode(",", $pro["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }

    if($pro["mb_id"]==$member["mb_id"]) {    //판매자취소
        $mb = get_member($chkid["mb_id"]);//구매자정보
        //취소 알림
        send_FCM($mb["regid"], $pro["pd_tag"], $pro["pd_tag"] . "구매가 취소 되었습니다.", G5_URL . "/?pd_id=" . $pro["pd_id"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
    }else { //구매자취소
        $mb = get_member($pro["mb_id"]); // 판매자정보
        //취소 알림
        send_FCM($mb["regid"], $pro["pd_tag"], $pro["pd_tag"] . "판매가 취소 되었습니다.", G5_URL . "/?pd_id=" . $pro["pd_id"], "fcm_buy_channel", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
    }
    if($pro["pd_type"]==1) {//물건만
        $sql = "update `product` set pd_status = 0 where pd_id = '{$pro["pd_id"]}'";
        sql_query($sql);
    }
    echo "2";
}else{
    echo "3";
}

?>