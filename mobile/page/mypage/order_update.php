<?php
include_once ("../../../common.php");
/*
$od_addr1 = $od_address1;
$od_addr2 = $od_address2;
$od_tel = $tel1."-".$tel2."-".$tel3;

$sql = "select *,o.pd_id as pd_id from `order_temp` as o left join `product` as p on o.pd_id = p.pd_id where group_id = '{$group_id}'";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    if($row[pd_type]==2){
        $step = 1;
    }else{
        $step = 0;
    }
    $sql = "insert into `order` SET " .
        "cid = {$row[cid]}," .
        "pd_id = {$row[pd_id]}," .
        "mb_id = '{$member["mb_id"]}'," .
        "od_price = {$row[od_price]}," .
        "od_status = 1," .
        "od_name = '{$od_name}'," .
        "od_tel = '{$od_tel}'," .
        "od_zipcode = {$od_zipcode}," .
        "od_addr1 = '{$od_addr1}'," .
        "od_addr2 = '{$od_addr2}'," .
        "od_content = '{$od_content}'," .
        "od_pay_type = 0," .
        "od_pay_status = 1," .
        "od_date = now()," .
        "od_pd_type = {$row[pd_type]}," .
        "od_step = $step," .
        "group_id = '{$group_id}'" ;
    if(sql_query($sql)){
        //물건일경우 물건 결제 완료 처리
        /*if($row["pd_type"]==1) {
            $sql = "update `product` set pd_status = 4 where pd_id = '{$row["pd_id"]}'";
            sql_query($sql);
        }*
        $sql = "select m.mb_id as mbid,p.pd_type,p.pd_type2,p.pd_tag,p.pd_images from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.pd_id = '{$row['pd_id']}'";
        $res = sql_fetch($sql);
        $mb = get_member($res["mbid"]);
        if($res["pd_type"]==1){
            $content = $res["pd_tag"]."의 결제가 완료 되었습니다. ";
        }else{
            if($row["od_step"] == 1){
                $content = $res["pd_tag"]."의 계약금이 결제되었습니다. ";
            }else{
                $content = $res["pd_tag"]."의 거래완료금이 결제되었습니다. ";
            }
        }

        if($res["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }

        send_FCM($mb["regid"],'48결제알림',$content,G5_MOBILE_URL."/page/mypage/mypage_order.php?type=2",'fcm_buy_channel',"구매알림",$mb["mb_id"],$row["pd_id"],$img);

        alert("결제 완료 되었습니다.",G5_MOBILE_URL."/page/mypage/order_history.php");
    }else{
        alert("주문 등록 오류 입니다. \r관리자에게 문의해 주세요.");
    }
}*/

$od_tel = $tel1."-".$tel2."-".$tel3;

if($od_pd_type==2){
    if($od_step==0){
        $set = ", od_step = 1";
    }
    if($od_step==1){
        $set = ", od_step = 2";
    }
}


$sql = "update `order` SET 
            od_name = '{$od_name}',
            od_tel = '{$od_tel}',
            od_zipcode = '{$od_zipcode}',
            od_addr1 = '{$od_address1}',
            od_addr2 = '{$od_address2}',
            od_content = '{$od_content}',
            od_pay_type = 6,
            od_pay_status = 1,
            od_date = now(),
            od_pd_type = '{$od_pd_type}'
            {$set}
            where od_id = '{$od_id}'
        ";

if(sql_query($sql)){
    $order = sql_fetch("select *,p.mb_id as pd_mb_id,o.pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'");

    //판매자에게 주문업데이트 알림
    $mb = get_member($order["pd_mb_id"]);
    if($order["pd_images"]){
        $imgs = explode(",",$pd["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    if($mb["regid"]) {
        send_FCM($mb["regid"], '48 주문 알림', cut_str($order["pd_tag"],10,"...")."의 주문이 완료 되었습니다.\r\n빠른 거래진행은 간편대화를 통해 진행해보세요!\r\n마이페이지 > 거래진행중 > 연락하기를 통해 가능합니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?od_cate=1&pd_type=".$order["pd_type"], 'fcm_buy_channel', "구매알림", $mb["mb_id"], $order["pd_id"], $img);
    }

    alert("주문이 정상 처리되었습니다.",G5_MOBILE_URL.'/page/mypage/mypage_order.php?od_cate=2&pd_type='.$order["pd_type"]);
}else{
    alert("주문 등록이 실패 하였습니다.");
}

?>