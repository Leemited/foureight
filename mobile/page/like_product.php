<?php
include_once ("../../common.php");
//해당 게시물의 주문건을 조회해서 해당 주문번호와 일치한 댓글이 있는지 파악

$sql = "select COUNT(*)as cnt from `product_like` where mb_id='{$mb_id}' and pd_id='{$pd_id}' and od_id = '{$od_id}'";
$chklike = sql_fetch($sql);
if($chklike['cnt'] != 0){
    //$result = 1;
    $result = 1;
    echo json_encode(array("result" => $result));
    return false;
}
$prochk = sql_fetch("select * from `product` where pd_id = {$pd_id}");
if($prochk["pd_type"] == 2) {//능력
    if($likeup=="on"){
        $sql = "update `procuet` set pd_recom = pd_recom + 1 where mb_id = '{$pd_id}' ";
        sql_query($sql);
    }

    $sql = "insert into `product_like` set pd_id='{$pd_id}', mb_id='{$mb_id}', od_id = '{$od_id}',pd_mb_id = '{$prochk["mb_id"]}', like_content='{$like_content}', like_date = now(), pd_type = 2";
    if (sql_query($sql)) {
        /*$sql = "update `product_like` set pd_recom = pd_recom + 1 where pd_id = '{$pd_id}'";
        sql_query($sql);*/
        $sql = "select Count(*)as cnt from `product_like` where pd_id = '{$pd_id}'  and pd_type = 2";
        $pdcnt = sql_fetch($sql);
        $result = 2;
    } else {
        $result = 3;
    }
}else{//물건
    if($likeup=="on"){
        $sql = "update `g5_member` set mb_4 = mb_4 + 1 where mb_id = '{$prochk["mb_id"]}' ";
        sql_query($sql);
    }
    //if(sql_query($sql)){
    $sql = "insert into `product_like` set pd_id='{$pd_id}', mb_id='{$mb_id}', od_id = '{$od_id}', pd_mb_id = '{$prochk["mb_id"]}', like_content='{$like_content}', like_date = now(), pd_type = 1";
    if (sql_query($sql)) {
        if($fin_type==1){
            //물건 거래 완료 처리
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
            }else{
                $result = 4;
            }
        }
        $sql = "select Count(*)as cnt from `product_like` where pd_id = '{$pd_id}' and pd_type = 1";
        $pdcnt = sql_fetch($sql);
        $result = 2;
    }else{
        $result = 3;
    }
}
echo json_encode(array("result" => $result,"count" => $pdcnt["cnt"],"REQUEST",$_REQUEST));