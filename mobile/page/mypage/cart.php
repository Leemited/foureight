<?php
include_once ("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

$sql = "select * from `cart` as c left join `product` as p on c.pd_id = p.pd_id where c.mb_id = '{$mb_id}' ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cart[] = $row;
}

?>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>장바구니</h2>
</div>
<div class="mycart_tab">
    <ul>
        <li class="active">물건</li>
        <li>능력</li>
    </ul>
</div>
<div id="settings">
    <div class="setting_wrap_top">
    </div>
</div>
<?php
include_once (G5_PATH."/tail.php");