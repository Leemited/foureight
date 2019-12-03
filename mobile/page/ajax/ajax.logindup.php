<?php
include_once ("../../../common.php");
//로그인 기록
// 현재접속자 처리
set_session("device",$device);
set_session("mac",$mac);

$tmp_sql = " select count(*) as cnt from {$g5['login_table']} where lo_device = '{$device}' and lo_device_id = '{$mac}' and mb_id='{$member["mb_id"]}'";
$tmp_row = sql_fetch($tmp_sql);

if ($tmp_row['cnt']) {
    $tmp_sql = " update {$g5['login_table']} set mb_id = '{$member['mb_id']}', lo_datetime = '".G5_TIME_YMDHIS."', lo_location = '{$g5['lo_location']}', lo_url = '{$g5['lo_url']}' where lo_device = '{$device}' and lo_device_id = '{$mac}' and mb_id='{$member["mb_id"]}'";
} else {
    $tmp_sql = " insert into {$g5['login_table']} ( lo_ip, mb_id, lo_datetime, lo_location, lo_url,lo_device,lo_device_id ) values ( '{$_SERVER['REMOTE_ADDR']}', '{$member['mb_id']}', '".G5_TIME_YMDHIS."', '{$g5['lo_location']}',  '{$g5['lo_url']}','{$device}','{$mac}') ";
    // 시간이 지난 접속은 삭제한다
    sql_query(" delete from {$g5['login_table']} where lo_datetime < '".date("Y-m-d H:i:s", G5_SERVER_TIME - (60 * $config['cf_login_minutes']))."' ");
}

if(sql_query($tmp_sql)){
    echo "1";
}else{
    echo "2";
}