<?php
include_once("./_common.php");

if (!isset($_POST['wr_subject']) || !trim($_POST['wr_subject']))
    alert('제목을 입력하여 주십시오.');

if (!isset($_POST['wr_content']) || !trim($_POST['wr_content']))
    alert('내용을 입력하여 주십시오.');

$wr_subject = '';
if (isset($_POST['wr_subject'])) {
    $wr_subject = substr(trim($_POST['wr_subject']),0,255);
    $wr_subject = preg_replace("#[\\\]+$#", "", $wr_subject);
}
if ($wr_subject == '') {
    $msg[] = '<strong>제목</strong>을 입력하세요.';
}

$wr_content = '';
if (isset($_POST['wr_content'])) {
    $wr_content = substr(trim($_POST['wr_content']),0,65536);
    $wr_content = preg_replace("#[\\\]+$#", "", $wr_content);
}
if ($wr_content == '') {
    $msg[] = '<strong>내용</strong>을 입력하세요.';
}

// 090710
if (substr_count($wr_content, '&#') > 50) {
    alert('내용에 올바르지 않은 코드가 다수 포함되어 있습니다.');
    exit;
}

if($w == ''){
	$sql = " insert into $write_table
                set wr_num = '$wr_num',
                     wr_reply = '$wr_reply',
                     wr_comment = 0,
                     ca_name = '$ca_name',
                     wr_option = '$html,$secret,$mail',
                     wr_subject = '$wr_subject',
                     wr_content = '$wr_content',
                     wr_link1 = '$wr_link1',
                     wr_link2 = '$wr_link2',
                     wr_link1_hit = 0,
                     wr_link2_hit = 0,
                     wr_hit = 0,
                     wr_good = 0,
                     wr_nogood = 0,
                     mb_id = '{$member['mb_id']}',
                     wr_password = '$wr_password',
                     wr_name = '$wr_name',
                     wr_email = '$wr_email',
                     wr_homepage = '$wr_homepage',
                     wr_datetime = '".G5_TIME_YMDHIS."',
                     wr_last = '".G5_TIME_YMDHIS."',
                     wr_ip = '{$_SERVER['REMOTE_ADDR']}',
                     wr_1 = '$wr_1',
                     wr_2 = '$wr_2',
                     wr_3 = '$wr_3',
                     wr_4 = '$wr_4',
                     wr_5 = '$wr_5',
                     wr_6 = '$wr_6',
                     wr_7 = '$wr_7',
                     wr_8 = '$wr_8',
                     wr_9 = '$wr_9',
                     wr_10 = '$wr_10' ";
    sql_query($sql);

    $wr_id = sql_insert_id();

    // 부모 아이디에 UPDATE
    sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

    // 새글 INSERT
    sql_query(" insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '".G5_TIME_YMDHIS."', '{$member['mb_id']}' ) ");

    // 게시글 1 증가
    sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");
	
	alert("등록되었습니다.",G5_URL."/admin/board_view.php?bo_table=".$bo_table."&wr_id=".$wr_id);


}else if($w == 'u'){
	
	$wr = get_write($write_table, $wr_id);
	if (!$wr['wr_id']) {
		alert("글이 존재하지 않습니다.\\n글이 삭제되었거나 이동하였을 수 있습니다.");
	}
	

	$sql_password = $wr_password ? " , wr_password = '".get_encrypt_string($wr_password)."' " : "";

	$sql_ip = '';
	if (!$is_admin)
		$sql_ip = " , wr_ip = '{$_SERVER['REMOTE_ADDR']}' ";

	$sql = " update {$write_table}
			set ca_name = '{$ca_name}',
				 wr_option = '{$html},{$secret},{$mail}',
				 wr_subject = '{$wr_subject}',
				 wr_content = '{$wr_content}',
				 wr_link1 = '{$wr_link1}',
				 wr_link2 = '{$wr_link2}',
				 mb_id = '{$mb_id}',
				 wr_name = '{$wr_name}',
				 wr_email = '{$wr_email}',
				 wr_homepage = '{$wr_homepage}',
				 wr_1 = '{$wr_1}',
				 wr_2 = '{$wr_2}',
				 wr_3 = '{$wr_3}',
				 wr_4 = '{$wr_4}',
				 wr_5 = '{$wr_5}',
				 wr_6 = '{$wr_6}',
				 wr_7 = '{$wr_7}',
				 wr_8 = '{$wr_8}',
				 wr_9 = '{$wr_9}',
				 wr_10= '{$wr_10}'
				 {$sql_ip}
				 {$sql_password}
		  where wr_id = '{$wr['wr_id']}' ";
	sql_query($sql);

	// 분류가 수정되는 경우 해당되는 코멘트의 분류명도 모두 수정함
	// 코멘트의 분류를 수정하지 않으면 검색이 제대로 되지 않음
	$sql = " update {$write_table} set ca_name = '{$ca_name}' where wr_parent = '{$wr['wr_id']}' ";
	sql_query($sql);

	/*
	if ($notice) {
		//if (!preg_match("/[^0-9]{0,1}{$wr_id}[\r]{0,1}/",$board['bo_notice']))
		if (!in_array((int)$wr_id, $notice_array)) {
			$bo_notice = $wr_id . ',' . $board['bo_notice'];
			sql_query(" update {$g5['board_table']} set bo_notice = '{$bo_notice}' where bo_table = '{$bo_table}' ");
		}
	} else {
		$bo_notice = '';
		for ($i=0; $i<count($notice_array); $i++)
			if ((int)$wr_id != (int)$notice_array[$i])
				$bo_notice .= $notice_array[$i] . ',';
		$bo_notice = trim($bo_notice);
		//$bo_notice = preg_replace("/^".$wr_id."[\n]?$/m", "", $board['bo_notice']);
		sql_query(" update {$g5['board_table']} set bo_notice = '{$bo_notice}' where bo_table = '{$bo_table}' ");
	}
*/

	$bo_notice = board_notice($board['bo_notice'], $wr_id, $notice);
	sql_query(" update {$g5['board_table']} set bo_notice = '{$bo_notice}' where bo_table = '{$bo_table}' ");
	
	alert("수정되었습니다.",G5_URL."/admin/board_view.php?bo_table=".$bo_table."&wr_id=".$wr_id);
}

?>