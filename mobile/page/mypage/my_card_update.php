<?php
include_once ("../../../common.php");

if(!$is_member){
    alert("로그인 후 이용 가능합니다.",G5_MOBILE_URL."/page/login.intro.php");
}

if($mode=="insert"){
    $card_com = explode("/",$card_company);
    $card_num = implode("-",$card_number);
    $sql = "insert into `my_card` set card_name = '{$card_name}', card_company_name = '{$card_com[1]}', card_company_code = {$card_com[0]}, card_year = {$card_year}, card_month = {$card_month}, card_number= '{$card_num}', card_status =0, card_date = now(), mb_id = '{$member["mb_id"]}'";

    if(sql_query($sql)){
        alert("등록되었습니다.");
    }else{
        alert("잘못된 카드 정보/요청 입니다.");
    }
}else if($mode=="owner"){
    $sql = "select * from `my_card` where card_status = 1";
    $ids = sql_fetch($sql);

    $sql = "update `my_card` set card_status = 0 where id = {$ids["id"]}";
    sql_query($sql);
    $sql = "update `my_card` set card_status = 1 where id = '{$id}'";
    if(sql_query($sql)) {
        alert("기본카드로 등록되었습니다.");
    }else{
        $sql = "update `my_card` set card_status = 1 where id = {$ids["id"]}";
        sql_query($sql);
        alert("잘못된 요청으로 기존 기본카드를 유지합니다.");
    }
}else if($mode=="del"){
    $sql = "delete from `my_card` where id = {$id}";
    if(sql_query($sql)){
        alert("삭제 되었습니다.");
    }else{
        alert("다시 시도해 주세요.");
    }
}else{
    alert("잘못된 요청입니다.");
}