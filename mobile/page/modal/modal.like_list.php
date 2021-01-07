<?php
include_once ("../../../common.php");

//6개월간 피드백 설정확인
$pd_set = sql_fetch("select * from `mysetting` where mb_id = '{$mb_id}'");

if($pd_set["feed_set"]==1){
    $today = date("Y-m-d H:i:s");
    $monthDate = date("Y-m-d H:i:s",strtotime('- 6 month'));

    $setDate = " and like_date between '{$monthDate}' and '{$today}'";
}

$sql = "select * from `product_like` where pd_type = '{$pd_type}' and pd_mb_id = '{$mb_id}' and like_status = 1 {$setDate} limit 0, 8";
$res = sql_query($sql);

while($row = sql_fetch_array($res)) {
    $list[] = $row;
}
?>
<div id="id11" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <div class="con">
                <div class="like_list" >
                    <ul>
                        <?php
                        for($i=0;$i<count($list);$i++){
                            $mb = get_member($list[$i]["mb_id"]);
                            ?>
                            <li><span><?php echo "[".preg_replace('/(?<=.{2})./u','*',substr($mb["mb_nick"],0,5))."]";?></span> <span class="con"><?php echo $list[$i]["like_content"];?></span></li>
                        <?php }?>
                        <?php if(count($list)==0){?>
                            <li>등록된 후기가 없습니다.</li>
                        <?php }?>
                    </ul>
                </div>
                <div>
                    <input type="button" value="확인" onclick="likeClose()">
                    <input type="button" value="상세보기" onclick="fnMove('/mobile/page/mypage/mypage_like_detail_list.php?mb_id=<?php echo $mb_id;?>&pd_type=<?php echo $pd_type;?>&pd_id=<?php echo $pd_id;?>','<?php echo $pd_id;?>')" style="width:auto;">
                </div>
            </div>
        </div>
    </div>
</div>