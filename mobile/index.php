<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}
include_once(G5_EXTEND_PATH."/image.extend.php");

//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0 ";

//검색 정렬 기본값
if($_SESSION["list_basic_order"]=="location"){
    $od = " order by ISNULL(p.pd_lat) asc,  distance asc, p.pd_update desc, p.pd_date desc";
}else {
    $od = " order by p.pd_update desc, p.pd_date desc";
}

/*if($sc_id){
    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);

    //$stx = $schopt["sc_tag"];
}*/

if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

if($searchActive =="search") {
    //그냥 검색일경우

    //전체 검색어 업데이트
    $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip) values ('{$stx}', now(), '" . $_SERVER["REMOTE_ADDR"] . "')";
    sql_query($sql);

    //저장된 검색어를 불러올 경우 실행
    //검색목록 저장 or 업데이트
    //if(!$set_sc_id) {
    $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='', sc_cate1 = '', sc_cate2 = '',sc_align='{$order_sort}',sc_align_active='{$order_sort_active}' sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now(), sc_savetype = 1";
    echo $sql;
    sql_query($sql);
}
if($searchActive == "save"){
    
    $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='{$pd_type2}', sc_cate1 = '{$cate}', sc_cate2 = '{$cate2}', sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now(), sc_price_type = '{$pd_price_type}', sc_timeFrom = '{$pd_timeFrom}' , sc_timeTo = '{$pd_timeTo}', sc_savetype = 2, sc_status = '{$set_status}'";
    echo $sql;
    sql_query($sql);
    $sc_id = sql_insert_id();
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
    $pd_timeForm = $schopt["sc_meetFrom"];
    $pd_timeTo = $schopt["sc_meetTo"];
}else{
    echo "간편 검색";
    print_r2($_REQUEST);
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

if($pd_timeForm != "00" && $pd_timeTo != "00" && $pd_timeForm != $pd_timeTo){
    $search .= " and p.pd_timeForm = '{$pd_timeForm}' and p.pd_timeTo = '{$pd_timeTo}'";
}

if($order_sort){
    $od = "";
    $order_sorts = explode(",",$order_sort);
    $actives = explode(",",$order_sort_active);
    for($i=0;$i<count($order_sorts);$i++){
        if($order_sorts[$i]=="pd_date"){
            if($actives[$i] == 1){
                $checked = "checked";
                $ods[] = " p.pd_date desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_date">'.
                '<input type="checkbox" name="orders[]" value="pd_date" id="pd_date" '.$checked.'>'.
                '<span class="round">최신순</span></label>';
        }
        if($order_sorts[$i]=="pd_price"){
            if($actives[$i] == 1){
                $checked = "checked";
                $ods[] = " p.pd_price asc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_price">'.
                '<input type="checkbox" name="orders[]" value="pd_price" id="pd_price" '.$checked.'>'.
                '<span class="round">가격순</span></label>';
        }
        if($order_sorts[$i]=="pd_recom"){
            if($actives[$i] == 1){
                $checked = "checked";
                $ods[] = " p.pd_recom desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_recom">'.
                '<input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" '.$checked.'>'.
                '<span class="round">추천순</span></label>';
        }
        if($order_sorts[$i]=="pd_hits"){
            if($actives[$i] == 1){
                $checked = "checked";
                $ods[] = " p.pd_hits desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_hits">'.
                '<input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" '.$checked.'>'.
                '<span class="round">인기순</span></label>';
        }
        if($order_sorts[$i]=="pd_loc"){
            if($actives[$i] == 1){
                $checked = "checked";
                $ods[] = " , distance asc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_loc">'.
                '<input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" '.$checked.'>'.
                '<span class="round">거리순</span></label>';
        }
    }
    $od = " order by ". implode(",",$ods);
}

if($_SESSION["lat"] && $_SESSION["lng"]){
    $sel = " , 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS distance";
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

//내 게시글 블라인드 처리 확인
$sql = "select * from `product` where mb_id = '{$wished_id}' and pd_blind >= 10 and pd_blind_status = 1 order by pd_date asc limit 0 , 1";
$myblind = sql_fetch($sql);

$sql = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od} limit {$start},{$rows}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$list[] = $row;
}
$sql = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $pd_ids[] = $row["pd_id"];
}


//검색 최근 목록 저장
/*if($schopt) {
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
}*/


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

include_once(G5_MOBILE_PATH.'/head.php');

?>

<div class="loader" >
    <img src="<?php echo G5_IMG_URL?>/loader.svg" alt="" style="width:100%;position:relative;z-index:1">
</div>
</div>
<input type="hidden" value="<?php echo $schopt['sorts'];?>" id="sorts">
<div id="id01" class="w3-modal w3-animate-opacity no-view">
	<div class="w3-modal-content w3-card-4">
		<div class="w3-container">
			<form name="write_form" id="write_form" method="post" action="" onsubmit="return false;">
                <input type="hidden" value="<?php if($type1){echo $type1;}else{echo 1;}?>" name="wr_type1" id="wr_type1">
                <input type="hidden" name="cate1" id="c" value="<?php echo $cate1;?>">
				<input type="hidden" name="cate2" id="sc" value="<?php echo $cate2;?>">
				<input type="hidden" name="pd_type2" id="pd_type2" value="<?php if($pd_type2){echo $pd_type2;}else{echo 8;}?>">
                <div class="type2_box">
                    <label class="switch schtype2" >
                        <input type="checkbox" id="wr_type2" name="wr_type2" value="4">
                        <span class="slider round" >판매</span>
                    </label>
                </div>
				<h2>검색어</h2>
				<div>
					<input type="text" name="title" id="wr_title" placeholder="검색어 구분은 #으로 해주세요" required value="#" onkeyup="fnfilter(this.value,'wr_title')">
					<input type="text" name="wr_price" id="wr_price" placeholder="<?php if($type1==1){?>판매금액<?php }else{?>계약금<?php }?>" required value="" onkeyup="number_only(this)" style="<?php if($type1==2){?>width:30%;<?php }else{?>width:70%<?php }?>margin-right:5%;margin-top:0">
                    <input type="text" name="wr_price2" id="wr_price2" placeholder="거래완료금" required value="" onkeyup="number_only(this)" style="width:30%;margin-top:0;<?php if($type1==2){?>display:inline-block;<?php }else{?>display:none;<?php }?>">
                    <p class="write_help">예시 : 등록된 예시가 없습니다.</p>
				</div>
				<div>
					<input type="button" value="확인" style="background-color:yellow" onclick="<?php if($app){ ?>fnOnCam();<?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL."/page/write.php";?>');<?php }?>" class="types1">
					<input type="button" value="간편등록" onclick="fnSimpleWrite();" class="types2">
					<input type="button" value="상세등록" onclick="<?php if($app){ ?>fnOnCam();<?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL."/page/write.php";?>');<?php }?>" class="types2">
				</div>
                <div class="modal_close" onclick="modalClose();">
                    <img src="<?php echo G5_IMG_URL?>/ic_modal_close.png" alt="">
                </div>
			</form>
		</div>
	</div>
</div>
<div id="id06" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>알림</h2>
            <div class="con">
                <p>회원님의 게시물이 블라인드 처리되었습니다.</p>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="사유보기" style="width:auto" onclick="fnBlindView('<?php echo $myblind["pd_id"];?>');" >
            </div>
        </div>
    </div>
</div>
<div id="container" >
	<!--<input type="hidden" value="<?php /*if($schopt["sc_type"]){echo $schopt["sc_type"];}else{echo "1";}*/?>" name="write_type" id="write_type">-->
	<div class="write" onclick="<?php if(!$is_member){?>alert('로그인이 필요합니다.');location.href=g5_url+'/mobile/page/login_intro.php'; <?php }else if($member["mb_certify"]==""){ ?>alert('본인인증이 필요합니다.');location.href=g5_url+'/mobile/page/mypage/hp_certify.php';<?php }else if($member["mb_id"]){ ?>fnwrite();<?php } ?> ">
		<div class="write_btn">
            <?php if($set_type == 1 || $set_type == ""){?>
            <img src="<?php echo G5_IMG_URL?>/ic_write_btn.svg" alt="">
            <?php }else{ ?>
            <img src="<?php echo G5_IMG_URL?>/ic_write_btn_2.svg" alt="">
            <?php }?>
        </div>
		<div class="text" <?php if($set_type==2){?>style="background-color: rgb(255, 61, 0); color: rgb(255, 255, 255);"<?php }?>>
            <img src="<?php if($set_type == 1 || $set_type==""){echo G5_IMG_URL."/write_text_1.svg"; }else{ echo G5_IMG_URL."/write_text_2.svg";}?>" alt=""></div>
	</div>
	<section class="main_list">
		<article class="post" id="post">
            <input type="hidden" id="dWidth">
            <input type="hidden" id="dHeight">
			<div class="list_item grid are-images-unloaded" id="test">
				<?php
				for($i=0;$i<count($list);$i++){
				    if($list[$i]["distance"] == 0 ){
				        $dist = "정보없음";
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
                                        <?php for($k=0;$k<count($tags);$k++){
                                            $rand_font = rand(3,6);
                                            if($tags[$k]!=""){
                                            ?>
                                            <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                        <?php } }?>
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
                                    <?php for($k=0;$k<count($tags);$k++){
                                        $rand_font = rand(3,6);
                                        if($tags[$k]!=""){
                                        ?>
                                        <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                    <?php } }?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?php }?>
						<div class="top">
							<div>
								<h2><?php echo ($list[$i]["mb_level"]==4)?"전":"　";?></h2>
								<div>
									<ul>
                                        <?php if($_SESSION["list_type"]=="list"){?>
                                        <li><?php echo $time_gep;?></li>
                                        <?php }?>
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
                                <div class="list_wished_cnt"><?php echo $wished_cnt["cnt"];?></div>
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
    <?php if(count($list) == 0){?>
        fnSetting();
        $(".search_setting .no-list").show();
    <?php } ?>

    <?php if($myblind["pd_id"]){?>
    $("#id06").css("display","block");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
    location.hash = "#modal";
    <?php }?>
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
			$(".text img").attr("src","<?php echo G5_IMG_URL?>/write_text_1.svg");
			$(".write_btn img").attr("src","<?php echo G5_IMG_URL;?>/ic_write_btn.svg");
			$("#set_type").val("1");
			$("#wr_type1").val("1");
			$("#type").val("1");
            $("#theme-color").attr("content","#000000");
            $("#wr_price").attr("placeholder","판매금액");
            $("#wr_price").css("width","70%");
            $("#wr_price2").css("display","none");
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
            $(".text img").attr("src","<?php echo G5_IMG_URL?>/write_text_2.svg");
			$(".write_btn img").attr("src","<?php echo G5_IMG_URL;?>/ic_write_btn_2.svg");
			$("#set_type").val("2");
			$("#wr_type1").val("2");
			$("#type").val("2");
            $("#theme-color").attr("content","#ff3d00");
            $("#wr_price").attr("placeholder","계약금");
            $("#wr_price2").attr("placeholder","거래완료금");
            $("#wr_price").css("width","30%");
            $("#wr_price2").css({"display":"inline-block","width":"30%"});
			finish = false;

			fnlist(1,'');
		}
	});

    /*$("#wr_type2").change(function(){
        if($(this).prop("checked")==true){
            $("#pd_type2").val("4");
        }else{
            $("#pd_type2").val("8");
        }
    });*/
	//검색어 등록시 판매 구매 선택
    $(".schtype2 .slider").click(function(){
        console.log($("#wr_type1").val());
        if($(this).prev().prop("checked")==true){
            $(this).html('판매');
            $(this).css("text-align","right");
            //등록 버튼 수정
            $(".types1").css("display","inline-block");
            $(".types2").css("display","none");
            $("#pd_type2").val("8");
            if($("#wr_type1").val()==1){ //물건 판매 / 판매금액필요
                $("#wr_price").attr("placeholder","판매금액");
                $("#wr_price").css("width","70%");
                $("#wr_price2").css("display","none");
            }
            if($("#wr_type1").val()==2){ //능력 판매 / 계약금 / 계약완료금
                $("#wr_price").attr("placeholder","계약금");
                $("#wr_price2").attr("placeholder","거래완료금");
                $("#wr_price").css("width","30%");
                $("#wr_price2").css({"display":"inline-block","width":"30%"});
            }
        }else{
            $(this).html('구매');
            $(this).css("text-align","left");
            //등록 버튼 수정
            $(".types1").css("display","none");
            $(".types2").css("display","inline-block");
            $("#pd_type2").val("4");
            if($("#wr_type1").val()==1){ //물건 구매 / 구매예상금
                $("#wr_price").attr("placeholder","구매예상금");
                $("#wr_price").css("width","70%");
                $("#wr_price2").css("display","none");
            }
            if($("#wr_type1").val()==2){ //능력 구매 / 구매예상금
                $("#wr_price").attr("placeholder","구매예상금");
                $("#wr_price").css("width","70%");
                $("#wr_price2").css("display","none");
            }
        }
    })

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
			$(this).css({"background-image":"url(./img/ic_switch_grid.svg)"});
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
			$(this).css({"background-image":"url(./img/ic_switch_list.svg)"});
			$(".grid__item").addClass("type_list");
            finish = false;
			fnlist(1,'true');
		}
	});

	$(".category ul > li").click(function(){
	    if(!$(this).hasClass("sugg")) {
            $(this).addClass("active");
            $(".category ul li").not($(this)).removeClass("active");
            var id = $(this).attr("id");
            $("." + id).addClass("active");
            $(".category2 ul").not($("." + id)).removeClass("active");
            $("html, body").css("overflow", "hidden");
            $("html, body").css("height", "100vh");
        }
	});
	$(".category_menu .category2 ul li, .category_menu2 .category2 ul li").click(function(){
        var c = $(this).parent().parent().prev().children().find("li.active a").text();
        var sc = $(this).find("a").text();
        if(sc != "") {
            var type = $("#wr_type1").val();
            var msg = '';
            $.ajax({
                url: g5_url + "/mobile/page/ajax/ajax.category_info.php",
                method: "post",
                data: {cate: c, type: type}
            }).done(function (data) {
                msg = data;
                if (confirm(msg + "\r\n해당 카테고리로 게시글을 등록할까요?")) {
                    //$("#type").val(type);
                    $("#c").val(c);
                    $("#sc").val(sc);
                    $.ajax({
                        url: g5_url + "/mobile/page/ajax/ajax.category_tag.php",
                        method: "post",
                        data: {cate1: c, cate2: sc}
                    }).done(function (data) {
                        if (data != "") {
                            $(".write_help").html("예시 : " + data);
                        }
                    });
                    cateClose();
                    if(type==1){
                        $("#wr_price").attr("placeholder","판매금액");
                        $("#wr_price2").css("display","none");
                    }
                    if(type==2){
                        $("#wr_price").attr("placeholder","계약금");
                        $("#wr_price2").attr("placeholder","거래완료금");
                        $("#wr_price2").css("display","inline-block");
                    }
                    $("#id01").css("display", "block");
                    $("html, body").css("overflow", "hidden");
                    $("html, body").css("height", "100vh");
                    location.hash = "#modal";
                    $("#id01 #wr_title").selectRange(1,2);
                }
            });
        }
	});

    $.fn.selectRange = function(start, end) {
        return this.each(function() {
            if(this.setSelectionRange) {
                this.focus();
                this.setSelectionRange(start, end);
            } else if(this.createTextRange) {
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', start);
                range.select();
            }
        });
    };

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
                    $("#debug").html("내 글은 휴지통에 보낼 수 없습니다.");
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
        swiperm.add( new Hammer.Tap({ event: 'singletap', interval: 100}) );

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

                    $.ajax({
                        url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                        method:"POST",
                        data:{pd_id:pd_id,mode:"delete",mb_id:"<?php echo $wished_id;?>"}
                    }).done(function(data){
                        if(data == "delete query") {
                            console.log("A");
                            $("#" + id).removeClass("wishedon");
                            var wished = $("#" + id).children().find($(".wished"));
                            var wished_cnt = $("#" + id).children().find($(".list_wished_cnt"));
                            var wished_total = Number(wished_cnt.text()) - 1;
                            if(wished_total < 0){
                                wished_total = '';
                            }
                            wished.removeClass("element-animation");
                            wished.attr("src", g5_url + "/img/ic_wish.svg");
                            wished_cnt.html(wished_total);
                        }else{

                        }
                    });
                }else{
                    $.ajax({
                        url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                        method:"POST",
                        data:{pd_id:pd_id,mode:"insert",mb_id:"<?php echo $wished_id;?>"}
                    }).done(function(data){
                        if(data=="myproduct"){
                            alert("내가 올린 게시물은 추가할 수 없습니다.");
                        }
                        if(data=="ok query"){
                            $("#"+id).addClass("wishedon");
                            var wished = $("#"+id).children().find($(".wished"));
                            var wished_cnt = $("#" + id).children().find($(".list_wished_cnt"));
                            var wished_total = Number(wished_cnt.text()) + 1;
                            wished.removeClass("element-animation");
                            wished.addClass("element-animation");
                            wished.attr("src",g5_url+"/img/ic_wish_on.svg");
                            wished_cnt.html(wished_total);
                        }
                    });
                }
            }
        });
    });


    //게시글 등록 엔터
    $("#wr_title").keyup(function (e) {
        var type2 = $("#wr_type2").val();
        var text = $(this).val();
        /*if(text==""){
            $(this).val("#");
        }*/
        <?php if(!$app){?>
        //키보드 32
        if (e.keyCode == 32) {
            text = text.replace(" ","#");
            $(this).val(text);
        }
        <?php }else{ ?>
        text = text.replace(" ","#");
        $(this).val(text);
        <?php }?>
        /*
        var check = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/;
        if(check.test(text)){
            console.log("A");
            var chk = text.substr(0,1);
            if(chk != "#"){
                console.log("B");
                $(this).val("#"+text);
            }
            if(text.length >= 2 && (e.keyCode == 32 || e.keyCode == 229)){
                console.log("C");
                $(this).val(text + "#")
            }
        }else{
            if(e.keyCode==8 && text == "#" || e.keyCode==46 && text == "#"){
                $(this).val('');
                return false;
            }
            if(text.length == 1 && (e.keyCode != 32 || e.keyCode != 229)){
                $(this).val("#"+text);
            }
            if(text.length == 1 && (e.keyCode == 32 || e.keyCode == 229)){
                $(this).val("#");
            }
            if (text.length >= 2 && (e.keyCode == 32 || e.keyCode == 229)) {
                $(this).val(text + "#");
            }
            var chk = text.substr(0,1);
            if(chk != "#"){
                $(this).val("#"+text);
            }
        }
        */
        var chk = text.substr(0,1);
        if(chk != "#"){
            $(this).val("#"+text);
        }
        var cnt = text.split("#");
        if (cnt.length > 10) {
            if(confirm("검색어는 최대 10개까지 등록가능합니다. \r등록 하시겠습니까?")){
                if (type2 == 8) {
                    //판매시
                    <?php if($app){ ?>fnOnCam();
                    <?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL . "/page/write.php";?>');<?php }?>
                } else {
                    //구매시
                    <?php if($app){ ?>fnOnCam();
                    <?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL . "/page/write.php";?>');<?php }?>
                }
            }else{
                return false;
            }
        }
        if (e.keyCode == 13) {
            if (type2 == 8) {
                //판매시
                <?php if($app){ ?>fnOnCam();
                <?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL . "/page/write.php";?>');<?php }?>
            } else {
                //구매시
                <?php if($app){ ?>fnOnCam();
                <?php }else{ ?>fnWriteStep2('<?php  echo G5_MOBILE_URL . "/page/write.php";?>');<?php }?>
            }
        }
    });
});
function fnlist(num,list_type){
	//사고팔고/ /카테코리1/카테고리2/가격시작/가격끝/정렬순서1/정렬순서2/정렬순서3/정렬순서4/정렬순서5
	var type1,type2,cate1,cate2,stx,priceFrom,priceTo,sorts,app,mb_id,sc_id,align,orderactive,typecompany,price_type,meetFrom,meetTo;

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
    if($("#four").prop("checked") == true){
        type2 = 8;
    }else {
        type2 = 4;
    }
    if($("#mb_level").prop("checked")==true) {
        typecompany = 4;
    }else{
        typecompany = '';
    }
    cate1 = $("#cate").val();
    cate2 = $("#cate2").val();
    priceFrom = $("#sc_priceFrom").val();
    priceTo = $("#sc_priceTo").val();
    sorts = $("#order_sort").val();
    mb_id = $("#mb_id").val();
    align = $("#order_sort").val();
    orderactive = $("#order_sort_active").val();
    price_type = $("#pd_price_type").val();
    meetFrom = $("#pd_timeForm").val();
    meetTo = $("#pd_timeTo").val();


    var list_type = $("#set_list_type").val();
    var pd_ids = "<?php echo $saves["pd_ids"];?>";

    console.log(type1+"//"+type2+"//"+cate1+"//"+cate2+"//"+priceFrom+"//"+priceTo+"//"+sorts+"//"+align+"//"+mb_id+"//"+list_type+"//"+stx+"//"+pd_ids+"//"+orderactive+"//"+typecompany +"//" +set_search + "//" + price_type + "//" + meetFrom + "//" + meetTo);

	$.ajax({
		url:g5_url+"/mobile/page/ajax/ajax.index.list.php",
		method:"POST",
		data:{page:page,list_type:list_type,stx:stx,app:app,type1:type1,type2:type2,cate1:cate1,cate2:cate2,priceFrom:priceFrom,priceTo:priceTo,sorts:sorts,sc_id:sc_id,mb_id:mb_id,order_sort:align,latlng:latlng,pd_ids:pd_ids,mb_level:typecompany,order_sort_active:orderactive,set_search:set_search,pd_price_type:price_type,pd_timeFrom:meetFrom,pd_timeTo:meetTo},
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
		var type1 = $("#wr_type1").val();
		var type2 = $("#pd_type2").val();
		var cate1 = $("#c").val();
		var cate2 = $("#sc").val();
		var wr_price = $("#wr_price").val();
		var wr_price2 = $("#wr_price2").val();
		window.android.camereOn('<?php echo $member["mb_id"];?>',title,cate1,cate2,type1,type2,wr_price,wr_price2);
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
        console.log(data);
        if(data.result=="1"){
            alert('이미 평가한 글입니다.');
        }else if(data.result=="2"){
            alert("평가가 정상 등록됬습니다.");
        }else{
            alert("잘못된 요청입니다.");
        }
        $(".pd_like span").html(data.count);
        modalClose();
    });
}

function fnSimpleWrite(){
    if(confirm('간편등록 하시겠습니까?')){
        document.write_form.action = g5_url+"/mobile/page/write_simple_update.php";
        document.write_form.submit();
    }else{
        return false;
    }
}

</script>
<script src="https://hammerjs.github.io/dist/hammer.js"></script>

<?php
$p = "index";
include_once(G5_MOBILE_PATH.'/tail.php');
?>