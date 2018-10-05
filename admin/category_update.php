<?php
include_once ("./_common.php");

@mkdir(G5_DATA_PATH.'/cate/', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/cate/', G5_DIR_PERMISSION);

$upload_path = G5_DATA_PATH.'/cate/';

$sql = "select * from `categorys` where ca_id = '{$ca_id}'";
$file = sql_fetch($sql);

$icon = "";

$error = "";
if ($_FILES["icon"]["tmp_name"]) {
    unlink($upload_path . $file["icon"]);

    $icon = $_FILES["icon"]["name"];

    $filetmp = explode(".", $icon);

    $filename = $filetmp[0] . "_" . date("Ymd_hms") . "." . $filetmp[1];

    $uploadfile = $upload_path . $filename;

    $error .= move_uploaded_file($_FILES["icon"]["tmp_name"], $uploadfile);

    $where = " , icon = '{$filename}'";
}


$sql = "update `categorys` set cate_name = '{$cate_name}', cate_tag = '{$cate_tag}', info_text1 = '{$info_text1}', info_text2 = '{$info_text2}' {$where} WHERE ca_id = '{$ca_id}'";
if(sql_query($sql)){
    alert("카테고리가 수정되었습니다.");
}else{
    alert("잘못된 요청입니다.");
}