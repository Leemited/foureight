<?php
include_once ("../../common.php");

if($pd_id=="" || !$pd_id){
    alert("잘못된 요청입니다.");
    return false;
}
//file
$sql = "select pd_images, pd_video from `product` where pd_id = '{$pd_id}'";

$file = sql_fetch($sql);
$path = G5_DATA_URL."/product/";


$sql = "delete from `product` where pd_id = '{$pd_id}'";

if(sql_query($sql)){
    if($file["pd_images"] != ""){
        $images = explode(",",$file["pd_images"]);
        for($i=0;$i<count($images);$i++) {
            unlink($path.$images[$i]);
        }
    }
    if($file["pd_video"]!=""){
        unlink($path.$file["pd_video"]);
    }

    $sql = "delete from `product` where pd_id = '{$pd_id}'";
    sql_query($sql);

    alert("삭제되었습니다.",G5_URL);
}else {
    alert("잘못된 요청입니다.");
}