<?php
include_once("./_common.php");

$cate_type = $_REQUEST["cate_type"];
$cate_name = $_REQUEST["cate_name"];
$parent_ca_id = $_REQUEST["parent_ca_id"];
$ca_temp_id = $_REQUEST["ca_temp_id"];


if($parent_ca_id){

	$sql = "select * from `categorys` where cate_type = '{$cate_type}' and parent_ca_id = '{$parent_ca_id}' order by cate_order desc limit 0, 1";
	$order = sql_fetch($sql);

	$auto_order = $order["cate_order"] + 1;

	$sql = "insert into `categorys` set cate_type = '{$cate_type}', cate_name = '{$cate_name}', parent_ca_id = '{$parent_ca_id}', cate_order = '{$auto_order}', cate_depth = 2 ";

}else{
    @mkdir(G5_DATA_PATH.'/cate/', G5_DIR_PERMISSION);
    @chmod(G5_DATA_PATH.'/cate/', G5_DIR_PERMISSION);

    $upload_path = G5_DATA_PATH.'/cate/';
    $error = "";
    if($_FILES["icon"]["tmp_name"]){
        $icon = $_FILES["icon"]["name"];

        $filetmp = explode(".",$icon);

        $filename = $filetmp[0]."_".date("Ymd_hms").".".$filetmp[1];

        $uploadfile = $upload_path.$filename;

        $error .= move_uploaded_file($_FILES["icon"]["tmp_name"],$uploadfile);
    }

	$sql = "select * from `categorys` where cate_type = '{$cate_type}' and parent_ca_id = 0 order by cate_order desc limit 0, 1";
	$order = sql_fetch($sql);

	$auto_order = $order["cate_order"] + 1;

	$sql = "insert into `categorys` set cate_type = '{$cate_type}', cate_name = '{$cate_name}', parent_ca_id = 0, cate_order = '{$auto_order}', cate_depth = 1 , icon = '{$filename}'";
}

if(sql_query($sql)){
	if($ca_temp_id){
		$sql = "update `category_user_temp` set status = 1 where ca_temp_id = '{$ca_temp_id}'";
		sql_query($sql);
	}

    if($parent_ca_id) {
        $sql = "select * from `categorys` where cate_type='{$cate_type}' and cate_name = '{$cate_name}' and $parent_ca_id = '{$parent_ca_id}' and cate_depth = 2 limit 0, 1";
        $auto_code = sql_fetch($sql);
        $code = $auto_code["cate_depth"]."".$auto_code["ca_id"];
        $sql = "update `categorys` set cate_code = '{$code}' where ca_id = '{$auto_code[ca_id]}'";

        sql_query($sql);
    }

	alert("카테고리가 등록되었습니다.");
}else{
	alert("잘못된 요청입니다.");
}
?>