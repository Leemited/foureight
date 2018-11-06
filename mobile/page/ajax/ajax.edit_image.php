<?php
include_once("../../../common.php");
//include_once(G5_EXTEND_PATH."/image.extend.php");

$path = G5_DATA_PATH."/product/";

$file_path = $path.$img;

if(is_file($file_path)) {
    $img = get_images($file_path, '', '');

    echo $img;
}
?>