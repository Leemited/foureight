<?php
include_once ("../../common.php");

$sql = "select COUNT(*)as cnt from `product_like` where mb_id='{$mb_id}' and pd_id='{$pd_id}'";
$chklike = sql_fetch($sql);
if($chklike[cnt] != 0){
    //$result = 1;
    echo 1;
    return false;
}
$sql = "insert into `product_like` set pd_id='{$pd_id}', mb_id='{$mb_id}', like_content='{$like_content}', like_date = now()";
if(sql_query($sql)){
    $sql = "update `product_like` set pd_recom = pd_recom + 1 where pd_id = '{$pd_id}'";
    sql_query($sql);
    $sql = "select Count(*)as cnt from `product_like` where pd_id = '{$pd_id}'";
    $pdcnt = sql_fetch($sql);
    $result = 2;
}else{
    $result = 3;
}
echo json_encode(array("result" => $result,"count" => $pdcnt["cnt"]));