<?php
include_once("./_common.php");


if(!$cate_name){
    alert("등록할 카테고리가 없습니다.");
    return false;
}

$sql = "select * from `categorys` where cate_name = '{$cate_name}' and cate_type = '{$cate_type}' ";
$chkName = sql_fetch($sql);
if($chkName["ca_id"]){
    // 카테고리가 있을때
    $parent_id = $chkName["ca_id"];
    $sql = "select MAX(cate_order) as ca_max_order from `categorys` where parent_ca_id = '{$parent_id}' ";
    $maxid = sql_fetch($sql);

    $auto_order = $maxid["ca_max_order"] + 1;
    if($cate_name2){
        $sql = "select MAX(ca_id) as maxid from `categorys`";
        $pid = sql_fetch($sql);
        $max = $pid["maxid"] + 1;
        $sql = "insert into `categorys` set cate_type = '{$cate_type}', cate_order = '{$auto_order}' , cate_name = '{$cate_name2}', cate_depth = 2,parent_ca_id = '{$parent_id}', cate_code = CONCAT(2,'{$max}')";
        if(sql_query($sql)){
            $sql = "update `category_user_temp` set status = 1 where ca_temp_id = '{$ca_temp_id}'";
            sql_query($sql);
            alert("등록 되었습니다.");
        }else{
            alert("잘못된 요청입니다. 다시 시도해 주세요.");
        }
    }else{
        alert("등록할 상세 카테고리가 없습니다.");
    }
}else {
    // 카테고리가 없을때
    $sql = "select MAX(cate_order) as ca_max_order from `categorys` where cate_type = '{$cate_type}' ";
    $maxid = sql_fetch($sql);

    $auto_order = $maxid["ca_max_order"] + 1;

    $sql = "insert into `categorys` set cate_name = '{$cate_name}' , cate_depth = 1 , cate_order = '{$auto_order}' , cate_type = '{$cate_type}'";

    if(sql_query($sql)){

        $sql = "select ca_id from `categorys` order by ca_id desc limit 0,1";
        $max_id = sql_fetch($sql);
        $parent_id = $max_id["ca_id"];

        $sql = "update `category_user_temp` set status = 1 where ca_temp_id = '{$ca_temp_id}'";
        sql_query($sql);
        alert("등록 되었습니다.");
    }else{
        alert("잘못된 요청입니다. 다시 시도해 주세요.");
    }
}

?>
