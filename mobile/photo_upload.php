<?php
include_once("../common.php");
include_once(G5_EXTEND_DIR."/image.extend.php");

//$mb_id = $_REQUEST["mb_id"];

$file_path = G5_DATA_PATH."/product/";

@mkdir($file_path , G5_DIR_PERMISSION);
@chmod($file_path , G5_DIR_PERMISSION);


$file_path = $file_path.basename($_FILES['uploaded_file']["name"]);


if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"],$file_path)){
    //image_resize_update($_FILES["uploaded_file"]["tmp_name"],$_FILES['uploaded_file']["name"],$_FILES["uploaded_file"]["name"],1200);
	echo "success";
}else{
	echo "fail";
}

?>