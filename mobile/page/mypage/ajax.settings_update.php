<?php
include_once("../../../common.php");

$type = $_REQUEST["type"];
$state = $_REQUEST["state"];
$mb_id = $_REQUSET["mb_id"];
if(!$mb_id){
	$mb_id = $member["mb_id"];
}
if($type=="push_set"){
    $sql = "update `mysetting` set `{$type}` = '{$state}', etiquette_set = '{$state}',comment_alarm_set = '{$state}',notice_alarm_set = '{$state}',pay_reser_set = '{$state}',pricing_set = '{$state}',chat_alarm_set = '{$state}',recomment_alarm_set = '{$state}',search_alarm_set = '{$state}' where mb_id ='{$mb_id}'";
}else {
    $sql = "update `mysetting` set `{$type}` = '{$state}' where mb_id = '{$mb_id}'";
}
echo $sql;
if (sql_query($sql)) {
    echo "A";
} else {
    echo "B";
}

?>