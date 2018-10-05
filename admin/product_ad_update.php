<?php
include_once ("_common.php");

if($_FILES["ad_photo"]["tmp_name"]){

    if($ad_id) {
        $sql = "select * from `product_ad` where ad_id = '{$ad_id}'";
        $file = sql_fetch($sql);
        if(is_file($upload_path . $file["ad_photo"])){
            unlink($upload_path . $file["ad_photo"]);
        }
    }


    @mkdir(G5_DATA_PATH.'/product/', G5_DIR_PERMISSION);
    @chmod(G5_DATA_PATH.'/product/', G5_DIR_PERMISSION);

    $upload_path = G5_DATA_PATH.'/product/';

    $icon = "";

    $error = "";

    $icon = $_FILES["ad_photo"]["name"];

    $filetmp = explode(".", $icon);

    $filename = $filetmp[0] . "_" . date("Ymd_hms") . "." . $filetmp[1];

    $uploadfile = $upload_path . $filename;

    $error .= move_uploaded_file($_FILES["ad_photo"]["tmp_name"], $uploadfile);

    $where = " , ad_photo = '{$filename}'";

}

if(!$ad_id){
    $sql = "insert into `product_ad` set ad_link = '{$ad_link}', ad_subject = '{$ad_subject}', ad_con = '{$ad_con}', ad_from='{$ad_from}', ad_to = '{$ad_to}', ad_status = '{$ad_status}', ad_cate='{$cate1}',ad_type='{$ad_type}',ad_sort = '{$ad_sort}', ad_date = now() {$where}";
}else{
    $sql = "update `product_ad` set ad_link = '{$ad_link}', ad_subject = '{$ad_subject}', ad_con = '{$ad_con}', ad_from='{$ad_from}', ad_to = '{$ad_to}', ad_status = '{$ad_status}', ad_cate='{$cate1}',ad_type='{$ad_type}',ad_sort = '{$ad_sort}' {$where} where ad_id = '{$ad_id}'";
}

if(sql_query($sql)){
    alert("등록되었습니다.",G5_URL."/admin/product_ad_list.php");
}else{
    alert("잘못된 요청입니다.\r\n다시 시도해 주세요.");
}