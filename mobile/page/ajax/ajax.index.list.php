<?php
include_once("../../../common.php");

if($_REQUEST["latlng"]) {
    $locs = explode("/", $_REQUEST["latlng"]);
    $lat = $locs[0];
    $lng = $locs[1];
}

//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0 ";

//검색 정렬 기본값
if($_SESSION["list_basic_order"]=="location"){
    $od = " order by ISNULL(p.pd_lat) asc,  distance asc, p.pd_update desc, p.pd_date desc";
}else {
    $od = " order by p.pd_update desc, p.pd_date desc";
}
if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

if($sc_id){
    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);
    $set_type = $schopt["sc_type"];
    $type2 = $schopt["sc_type2"];
    $cate = $schopt["sc_cate1"];
    $cate2 = $schopt["sc_cate2"];
    $stx = $schopt["sc_tag"];
    $order_sort = $schopt["sc_align"];
    $order_sort_active = $schopt["sc_align_active"];
    $priceFrom = $schopt["sc_priceFrom"];
    $priceTo = $schopt["sc_priceTo"];
    $pd_price_type = $schopt["sc_worktime"];
    $pd_timeFrom = $schopt["sc_meetFrom"];
    $pd_timeTo = $schopt["sc_meetTo"];
}

if($set_type){
    $search .= " and p.pd_type = {$set_type}";
}

if($type2){
    $search .= " and p.pd_type2 = {$type2}";
}
if($stx){
    $search .= " and (p.pd_name like '%{$stx}%' or p.pd_tag like '%{$stx}%' or p.pd_cate like '%{$stx}%' or p.pd_cate2 like '%{$stx}%')";
}
if($cate){
    $search .= " and p.pd_cate = '{$cate}'";
}
if($cate2){
    $search .= " and p.pd_cate2 = '{$cate2}'";
}
if($priceFrom && $priceTo){
    $search .= " and p.pd_price between '{$priceFrom}' and '{$priceTo}'";
}
if($pd_price_type){
    $search .= " and p.pd_price_type = {$pd_price_type}";
}

if($pd_timeFrom && $pd_timeTo){
    $search .= " and p.pd_timeFrom = '{$pd_timeFrom}' and p.pd_timeTo = '{$pd_timeTo}'";
}

if($mb_level){
    $search .= " and m.mb_level = 4 ";
}
if($order_sort){
    $od = "";
    $order_sorts = explode(",",$order_sort);
    $actives = explode(",",$order_sort_active);
    for($i=0;$i<count($order_sorts);$i++){
        if($order_sorts[$i]=="pd_date"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_date desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_date">'.
                '<input type="checkbox" name="orders[]" value="pd_date" id="pd_date" '.$checked[$i].'>'.
                '<span class="round">최신순</span></label>';
        }
        if($order_sorts[$i]=="pd_price"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_price asc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_price">'.
                '<input type="checkbox" name="orders[]" value="pd_price" id="pd_price" '.$checked[$i].'>'.
                '<span class="round">가격순</span></label>';
        }
        if($order_sorts[$i]=="pd_recom"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_recom desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_recom">'.
                '<input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" '.$checked[$i].'>'.
                '<span class="round">추천순</span></label>';
        }
        if($order_sorts[$i]=="pd_hits"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_hits desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_hits">'.
                '<input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" '.$checked[$i].'>'.
                '<span class="round">인기순</span></label>';
        }
        if($order_sorts[$i]=="pd_loc"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                if($_SESSION["lat"] && $_SESSION["lng"]){
                    $ods[] = " ISNULL(p.pd_lat) asc, distance asc";
                }
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_loc">'.
                '<input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" '.$checked[$i].'>'.
                '<span class="round">거리순</span></label>';
        }
    }
    //print_r2($ods);
    $od = " order by ". implode(",",$ods);
}


if($_SESSION["lat"] && $_SESSION["lng"]){
    $sel = " , 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS distance";
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


$sqls = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od} limit {$start},{$rows}";
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

    $wished_cnt = sql_fetch("select count(*)as cnt from `wish_product` where pd_id = {$list[$i]["pd_id"]}");

    if($list[$i]["pd_date"]) {
        $loc_data = $list[$i]["pd_date"];
        if($list[$i]["pd_update"]){
            $loc_data = $list[$i]["pd_update"];
        }
        $now = date("Y-m-d H:i:s");
        $time_gep = round((strtotime($now) - strtotime($loc_data)) / 3600);
        if($time_gep == 0){
            $time_gep = "몇 분전";
        }else if($time_gep < 24){
            $time_gep = $time_gep."시간 전";
        }else if($time_gep > 24){
            $time_gep = round($time_gep / 24)."일 전";
        }
    }else{
        $time_gep = "정보 없음";
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
                        <?php if($list_type=="list"||$_SESSION["list_type"]=="list"){?>
                        <li style="margin-right:2vw;"><?php echo $time_gep;?></li>
                        <?php }?>
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

                <?php if($list[$i]["pd_type2"]==4){?>
                    <?php if($list[$i]["pd_price"]==0){?>
                        <h1>가격 제시</h1>
                    <?php }else{ ?>
                        <h1>￦ <?php echo number_format($list[$i]["pd_price"]);?></h1>
                    <?php }?>
                <?php }else{?>
                    <h1>￦ <?php echo number_format($list[$i]["pd_price"]);?></h1>
                <?php }?>
                <?php if($wished_cnt["cnt"]>0){?>
                    <div class="list_wished_cnt"><?php echo $wished_cnt["cnt"];?></div>
                <?php }?>
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
if(count($list)==0){echo "no-list//".$sqls;}
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
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                    method:"POST",
                    data:{pd_id:pd_id}
                }).done(function(data){
                    if(data=="1") {
                        $("#"+id).remove();
                        $grid.masonry('remove', this).masonry("layout");
                        $("#mobile_header #mobile_menu_btn").addClass("active");
                        $("#debug").addClass("active");
                        $("#debug").html("휴지통으로 이동되었습니다.");
                        setTimeout(removeDebug, 1500);
                    }else if(data=="3"){
                        $("#debug").addClass("active");
                        $("#debug").html("자신의 글은 휴지통에 보낼 수 없습니다.");
                        setTimeout(removeDebug, 1500);
                    }
                });
            });
            swiper.on("swiperight",function(e){

                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                    method:"POST",
                    data:{pd_id:pd_id}
                }).done(function(data){
                    if(data=="1") {
                        $("#"+id).remove();
                        $grid.masonry('remove', this).masonry("layout");
                        $("#mobile_header #mobile_menu_btn").addClass("active");
                        $("#debug").addClass("active");
                        $("#debug").html("휴지통으로 이동되었습니다.");
                        setTimeout(removeDebug, 1500);
                    }else if(data=="3"){
                        $("#debug").addClass("active");
                        $("#debug").html("자신의 글은 휴지통에 보낼 수 없습니다.");
                        setTimeout(removeDebug, 1500);
                    }
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
                    console.log("A");
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

