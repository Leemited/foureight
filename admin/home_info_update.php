<?php
include_once("../common.php");

$sql = "update `homepage_info` set
		`company_name` = '{$company_name}',
		`ceo` = '{$ceo}',
		`zipcode` = '{$zipcode}',
		`addr1` = '{$addr1}',
		`addr2` = '{$addr2}',
		`company_number` = '{$company_number}',
		`company_number2` = '{$company_number2}',
		`tel` = '{$tel}',
		`fax` = '{$fax}',
		`email` = '{$email}'
		where `home_id`= 1";
sql_query($sql);

if(sql_query($sql)){
	alert("수정되었습니다.");
}else{
	alert("에러");
}
?>