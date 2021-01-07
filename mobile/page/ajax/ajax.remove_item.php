<?php 
include_once("../../../common.php");

$pd_id = $_REQUEST["pd_id"];

$sql = "select * from `product` where pd_id = '{$pd_id}'";
$id_chk = sql_fetch($sql);

$chk = true;
if($member["mb_id"]==$id_chk["mb_id"]){
    echo "3";
    return false;
}
if($member["mb_id"]){
	$sql = "select pd_id from `my_trash` where mb_id = '{$member[mb_id]}'";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)){
		if($row["pd_id"]==$pd_id){
			$chk = false;
		}
	}
}else{
	$ss_id = session_id();
	$sql = "select pd_id from `my_trash` where mb_id = '{$ss_id}'";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)){
		if($row["pd_id"]==$pd_id){
			$chk = false;
		}
	}
}

if($chk==true){
	if($member["mb_id"]){
		$sql = "insert into `my_trash` (`pd_id`,`mb_id`,`trash_date`) values ('{$pd_id}','{$member[mb_id]}',now())";
		if(sql_query($sql)){
			echo "1";
		}else{
			echo "2";
		}
	}else{
		$ss_id = session_id();
		$sql = "insert into `my_trash` (`pd_id`,`mb_id`,`trash_date`) values ('{$pd_id}','{$ss_id}',now())";
		if(sql_query($sql)){
			echo "1";
		}else{
			echo "2";
		}
	}
}

?>