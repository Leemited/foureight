<?php

$sql = "select * from `categorys` where `cate_depth` = 1 and cate_type = 2 and cate_status = 0 order by `cate_order`";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $parent4[] = $row;
}
?>
<div class="category2">
        <ul class="scate000000 active">
            <li id="000000" ><a href="#">전체</a></li>
        </ul>
    <?php for($i=0;$i<count($parent4);$i++){
        $sql = "select * from `categorys` where `cate_depth` = 2 and `cate_type` = 2 and parent_ca_id = {$parent4[$i]["ca_id"]} order by cate_order, ca_id asc";
        $res = sql_query($sql);
        ?>
        <ul class="scate<?php echo $parent4[$i]["ca_id"];?>">
            <?php for($j=0;$row=sql_fetch_array($res);$j++){?>
                <li id="<?php echo $row["cate_code"];?>"><a href="#"><?php echo $row["cate_name"];?></a></li>
            <?php }?>
            <li onclick="fnsuggestion2('<?php echo $parent4[$i]["ca_id"]-1;?>');">제안하기</li>
        </ul>
    <?php }?>
</div>