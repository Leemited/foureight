<?php
include_once("../../../common.php");

include_once(G5_MOBILE_PATH."/head.login.php");

if($member["mb_id"]){
	$chk_id = $member["mb_id"];
}else{
	$chk_id = session_id();
}

$sql = "select * from `product` as p left join `my_trash` as t on p.pd_id = t.pd_id where t.mb_id = '{$chk_id}' {$search} order by p.pd_id ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$list[] = $row;
}
?>
<div class="sub_head">
	<div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>휴지통</h2>
    <div class="all_clear" onclick="fnTrashClear();"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash_all.svg" alt=""></div>
</div>
<div id="container" style="background-color:#e7e7e7;height:calc(100vh - 30vw);overflow-y:scroll" >
	<!--<p class="trash_info">해당 기간이 지나면 항목이 영구적으로 삭제됩니다. 최대 40일이 소요 될 수 있습니다.</p>-->
	<section class="wish_list">
		<article class="post">
			<div class="list_item grid are-images-unloaded ">
			<?php 
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
				<div class="grid__item trash" id="list_<?php echo $list[$i]['pd_id'];?>">
					<div>
                        <?php if($list[$i]["pd_images"]!=""){
                            $img = explode(",",$list[$i]["pd_images"]);
                            $img[0] = trim($img[0]);
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
                                $tags = explode("#",$list[$i]["pd_tag"]);
                                $rand = rand(1,13);
                                ?>
                                <div class="bg rand_bg<?php echo $rand;?> item_images" >
                                    <div class="tags">
                                        <?php //for($k=0;$k<count($tags);$k++){
                                            $rand_font = rand(3,6);
                                        ?>
                                            <div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
                                        <?php //} ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            <?php }?>
                        <?php }else{
                            $tags = explode("#",$list[$i]["pd_tag"]);
                            $rand = rand(1,13);
                            ?>
                            <div class="bg rand_bg<?php echo $rand;?> item_images" >
                                <div class="tags">
                                    <?php //for($k=0;$k<count($tags);$k++){
                                        $rand_font = rand(3,6);
                                     ?>
                                        <div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
                                    <?php //} ?>
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
								<img src="<?php echo G5_IMG_URL?>/ic_trash_del.svg" alt="복원아이콘" class="" onclick="fnTrashDelete('<?php echo $list[$i]["pd_id"];?>');">
								<!-- <?php 		
								
								if($flag){
								?>
								<img src="<?php echo G5_IMG_URL?>/ic_wish_on.svg" alt="" class="wished" >
								<?php }else{ ?>
								<img src="<?php echo G5_IMG_URL?>/ic_wish.svg" alt="" class="wished" >
								<?php } ?> -->
							</div>
						</div>
					</div>
				</div>
			<?php }?>
			</div>
		</article>
	</section>
</div>
<script>
var $grid;
function initpkgd(){
//-------------------------------------//
	// init Masonry
	$grid = $('.grid').masonry({
	  itemSelector: 'none', // select none at first
	  columnWidth: '.grid__item',
	  gutter: 10,
	  //percentPosition: true,
	  //stagger: 30,
	  // nicer reveal transition
	  visibleStyle: { transform: 'translateY(0)', opacity: 1 },
	  hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
	});
	

	// get Masonry instance
	var msnry = $grid.data('masonry');

	// initial items reveal
	$grid.imagesLoaded( function() {
	  $grid.removeClass('are-images-unloaded');
	  $grid.masonry( 'option', { itemSelector: '.grid__item' ,columnWidth: '.grid__item', percentPosition:true,gutter: 10,});
	  var $items = $grid.find('.grid__item');
	  $grid.masonry( 'appended', $items );
	});

	//-------------------------------------//
	
}

$(document).ready(function(){
	//masonry 초기화
	initpkgd();	
});

function fnTrashClear(){
    if(confirm("휴지통에서 삭제하면 다시 목록에 노출됩니다.\r\n휴지통을 비우시겠습니까?")) {
        $.ajax({
            url: g5_url + "/mobile/page/ajax/ajax.trash_clear.php",
            mtehod: "post",
            data:{mb_id:"<?php echo $member["mb_id"];?>"}
        }).done(function (data) {
            alert(data);
            location.reload();
        });
    }else{
        return false;
    }
}

function fnTrashDelete(pd_id){
    $.ajax({
        url: g5_url + "/mobile/page/ajax/ajax.trash_delete.php",
        mtehod: "post",
        data:{mb_id:"<?php echo $member["mb_id"];?>",pd_id:pd_id}
    }).done(function (data) {
        if(data==1) {
            $("#list_" + pd_id).remove();
            $grid.masonry('remove', this).masonry("layout");
        }else{
            alert("잘못된 요청입니다.");
        }
    });
}
</script>
<?php 
$p="wished";
include_once(G5_MOBILE_PATH."/tail.php");
?>

