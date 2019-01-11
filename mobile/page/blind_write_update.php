<?php
include_once ("../../common.php");
if($blind_con=="기타") {
    if($blind_con_txt==""){
        alert("기타 내용을 입력해주세요.");
    }
    $blind_con = $blind_con_txt;
}


$sql = "select * from `product` where pd_id = '{$pd_id}'";
$mypro = sql_fetch($sql);
if($mypro["mb_id"] == $mb_id){
    alert("자신의 글은 신고할 수 없습니다.");
    return false;
}


$sql = "select count(*) as cnt from `product_blind` where mb_id = '{$member["mb_id"]}' and pd_id = '{$pd_id}'";
$myblind = sql_fetch($sql);
if($myblind["cnt"] > 0){
    alert("이미 신고한 게시물입니다.");
    return false;
}

$sql = "insert into `product_blind` set mb_id= '{$member["mb_id"]}', pd_id='{$pd_id}', blind_content = '{$blind_con}', blind_date = now()";


if(sql_query($sql)){
    $sql = "update `product` set pd_blind = pd_blind + 1 where pd_id ='{$pd_id}'";
    sql_query($sql);
    alert("신고처리 되었습니다. 신고는 게시물당 한번만 할 수 있으며 누락 10건일 경우 자동 블라인드 처리됩니다.");
}else{
   alert("신고처리가 제대로 되지 않았습니다. \r다시 시도해 주세요.");
}