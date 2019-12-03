<?php
include_once ("../../../common.php");
if($sc_id!="all"){
    $sql = "delete  from `my_search_list` where sc_id = '{$sc_id}'";
}else{
    if($mb_id==""){
        alert("잘못된 정보입니다.");
    }
    $sql = "delete  from `my_search_list` where mb_id = '{$mb_id}'";
}

if(sql_query($sql)){
    alert("삭제되었습니다.",G5_URL."/mobile/page/recent/recent.list.php");
}else{
    alert("잘못된 요청입니다. 다시한번 시도해 주세요.");
}