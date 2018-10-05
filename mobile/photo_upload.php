<?php
include_once("../common.php");
include_once(G5_EXTEND_DIR."/image.extend.php");

//$mb_id = $_REQUEST["mb_id"];

$file_path = G5_DATA_PATH."/product/";

@mkdir($file_path , G5_DIR_PERMISSION);
@chmod($file_path , G5_DIR_PERMISSION);


$file_path = $file_path.basename($_FILES['uploaded_file']["name"]);
/*
$exif = exif_read_data($file_path);
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
    $source = imagecreatefromgif($file_path);
    $source = imagerotate($source , $degree);
    imagegif($source,$file_path);
}else if($exif["FileType"] == 2){
    $source = imagecreatefromjpeg($file_path);
    $source = imagerotate($source, $degree, 1);
    imagejpeg($source,$file_path);
}else if($exif["FileType"] == 3){
    $source = imagecreatefrompng($file_path);
    $source = imagerotate($source,$degree);
    imagespng($source,$file_path);
}
imagedestroy($source);*/

if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"],$file_path)){
    //image_resize_update($_FILES["uploaded_file"]["tmp_name"],$_FILES['uploaded_file']["name"],$_FILES["uploaded_file"]["name"],1200);
	echo "success";
}else{
	echo "fail";
}

?>