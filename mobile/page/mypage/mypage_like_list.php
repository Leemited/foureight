<?php
include_once ("../../../common.php");

$sql = "select * from `product_like` where pd_type = '{$pd_type}' and pd_mb_id = '{$mb_id}' limit 0, 8";
$res = sql_query($sql);

while($row = sql_fetch_array($res)) {
    $list[] = $row;
}
?>
<div class="like_list" >
    <ul>
<?php
for($i=0;$i<count($list);$i++){
    $mb = get_member($list[$i]["mb_id"]);
?>
    <li><?php echo "[".$mb["mb_nick"]."] ";?> <span><?php echo $list[$i]["like_content"];?></span></li>
<?php }?>
<?php if(count($list)==0){?>
    <li>등록된 후기가 없습니다.</li>
<?php }?>
    </ul>
    <div>
        <input type="button" value="확인" onclick="modalClose(this)">
        <input type="button" value="상세보기" onclick="location.href=g5_url+'/mobile/page/mypage/mypage_like_detail_list.php?mb_id=<?php echo $mb_id;?>&pd_type=<?php echo $pd_type;?>'">
    </div>
</div>
