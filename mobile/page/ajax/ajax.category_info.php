<?php
include_once("../../../common.php");

if($cate == ""){
    echo "1";
    return false;
}

$sql = "select  * from `categorys` where cate_name = '{$cate}'";
$cates  = sql_fetch($sql);

if ($cates["info_text1"] != "") {
    echo "[판매시]\r";
    echo $cates["info_text1"]."\r\n";
}
if ($cates["info_text1"] != "") {
    echo "[구매시]\r";
    echo $cates["info_text2"]."\r\n";
}
