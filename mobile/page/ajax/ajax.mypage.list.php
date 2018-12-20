<?php
include_once("../../../common.php");

$page = $_REQUEST["page"];


//검색 
$search = " p.pd_status < 10 ";

//정렬
$od = " order by p.pd_date desc";

//구분[구분 없음]
$type1 = $_REQUEST["type1"];
if($type1){
	$search .= " and p.pd_type = ".$type1;
}

$mb_id = $_REQUEST["mb_id"];
if($mb_id){
    $search .= " and p.mb_id = '{$mb_id}'";
}


$sql = "select *, m.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where  {$search} order by p.pd_date desc";
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

	for($j=0;$j<count($wished);$j++){
		if($wished[$j]["pd_id"]==$list[$i]["pd_id"]){
			$flag = true;
			break;
		}else{
			$flag = false;
		}
	}
?>
<div class="grid__item <?php if($list[$i]["pd_blind"]>=10){?>blinds<?php }?>" <?php if($list[$i]["pd_blind"]<10){?>onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>')"<?php }?>>
    <?php if($list[$i]["pd_blind"]>=10){?>
        <div class="blind_bg">
            <div>
                <input type="button" value="사유보기" class="list_btn"  onclick="fnBlindView('<?php echo $list[$i]["pd_id"];?>')">
                <input type="button" value="게시물보기" class="list_btn"  onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>')">
            </div>
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
						<li><img src="<?php echo G5_IMG_URL?>/ic_hit<?php if($list_type == "true"){echo "_list";}?>.svg" alt=""> <?php echo $list[$i]["pd_hits"];?></li>
						<?php if($app){?>
						<li><img src="<?php echo G5_IMG_URL?>/ic_loc<?php if($list_type == "true"){echo "_list";}?>.svg" alt=""> 0</li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
		<div class="bottom">
            <?php if($list[$i]["pd_name"]){?>
                <h2><?php echo $list[$i]["pd_name"];?></h2>
            <?php }?>
            <div>
                <h1>￦ <?php echo number_format($list[$i]["pd_price"]);?></h1>
            </div>
		</div>
		
	</div>
</div>
<?php }
if(count($list)==0){echo "no-list//".$odr;}
?>

