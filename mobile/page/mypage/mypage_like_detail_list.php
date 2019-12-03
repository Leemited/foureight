<?php
include_once ("../../../common.php");

include_once(G5_MOBILE_PATH."/head.login.php");
$sql = "select * from `product_like` where pd_type = '{$pd_type}' and pd_mb_id = '{$mb_id}'";
$res = sql_query($sql);

while($row = sql_fetch_array($res)) {
    $list[] = $row;
}
$back_url = G5_URL;
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
    <li onclick="location.href=g5_url+'/mobile/page/mypage/mypage.php?mode=profile&pro_id=<?php echo $mb["mb_id"];?>'"><?php echo "[".$mb["mb_nick"]."]";?> <span><?php echo $list[$i]["like_content"];?></span></li>
<?php }?>
<?php if(count($list)==0){?>
    <li>등록된 후기가 없습니다.</li>
<?php }?>
    </ul>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>