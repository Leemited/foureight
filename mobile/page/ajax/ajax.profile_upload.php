<?php
include_once("../../../common.php");

if($_FILES["profile_img"][name]!=""){
    @mkdir(G5_DATA_PATH.'/profile/'.$mb_no."/", G5_DIR_PERMISSION);
    @chmod(G5_DATA_PATH.'/profile/'.$mb_no."/", G5_DIR_PERMISSION);

    $path = G5_DATA_PATH.'/profile/'.$mb_no."/";

    $sql = "select mb_profile from `g5_member` where mb_no ='{$mb_no}'";
    $profile = sql_fetch($sql);
    if(is_file($path.$profile["mb_profile"])){
        unlink($path.$profile["mb_profile"]);
    }

    $file = explode(".",$_FILES["profile_img"]["name"]);
    $ext = $file[1];

    $filename = $file[0]."_".$mb_no."_".date("Ymd").".".$ext;

    $uploadfile = $path . $filename;
    $img = G5_DATA_URL."/profile/".$mb_no."/".$filename;

    $error .= move_uploaded_file($_FILES["profile_img"]["tmp_name"], $uploadfile);

    $sql = "update `g5_member` set mb_profile = '{$img}' where mb_no = '{$mb_no}'";
    if(sql_query($sql)){
        $result = array("result"=> 1, "filename"=>$img);
        echo json_encode($result);
    }else{
        $result = array("result"=> 2);
        echo json_encode($result);
    }
}else{
    $result = array("result"=> 3);
    echo json_encode($result);
}