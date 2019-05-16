<?php
include_once ("../../common.php");

$sql = "select COUNT(*)as cnt from `product_like` where mb_id='{$mb_id}' and pd_id='{$pd_id}'";
$chklike = sql_fetch($sql);
if($chklike[cnt] != 0){
    //$result = 1;
    $result = 1;
    echo json_encode(array("result" => $result));
    return false;
}
$prochk = sql_fetch("select * from `product` where pd_id = {$pd_id}");
if($prochk["pd_type"] == 2) {
    $sql = "insert into `product_like` set pd_id='{$pd_id}', mb_id='{$mb_id}', pd_mb_id = '{$pd_mb_id}', like_content='{$like_content}', like_date = now(), pd_type = 2";
    if (sql_query($sql)) {
        $sql = "update `product_like` set pd_recom = pd_recom + 1 where pd_id = '{$pd_id}'";
        sql_query($sql);
        $sql = "select Count(*)as cnt from `product_like` where pd_id = '{$pd_id}'  and pd_type = 2";
        $pdcnt = sql_fetch($sql);
        $result = 2;
    } else {
        $result = 3;
    }
}else{
    $sql = "update `g5_member` set mb_4 = mb_4 + 1 , mb_5 = '{$like_content}' where mb_id = '{$prochk["mb_id"]}' ";
    if(sql_query($sql)){
        $sql = "insert into `product_like` set pd_id='{$pd_id}', mb_id='{$mb_id}', pd_mb_id = '{$pd_mb_id}', like_content='{$like_content}', like_date = now(), pd_type = 1";
        sql_query($sql);
        $sql = "select Count(*)as cnt from `product_like` where pd_id = '{$pd_id}' and pd_type = 1";
        $pdcnt = sql_fetch($sql);
        $result = 2;
    }else{
        $result = 3;
    }
}
echo json_encode(array("result" => $result,"count" => $pdcnt["cnt"]));