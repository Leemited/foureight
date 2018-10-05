<?php 
include_once("./_common.php");

$cate_name = trim($_REQUEST["cate_name"]);
$ca_id = $_REQUEST["ca_id"];

$sql = "update `categorys` set cate_name = '{$cate_name}' where ca_id = '{$ca_id}'";

if(sql_query($sql)){
	echo "1";
}else{
	echo "0";
}

?>