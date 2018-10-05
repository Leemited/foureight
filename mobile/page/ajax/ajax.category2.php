<?php 
include_once("../../../common.php");

$ca_id = $_REQUEST["ca_id"];

$sql = "select * from `categorys` where parent_ca_id = '{$ca_id}'" ;

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$cate1[] = $row;
}
?>
<option value="">2차 카테고리</option>
<?php
for($i=0;$i<count($cate1);$i++){
?>
<option value="<?php echo $cate1[$i]["cate_name"];?>"><?php echo $cate1[$i]["cate_name"];?></option>
<?php
}
?>