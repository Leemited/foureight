<?php
include_once ("./_common.php");

if($w == 'a' && !$is_admin)
    alert('답변은 관리자만 등록할 수 있습니다.');

$sql = " select * from {$g5['qa_content_table']} where qa_id = '$qa_id' ";
if(!$is_admin) {
    $sql .= " and mb_id = '{$member['mb_id']}' ";
}

$write = sql_fetch($sql);

if($w == 'u') {
    if(!$write['qa_id'])
        alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');

    if(!$is_admin) {
        if($write['qa_type'] == 0 && $write['qa_status'] == 1)
            alert('답변이 등록된 문의글은 수정할 수 없습니다.');

        if($write['mb_id'] != $member['mb_id'])
            alert('게시글을 수정할 권한이 없습니다.\\n\\n올바른 방법으로 이용해 주십시오.', G5_URL);
    }
}

if($w == 'a') {
    if(!$write['qa_id'])
        alert('문의글이 존재하지 않아 답변글을 등록할 수 없습니다.');

    if($write['qa_type'] == 1)
        alert('답변글에는 다시 답변을 등록할 수 없습니다.');
}

if($w == '' || $w == 'a' || $w == 'r') {
    if($w == '' || $w == 'r') {
        $row = sql_fetch(" select MIN(qa_num) as min_qa_num from {$g5['qa_content_table']} ");
        $qa_num = $row['min_qa_num'] - 1;
    }

    if($w == 'a') {
        $qa_num = $write['qa_num'];
        $qa_parent = $write['qa_id'];
        $qa_related = $write['qa_related'];
        $qa_category = $write['qa_category'];
        $qa_type = 1;
        $qa_status = 1;
    }

    $sql = " insert into {$g5['qa_content_table']}
                set qa_num          = '$qa_num',
                    mb_id           = '{$member['mb_id']}',
                    qa_name         = '".addslashes($member['mb_nick'])."',
                    qa_email        = '$qa_email',
                    qa_hp           = '$qa_hp',
                    qa_type         = '$qa_type',
                    qa_parent       = '$qa_parent',
                    qa_related      = '$qa_related',
                    qa_category     = '$qa_category',
                    qa_email_recv   = '$qa_email_recv',
                    qa_sms_recv     = '$qa_sms_recv',
                    qa_html         = '$qa_html',
                    qa_subject      = '$qa_subject',
                    qa_content      = '$qa_content',
                    qa_status       = '$qa_status',
                    qa_file1        = '{$upload[1]['file']}',
                    qa_source1      = '{$upload[1]['source']}',
                    qa_file2        = '{$upload[2]['file']}',
                    qa_source2      = '{$upload[2]['source']}',
                    qa_ip           = '{$_SERVER['REMOTE_ADDR']}',
                    qa_datetime     = '".G5_TIME_YMDHIS."',
                    qa_1            = '$qa_1',
                    qa_2            = '$qa_2',
                    qa_3            = '$qa_3',
                    qa_4            = '$qa_4',
                    qa_5            = '$qa_5' ";
    sql_query($sql);

    if($w == '' || $w == 'r') {
        $qa_id = sql_insert_id();

        if($w == 'r' && $write['qa_related']) {
            $qa_related = $write['qa_related'];
        } else {
            $qa_related = $qa_id;
        }

        $sql = " update {$g5['qa_content_table']}
                    set qa_parent   = '$qa_id',
                        qa_related  = '$qa_related'
                    where qa_id = '$qa_id' ";
        sql_query($sql);
    }

    if($w == 'a') {
        $sql = " update {$g5['qa_content_table']}
                    set qa_status = '1'
                    where qa_id = '{$write['qa_parent']}' ";
        sql_query($sql);
    }
} else if($w == 'u') {
    if(!$upload[1]['file'] && !$upload[1]['del_check']) {
        $upload[1]['file'] = $write['qa_file1'];
        $upload[1]['source'] = $write['qa_source1'];
    }

    if(!$upload[2]['file'] && !$upload[2]['del_check']) {
        $upload[2]['file'] = $write['qa_file2'];
        $upload[2]['source'] = $write['qa_source2'];
    }

    $sql = " update {$g5['qa_content_table']}
                set qa_email    = '$qa_email',
                    qa_hp       = '$qa_hp',
                    qa_category = '$qa_category',
                    qa_html     = '$qa_html',
                    qa_subject  = '$qa_subject',
                    qa_content  = '$qa_content',
                    qa_file1    = '{$upload[1]['file']}',
                    qa_source1  = '{$upload[1]['source']}',
                    qa_file2    = '{$upload[2]['file']}',
                    qa_source2  = '{$upload[2]['source']}',
                    qa_1        = '$qa_1',
                    qa_2        = '$qa_2',
                    qa_3        = '$qa_3',
                    qa_4        = '$qa_4',
                    qa_5        = '$qa_5' ";
    if($qa_sms_recv)
        $sql .= ", qa_sms_recv = '$qa_sms_recv' ";
    $sql .= " where qa_id = '$qa_id' ";
    sql_query($sql);
}

alert("답변등록이 완료 되었습니다.");




