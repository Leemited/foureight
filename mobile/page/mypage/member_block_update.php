<?php
include_once ("../../../common.php");

if($type=="cancel"){
    $sql = "delete from `member_block` where id = '{$id}'";
    if(sql_query($sql)){
        alert("차단이 해제 되었습니다.");
    }else{
        alert("이미 해제 되었거나 없는 회원 정보입니다.");
    }
}else {
    switch ($block_date) {
        case "1":
            $block_dateTo = date("Y-m-d H:i:s", strtotime(" + 1 month"));
            break;
        case "2":
            $block_dateTo = date("Y-m-d H:i:s", strtotime(" + 6 month"));
            break;
        case "3":
            $block_dateTo = date("Y-m-d H:i:s", strtotime(" + 10 year"));
            $block_status = " , block_status = 1 ";
            break;
    }

    $sql = "insert into `member_block` set mb_id = '{$mb_id}', target_id = '{$target_id}', block_dateFrom = now(), block_dateTo = '{$block_dateTo}' {$block_status}";
    if (sql_query($sql)) {
        alert("차단되었습니다.", G5_URL);
    } else {
        alert("처리 오류 입니다. \r다시 시도해 주세요.");
    }
}
?>