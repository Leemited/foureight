<?php
include_once ("./_common.php");

$sql = "select * from `product_ad` where ad_id = '{$ad_id}'";
$file = sql_fetch($sql);

$sql = "delete from `product_ad` where ad_id = '{$ad_id}'";
if(sql_query($sql)){
    if($file["ad_photo"]){
        $path = G5_DATA_URL."/product/";
        $filename = $path.$file["ad_photo"];
        unlink($filename);
    }
    alert("삭제되었습니다.");
}else{
    alert("잘못된 요청입니다. ");
}
