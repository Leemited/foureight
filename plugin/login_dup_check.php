<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// ===========================================================
//	그누보드 G5 중복로그인 체크  v 0.1
//	Copyright(c) 2013 그누보드 닉 마스타 All rights reserved.
// ===========================================================

if ($member['mb_id']) {
	if($app || $app2){
	    if($device){
	        set_session("device",$device);
        }else{
	        $device = $_SESSION["device"];
        }
        if($mac){
	        set_session("mac",$mac);
        }else{
            $mac = $_SESSION["mac"];
        }

        // 내 기기 가 아닌 다른 기기 에서 로그인 한 게 있는가?
        $tmp_sql = " select * from {$g5['login_table']} ";
        $tmp_sql .= "	where mb_id = '{$member['mb_id']}' and lo_device <> '' and lo_device_id <> '' and (lo_device <> '{$device}' or lo_device_id <> '{$mac}')";
        $tmp_sql .= "	order by lo_datetime desc ";
        $tmp_row = sql_fetch($tmp_sql);
        if ($tmp_row['mb_id']) {
            // 다른 데서 로그인 된 정보가 있다..
            $tmp_device_other = $tmp_row['lo_device'];        // 로그인된 다른 곳의 기기
            $tmp_device_id_other = $tmp_row['lo_device_id'];        // 로그인된 다른 곳의 기기 MAC

            // 중복로그인 저장 테이블을 없으면 만든다.
            $g5['login_dup_table'] = G5_TABLE_PREFIX . 'login_dup'; // 로그인 중복정보 테이블
			
			//테이블 생성
            if (!sql_query(" DESC {$g5['login_dup_table']} ", FALSE)) {
                sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['login_dup_table']}` (
							`ld_id` int(11) NOT NULL AUTO_INCREMENT,
							`mb_id` varchar(150) NOT NULL,
							`ld_datetime` datetime NOT NULL,
							`ld_ip_before` varchar(255) NOT NULL,
							`ld_ip_after` varchar(255) NOT NULL,
							`ld_status` varchar(255) NOT NULL,
							`ld_device` varchar(45) NOT NULL,
							`ld_device_id` varchar(255) NOT NULL,
							`ld_device_after` varchar(45) NOT NULL,
							`ld_device_id_after` varchar(255) NOT NULL,
							PRIMARY KEY (`ld_id`)
						) ", FALSE);
            }

            // 내가 ld_device_id 에 걸린게 있는지 확인해 본다.
            $tmp_sql = " select * from {$g5['login_dup_table']} where mb_id = '{$member['mb_id']}' and ld_device_id = '{$mac}' and ld_device_id <> '' and ld_status = '' ";
            $tmp_row = sql_fetch($tmp_sql);
            if ($tmp_row['mb_id']) {
                // 내가 ip_before 에 걸린게 있다. 나를 logout 시킨다.
                $tmp_sql = " update {$g5['login_dup_table']} set ld_status = 'logout_before' where ld_id = " . $tmp_row['ld_id'] . " ";
                sql_query($tmp_sql, FALSE);

                // 여기 IP 에서의 로그인 정보는 지워버린다. => 활동하면 다시 생기므로 지워도 상관없다.
                sql_query(" delete from {$g5['login_table']} where mb_id = '{$member['mb_id']}' and lo_device_id = '{$mac}' ");

                echo "<script>alert('" . $tmp_device_other . " 에서 로그인 되어서, 현재 세션이 로그아웃됩니다.');</script>";
                echo "<script>location.href = '" . G5_BBS_URL . "/logout.php';</script>";
                break;    // 이걸 해주지 않으면, 어딘가에서 또 한 record 가 삽입되어 버린다.
            } else {
                // 중복로그인 정보를 입력한다.
                $tmp_sql = " insert into {$g5['login_dup_table']} ( mb_id, ld_datetime, ld_ip_before, ld_ip_after,ld_device, ld_device_after,ld_device_id,ld_device_id_after) values ( '{$member['mb_id']}', '" . G5_TIME_YMDHIS . "', '" . $tmp_ip_other . "',  '{$_SERVER['REMOTE_ADDR']}','{$tmp_device_other}','{$device}','{$tmp_device_id_other}','{$mac}') ";
                sql_query($tmp_sql, false);

                // 다른 IP 에서의 로그인 정보는 지워버린다. => 다른 곳에서 페이지 옮겨 다니면 다시 생기므로 지워도 상관없다.
                sql_query(" delete from {$g5['login_table']} where mb_id = '{$member['mb_id']}' and lo_device_id <> '' ");

                echo "<script>alert('" . $tmp_device_other . " 에서 로그인 된 정보가 있습니다.');</script>";

            }
            // 오래된 중복로그인 정보는 삭제한다. 14일 이전 데이타는 삭제한다.
            sql_query(" delete from {$g5['login_dup_table']} where ld_datetime < '" . date("Y-m-d H:i:s", strtotime("-14 day", time())) . "' ");
        }
        
	}else {
        // 내 IP 가 아닌 다른 IP 에서 로그인 한 게 있는가?
        $tmp_sql = " select * from {$g5['login_table']} ";
        $tmp_sql .= "	where mb_id = '{$member['mb_id']}' and lo_ip <> '{$_SERVER['REMOTE_ADDR']}' and isnull(lo_device) and isnull(lo_device_id)";
        $tmp_sql .= "	order by lo_datetime desc ";
        $tmp_row = sql_fetch($tmp_sql);
        if ($tmp_row['mb_id']) {
            // 다른 데서 로그인 된 정보가 있다..
            $tmp_ip_other = $tmp_row['lo_ip'];        // 로그인된 다른 곳의 IP

            // 중복로그인 저장 테이블을 없으면 만든다.
            $g5['login_dup_table'] = G5_TABLE_PREFIX . 'login_dup'; // 로그인 중복정보 테이블

            if (!sql_query(" DESC {$g5['login_dup_table']} ", FALSE)) {
                sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['login_dup_table']}` (
							`ld_id` int(11) NOT NULL AUTO_INCREMENT,
							`mb_id` varchar(150) NOT NULL,
							`ld_datetime` datetime NOT NULL,
							`ld_ip_before` varchar(255) NOT NULL,
							`ld_ip_after` varchar(255) NOT NULL,
							`ld_status` varchar(255) NOT NULL,
							PRIMARY KEY (`ld_id`)
						) ", FALSE);
            }

            //현재 등록된 아이피확인
            $chk_sql = "select * from `mydevice` where mb_id = '{$member["mb_id"]}'";
            $chk_row = sql_fetch($chk_sql);
            if($chk_row==null){
                echo "VB";
                // 여기 IP 에서의 로그인 정보는 지워버린다. => 활동하면 다시 생기므로 지워도 상관없다.
                sql_query(" delete from {$g5['login_table']} where mb_id = '{$member['mb_id']}' and lo_ip = '{$_SERVER['REMOTE_ADDR']}' and isnull(lo_device)");

                echo "<script>alert('등록되지 않은 기기에서 로그인 되어 로그아웃됩니다.');</script>";
                echo "<script>location.href = '" . G5_BBS_URL . "/logout.php';</script>";
                break;    // 이걸 해주지 않으면, 어딘가에서 또 한 record 가 삽입되어 버린다.
            }else{
                echo "AB";
                $device_ip = str_replace("-",".",base64_decode($chk_row["device_ip"]));
                if($_SERVER['REMOTE_ADDR']!=$device_ip){
                    sql_query(" delete from {$g5['login_table']} where mb_id = '{$member['mb_id']}' and lo_ip = '{$_SERVER['REMOTE_ADDR']}' and isnull(lo_device)");
                    echo "<script>alert('등록되지 않은 기기에서 로그인 되어 로그아웃됩니다.');</script>";
                    echo "<script>location.href = '" . G5_BBS_URL . "/logout.php';</script>";
                    break;    // 이걸 해주지 않으면, 어딘가에서 또 한 record 가 삽입되어 버린다.
                }
            }

            // 내가 ip_before 에 걸린게 있는지 확인해 본다.
            $tmp_sql = " select * from {$g5['login_dup_table']} where mb_id = '{$member['mb_id']}' and ld_ip_before = '{$_SERVER['REMOTE_ADDR']}' and ld_status = '' ";
            $tmp_row = sql_fetch($tmp_sql);

            if ($tmp_row['mb_id']) {
                // 내가 ip_before 에 걸린게 있다. 나를 logout 시킨다.
                $tmp_sql = " update {$g5['login_dup_table']} set ld_status = 'logout_before' where ld_id = " . $tmp_row['ld_id'] . " and ld_device = ''";
                sql_query($tmp_sql, FALSE);

                // 여기 IP 에서의 로그인 정보는 지워버린다. => 활동하면 다시 생기므로 지워도 상관없다.
                sql_query(" delete from {$g5['login_table']} where mb_id = '{$member['mb_id']}' and lo_ip = '{$_SERVER['REMOTE_ADDR']}' and isnull(lo_device)");

                echo "<script>alert('" . $tmp_ip_other . " 에서 로그인 되어서, 현재 세션이 로그아웃됩니다.');</script>";
                echo "<script>location.href = '" . G5_BBS_URL . "/logout.php';</script>";
                break;    // 이걸 해주지 않으면, 어딘가에서 또 한 record 가 삽입되어 버린다.
            } else {

                // 중복로그인 정보를 입력한다.
                $tmp_sql = " insert into {$g5['login_dup_table']} ( mb_id, ld_datetime, ld_ip_before, ld_ip_after ) values ( '{$member['mb_id']}', '" . G5_TIME_YMDHIS . "', '" . $tmp_ip_other . "',  '{$_SERVER['REMOTE_ADDR']}' ) ";
                sql_query($tmp_sql, false);

                // 다른 IP 에서의 로그인 정보는 지워버린다. => 다른 곳에서 페이지 옮겨 다니면 다시 생기므로 지워도 상관없다.
                sql_query(" delete from {$g5['login_table']} where mb_id = '{$member['mb_id']}' and lo_ip <> '{$_SERVER['REMOTE_ADDR']}' and isnull(lo_device)");

                echo "<script>alert('" . $tmp_ip_other . " 에서 로그인 된 정보가 있습니다.');</script>";

            }
            // 오래된 중복로그인 정보는 삭제한다. 14일 이전 데이타는 삭제한다.
            sql_query(" delete from {$g5['login_dup_table']} where ld_datetime < '" . date("Y-m-d H:i:s", strtotime("-14 day", time())) . "' ");

        }
    }
}
?>
