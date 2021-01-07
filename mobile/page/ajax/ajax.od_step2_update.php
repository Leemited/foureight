<?php
include_once ("../../../common.php");

$sql = "update `cart` set c_price2 = '{$od_step2_price}' where cid ='{$cid}'";
if(sql_query($sql)){
    $sql = "update `order` set od_step2_confirm = 1 where od_id = '{$od_id}'";
    sql_query($sql);

    $sql = "select pd_images,o.mb_id as mb_id,p.pd_tag,p.pd_type from `product` as p left join `order` as o on p.pd_id = o.pd_id where od_id = '{$od_id}'";
    $pro = sql_fetch($sql);

    $mb = get_member($pro["mb_id"]);

    if ($pro["pd_images"]) {
        $imgs = explode(",", $pro["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    //요청알림
    send_FCM($mb["regid"], "잔금결제요청", cut_str($pro["pd_tag"],10,"...") . "의 잔금결제 요청입니다.\r\n잔금을 확인 후 결제 바랍니다.\r\n이유없는 거래 불이행은 서비스 이용 제재 대상입니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?od_cate=2&pd_type=".$pro["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pro["pd_id"], $img);

    echo "1";
}else{
    echo "2";
}


?>