<?php
$sql = "select ca_id from `categorys` where `cate_depth` = 1 and cate_type = 1 and cate_status = 0 order by `cate_order`";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $parent3[] = $row["ca_id"];
}

?>
<div class="category2">
    <ul class="scate00000 active">
        <li id="00000" ><a href="#">전체</a></li>
    </ul>
	<?php for($i=0;$i<count($parent3);$i++){
        $sql = "select * from `categorys` where `cate_depth` = 2 and `cate_type` = 1 and parent_ca_id = {$parent3[$i]} order by cate_order, ca_id asc";
        $res = sql_query($sql);
	    ?>
	<ul class="scate<?php echo $parent3[$i];?>">
        <?php for($j=0;$row=sql_fetch_array($res);$j++){?>
		<li id="<?php echo $row["cate_code"];?>"><a href="#"><?php echo $row["cate_name"];?></a></li>
		<?php }?>
        <li onclick="fnsuggestion2('<?php echo $parent3[$i]-1;?>');">제안하기</li>
	</ul>
	<?php }?>

</div>