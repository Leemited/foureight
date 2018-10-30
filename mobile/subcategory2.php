<?php 

$sql = "select * from `categorys` where `cate_depth` = 1 and cate_type = 2 order by cate_order ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $parent[] = $row;
}
?>
<div class="category2">
<?php
for($i=0;$i<count($parent);$i++) {
?>
    <ul class="scate<?php echo $parent[$i]["ca_id"];?> <?php if($i==0){?>active<?php }?>">

    <?php
    $sql = "select * from `categorys` where `cate_depth` = 2 and cate_type = 2 and parent_ca_id = {$parent[$i]["ca_id"]} order by parent_ca_id , cate_order ";
    $res = sql_query($sql);
    while ($row = sql_fetch_array($res)) {
    ?>
        <li id="<?php echo $row["cate_code"];?>"><a href="#"><?php echo $row["cate_name"];?></a></li>
    <?php
    }
    ?>
        <li onclick="fnsuggestion2('<?php echo $parent[$i]['parent_ca_id'];?>');">제안하기</li>
    </ul>
    <?php
}
?>
</div>