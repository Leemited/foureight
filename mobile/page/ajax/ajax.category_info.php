<?php
include_once("../../../common.php");

if($cate == ""){
    echo "1";
    return false;
}

$sql = "select * from `categorys` where cate_name = '{$cate}'";
$cates  = sql_fetch($sql);

$sql = "select * from `write_confirm` where mb_id = '{$member["mb_id"]}' and cate_id = '{$cates["ca_id"]}'";
$chk = sql_fetch($sql);
if($chk==null) {
    if ($cates["info_text1"] != "") {
        $result["info_text"] = "<h3>[판매시]</h3><br>";
        $result["info_text"] .= $cates["info_text1"];
        if ($cates["info_text2"]) {
            $result["info_text"] .= "<br><br>";
        }
    }
    if ($cates["info_text2"] != "") {
        $result["info_text"] .= "<h3>[구매시]</h3><br>";
        $result["info_text"] .= $cates["info_text2"];
    }
}
$sql = "select * from `categorys` where cate_name = '{$cate}' and cate_depth = 2 ";
$cate_tag = sql_fetch($sql);

$result["catetag"] = $cate_tag["cate_tag"];
$result["ca_id"] = $cates["ca_id"];

echo json_encode($result);