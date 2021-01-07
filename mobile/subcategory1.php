<?php 

$sql = "select * from `categorys` where `cate_depth` = 2 and cate_type = 1 and cate_status = 0 order by `parent_ca_id`, `cate_order`";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $scate[$row["parent_ca_id"]][] = $row;
    $parentss[$row["parent_ca_id"]][] = $row["parent_ca_id"];
}

$sql = "select ca_id from `categorys` where `cate_depth` = 1 and cate_type = 1 order by `cate_order`";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $parent[] = $row["ca_id"];
}
?>
<div class="category2">
    <!--<ul class="scate000 active">
        <li id="000"><a href="#">전체</a></li>
    </ul>-->
	<?php for($i=0;$i<count($parent);$i++){
	    $num = $parent[$i];
	    ?>
	<ul class="scate<?php echo ($num);?> <?php if($i==0){?>active<?php }?>">
		<?php for($j=0;$j<count($scate[$num]);$j++){?>
		<li id="<?php echo $scate[$num][$j]["cate_code"];?>"><?php echo $scate[$num][$j]["cate_name"];?></li>
		<?php }?>
        <li onclick="fnsuggestion2('<?php echo $num;?>','menu');">카테고리 제안</li>
    </ul>
	<?php }?>

</div>