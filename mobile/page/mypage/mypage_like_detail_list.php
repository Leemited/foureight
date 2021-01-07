<?php
include_once ("../../../common.php");

$settings = sql_fetch("select * from `mysetting` where mb_id = '{$mb_id}'");

if($settings["feed_set"]==1){
    $today = date("Y-m-d H:i:s");
    $monthDate = date("Y-m-d H:i:s",strtotime('- 6 month'));

    $setDate = " and like_date between '{$monthDate}' and '{$today}'";
}

$sql = "select * from `product_like` where pd_type = '{$pd_type}' and pd_mb_id = '{$mb_id}' and like_status = 1 {$setDate}";
$res = sql_query($sql);

while($row = sql_fetch_array($res)) {
    $list[] = $row;
}
$back_url = G5_URL;
if($pd_id){
    $back_url = $back_url."/?pd_id={$pd_id}";
}
$set_type = $pd_type;
include_once(G5_MOBILE_PATH."/head.login.php");
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>후기 보기</h2>
</div>
<div class="like_detail_list" >
    <ul>
<?php
for($i=0;$i<count($list);$i++){
    $mb = get_member($list[$i]["mb_id"]);

    ?>
    <li>
        <span class="nick"><?php echo "[".preg_replace('/(?<=.{2})./u','*',$mb["mb_nick"])."]";?></span>
        <span class="content"><?php echo $list[$i]["like_content"];?></span>
        <span class="date"><?php echo $list[$i]["like_date"];?></span>
    </li>
<?php }?>
<?php if(count($list)==0){?>
    <li>등록된 후기가 없습니다.</li>
<?php }?>
    </ul>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>