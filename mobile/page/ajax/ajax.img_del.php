<?php
include_once ("../../../common.php");

if($pd_id) {
    $sql = "select pd_images from `product` where pd_id ='{$pd_id}'";
    $pd_images = sql_fetch($sql);

    $files = explode(",", $pd_images["pd_images"]);

    for ($i = 0; $i < count($files); $i++) {
        if ($files[$i] == $img) {
            $files[$i] = "";
        }
    }
    $files = array_filter($files);
    $fileup = implode(",", $files);

    $sql = "update `product` set pd_images = '{$fileup}' where pd_id = '{$pd_id}'";
    if (sql_query($sql)) {
        // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
        @chmod(G5_DATA_PATH . '/product', G5_DIR_PERMISSION);
        @unlink(G5_DATA_PATH . "/product/" . $img);
        $result["msg"] = 1;
        $result["filename"] = $fileup;
    } else {
        $result["msg"] = 2;
    }
}else{
    $result["filename"] = $img;
    //$files = array_filter($files);
}
echo json_encode($result);
?>