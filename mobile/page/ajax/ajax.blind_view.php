<?php
include_once ("../../../common.php");

if($pd_id==""){
    echo "1";
    return false;
}

$sql = "select *,count(blind_content) as cnt from `product_blind` where pd_id={$pd_id} group by blind_content";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $blind_list[] = $row;
}

//게시글 정보
$sql = "select * from `product` where pd_id = {$pd_id}";
$product = sql_fetch($sql);

//상태 업데이트
/*$sql = "update `product` set pd_blind_status = 2 where pd_id = {$pd_id}";
sql_query($sql);*/
?>
<div>
    <h3><?php echo "[".$product["pd_cate"]." | ".$product["pd_cate2"]."] ". $product["pd_name"];?></h3>
    <div>
        <ul>
            <?php for($i=0;$i<count($blind_list);$i++){?>
            <li><?php echo $blind_list[$i]["blind_content"]."/".$blind_list[$i]["cnt"]."건";?></li>
            <?php }
            if(count($blind_list)==0){
            ?>
                <li>등록된 사유가 없거나 <br>관리자가 직접 처리한 게시물입니다.</li>
            <?php }?>
        </ul>
    </div>
</div>
