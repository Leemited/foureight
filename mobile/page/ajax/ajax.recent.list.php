<?php
include_once("../../../common.php");

$page = $_REQUEST["page"];

$search = "pd_status = 0 ";

if($member["mb_id"]){
	$now = date("Y-m-d");
	$sql = "select * from `recent_product` where mb_id = '{$member[mb_id]}' and pd_date = '{$now}'";
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
	$pd_ids = $_SESSION["pd_id"];
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
<div class="grid__item <?php if($list[$i]["pd_images"]!=""){echo ' no-photo';} if($list_type == "true"){echo " type_list";}?> <?php if($flag){echo "wishedon";} ?>" id="list_<?php echo $list[$i]['pd_id'];?>" >
	<div>
		<?php if($list[$i]["pd_images"]!=""){
			$img = explode(",",$list[$i]["pd_images"]);
		?>
		<div class="item_images">
		<img src="<?php echo G5_DATA_URL?>/product/<?php echo $img[0];?>" alt="" class="main" >
		</div>
		<?php }else{ 
		$tags = explode("/",$list[$i]["pd_tag"]);
		$rand = rand(1,13);
		?>
		
		<div class="bg rand_bg<?php echo $rand;?> item_images" >
			<div class="tags">
				<?php for($k=0;$k<count($tags);$k++){
					$rand_font = rand(3,6);
				?>
				<div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
				<?php }?>
			</div>
			<div class="clear"></div>
		</div>
		<?php }?>
		<div class="top">
			<div>
				<h2><?php echo $type;?> </h2>
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
				<img src="<?php echo G5_IMG_URL?>/ic_wish.svg" alt="">
			</div>
		</div>
		
	</div>
</div>
<?php }
if(count($list)==0){echo "no-list";}
?>

