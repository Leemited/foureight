<?php
include_once ("_common.php");
$type = $_REQUEST["ad_type"];

if($type == "1"){
$sql = "select * from `categorys` where `cate_type` = 1 and `cate_depth` = 1";
}else{
$sql = "select * from `categorys` where `cate_type` = 2 and `cate_depth` = 1";
}

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
$cate1[] = $row;
}
?>
<option value="">1차 카테고리</option>
<?php
for($i=0;$i<count($cate1);$i++){
    ?>
    <option value="<?php echo $cate1[$i]["ca_id"];?>"><?php echo $cate1[$i]["cate_name"];?></option>
    <?php
}
?>