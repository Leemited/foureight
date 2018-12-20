<?php
include_once("../../../common.php");

$page = $_REQUEST["page"];

$search = "pd_status = 0 ";

if($member["mb_id"]){
	$now = date("Y-m-d");
	$sql = "select * from `wish_product` where mb_id = '{$member[mb_id]}'";
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
}else{
	$pd_ids = $_SESSION["ws_pd_id"];
}

if($pd_ids){
	$search .= " and pd_id in ({$pd_ids}) ";
}

$total=sql_fetch("select count(*) as cnt from `product` where {$search} ");
if(!$page)
	$page=1;
else
	$page++;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `product` where {$search} order by pd_id limit {$start},{$rows}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$list[] = $row;
}
if($member["mb_id"])
    $sql = "select * from `wish_product` where mb_id ='{$member[mb_id]}'";
else
    $sql = "select * from `wish_product` where mb_id ='{$ss_id}'";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $wished[] = $row;
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
	for($j=0;$j<count($wished);$j++){
		if($wished[$j]["pd_id"]==$list[$i]["pd_id"]){
			$flag = true;
			break;
		}else{
			$flag = false;
		}
	}
?>
<div class="grid__item <?php if($list[$i]["pd_images"]!=""){echo ' no-photo';} if($list_type == "true"){echo " type_list";}?> <?php if($flag){echo "wishedon";} ?>" id="list_<?php echo $list[$i]['pd_id'];?>">
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
                            <div class="rand_size<?php echo $rand_font;?>">#<?php echo $list[$i]["pd_tag"];?></div>
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
                        <div class="rand_size<?php echo $rand_font;?>">#<?php echo $list[$i]["pd_tag"];?></div>
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
				<h1>￦ <?php echo number_format($list[$i]["pd_price"]);?></h1>
				<?php

				if($flag){
				?>
				<img src="<?php echo G5_IMG_URL?>/ic_wish_on.svg" alt="" class="wished" >
				<?php }else{ ?>
				<img src="<?php echo G5_IMG_URL?>/ic_wish.svg" alt="" class="wished" >
				<?php } ?>
			</div>
		</div>

	</div>
</div>
<?php }
if(count($list)==0){echo "no-list";}
?>

