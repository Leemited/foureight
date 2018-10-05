<?php
include_once("../../../common.php");
$mb_id = $_REQUEST["mb_id"];
if($mode=="insert"){
	if($mb_id){
		$now = date("Y-m-d");
		$recent = sql_query("select * from `wish_product` where mb_id = '{$mb_id}' and pd_id = '{$pd_id}' and ws_date = '{$now}'");
		$cnt = 0;
		while($row = sql_fetch_array($recent)){
			$cnt++;
		}
		if($cnt==0){
			$sql = "insert into `wish_product` (mb_id,pd_id,ws_date)values('{$mb_id}','{$pd_id}','{$now}');";
			if(sql_query($sql)){
				echo "ok query";
			}else{
				echo "not ok";
			}
		}
	}
}else if($mode=="delete"){
	if($mb_id){
		$sql = "delete from `wish_product` where mb_id='{$mb_id}' and pd_id = '{$pd_id}'";	
		if(sql_query($sql)){
			echo "delete query";
		}else{
			echo "not delete";
		}
	}else{
		$_SESSION["wr_pd_id"]=str_replace(",,","",str_replace($pd_id,"",$_SESSION["ws_pd_id"]));
		echo "delete session";
	}
}

?>