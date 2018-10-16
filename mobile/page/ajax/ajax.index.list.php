<?php
include_once("../../../common.php");

if($_REQUEST["latlng"]) {
    $locs = explode("/", $_REQUEST["latlng"]);
    $lat = $locs[0];
    $lng = $locs[1];
}

//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0";

//검색 정렬 기본값
if($_SESSION["list_basic_order"]=="location"){
    $od = " order by p.pd_update desc, p.pd_date desc";
}else {
    $od = " order by p.pd_update desc, p.pd_date desc";
}

if($_REQUEST["sc_id"]){
    $sc_id = $_REQUEST["sc_id"];
    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);
}

if($schopt){
    $stx = $schopt["sc_tag"];

    $search .= " and (p.pd_name like '%{$stx}%' or p.pd_tag like '%{$stx}%' or p.pd_content like '%{$stx}%')";

    $type1 = $schopt["sc_type"];
    $type2 = $schopt["sc_type2"];
    $cate1 = $schopt["sc_cate1"];
    $cate2 = $schopt["sc_cate2"];


    if($type1!= $_SESSION["type1"]){
        $type1 = $_REQUEST["type1"];
    }

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

    if($align){
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
                            $od .= " p.pd_hits desc";
                        }else {
                            $od .= " , p.pd_hits desc";
                        }
                    }
                    break;
                case "pd_loc":
                    $align_active[$i] = $schopt["sc_od_loc"];
                    if($_SESSION["lat"] && $_SESSION["lng"]){
                        $sel = " , IF( p.pd_lat != '', (6371*acos(cos(radians({$_SESSION["lat"] }))*cos(radians(p.pd_lat))*cos(radians(p.pd_lng)-radians({$_SESSION["lng"]}))+sin(radians({$_SESSION["lat"] }))*sin(radians(p.pd_lat)))) AS distance , 0 as distance )";
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
    }else if($_SESSION["type1"]==2){
        $search .= " and p.pd_type = 2";
    }
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
$ss_id = session_id();
if($member["mb_id"]){
    $wished_id = $member["mb_id"];
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$member[mb_id]}') ";
}else{
    $wished_id = $ss_id;
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$ss_id}') ";
}

$sqls = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od} limit {$start},{$rows}";
//echo $sqls;
$res = sql_query($sqls);
while($row = sql_fetch_array($res)){
    $list[] = $row;
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



for($i=0;$i<count($list);$i++){
    if($list[$i]["distance"] == 0 ){
        $dist = "정보없음";
    }else {
        $dist = round($list[$i]["distance"],1) . "km";
    }
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
    for($k=0;$k<count($listadd);$k++){
        if($listadd[$k]["ad_sort"]==$cnt[$i]["num"]){
            ?>
            <div class="grid__item ad_list <?php if($list_type=="list"){echo " type_list";}?>" onclick="location.href='<?php echo $listadd[$k]["ad_link"];?>'">
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
}?>
<!-- div class="grid__item <?php echo "ajax_list";?> <?php if($list_type == "true"){echo " type_list";}?> <?php if($flag){echo "wishedon";}?>" id="list_<?php echo $list[$i]['pd_id'];?>" style="min-width: <?php echo $size[$i][0];?>;min-height:<?php echo $size[$i][1];?>;" -->
<div class="grid__item ajax_list <?php if($img1!=""){echo "images_list";}?> <?php if($flag){echo "wishedon";}?>  <?php if($list_type=="list"||$_SESSION["list_type"]=="list"){echo " type_list";}?> <?php if($list[$i]["pd_blind"]>=10){?>blinds<?php }?>" id="list_<?php echo $list[$i]['pd_id'];?>">
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
						<li><img src="<?php echo G5_IMG_URL?>/ic_hit<?php if($_SESSION["list_type"] == "grid"){echo "_list";}?>.svg" alt=""> <?php echo $list[$i]["pd_hits"];?></li>
						<?php if($app){?>
						<li><img src="<?php echo G5_IMG_URL?>/ic_loc<?php if($_SESSION["list_type"] == "grid"){echo "_list";}?>.svg" alt=""><?php echo $dist;?></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
        <?php if(count($save_pd_id_all)>0){
            if(!in_array($list[$i]['pd_id'],$save_pd_id_all)){
                ?>
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
                <h2><?php echo $pt2." ".$list[$i]["pd_tag"];?></h2>
            <?php }?>
			<div>
				<h1>￦ <?php echo number_format($list[$i]["pd_price"]);?></h1>
				<?php 
				if($flag){?>

				<img src="<?php echo G5_IMG_URL?>/ic_wish_on.svg" alt="" class="wished" >
				<?php }else{ ?>
				<img src="<?php echo G5_IMG_URL?>/ic_wish.svg" alt="" class="wished" >
				<?php }?>
			</div>
		</div>
		
	</div>
</div>

<?php }
if(count($list)==0){echo "no-list//".$odr;}
?>
<script>

    $(function(){
        // initial items reveal

        //그리드 아이템 가로 스크롤체크
        $(".ajax_list").each(function(e){
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
</script>

