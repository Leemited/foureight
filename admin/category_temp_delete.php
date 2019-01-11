<?php
include_once("./_common.php");
if(!$ca_temp_id){
    alert("삭제할 제안이 없습니다.");
    return false;
}
$sql = "delete from `categorys` where ca_id = '{$ca_id}'";
if(sql_query($sql)){
    $sql = "delete from `category_user_temp` where ca_temp_id = '{$ca_temp_id}'";
    sql_query($sql);
    alert("제안목록에서 삭제되었습니다.");
}else{
    alert("잘못된 요청입니다.");
}
?>