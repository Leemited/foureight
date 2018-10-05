<?php 
include_once("../../../common.php");

if($member["mb_id"]){
	$sql = "delete from `recent_product` where mb_id = '{$member['mb_id']}' ";
	if(sql_query($sql)){
		echo "1";
	}else{
		echo "2";
	}
}else{
	$_SESSION["pd_id"] = "";
	echo "1";
}

?>