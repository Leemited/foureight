<?php 
include_once("../../../common.php");

$ca_id = $_REQUEST["ca_id"];

$sql = "select * from `product` where  pd_name like '{$ca_id}' or pd_tags like '{$ca_id}' " ;

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$tags[] = $row;
}

for($i=0;$i<count($tags);$i++){
	print_r2($tags[$i]);
}
?>