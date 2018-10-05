<?php 
include_once("./_common.php");

$info_text = trim($_REQUEST["info_text"]);
$ca_id = $_REQUEST["ca_id"];

$sql = "update `categorys` set info_text = '{$info_text}' where ca_id = '{$ca_id}'";

if(sql_query($sql)){
	echo "1";
}else{
	echo "0";
}

?>