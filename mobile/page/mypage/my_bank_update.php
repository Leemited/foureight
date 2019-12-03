<?php
include_once ("../../../common.php");

if(!$is_member){
    alert("로그인 후 이용 가능합니다.",G5_MOBILE_URL."/page/login.intro.php");
}

if($mode=="insert"){
    $sql = "select count(*) as cnt from `my_bank` where mb_id = '{$member["mb_id"]}' and bank_status = 1 ";
    $bankcnt = sql_fetch($sql);
    if($bankcnt["cnt"]==0){
        $where = " , bank_status = 1 ";
    }else{
        $where = " , bank_status = 0 ";
    }

    if($bankcnt["cnt"]>=3){
        alert("계좌번호 등록 가능 수를 초과하였습니다.");
    }

    $bank_number = base64_encode($bank_number);

    $sql = "insert into `my_bank` set account_name = '{$account_name}',bank_name = '{$bank_name}',bank_number= '{$bank_number}', bank_date = now(), mb_id = '{$member["mb_id"]}' {$where}";
    if(sql_query($sql)){
        alert("등록되었습니다.",G5_MOBILE_URL."/page/mypage/my_bank.php");
    }else{
        alert("잘못된 계좌 정보/요청 입니다.",G5_MOBILE_URL."/page/mypage/my_bank.php");
    }
}else if($mode=="owner"){
    $sql = "select * from `my_bank` where bank_status = 1";
    $ids = sql_fetch($sql);

    $sql = "update `my_bank` set bank_status = 0 where id = {$ids["id"]}";
    sql_query($sql);
    $sql = "update `my_bank` set bank_status = 1 where id = '{$id}'";
    if(sql_query($sql)) {
        alert("기본계좌로 등록되었습니다.",G5_MOBILE_URL."/page/mypage/my_bank.php");
    }else{
        $sql = "update `my_bank` set bank_status = 1 where id = {$ids["id"]}";
        sql_query($sql);
        alert("잘못된 요청으로 기존 기본계좌를 유지합니다.",G5_MOBILE_URL."/page/mypage/my_bank.php");
    }
}else if($mode=="del"){
    $sql = "delete from `my_bank` where id = {$id}";
    if(sql_query($sql)){
        alert("삭제 되었습니다.",G5_MOBILE_URL."/page/mypage/my_bank.php");
    }else{
        alert("다시 시도해 주세요.",G5_MOBILE_URL."/page/mypage/my_bank.php");
    }
}else{
    alert("잘못된 요청입니다.",G5_MOBILE_URL."/page/mypage/my_bank.php");
}