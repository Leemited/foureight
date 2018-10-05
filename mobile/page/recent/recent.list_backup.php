<?php
include_once("../../../common.php");

include_once(G5_MOBILE_PATH."/head.login.php");

$page = $_REQUEST["page"];

$search = "sc_status = 0 ";

$search .= " and md_id in ({$pd_ids}) ";

$total=sql_fetch("select count(*) as cnt from `my_search_list` where {$search} ");
if(!$page)
	$page=1;
else
	$page++;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `my_search_list` where {$search} order by pd_id limit {$start},{$rows}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$list[] = $row;
}

?>
<div class="sub_head">
	<div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>최근본 게시물</h2>
	<div class="all_clear" onclick="fnRecentClear();"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash_all.svg" alt=""></div>
</div>
<div id="container" style="background-color:#e7e7e7;height:calc(100vh - 42vw);overflow-y:scroll">
	<section class="recent_list">
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
				<div class="grid__item <?php if($list[$i]["pd_images"]!=""){echo ' no-photo';} if($list_type == "true"){echo " type_list";}?> <?php if($flag){echo "wishedon";} ?>" id="list_<?php echo $list[$i]["pd_id"];?>">
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
                if(ev.type == "doubletap"){
                    if($("#"+id).hasClass("wishedon")){
                        $("#"+id).removeClass("wishedon");
                        var wished = $("#"+id).children().find($(".wished"));
                        wished.removeClass("element-animation");
                        wished.attr("src",g5_url+"/img/ic_wish.svg");
                        $.ajax({
                            url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                            method:"POST",
                            data:{pd_id:pd_id,mode:"delete",mb_id:"<?php echo $wished_id;?>"}
                        }).done(function(data){
                            console.log(data);
                        });
                    }else{
                        $("#"+id).addClass("wishedon");
                        var wished = $("#"+id).children().find($(".wished"));
                        wished.removeClass("element-animation");
                        wished.addClass("element-animation");
                        wished.attr("src",g5_url+"/img/ic_wish_on.svg");
                        $.ajax({
                            url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                            method:"POST",
                            data:{pd_id:pd_id,mode:"insert",mb_id:"<?php echo $wished_id;?>"}
                        }).done(function(data){
                            console.log(data);
                        });
                    }
                }
            }
        });
    });

	$(window).scroll(function(){
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			fnlist(2,'');
		}
	});

});
var page=1;
function fnlist(){
	$.ajax({
		url:g5_url+"/mobile/page/ajax/ajax.recent.list.php",
		method:"POST",
		data:{page:page}
	}).done(function(data){
		var items = $(data);
		$grid.append(items).masonry( 'appended', items );
		page++;
	});
}
function fnRecentClear(){
	if(confirm("최근 본 항목을 삭제 할까요?")){
		$.ajax({
			url:g5_url+"/mobile/page/ajax/ajax.recent.clear.php",
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

include_once(G5_MOBILE_PATH."/tail.php");
?>

