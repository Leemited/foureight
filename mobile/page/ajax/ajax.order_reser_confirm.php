<?php
include_once ("../../../common.php");

if(!$cid){
    $result["msg"] = "1";
    echo json_encode($result);
    return false;
}
$sql = "select * from `cart` where cid = '{$cid}'";
$chkid = sql_fetch($sql);

$sql = "update `cart` set c_status = 1 where cid = '{$cid}'";
if(sql_query($sql)){
    $mb = get_member($chkid["mb_id"]);
    $pro = sql_fetch("select * from `product` where pd_id = '{$chkid["pd_id"]}'");
    if($pro["pd_images"]) {
        $imgs = explode(",",$pro["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }

    //승인자
    if($mb["regid"]) {
        //승인 알림
        send_FCM($mb["regid"], $pro["pd_tag"], $pro["pd_tag"] . "구매 예약이 승인 되었습니다.", G5_MOBILE_URL . "/page/mypage/cart.php", "pay_reser_set", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
    }

    //나머지 취소
    $sql = "update `cart` set c_status = 3 where pd_id = '{$pd_id}' and cid != '{$cid}'";
    sql_query($sql);
    $sql = "select * from `cart` where pd_id = '{$pd_id}' and cid != '{$cid}'";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $cancel[] = $row;
        $result["cid"][] = $row["cid"];
    }
    if(count($cancel) > 0) {
        for($i=0;$i<count($cancel);$i++) {
            $mb = get_member($cancel[$i]["mb_id"]);
            if($mb["regid"]) {
                $regid[] = $mb["regid"];
                //취소 알림
            }
        }
        send_FCM($regid[], $pro["pd_tag"], $pro["pd_tag"] . "구매 예약이 취소 되었습니다.", G5_MOBILE_URL . "/page/mypage/cart.php", "pay_reser_set", "구매예약", $mb["mb_id"], $pro["pd_id"], $img);
    }

    //물건일 경우 업데이트
    if($pro["pd_type"]==1){
        $sql = "update `product` set pd_status = 1 where pd_id = '{$pd_id}'";
        sql_query($sql);
    }
    $result["msg"] = "2";
}else{
    $result["msg"] = "1";
}
echo json_encode($result);

?>