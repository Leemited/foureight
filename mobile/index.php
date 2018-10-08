<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}
include_once(G5_EXTEND_PATH."/image.extend.php");

include_once(G5_MOBILE_PATH.'/head.php');

//검색 기본값
$search = "p.pd_status = 0";

//검색 정렬 기본값
if($_SESSION["list_basic_order"]=="location"){
    $od = " order by p.pd_update desc, p.pd_date desc";
}else {
    $od = " order by p.pd_update desc, p.pd_date desc";
}

if($schopt){

    $stx = $schopt["sc_tag"];

    $search .= " and ( p.pd_name like '%{$stx}%' or p.pd_tag like '%{$stx}%' or p.pd_content like '%{$stx}%')";

    $type1 = $schopt["sc_type"];
    $type2 = $schopt["sc_type2"];
    $cate1 = $schopt["sc_cate1"];
    $cate2 = $schopt["sc_cate2"];

    if($type1){
        $search .= " and p.pd_type = '{$type1}' ";
    }
    if($type2){
        $search .= " and p.pd_type2 = '{$type2}' ";
    }
    if($cate1){
        $search .= " and p.pd_cate = '{$cate1}' ";
    }
    if($cate2){
        $search .= " and p.pd_cate2 = '{$cate2}' ";
    }

    $priceFrom = $schopt["sc_priceFrom"];
    $priceTo = $schopt["sc_priceTo"];

    if($priceFrom!=0 && $priceTo!=0) {
        $search .= " and p.pd_price between '{$priceFrom}' and '{$priceTo}'";
    }

    $align = $schopt["sc_align"];
    $aligns = explode(",", $align);
    //정렬 초기화

    if($align !=""){
        $od = " order by ";
        for ($i = 0; $i < count($aligns); $i++) {
            switch ($aligns[$i]) {
                case "pd_date":
                    $align_active[$i] = $schopt["sc_od_date"];
                    if($schopt["sc_od_date"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_date desc";
                        }else {
                            $od .= " , p.pd_date desc";
                        }
                    }
                    break;
                case "pd_price":
                    $align_active[$i] = $schopt["sc_od_price"];
                    if($schopt["sc_od_price"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_price desc";
                        }else {
                            $od .= " , p.pd_price desc";
                        }
                    }
                    break;
                case "pd_recom":
                    $align_active[$i] = $schopt["sc_od_recom"];
                    if($schopt["sc_od_recom"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_recom desc";
                        }else {
                            $od .= " , p.pd_recom desc";
                        }
                    }
                    break;
                case "pd_hit":
                    $align_active[$i] = $schopt["sc_od_hit"];
                    if($schopt["sc_od_hit"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_hit desc";
                        }else {
                            $od .= " , p.pd_hit desc";
                        }
                    }
                    break;
                case "pd_loc":
                    $align_active[$i] = $schopt["sc_od_loc"];
                    if($_SESSION["lat"] && $_SESSION["lng"]){
                        $sel = " , IF( p.pd_lat != '', (6371*acos(cos(radians({$list_lat}))*cos(radians(p.pd_lat))*cos(radians(p.pd_lng)-radians({$list_lng}))+sin(radians({$list_lat}))*sin(radians(p.pd_lat)))) AS distance , 0 as distance )";
                        if($schopt["sc_od_loc"]==1){
                            if($od==" order by "){
                                $od .= " `p.location` desc";
                            }else {
                                $od .= " , `p.location` desc";
                            }
                        }
                    }
                    break;
            }
        }
        $actives = implode(",", $align_active);
    }
}else{
    if($_SESSION["type1"]==1){
        $search .= " and p.pd_type = 1";
    }else{
        $search .= " and p.pd_type = 2";
    }
}

$total=sql_fetch("select count(*) as cnt from `product` where {$search} ");
if(!$page)
	$page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$ss_id = session_id();
if($member["mb_id"]){
    $wished_id = $member["mb_id"];
	$search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$member[mb_id]}') ";
}else{
    $wished_id = $ss_id;
	$search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$ss_id}') ";
}

$sql = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od} limit {$start},{$rows}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$list[] = $row;
	$pd_ids[] = $row["pd_id"];
}

//검색 최근 목록 저장
if($schopt) {
    $sql = "select *,count(*)as cnt from `save_read` where sc_id = '{$sc_id}' and mb_id = '{$wished_id}' ";
    $saves = sql_fetch($sql);
    $save_pd_id_all = explode(",",$saves["pd_ids"]);
    if(count($pd_ids)>0) {
        $pd_id_all = implode(",", $pd_ids);
    }
    //항상 업데이트
    if($saves["cnt"] == 0){//등록
        if(count($pd_ids)>0) {
            $sql = "insert into `save_read` set pd_ids = '{$pd_id_all}', save_datetime = now(), mb_id = '{$wished_id}', sc_id = '{$sc_id}'";
            //echo $sql;
            sql_query($sql);
        }
    }else {//업데이트
        $sql = "update `save_read` set pd_ids = '{$pd_id_all}' where sc_id = '{$sc_id}' and mb_id = '{$wished_id}'";
        //echo $sql;
        sql_query($sql);
    }
}
//ad 가져오기
$today = date("Y-m-d");
$sql = "select * from `product_ad` where ad_status = 0 and  '{$today}' >= ad_from and '{$today}' < ad_to ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $listadd[] = $row;
}

if($wished_id)
    $sql = "select * from `wish_product` where mb_id ='{$wished_id}'";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $wished[] = $row;
}

//해당 최대가격

//해당 최소가격

?>

<div class="loader" >
    <img src="<?php echo G5_IMG_URL?>/loader.svg" alt="" style="width:100%;position:relative;z-index:1">
    <!--<div style="background-color:#000;opacity: 0.4;width:100%;height:100%;position:absolute;top:0;left:0;"></div>-->
</div>
<!-- <div class="delarea1" style="height:100vh;width:5vw;position:absolute;left:0;top:0;background-color:#FFF"></div>
<div class="delarea2" style="height:100vh;width:5vw;position:absolute;right:0;top:0;background-color:#FFF"></div> -->
<input type="hidden" value="<?php echo $schopt['sorts'];?>" id="sorts">
<div id="id01" class="w3-modal w3-animate-opacity no-view">
	<div class="w3-modal-content w3-card-4">
		<div class="w3-container">
			<form name="write_form" id="write_form" method="post" action="">
				<input type="hidden" value="<?php if($schopt["sc_type"]){echo $schopt["sc_type"];}else if($_SESSION["type1"]){echo $_SESSION["type1"];}else{echo "1";}?>" name="type" id="type">
                <input type="hidden" name="cate1" id="c" value="<?php echo $schopt['pd_cate'];?>">
				<input type="hidden" name="cate2" id="sc" value="<?php echo $schopt['pd_cate2'];?>">
				<h2>검색어</h2>
				<div>
					<input type="text" name="title" id="wr_title" placeholder="" required>
				</div>
				<div>
					<input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="<?php if($app){ ?>fnOnCam();<?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL."/page/write.php";?>');<?php }?>" >
				</div>
			</form>
		</div>
	</div>
</div>
<div id="container" >
	<!--<input type="hidden" value="<?php /*if($schopt["sc_type"]){echo $schopt["sc_type"];}else{echo "1";}*/?>" name="write_type" id="write_type">-->
	<div class="write" onclick="<?php if(!$is_member){?>alert('로그인이 필요합니다.');location.href=g5_url+'/bbs/login.php'; <?php }else if($member["mb_certify"]==""){ ?>alert('본인인증이 필요합니다.');location.href=g5_url+'/mobile/page/mypage/hp_certify.php';<?php }else if($member["mb_id"]){ ?>fnwrite();<?php } ?> ">
		<div class="write_btn">
            <?php if($schopt["sc_type"]==1 || $_SESSION["type1"] == 1){?>
            <img src="<?php echo G5_IMG_URL?>/ic_write_btn.svg" alt="">
            <?php }else{ ?>
            <img src="<?php echo G5_IMG_URL?>/ic_write_btn_2.svg" alt="">
            <?php }?>
        </div>
		<div class="text" <?php if($schopt["sc_type"]==2 || $_SESSION["type1"] == 2){?>style="background-color: rgb(255, 61, 0); color: rgb(255, 255, 255);"<?php }?>><?php if($schopt["sc_type"]==1 || $_SESSION["type1"] == 1){?>안쓰는건 팔고 나눠보세요!<?php }else{ ?>작은 능력이라도 올려보세요<?php }?></div>
	</div>
	<section class="main_list">
		<article class="post" id="post">
            <input type="hidden" id="dWidth">
            <input type="hidden" id="dHeight">
			<div class="list_item grid are-images-unloaded" id="test">
				<?php
				for($i=0;$i<count($list);$i++){
				    if($list[$i]["distance"] == 0 ){
				        $dist = "거리정보 없음";
                    }else {
                        $dist = round($list[$i]["distance"],1) . "km";
                    }
				for($j=0;$j<count($wished);$j++){
					if($wished[$j]["pd_id"]==$list[$i]["pd_id"]){
						$flag = true;
						break;
					}else{
						$flag = false;
					}
				}
				for($k=0;$k<count($listadd);$k++){
				    if($listadd[$k]["ad_sort"]==$i){
				        ?>
                <div class="grid__item ad_list <?php if($_SESSION["list_type"]=="list"){echo " type_list";}?>" onclick="location.href='<?php echo $listadd[$k]["ad_link"];?>'">
                    <div>
                        <?php if($listadd[$k]["ad_photo"]!=""){
                            ?>
                            <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $listadd[$k]["ad_photo"];?>');background-repeat:no-repeat;background-size:cover;background-position:center;">
                                <img src="<?php echo G5_DATA_URL?>/product/<?php echo $listadd[$k]["ad_photo"];?>" alt="ad" class="main" style="opacity:0">
                            </div>
                        <?php  }?>
                        <div class="bottom" >
                            <div>
                                <h1 class="ad_h1"><?php echo $listadd[$k]["ad_subject"];?></h1>
                            </div>
                            <?php if($listadd[$k]["ad_con"]){?>
                                <h2 class="ad_h2"><?php echo $listadd[$k]["ad_con"];?></h2>
                            <?php }?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                        <?php
                    }
                }
				?>
				<!-- <div class="grid__item" onclick="fn_viewer('<?php echo $list[$i]['pd_id'];?>')"> -->
				<div class="grid__item <?php if($flag){echo "wishedon";}?> <?php if($_SESSION["list_type"]=="list"){echo " type_list";}?> <?php if($list[$i]["pd_blind"]>=10){?>blinds<?php }?>" id="list_<?php echo $list[$i]['pd_id'];?>">
                    <?php if($list[$i]["pd_blind"]>=10){?>
                        <div class="blind_bg">
                            <input type="button" value="사유보기" class="list_btn"  >
                        </div>
                    <?php }?>
					<div class="in_grid">
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
                                        <?php for($k=0;$k<count($tags);$k++){
                                            $rand_font = rand(3,6);
                                            ?>
                                            <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                        <?php }?>
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
								<h2><?php echo ($list[$i]["mb_level"]==4)?"전":"　";?></h2>
								<div>
									<ul>
										<li><img src="<?php echo G5_IMG_URL?>/ic_hit.svg" alt=""> <?php echo $list[$i]["pd_hits"];?></li>
										<?php if($app || $list[$i]["distance"]){?><li><img src="<?php echo G5_IMG_URL?>/ic_loc.svg" alt="">
                                            <?php echo $dist;?>
                                            </li><?php }?>
									</ul>
                                    <div class="clear"></div>
								</div>
							</div>
						</div>
                        <?php if(count($save_pd_id_all)>0){
                            if(!in_array($list[$i]['pd_id'],$save_pd_id_all)){ ?>
                        <div class="new" style="">
                            <img src="<?php echo G5_IMG_URL?>/ic_list_new.svg" alt="">
                        </div>
                        <?php } }?>
						<div class="bottom">
							<?php if($list[$i]["pd_name"]){
							    switch($list[$i]["pd_type2"]){
                                    case "4":
                                        $pt2 = "[삽니다]";
                                        break;
                                    case "8":
                                        $pt2 = "[팝니다]";
                                        break;
                                }
							    ?>
							<h2><?php echo $pt2." ".$list[$i]["pd_name"];?></h2>
							<?php }?>
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
				if(count($list)==0){?>
                    <div class="no-list">
                        검색된 리스트가 없습니다.
                    </div>
                <?php }?>
			</div>
			<div class="clear"></div>
			<div class="page-load-status">
				<div class="loader-ellips infinite-scroll-request">
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
				</div>
				<p class="infinite-scroll-last">End of content</p>
				<p class="infinite-scroll-error">목록 끝</p>
			</div>
		</article>
	</section>

</div>
<script>
var page=1;
var $grid;
var scrollchk = true;
var finish = false;

function initpkgd(){
//-------------------------------------//
	// init Masonry
	$grid = $('.grid').masonry({
	  itemSelector: '.none', // select none at first
	  columnWidth: '.grid__item',
	  gutter: 10,
      //  horizontalOrder:true,
	  percentPosition: true,
	  //stagger: 30,
	  // nicer reveal transition
	  visibleStyle: { transform: 'translateY(0)', opacity: 1 },
	  hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
	});


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
    console.log("<?php echo $_SESSION["list_type"];?>");
    <?php if($stx){?>
    $("#stx").val("<?php echo $stx;?>");
    <?php } ?>

    var height = $(window).height();
    var width = $(window).width();

    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.setWidthHeight.php",
        method:"post",
        dataType:"json",
        data:{width:width,height:height}
    }).done(function(data){
        console.log(data.dWidth);
        $("#dWidth").val(data.dWidth);
        $("#dHeight").val(data.dHeight);
    });

	//masonry 초기화
	initpkgd();

	$(".search .slider").click(function(){
		if($(this).prev().prop("checked") == true){

		    $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"type1",value:"1"}
            }).done(function(data){
               console.log(data);
            });
			$(this).html("물품");
			$(this).css({"text-align":"right"});
			$(".top_header").css("background-color","#000");
			$("#search").attr("placeholder","원하는 물건이 있으세요?");
			$(".text").css({"background-color":"#ffe400","color":"#000"});
			$(".text").html("안쓰는건 팔고 나눠보세요!");
			$(".write_btn img").attr("src","<?php echo G5_IMG_URL;?>/ic_write_btn.svg");
			$("#set_type").val("1");
			$("#type").val("1");
            $("#theme-color").attr("content","#000000");
			finish = false;

			fnlist(1,'');
		}else{
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"type1",value:"2"}
            }).done(function(data){
                console.log(data);
            });
			$(this).html("능력");
			$(this).css({"text-align":"left"});
			$(".top_header").css("background-color","#ff3d00");
			$("#search").attr("placeholder","누군가의 능력이 필요하세요?");
			$(".text").css({"background-color":"#ff3d00","color":"#fff"});
			$(".text").html("작은 능력이라도 올려보세요");
			$(".write_btn img").attr("src","<?php echo G5_IMG_URL;?>/ic_write_btn_2.svg");
			$("#set_type").val("2");
			$("#type").val("2");
            $("#theme-color").attr("content","#ff3d00");
			finish = false;

			fnlist(1,'');
		}
	});
	
	//인기 거리
	$(".align .slider").click(function(){
		if($(this).prev().prop("checked") == true){
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"list_basic_order",value:"hits"}
            }).done(function(data){
                console.log(data);
            });
			$(this).html("등록");
			$(this).css({"text-align":"right"});
			//인기순 정렬
            finish = false;
			fnlist(1,'');
		}else{
		    <?php if($_SESSION["lat"] && $_SESSION["lng"]){?>
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"list_basic_order",value:"location"}
            }).done(function(data){
                console.log(data);
            });
			$(this).html("거리");
			$(this).css({"text-align":"left"});
			//거리순 정렬
            finish = false;
			fnlist(1,'');

			<?php  }else{ ?>
            alert("거리정보가 없어 리스트를 불러올수 없습니다.");
            setTimeout(function(){$("#paplur").removeAttr("checked");},400);
            <?php }?>
		}
	});
	$(document).on("click",".list .slider",function(){
		if($(this).prev().prop("checked") == true){//LIST
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"list_type",value:"gird"}
            }).done(function(data){
                console.log(data);
            });
		    $("#set_list_type").val("gird");
			$(this).css({"text-align":"right","background-image":"url(./img/ic_grid.png)","background-position":"10.2vw center"});
			$(".grid__item").removeClass("type_list");
            finish = false;
			fnlist(1,'false');
			//fnlist(1,'');
		}else{//GIRD
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"list_type",value:"list"}
            }).done(function(data){
                console.log(data);
            });
            $("#set_list_type").val("list");
			$(this).css({"text-align":"left","background-image":"url(./img/ic_list.png)","background-position":"2.2vw center"});
			$(".grid__item").addClass("type_list");
            finish = false;
			fnlist(1,'true');
		}
	});

	$(".category ul > li").click(function(){
		$(this).addClass("active");
		$(".category ul li").not($(this)).removeClass("active");
		var id = $(this).attr("id");
		$("."+id).addClass("active");
		$(".category2 ul").not($("."+id)).removeClass("active");
	});
	$(".category2 ul li").click(function(){
        var c = $(this).parent().parent().prev().children().find("li.active a").text();
        var sc = $(this).find("a").text();
        var type = $("#type").val();
        var msg = '';
	    $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.category_info.php",
            method:"post",
            data:{cate:c,type:type}
        }).done(function(data){
            msg = data;
            if(confirm(msg+ "\r\n해당 카테고리로 게시글을 등록할까요?")){
                $("#type").val(type);
                $("#c").val(c);
                $("#sc").val(sc);
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.category_tag.php",
                    method:"post",
                    data:{cate1:c,cate2:sc}
                }).done(function(data){
                    $("#wr_title").attr("placeholder",data);
                });
                cateClose();
                $("#id01").css("display","block");
                location.hash="#modal";
                $("#id01 #wr_title").focus();
            }else{
                alert("글등록이 취소되어 메인으로 이동합니다.");
                cateClose();
            }
        });
	});

	var container = document.getElementById('test');
	var swipe2 = new Hammer(container);
    swipe2.on('swipeleft',function(e){
        console.log(e);
    });
    swipe2.on('swiperight',function(e){
        console.log(e);
    });

    //그리드 아이템 가로 스크롤체크
    $("div[id^=list]").each(function(e){
        //$(document).on("each","div[id^=list]",function(e){

        var id = $(this).attr("id");
        var item = document.getElementById(id);
        var swiper = new Hammer(item);


        swiper.on('swipeleft',function(e){
            $("#"+id).remove();
            $grid.masonry('remove', this).masonry("layout");
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                method:"POST",
                data:{pd_id:pd_id}
            }).done(function(data){
                $("#debug").addClass("active");
                $("#debug").html("휴지통으로 이동되었습니다.");
                setTimeout(removeDebug,1500);
            });
        });
        swiper.on("swiperight",function(e){
            $("#"+id).remove();
            $grid.masonry('remove', this).masonry("layout");
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                method:"POST",
                data:{pd_id:pd_id}
            }).done(function(data){
                $("#debug").addClass("active");
                $("#debug").html("휴지통으로 이동되었습니다.");
                setTimeout(removeDebug,1500);
            });
        });

        var swiperm = new Hammer.Manager(item);
        var pd_id = id.replace("list_","");


        // Tap recognizer with minimal 2 taps
        swiperm.add( new Hammer.Tap({ event: 'doubletap', taps: 2 }) );
        // Single tap recognizer
        swiperm.add( new Hammer.Tap({ event: 'singletap' }) );

        // we want to recognize this simulatenous, so a quadrupletap will be detected even while a tap has been recognized.
        swiperm.get('doubletap').recognizeWith('singletap');
        // we only want to trigger a tap, when we don't have detected a doubletap
        swiperm.get('singletap').requireFailure('doubletap');

        swiperm.on("singletap ", function(ev) {
            if(ev.type == "singletap"){
                fn_viewer(pd_id);
            }
        });

        swiperm.on("doubletap",function(ev){
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
        });
    });
});
function fnlist(num,list_type){
	//사고팔고/ /카테코리1/카테고리2/가격시작/가격끝/정렬순서1/정렬순서2/정렬순서3/정렬순서4/정렬순서5
	var type1,type2,cate1,cate2,stx,priceFrom,priceTo,sorts,app,mb_id,sc_id,align;

	if(num == 1){
		page=0;
	}
    var align = 0;
	var pchk = $("#paplur").is(":checked");
	if(pchk == false) align = 1;
	var latlng = '';
	<?php if($app){?>
	if(align == 1){
	    latlng = window.android.getLocation();
    }
    <?php }?>
    sc_id = $("#sc_id").val();

    stx = $("#stx").val();
    app = "<?php echo $app;?>";
    type1 = $("#set_type").val();
    type2 = $("#type2").val();
    cate1 = $("#cate1").val();
    cate2 = $("#cate2").val();
    priceFrom = $("#priceFrom").val();
    priceTo = $("#priceTo").val();
    sorts = $("#order_sort").val();
    mb_id = $("#mb_id").val();
    align = $("#order_sort").val();
    var list_type = $("#set_list_type").val();
    var pd_ids = "<?php echo $saves["pd_ids"];?>";

    console.log("sc_id : "+sc_id);

	$.ajax({
		url:g5_url+"/mobile/page/ajax/ajax.index.list.php",
		method:"POST",
		data:{page:page,list_type:list_type,stx:stx,app:app,type1:type1,type2:type2,cate1:cate1,cate2:cate2,priceFrom:priceFrom,priceTo:priceTo,sorts:sorts,sc_id:sc_id,mb_id:mb_id,align:align,latlng:latlng,pd_ids:pd_ids},
		beforeSend:function(){
            $('.loader').show();
		},
		complete:function(){
			$(".loader").css("display","none");
		}
	}).done(function(data){
	    console.log(data);
		if(data.indexOf("no-list")==-1){
			if(num == 1){
                //새리스트
                $(".grid").html('');
                $(".grid").append(data);
                initpkgd();
                page=1;
			}else{
			    //스크롤
                var $items = $(data);
                $items.imagesLoaded(function(){
                    $grid.append($items).masonry( 'appended', $items );
                });

                page++;
			}
		}else{
		    finish = true;
		    if(num==1) {
                var noitem = '<div class="no-list">검색된 리스트가 없습니다.</div>';
                $("#test").html(noitem);
            }
			$("#debug").addClass("active");
			$("#debug").html("목록이 없습니다.");
			setTimeout(removeDebug,1500);
		}
		scrollchk=true;
        $('#container').off('scroll mousedown DOMMouseScroll mousewheel keyup');
	});

}
function fnwrite(){
	var type = $("#set_type").val();
	if(type == 1){
		//물건
		$(".category_menu").fadeIn(300,function(){
			$(".category_menu").addClass("active");
			location.hash='#category';
		});
	}else if(type == 2){
		//능력
		$(".category_menu2").fadeIn(300,function(){
			$(".category_menu2").addClass("active");
            location.hash='#category';
		});
	}else{
		alert("정상적인 방법으로 등록 바랍니다.");
		return false;
	}
}

function fnWriteStep2(url){
	document.write_form.action = url;
    //console.log(url + document.getElementById("write_from").action);
	document.write_form.submit();
}

function fnOnCam(){

	if($("#wr_title").val()==""){
		alert("제목을 입력해주세요");
		return false;
	}else{
		var title = $("#wr_title").val();
		var type = $("#type").val();
		var cate1 = $("#c").val();
		var cate2 = $("#sc").val();
		window.android.camereOn('<?php echo $member["mb_id"];?>',title,cate1,cate2,type);
	}
}

$(window).scroll(function(){
	if (Math.ceil($(window).scrollTop()) >= ($(document).height() - $(window).height())) {
	    if(scrollchk==true && finish == false) {
            $('.loader').show();
            fnlist(2, '');
            scrollchk = false;

            $('#container').bind('scroll mousedown DOMMouseScroll mousewheel keyup', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $("#container").stop();
                return false;
            });
        }
	}
});

function fnLikeUpdate(){
    var id = $("#like_id").val();
    var mb_id;
    <?php if($member["mb_id"]!=""){ ?>
        mb_id = "<?php echo $member["mb_id"];?>";
    <?php }else{?>
        alert("로그인후 이용바랍니다.");
        location.href=g5_bbs_url+'/login.php';
        return false;
    <?php }?>
    var text = $("#like_content").val();
    $.ajax({
       url:g5_url+"/mobile/page/like_product.php",
       method:"post",
        dataType:"json",
        data:{pd_id:id,mb_id:mb_id,like_content:text}
    }).done(function(data){
        if(data.result=="1"){
            alert('이미 평가한 글입니다.');
        }else if(data.result=="2"){
            alert("평가가 정상 등록됬습니다.");
        }else{
            alert("잘못된 요청입니다.");
        }
        $(".txt span").html(data.count);
        modalClose();
    });
}

</script>
<script src="https://hammerjs.github.io/dist/hammer.js"></script>

<?php
$p = "index";
include_once(G5_MOBILE_PATH.'/tail.php');
?>