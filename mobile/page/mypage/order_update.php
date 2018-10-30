<?php
include_once ("../../../common.php");

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
        }*/
        alert("결제 완료 되었습니다.",G5_MOBILE_URL."/page/mypage/order_history.php");
    }else{
        alert("주문 등록 오류 입니다. \r관리자에게 문의해 주세요.");
    }
}


?>