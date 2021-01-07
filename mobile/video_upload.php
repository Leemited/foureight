<?php
include_once("../common.php");

$file_path = G5_DATA_PATH."/product/";

@mkdir($file_path , G5_DIR_PERMISSION);
@chmod($file_path , G5_DIR_PERMISSION);


$file_path = $file_path.basename($_FILES['uploaded_file']["name"]);

if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"],$file_path)){
	echo "success";
}else{
	echo "fail";
}

?>