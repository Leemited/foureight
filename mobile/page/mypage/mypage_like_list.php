<?php
include_once ("../../../common.php");

$sql = "select * from `product_like` where pd_type = '{$pd_type}' and pd_mb_id = '{$mb_id}'";
$res = sql_query($sql);

while($row = sql_fetch_array($res)) {
    $list[] = $row;
}
?>
<div class="like_list" style="">
    <ul>
<?php
for($i=0;$i<count($list);$i++){
?>
    <li><?php echo $list[$i]["mb_id"];?> <span><?php echo $list[$i]["like_content"];?></span></li>
<?php }?>
    </ul>
</div>