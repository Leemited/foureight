<?php
include_once("../../../common.php");

$search = " p.pd_status < 10 ";

if($mb_id){
    $now = date("Y-m-d");
    $sql = "select * from `wish_product` where mb_id = '{$mb_id}'";
    $recent_pd = sql_query($sql);
    $i=0;
    while($rec = sql_fetch_array($recent_pd)){
        if($i==0){
            $pd_ids = "'".$rec["pd_id"]."'";
        }else{
            $pd_ids .= ",'".$rec["pd_id"]."'";
        }
        $i++;
    }
}

if($pd_ids) {
    $search .= " pd_id in ({$pd_ids}) ";

//유저 차단 목록
$block_time = date("Y-m-d H:i:s");
$sql = "select target_id from `member_block` where mb_id = '{$member["mb_id"]}' and '{$block_time}' BETWEEN block_dateFrom and block_dateTo";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $my_block["target_id"][] = $row;
}

if(count($my_block)>0){
    $block_id = implode(",",$my_block["target_id"]);
    $search .= " and p.mb_id not in ({$block_id}) ";
}

$sql = "select * from `product` where {$search} order by pd_id desc ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}
for($i=0;$i<count($list);$i++){
	switch($list[$i]["pd_type"]){
		case "1":
			$type = "4";
			break;
		case "2": 
			$type = "8";
			break;
	}
?>
    <input type="hidden" id="listCount" value="<?php echo count($list);?>">
<div class="grid__item <?php if($list[$i]["pd_blind"]>=10){?>blinds<?php }?>" onclick="<?php if($list[$i]["pd_blind"]<10){?>fn_viewer('<?php echo $list[$i]["pd_id"];?>')<?php }?>">
    <?php if($list[$i]["pd_blind"]>=10){?>
        <div class="blind_bg">
            <input type="button" value="사유보기" class="list_btn"  >
        </div>
    <?php }?>
	<div>
        <?php if($list[$i]["pd_images"]!=""){
            $img = explode(",",$list[$i]["pd_images"]);
            $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
            if(is_file(G5_DATA_PATH."/product/".$img1)){
                ?>
                <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;">
                    <?php if($img1!=""){?>
                        <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" style="opacity:0">
                    <?php }else{ ?>
                        <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" style="opacity:0">
                    <?php }?>
                </div>
            <?php }else{
                $tags = explode("/",$list[$i]["pd_tag"]);
                $rand = rand(1,13);
                ?>
                <div class="bg rand_bg<?php echo $rand;?> item_images" >
                    <div class="tags">
                        <?php //for($k=0;$k<count($tags);$k++){
                            $rand_font = rand(3,6);
                            ?>
                            <div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
                        <?php //}?>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php }?>
        <?php }else{
            $tags = explode("/",$list[$i]["pd_tag"]);
            $rand = rand(1,13);
            ?>
            <div class="bg rand_bg<?php echo $rand;?> item_images" >
                <div class="tags">
                    <?php //for($k=0;$k<count($tags);$k++){
                        $rand_font = rand(3,6);
                        ?>
                        <div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
                    <?php //}?>
                </div>
                <div class="clear"></div>
            </div>
        <?php }?>
		<div class="top">
			<div>
                <h2><?php echo ($list[$i]["mb_level"]==4)?"전":"　";?></h2>
				<div>
					<ul>
						<li><img src="<?php echo G5_IMG_URL?>/ic_hit.svg" alt=""> <?php echo $list[$i]["pd_hits"];?></li>
						<?php if($app){?>
						<li><img src="<?php echo G5_IMG_URL?>/ic_loc.svg" alt=""> 0</li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
		<div class="bottom">
			<h2><?php echo $list[$i]["pd_name"];?></h2>
			<div>
				<h1>￦ <?php echo number_format($list[$i]["pd_price"]+$list[$i]["pd_price2"]);?></h1>
			</div>
		</div>
		
	</div>
</div>
<?php }
}else{
    echo "no-list";
}

if(count($list)==0){echo "no-list";}
?>

