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
    $likeupChk = 0;
    if($likeup=="up"){
        $sql = "update `product` set pd_recom = pd_recom + 1 where pd_id = '{$pd_id}' ";
        sql_query($sql);

        $likeupChk = 1;
    }

    $sql = "insert into `product_like` set pd_id='{$pd_id}', mb_id='{$mb_id}', od_id = '{$od_id}',pd_mb_id = '{$prochk["mb_id"]}', like_content='{$like_content}', like_date = now(), pd_type = 2, like_status = '{$likeupChk}'";
    if (sql_query($sql)) {
        if($review=="") {
            //주문상태 업데이트
            if ($od_id) {
                $sql = "update `order` set od_fin_status = 1,od_fin_datetime = now() where od_id = '{$od_id}'";
                sql_query($sql);
            }
        }
        /*$sql = "update `product_like` set pd_recom = pd_recom + 1 where pd_id = '{$pd_id}'";
        sql_query($sql);*/
        $sql = "select Count(*)as cnt from `product_like` where pd_id = '{$pd_id}'  and pd_type = 2";
        $pdcnt = sql_fetch($sql);

        $mb = get_member($prochk["mb_id"]);
        if ($prochk["pd_images"]) {
            $imgs = explode(",", $prochk["pd_images"]);
            $img = G5_DATA_URL . "/product/" . $imgs[0];
        }
        if($mb["regid"]){//기기정보 있을때
            send_FCM($mb["regid"], "48 거래 완료 알림", cut_str($prochk["pd_tag"],10,"...") . "의 거래가 완료되었습니다.\r\n거래대금 정산은 4일이내에 자동 처리됩니다.\r\n정산계좌가 등록되지 않으면 지연될 수 있습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order.php?type=1&od_cate=1&pd_type=".$prochk["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img,'','');
        }
        $result = 2;
    } else {
        $result = 3;
    }
}else{//물건
    $likeupChk = 0;
    if($likeup=="up"){
        $sql = "update `g5_member` set mb_4 = mb_4 + 1 where mb_id = '{$prochk["mb_id"]}' ";
        sql_query($sql);
        $likeupChk = 1;
    }
    //if(sql_query($sql)){
    $sql = "insert into `product_like` set pd_id='{$pd_id}', mb_id='{$mb_id}', od_id = '{$od_id}', pd_mb_id = '{$prochk["mb_id"]}', like_content='{$like_content}', like_date = now(), pd_type = 1, like_status = '{$likeupChk}'";
    if (sql_query($sql)) {
        if($fin_type==1){
            //물건 거래 완료 처리
            $sql = "update `order` set od_fin_status = 1,od_fin_confirm = 1, od_admin_status = 1,od_fin_datetime = now() where od_id = '{$od_id}'";
            if(sql_query($sql)){
                //물건 판매완료 처리
                sql_query("update `product` set pd_status = 10 where pd_id = '{$order["pd_id"]}'");

                $mb = get_member($prochk["mb_id"]);
                if ($prochk["pd_images"]) {
                    $imgs = explode(",", $prochk["pd_images"]);
                    $img = G5_DATA_URL . "/product/" . $imgs[0];
                }
                if($mb["regid"]){//기기정보 있을때
                    send_FCM($mb["regid"], "48 거래 완료 알림", cut_str($prochk["pd_tag"],10,"...") . "의 거래가 완료되었습니다.\r\n거래대금 정산은 4일이내에 자동 처리됩니다.\r\n정산계좌가 등록되지 않으면 지연될 수 있습니다.", G5_MOBILE_URL . "/page/mypage/mypage_order_complete.php?type=1&od_cate=1&pd_type=".$prochk["pd_type"], 'fcm_buy_channel', '구매알림', $mb["mb_id"], $pd_id, $img,'','');
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