<?php
include_once("../../../common.php");

include_once(G5_MOBILE_PATH."/head.login.php");

$page = $_REQUEST["page"];
$search = "pd_status = 0 ";
if($member["mb_id"]){
    $wish_id = $member["mb_id"];
}else{
    $wish_id = session_id();
}

if($wish_id){
	$now = date("Y-m-d");
	$sql = "select * from `wish_product` where mb_id = '{$wish_id}'";
	$wish_id = sql_query($sql);
	$i=0;
	while($rec = sql_fetch_array($wish_id)){
		if($i==0){
			$pd_ids = "'".$rec["pd_id"]."'";
		}else{
			$pd_ids .= ",'".$rec["pd_id"]."'";
		}
		$i++;
	}
}

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

$search .= " and pd_id in ({$pd_ids})";

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

?>
<div id="id0s" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <div class="con">

            </div>
        </div>
    </div>
</div>
<div class="sub_head">
	<div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>WISH LIST</h2>
    <div class="all_clear" onclick="fnwishedDelete();"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash_all.svg" alt=""></div>
</div>
<div id="container" style="background-color:#e7e7e7;height:calc(100vh - 30vw);overflow-y:scroll">
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
			?>
				<div class="grid__item wishedon <?php if($list[$i]["pd_images"]!=""){echo ' no-photo';} if($list_type == "true"){echo " type_list";}?>" id="list_<?php echo $list[$i]['pd_id'];?>">
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
                                $tags = explode("#",$list[$i]["pd_tag"]);
                                $rand = rand(1,13);
                                ?>
                                <div class="bg rand_bg<?php echo $rand;?> item_images" >
                                    <?php echo $search;?>
                                    <div class="tags">
                                        <?php echo "<br><br><br><br><br><br><br>".$align;?>

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
                            $tags = explode("#",$list[$i]["pd_tag"]);
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
                                <h2><?php echo ($list[$i]["mb_level"]==4)?"<img src='".G5_IMG_URL."/ic_pro.svg'>":"　";?></h2>
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
								<img src="<?php echo G5_IMG_URL?>/ic_wish_on.svg" alt="" class="wished" >
							</div>
						</div>
					</div>
				</div>
			<?php }?>
			</div>
		</article>
	</section>
</div>
<script src="https://hammerjs.github.io/dist/hammer.js"></script>
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

    //그리드 아이템 가로 스크롤체크
    $("div[id^=list]").each(function(e) {
        //$(document).on("each","div[id^=list]",function(e){

        var id = $(this).attr("id");
        var item = document.getElementById(id);
        var swiper = new Hammer(item);
        var swiperm = new Hammer.Manager(item);
        var pd_id = id.replace("list_", "");


        // Tap recognizer with minimal 2 taps
        swiperm.add(new Hammer.Tap({event: 'doubletap', taps: 2}));
        // Single tap recognizer
        swiperm.add(new Hammer.Tap({event: 'singletap'}));

        // we want to recognize this simulatenous, so a quadrupletap will be detected even while a tap has been recognized.
        swiperm.get('doubletap').recognizeWith('singletap');
        // we only want to trigger a tap, when we don't have detected a doubletap
        swiperm.get('singletap').requireFailure('doubletap');

        swiperm.on("singletap ", function (ev) {
            console.log(ev);
            if (ev.type == "singletap") {
                fn_viewer(pd_id);
            }
        });

        swiperm.on("doubletap",function(ev){
            if(ev.type == "doubletap"){
                $("#"+id).removeClass("wishedon");
                var wished = $("#"+id).children().find($(".wished"));
                wished.removeClass("element-animation");
                wished.attr("src",g5_url+"/img/ic_wish.svg");
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                    method:"POST",
                    data:{pd_id:pd_id,mode:"delete",mb_id:"<?php echo $member["mb_id"];?>"}
                }).done(function(data){
                    $("#"+id).remove();
                    $grid.masonry('remove', this).masonry("layout");
                });
            }
        });
    });
});
var page=1;
function fnlist(){
	$.ajax({
		url:g5_url+"/mobile/page/ajax/ajax.wish.list.php",
		method:"POST",
		data:{page:page}
	}).done(function(data){
		var items = $(data);
		$grid.append(items).masonry( 'appended', items );
		page++;
	});
}
$(window).scroll(function(){
	if ($(window).scrollTop() == $(document).height() - $(window).height()) { 
		fnlist();
	}
});

function fnwishedDelete(){
    if(confirm("위시리스트 항목을 삭제 할까요?")){
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.wished.clear.php",
            method:"POST"
        }).done(function(data){
            if(data=="1"){
                $(".grid__item").remove();
            }else if(data=="2"){
                alert("잘못된 요청입니다.");
            }
        });
    }else{
        return false;
    }
}
</script>
<?php 
$p="wished";
include_once(G5_MOBILE_PATH."/tail.php");
?>

