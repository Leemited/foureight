<?php

$sql = "select * from `categorys` where `cate_depth` = 1 and cate_type = 2 order by `cate_order`";
$res = sql_query($sql);
$i=0;
while($row=sql_fetch_array($res)){
    $parent2[] = $row;
}
?>
<div class="category2">
        <ul class="scate0000">
            <li id="0000"><a href="#">전체</a></li>
        </ul>
    <?php for($i=0;$i<count($parent2);$i++){
        $sql = "select * from `categorys` where `cate_depth` = 2 and `cate_type` = 2 and parent_ca_id = {$parent2[$i]["ca_id"]} order by cate_order, ca_id asc";
        $res = sql_query($sql);
        ?>
        <ul class="scate<?php echo $parent2[$i]["ca_id"];?> <?php if($i==0){?>active<?php }?>">
            <?php for($j=0;$row=sql_fetch_array($res);$j++){?>
                <li id="<?php echo $row["cate_code"];?>"><a href="#"><?php echo $row["cate_name"];?></a></li>
            <?php }?>
            <li onclick="fnsuggestion2('<?php /*echo $parent[$i];*/?>');">제안하기</li>
        </ul>
    <?php }?>
</div>