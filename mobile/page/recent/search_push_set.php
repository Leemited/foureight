<?php
include_once ("../../../common.php");

if($sc_id=="" || !$sc_id){
    alert("잘못된 요청입니다.");
    return false;
}

$sc_alarm = "1";
if($setpush=="on"){
    $sc_alarm = "1";
}else if($setpush=="off"){
    $sc_alarm = "0";
}

$sql = "update `my_search_list` set set_alarm = {$sc_alarm} where sc_id = '{$sc_id}'";

if(sql_query($sql)){
    alert("정상 처리되었습니다.",G5_URL."/mobile/page/recent/recent.list.php");
}else{
    alert("잘못된 요청입니다.");
}
?>