<?php
include_once ("_common.php");

$sql = "select * from `categorys` where `cate_type` = {$type} and `cate_depth` = 2 and `parent_ca_id` = {$cate}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
$cate1[] = $row;
}
?>
<option value="">2차 카테고리</option>
<?php
for($i=0;$i<count($cate1);$i++){
    ?>
    <option value="<?php echo $cate1[$i]["ca_id"];?>" <?php if($id==$cate1[$i]["ca_id"]){?>selected<?php }?>><?php echo $cate1[$i]["cate_name"];?></option>
    <?php
}
?>