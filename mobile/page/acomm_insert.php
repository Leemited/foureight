<?php 
include_once("../../common.php");

$sql = "insert into `category_user_temp` set
			cate_name = '{$cate_name}',
			cate_name2 = '{$cate_name2}',
			cate_temp_content = '{$cate_content}',
			mb_id = '{$mb_id}',
			insert_date = now(),
			status = 0";

if(sql_query($sql)){
	alert("제안 완료 되었습니다.");
}else{
	alert("등록 하지 못했습니다.\r다시 확인해 주세요.");
}

?>