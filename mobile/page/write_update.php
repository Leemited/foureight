<?php
include_once("../../common.php");

$wr_subject = $sub_title;

if(!$wr_subject){
	alert("제목을 입력해 주세요");
	return false;
}
if($wr_content){
	//$wr_content = conv_content($wr_content);
	// 090710
	if (substr_count($wr_content, '&#') > 50) {
		alert('내용에 올바르지 않은 코드가 다수 포함되어 있습니다.');
		exit;
	}
}

$filter = explode(",",$config["cf_filter"]);

for($i=0;$i<count($filter);$i++){
    if(strpos($wr_subject,$filter[$i])!==false){
        $text = string_star($filter[$i],'harf','left');
        alert("제목에 부적절한 단어[{$text}]가 포함되어 있습니다.");
        exit;
    }
    if(strpos($wr_content,$filter[$i])!==false){
        $text = string_star($filter[$i],'harf','left');
        alert("내용에 부적절한 단어[{$text}]가 포함되어 있습니다.");
        exit;
    }
    if(strpos($sub_title,$filter[$i])!==false){
        $text = string_star($filter[$i],'harf','left');
        alert("검색어에 부적절한 단어[{$text}]가 포함되어 있습니다.");
        exit;
    }
    if(strpos($pd_infos,$filter[$i])!==false){
        $text = string_star($filter[$i],'harf','left');
        alert("능력 거래 조건 및 유의 사항에 부적절한 단어[{$text}]가 포함되어 있습니다.");
        exit;
    }
}

if($type==""){
	$type = "1";
}

if (count($words) > 1) {
    $words = implode("\r\n", $words);
}else if (count($words) == 1) {
    $words = $words[0];
}else if(count($words) == 0){
    $words = '';
}

//$wr_content = $words.' '.$wr_content;

if(count($links) > 1) {
    $links = array_filter($links);
    for($i=0;$i<count($links);$i++){
        $links2[] = array_pop(explode("/",$links[$i]));
    }
    $links = implode(",", $links2);
}else if(count($links) == 1) {
    $links = array_pop(explode("/",$links[0]));
}else if(count($links) == 0){
    $links = '';
}

$price = str_replace(",","",$price);
$price2 = str_replace(",","",$price2);

if(!$pd_id || $pd_id == ""){

	//등록
	$sql = "insert into `product` set
			pd_name = '{$wr_subject}',
			pd_type = '{$type}',
			pd_type2 = '{$type2}',
			pd_cate = '{$cate1}',
			pd_cate2 = '{$cate2}',
			pd_images = '{$filename}',
			pd_video = '{$videoname}',
			pd_content = '{$wr_content}',
			pd_tag = '{$sub_title}',
			pd_location = '{$locs_name}',
			pd_location_name = '{$locs}',
			pd_price = '{$price}',
			pd_price2 = '{$price2}',
			pd_infos = '{$pd_infos}',
			pd_status = 0,
			mb_id = '{$mb_id}',
			pd_words = '{$words}',
			pd_video_link = '{$links}',
			pd_date = now(),
			pd_update = now(),
			pd_discount = '{$discount_use}',
			pd_lat = '{$pd_lat}',
			pd_lng = '{$pd_lng}',
			pd_update_cnt = 0,
            pd_timeFrom = '{$pd_timeFrom}',
            pd_timeTo = '{$pd_timeTo}',
            pd_timetype = '{$pd_timeType}',
            pd_price_type = '{$pd_price_type}'";
	if(!sql_query($sql)){
	    alert("입력 오류 입니다.다시 요청해 주세요");
    }
	$pd_id = sql_insert_id();

	if($type == 2 && $type2 == 8){
        $search = " and sc_price_type = '{$pd_price_type}' and sc_timeFrom >= '{$pd_timeFrom}' and sc_timeTo <= '{$pd_timeTo}'";
    }
    if($filename) {
	    $files = explode(",",$filename);
        $img = G5_DATA_URL . "/product/".$files[0];
    }

    //글등록시 검색 등록된 것과 비교해서 조건에 맞는 회원 불러오기
    if($wr_content){
	    //$where =  " and ('{$wr_content}' like CONCAT('%', sc_tag ,'%') or '{$wr_content}' like CONCAT('%', sc_cate1 ,'%') or '{$wr_content}' like CONCAT('%', sc_cate2 ,'%'))";
	    $where =  " or (INSTR( '{$wr_content}',s.sc_tag) > 0 or INSTR('{$wr_content}',if(s.sc_cate1 != '', s.sc_cate1, 'null')) > 0 or INSTR('{$wr_content}',if(s.sc_cate2 != '', s.sc_cate2, 'null')) > 0 )";
    }
	//$sql = "select *,m.mb_id as mb_id from `my_search_list` as s left join `g5_member` as m on s.mb_id = m.mb_id where ('{$sub_title}' like CONCAT('%', sc_tag ,'%') or '{$sub_title}' like CONCAT('%', sc_cate1 ,'%') or '{$sub_title}' like CONCAT('%', sc_cate2 ,'%')) {$where} and ({$price} between sc_priceFrom and sc_priceTo) and set_alarm = 1 and sc_type = {$type} and sc_type2 = {$type2} {$search} ";
	$sql = "select *,m.mb_id as mb_id from `my_search_list` as s left join `g5_member` as m on s.mb_id = m.mb_id where ((INSTR('{$sub_title}',s.sc_tag) > 0 or INSTR('{$sub_title}',if(s.sc_cate1 != '', s.sc_cate1, 'null')) > 0 or INSTR('{$sub_title}',if(s.sc_cate2 != '', s.sc_cate2, 'null')) > 0 ) {$where}) and set_alarm = 1 and sc_type = {$type} and sc_type2 = {$type2} {$search} and ({$price} between sc_priceFrom and sc_priceTo) ";

    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        if($row["regid"]!="" && $row["mb_id"] != $member["mb_id"]) {
            send_FCM($row["regid"],"검색알림",$row["sc_tag"]."의 게시물이 등록되었습니다.", G5_URL."/index.php?sc_id=".$row["sc_id"],"search_alarm_set","검색알림",$row["mb_id"],$pd_id,$img);
        }
    }
}else{
//수정
    $sql = "update `product` set
			pd_name = '{$wr_subject}',
			pd_type = '{$type}',
			pd_type2 = '{$type2}',
			pd_cate = '{$cate1}',
			pd_cate2 = '{$cate2}',
			pd_images = '{$filename}',
			pd_video = '{$videoname}',
			pd_content = '{$wr_content}',
			pd_tag = '{$sub_title}',
			pd_location = '{$locs_name}',
			pd_location_name = '{$locs}',
			pd_price = '{$price}',
			pd_price2 = '{$price2}',
			pd_infos = '{$pd_infos}',
			mb_id = '{$mb_id}',
			pd_words = '{$words}',
			pd_video_link = '{$links}',
			pd_discount = '{$discount_use}',
			pd_lat = '{$pd_lat}',
			pd_lng = '{$pd_lng}',
            pd_timeFrom = '{$pd_timeFrom}',
            pd_timeTo = '{$pd_timeTo}',
            pd_timetype = '{$pd_timeType}',
            pd_price_type = '{$pd_price_type}'
            where pd_id = {$pd_id}";
    sql_query($sql);
}

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH.'/product', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/product', G5_DIR_PERMISSION);

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

$upload_max_filesize = ini_get('upload_max_filesize');

// 가변 파일 업로드
$file_upload_msg = '';
$upload = array();

//기존파일 검색
$sql = "select * from `product` where pd_id = '{$pd_id}'";
$chkfile = sql_fetch($sql);

$files = explode(",",$chkfile["pd_images"]);

if(count($_FILES['files']['name']) > 0) {
    for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
        if ($_FILES['files']['name'][$i] != "") {
            $upload[$i]['file'] = '';
            $upload[$i]['source'] = '';
            $upload[$i]['filesize'] = 0;
            $upload[$i]['image'] = array();
            $upload[$i]['image'][0] = '';
            $upload[$i]['image'][1] = '';
            $upload[$i]['image'][2] = '';

            // 삭제에 체크가 되어있다면 파일을 삭제합니다.
            /*if (isset($_POST['files_del'][$i]) && $_POST['files_del'][$i]) {
                $upload[$i]['del_check'] = true;

                $row = sql_fetch(" select files from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$i}' ");
                @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['files']);
                // 썸네일삭제
                if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['files'])) {
                    delete_board_thumbnail($bo_table, $row['files']);
                }
            }
            else
                $upload[$i]['del_check'] = false;*/

            //기존파일 삭제
            @unlink(G5_DATA_PATH . "/product/" . $files[$i]);

            $tmp_file = $_FILES['files']['tmp_name'][$i];
            $filesize = $_FILES['files']['size'][$i];
            //$filename  = $_FILES['files']['name'][$i];
            $ext = array_pop(explode(".", $_FILES['files']['name'][$i]));
            $filename = date("Ymdhms") . "_" . $mb_id . "_" . $i . "." . $ext;

            // 서버에 설정된 값보다 큰파일을 업로드 한다면
            /*if ($filename) {
                if ($_FILES['files']['error'][$i] == 1) {
                    $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
                    continue;
                }
                else if ($_FILES['files']['error'][$i] != 0) {
                    $file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
                    continue;
                }
            }*/

            if (is_uploaded_file($tmp_file)) {
                // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
                /*if (!$is_admin && $filesize > $board['bo_upload_size']) {
                    $file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format($board['bo_upload_size']).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
                    continue;
                }*/

                //=================================================================\
                // 090714
                // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
                // 에러메세지는 출력하지 않는다.
                //-----------------------------------------------------------------
                $timg = @getimagesize($tmp_file);
                // image type
                if (preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
                    preg_match("/\.({$config['cf_flash_extension']})$/i", $filename)
                ) {
                    if ($timg['2'] < 1 || $timg['2'] > 16)
                        continue;
                }
                //=================================================================

                $upload[$i]['image'] = $timg;

                // 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
                /*if ($wr_id) {
                    // 존재하는 파일이 있다면 삭제합니다.
                    $row = sql_fetch(" select files from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
                    @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['files']);
                    // 이미지파일이면 썸네일삭제
                    if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['files'])) {
                        delete_board_thumbnail($bo_table, $row['files']);
                    }
                }*/

                // 프로그램 원래 파일명
                $upload[$i]['source'] = $filename;
                $upload[$i]['file'] = $filename;
                $upload[$i]['filesize'] = $filesize;

                // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
                $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

                $files[$i] = $filename;
                //shuffle($chars_array);
                //$shuffle = implode('', $chars_array);

                // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
                //$upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

                $dest_file = G5_DATA_PATH . '/product/' . $upload[$i]['file'];

                /*$exif = exif_read_data($dest_file);
                if($exif["Orientation"] == 6){
                    $degree = 270;
                }else if($exif["Orientation"] == 8){
                    $degree = 90;
                }else if($exif["Orientation"] == 3){
                    $degree = 180;
                }
                if(!$degree){
                    $degree = 0;
                }

                if($exif["FileType"] == 1){
                    $source = imagecreatefromgif($dest_file);
                    $source = imagerotate($source , $degree);
                    imagegif($source,$dest_file);
                }else if($exif["FileType"] == 2){
                    $source = imagecreatefromjpeg($dest_file);
                    $source = imagerotate($source, $degree, 1);
                    imagejpeg($source,$dest_file);
                }else if($exif["FileType"] == 3){
                    $source = imagecreatefrompng($dest_file);
                    $source = imagerotate($source,$degree);
                    imagespng($source,$dest_file);
                }
                imagedestroy($source);*/

                // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
                $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['files']['error'][$i]);


                // 올라간 파일의 퍼미션을 변경합니다.
                chmod($dest_file, G5_FILE_PERMISSION);
            }
        }
    }


// 나중에 테이블에 저장하는 이유는 $wr_id 값을 저장해야 하기 때문입니다.
    if (count($upload) > 0) {
        /*for($i=0;$i<count($upload);$i++){
            if($i==0){
                $filename = $upload[$i]["file"];
            }else{
                $filename .= ",".$upload[$i]["file"];
            }
        }*/
        $filenameAll = implode(",", $files);

        $sql = "update `product` set pd_images = '{$filenameAll}' where  pd_id = '{$pd_id}'";

        sql_query($sql);
    }
}
alert("정상등록되었습니다.", G5_URL);
?>