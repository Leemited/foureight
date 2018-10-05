<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

if($sc_id){
    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);
}

if($stx && $set == "simple"){
    if($member["mb_id"]){
        $mb_id = $member["mb_id"];
    }else{
        $mb_id = session_id();
    }
    $filter = explode(",",$config["cf_filter"]);

    for($i=0;$i<count($filter);$i++){
        if(strpos($stx,$filter[$i])!==false){
            $text = string_star($filter[$i],'harf','left');
            alert("검색어에 부적절한 단어[{$text}]가 포함되어 있습니다.");
            echo "<script>$(\"#stx\").val('');</script>";
            return false;
        }
    }
    //검색어 업데이트
    $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip) values ('{$stx}', now(), ".$_SERVER["REMOTE_ADDR"].")";
    sql_query($sql);
    
    //검색목록 저장 or 업데이트
    if(!$set_sc_id) {
        $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='', sc_cate1 = '', sc_cate2 = '', sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now()";
        sql_query($sql);
        $sc_id = sql_insert_id();
        $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
        $schopt = sql_fetch($sql);
    }else{
        $sql = "select * from `my_search_list` where sc_id = '{$set_sc_id}'";
        $schopt = sql_fetch($sql);
        if($stx != $schopt["sc_tag"]){
            $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='', sc_cate1 = '', sc_cate2 = '', sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now()";
            sql_query($sql);
            $sc_id = sql_insert_id();
            $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
            $schopt = sql_fetch($sql);
        }else {
            $sql = "update `my_search_list` set sc_datetime = now() where sc_id = '{$set_sc_id}'";
            sql_query($sql);
        }
    }
}

if($lat && $lng && $is_member){
    $sql = "update `g5_member` set mb_1 = '{$lat}', mb_2 = '{$lng}', mb_3 = now() where mb_id ='{$member[mb_id]}'";
    sql_query($sql);
}

if($schopt) {

    if($schopt["sc_type"]!=""){
        $_SESSION["type1"] = $schopt["sc_type"];
    }

    if($schopt["sc_align"]!="") {
        $order = explode(",", $schopt["sc_align"]);
        $order_disabled = explode(",", $schopt["sc_align_disabled"]);
        for ($i = 0; $i < count($order); $i++) {
            if ($order[$i] == "pd_date") {
                if($schopt["sc_od_date"] == 1) {
                    $sortActive[$i] = 1;
                    $orderlabel[$i] = '<label class=align for=pd_date><input type=checkbox name=orders[] value=pd_date id=pd_date checked><span class=round>최신순</span></label>';
                }else{
                    $sortActive[$i] = 0;
                    $orderlabel[$i] = '<label class=align for=pd_date><input type=checkbox name=orders[] value=pd_date id=pd_date ><span class=round>최신순</span></label>';
                }
            }
            if ($order[$i] == "pd_price") {
                if($schopt["sc_od_price"] == 1) {
                    $sortActive[$i] = 1;
                    $orderlabel[$i] = '<label class=align for=pd_price><input type=checkbox name=orders[] value=pd_price id=pd_price checked><span class=round>가격순</span></label>';
                }else{
                    $sortActive[$i] = 0;
                    $orderlabel[$i] = '<label class=align for=pd_price><input type=checkbox name=orders[] value=pd_price id=pd_price><span class=round>가격순</span></label>';
                }
            }
            if ($order[$i] == "pd_recom") {
                if($schopt["sc_od_recom"] == 1) {
                    $sortActive[$i] = 1;
                    $orderlabel[$i] = '<label class=align for=pd_recom><input type=checkbox name=orders[] value=pd_recom id=pd_recom checked><span class=round>추천순</span></label>';
                }else{
                    $sortActive[$i] = 0;
                    $orderlabel[$i] = '<label class=align for=pd_recom><input type=checkbox name=orders[] value=pd_recom id=pd_recom ><span class=round>추천순</span></label>';
                }
            }
            if ($order[$i] == "pd_hit") {
                if($schopt["sc_od_hit"] == 1) {
                    $sortActive[$i] = 1;
                    $orderlabel[$i] = '<label class=align for=pd_hit><input type=checkbox name=orders[] value=pd_hit id=pd_hit checked><span class=round>조회순</span></label>';
                }else{
                    $sortActive[$i] = 0;
                    $orderlabel[$i] = '<label class=align for=pd_hit><input type=checkbox name=orders[] value=pd_hit id=pd_hit ><span class=round>조회순</span></label>';
                }
            }
            if ($order[$i] == "pd_loc") {
                if($schopt["sc_od_loc"] == 1) {
                    $sortActive[$i] = 1;
                    $orderlabel[$i] = '<label class=align for=pd_loc><input type=checkbox name=orders[] value=pd_loc id=pd_loc checked><span class=round>거리순</span></label>';
                }else{
                    $sortActive[$i] = 0;
                    $orderlabel[$i] = '<label class=align for=pd_loc><input type=checkbox name=orders[] value=pd_loc id=pd_loc ><span class=round>거리순</span></label>';
                }
            }
        }

        $order_sort_active = implode(",", $sortActive);
    }
}

//글등록시
if($schopt["sc_type"]==2){
    $sql = "select * from `categorys` where `cate_type` = 2 and `cate_depth` = 1 order by cate_order";
}else {
    $sql = "select * from `categorys` where `cate_type` = 1 and `cate_depth` = 1 order by cate_order";
}

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$category1[] = $row;
}

if($schopt["sc_cate1"] && $schopt["sc_cate2"]){
    $sql = "select ca_id from `categorys` where `cate_name` = '{$schopt[sc_cate1]}' and `cate_depth` = 1 ";
    $ca_id = sql_fetch($sql);
    $sql = "select * from `categorys` where `cate_type` = '{$schopt[sc_type]}' and `cate_depth` = 2 and parent_ca_id = '{$ca_id[ca_id]}'";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $set_cate2[] = $row;
    }
}

//글등록시
$sql = "select * from `categorys` where `cate_type` = 2 and `cate_depth` = 1 order by cate_order";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $category2[] = $row;
}

$sql = "select * from `g5_write_help` where wr_is_comment = 0;";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$help[] = $row;
}

?>
<div id="id01s" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <div class="con">

            </div>
        </div>
    </div>
</div>
<div id="id0s" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <div class="con">

            </div>
        </div>
    </div>
</div>
<div id="id00" class="w3-modal w3-animate-opacity no-view">
	<div class="w3-modal-content w3-card-4">
		<div class="w3-container">
			<form name="write_from" method="post" action="<?php echo G5_URL?>/mobile/page/acomm_insert.php">
				<input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id" id="mb_id">
				<h2>제안하기</h2>
				<div>
					<input type="text" value="" name="cate_name" id="cate_name" placeholder="해당 '카테고리' 가 필요해요!" required>
					<textarea value="" name="cate_content" id="cate_content"  placeholder="사유를 적어주세요" required></textarea>
				</div>
				<div>
					<input type="button" value="취소" onclick="modalClose(this)"><input type="submit" value="확인" onclick="" >
				</div>
			</form>
		</div>
	</div>
</div>
<div id="id05" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2 style="width:65vw;margin-bottom:3vw;">키워드 상시 알림을 받겠습니까?</h2>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)">
                <input type="button" value="확인" onclick="fnSearchAgree();">
            </div>
        </div>
    </div>
</div>
<div id="id02" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" name="like_id" id="like_id" value="">
            <h2>평가하기</h2>
            <div class="likes">
                좋아요 <img src="<?php echo G5_IMG_URL?>/view_like.svg" alt="" class="likeimg" >
            </div>
            <div>
                <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnLikeUpdate();" >
            </div>
        </div>
    </div>
</div>
<div id="id03" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <h2>상태변경</h2>
                <div>
                    <ul class="modal_sel">
                        <li class="active" >판매중</li>
                        <li class="" id="status_buy">거래중</li>
                        <li class="" id="status_">판매보류</li>
                        <li class="" >판매완료</li>
                    </ul>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnStatusUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>
<div id="id04" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <h2>블라인드 사유</h2>
                <div>
                    <ul class="blind_ul">

                    </ul>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnStatusUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 모바일 헤더 시작 -->
<div id="head">
	<div class="top_header" onclick="location.href='<?php echo G5_URL?>';" <?php if($schopt["sc_type1"]==2 || $_SESSION["type1"] == 2){?>style="background-color: rgb(255, 61, 0);"<?php }?>>
		<div class="owl-carousel" id="helps">
			<?php for($i=0;$i<count($help);$i++){?>
			<div class="item"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=help&wr_id=<?php echo $help[$i]["wr_id"];?>"><?php echo $help[$i]["wr_subject"];?></a></div>
			<div class="item"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=help&wr_id=<?php echo $help[$i]["wr_id"];?>"><?php echo $help[$i]["wr_subject"];?></a></div>
			<?php }?>
		</div>
	</div>
    <script>
        var owl = $("#helps");
        owl.owlCarousel({
            center: true,
            items:1,
            loop:true,
            autoplay:true,
            smartSpeed:3000,
            autoplaySpeed:3000,
            nav:false,
            navText:['',''],
            dot:false
        });
    </script>
	<header id="mobile_header">
		<!-- <h1><a href="<?php echo G5_URL; ?>" title="HOME" class="logos"><i></i></a></h1> -->
		<div class="search">
			<form action="./" method="get" name="simplesearch" id="simplesearch" >
                <input type="text" style="display:none;">
                <input type="hidden" value="simple" name="set" id="set">
                <input type="hidden" name="set_type" id="set_type" value="hidden" >
                <input type="hidden" name="set_sc_id" id="set_sc_id" value="<?php echo $sc_id;?>" >
                <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="fnSimpleSearch();">
                <input type="text" name="stx" id="stx" value="<?php echo $schopt["sch_tag"];?>" placeholder="원하는 물건이 있으세요?" onkeyup="fnKeyword();" />
                <label class="switch schtype" >
                    <input type="checkbox" id="type1" name="type1" <?php if($schopt["sc_type"]=="2" || $_SESSION["type1"]=="2"){?>checked<?php }?> >
                    <span class="slider round" <?php if($schopt["sc_type"]=="2"){?>style="text-align:left"<?php }else{ if($_SESSION["type1"]=="2"){ ?>style="text-align:left"<?php } }?>>
                        <?php if($schopt["sc_type"]=="2" || $_SESSION["type1"]=="2"){?>능력<?php }else{ ?>물건<?php }?>
                    </span>
                </label>
			</form>
		</div>
		<a href="javascript:" id="mobile_menu_btn" class="mobile_menu_btn" title="MENU"><i></i></a>
		<a href="javascript:fnSetting();" id="mobile_setting_btn" title="SETTING"><i></i></a>
	</header>
	<div class="keyword">
		<div>
			연관검색어		
		</div>
		<ul>
			<li>인기 검색어</li>
			<li>최신 검색어</li>
		</ul>	
	</div>
	<div class="search_setting">
		<form action="<?php echo G5_URL?>/mobile/page/savesearch/save_search.php" method="post" name="savesch" id="savesch" >
			<input type="hidden" name="sc_id" id="sc_id" value="<?php echo $sc_id;?>" >
			<input type="hidden" name="set_type" id="set_type" value="<?php if($schopt["sc_type"]){echo $schopt["sc_type"];}else if($_SESSION["type1"]){echo $_SESSION["type1"];}else{echo "1";}?>" >
			<input type="hidden" value="<?php if($schopt["sc_priceFrom"]!=""){echo $schopt['sc_priceFrom'];}?>" id="sc_priceFrom" name="priceFrom">
			<input type="hidden" value="<?php if($schopt["sc_priceTo"]!=""){echo $schopt['sc_priceTo'];}?>" id="sc_priceTo" name="priceTo">
			<input type="hidden" value="<?php if($schopt["sc_tag"]!=""){echo $schopt['sc_tag'];}?>" name="sch_text" id="sch_text" >
            <input type="hidden" value="save" name="formtype" id="formtype">
            <input type="hidden" value="N" name="saveAgree" id="saveAgree">
            <input type="hidden" value="<?php echo $schopt["sc_align"];?>" name="order_sort" id="order_sort">
            <input type="hidden" value="<?php echo $schopt["sc_align_disabled"];?>" name="un_order_sort" id="un_order_sort">
            <input type="hidden" value="<?php echo $order_sort_active;?>" name="order_sort_active" id="order_sort_active">
            <input type="hidden" value="<?php echo $_SESSION["list_type"];?>" name="set_list_type" id="set_list_type">
			<div class="sch_top">
				<select name="cate" id="cate" class="sel_cate input01s" required >
					<option value="">1차 카테고리</option>
					<?php for($i=0;$i<count($category1);$i++){ ?>
					<option value="<?php echo $category1[$i]["cate_name"];?>" id="<?php echo $category1[$i]["ca_id"];?>" <?php if($schopt["sc_cate1"]!=""){if($schopt["sc_cate1"] == $category1[$i]["cate_name"]){?>selected<?php } }?>><?php echo $category1[$i]["cate_name"];?></option>
					<?php } ?>
				</select>
				<select name="cate2" id="cate2" class="sel_cate input01s" required >
					<option value="">2차 카테고리</option>
                    <?php if($sc_id && $schopt["sc_cate2"]!=""){
                        for($i=0;$i<count($set_cate2);$i++){
                        ?>
                            <option value="<?php echo $set_cate2[$i]["cate_name"];?>" id="<?php echo $set_cate2[$i]["ca_id"];?>" <?php if($schopt["sc_cate2"]!=""){if($schopt["sc_cate2"] == $set_cate2[$i]["cate_name"]){?>selected<?php } }?>><?php echo $set_cate2[$i]["cate_name"];?></option>
                    <?php } }?>
				</select>
                <a href="javascript:fnsuggestion();">제안하기</a>
			</div>
			<div class="types sch_mid">
				<label class="radio_tag" for="four">
					<input type="radio" name="type2" id="four" value="8" <?php if($schopt["sc_type2"]=="8" || $schopt["sc_type2"] == ""){?>checked<?php  }?>>
					<span class="slider2 round">팝니다</span>
				</label>
				<label class="radio_tag" for="eight">
					<input type="radio" name="type2" id="eight" value="4" <?php if($schopt["sc_type2"]=="4"){?>checked<?php } ?>>
					<span class="slider2 round">삽니다</span>
				</label>
			</div>
			<div class="sch_ord">
                <?php if(count($order)==0 || $schopt["sc_align"] == ""){?>
				<label class="align" id="sortable" for="pd_date">
					<input type="checkbox" name="orders[]" value="pd_date" id="pd_date" checked>
					<span class="round">최신순</span>
				</label>
				<label class="align" id="sortable" for="pd_price">
					<input type="checkbox" name="orders[]" value="pd_price" id="pd_price" checked>
					<span class="round">가격순</span>
				</label>
				<label class="align" id="sortable" for="pd_recom">
					<input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" checked>
					<span class="round">추천순</span>
				</label>
				<label class="align" id="sortable" for="pd_hit">
					<input type="checkbox" name="orders[]" value="pd_hit" id="pd_hit" checked>
					<span class="round">인기순</span>
				</label>
				<label class="align" id="sortable" for="pd_loc">
					<input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" checked>
					<span class="round">거리순</span>
				</label>
                <?php }else{
                    for($i=0;$i<count($orderlabel);$i++){
                        echo $orderlabel[$i];
                    } }?>
			</div>
			<div class="clear"></div>
			<div class="sch_price">
				<div class="pr">
					<h2>금액설정</h2>
					<p class="price" id="schp"></p>
				</div>
				<div id="slider-range"></div>
			</div>
			<!--<div class="sch_tags">
				<p>카테고리를 선택해주세요.</p>
			</div>-->
			<div class="sch_btn_group">
				<!--<input type="button" value="제안하기" class="sch_btn btn_light" onclick="fnsuggestion();">	-->
				<input type="button" value="현검색저장" class="sch_btn" onclick="fnSaveSch()">
				<input type="button" value="검색" class="sch_btn" onclick="list_search();">
				<input type="button" value="삽니다 간편등록" class="sch_btn sch_save_write" onClick="fnWrite();">
			</div>
            <div class="search_close" onclick="fnSetting()">
                <img src="<?php echo G5_IMG_URL?>/search_close.png" alt="">
            </div>
            <div class="header_bg"></div>
		</form>
	</div>
	<div class="mobile_menu">
		<span></span>
		<div class="menu">
			<div class="user_box">
				<?php if(!$member["mb_id"]){?>
				<p><a href="<?php echo G5_URL?>/mobile/page/login_intro.php">로그인</a></p>
				<?php }else{?>
				<p><a href="<?php echo G5_BBS_URL?>/logout.php?url=../index.php?device=mobile">로그아웃</a></p>
				<?php } ?>
				<div onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php'">
					<?php if($member["mb_id"] && $member["mb_profile"]){?>
					<img src="<?php echo $member["mb_profile"];?>" alt="" class="user_profile">
					<?php }else{?>
					<img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
					<?php }?>
				</div>
				<?php if(!$member["mb_id"]){?>
				<h4>지금 로그인하세요</h4>
				<span>로그인 상태가 아닙니다.</span>
				<?php }else{?>
				<h4><?php echo $member["mb_name"];?></h4>
				<span><?php echo ($member["mb_addr1"])?$member["mb_addr1"]:"저장된 주소가 없습니다.";?></span>

				<?php }?>
			</div>
			<ul>
				<li class="menu1"><a href="<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_profile.svg" alt="">내프로필</a></li>
				<li class="menu2"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_chat.svg" alt="">대화목록</a></li>
				<li class="menu3"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_cart.svg" alt="">장바구니</a></li>
				<li class="menu4"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_order.svg" alt="">거래내역</a></li>
				<li class="menu6"><a href="<?php echo G5_MOBILE_URL?>/page/trash/trash_list.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash.svg" alt="">휴지통</a></li>
                <li class="menu6"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice"><img src="<?php echo G5_IMG_URL?>/ic_menu_customer.svg" alt="">고객센터</a></li>
				<li class="menu7"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=help"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">도움말</a></li>
				<li class="menu8"><a href="<?php echo G5_MOBILE_URL?>/page/mypage/settings.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_settings.svg" alt="">설정</a>
                    <div class="sugg"><a href="javascript:fnsuggestion();">제안하기</a></div>
                </li>
			</ul>
			<!-- <div class="copyright" style="">
				<img src="<?php echo G5_IMG_URL?>/mobile_menu_logo.png" alt="" />
			</div> -->
		</div>
	</div>
	<div class="category_menu">
		<div class="cate_header">
			<img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" onclick="cateClose();">
			<h2>카테고리 설정</h2>
		</div>
		<div class="category catetype1">
			<ul>
				<?php for($i=0;$i<count($category1);$i++){ ?>
				<li class="cate<?php echo $category1[$i]["ca_id"]; ?> <?php if($i==0){?>active<?php } ?>" id="scate<?php echo $category1[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category1[$i]["cate_name"];?></a></li>
				<?php } ?>
                <li class="sugg" onclick="fnsuggestion();"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">제안하기</li>
			</ul>
		</div>
		<?php include_once(G5_MOBILE_PATH."/subcategory1.php"); ?>
	</div>
	<div class="category_menu2">
		<div class="cate_header">
			<img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" onclick="cateClose();">
			<h2>카테고리 설정</h2>
		</div>
		<div class="category catetype2">
			<ul>
				<?php for($i=0;$i<count($category2);$i++){ ?>
				<li class="cate<?php echo $category2[$i]["ca_id"]; ?> <?php if($i==0){?>active<?php } ?>" id="scate<?php echo $category2[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category2[$i]["cate_name"];?></a></li>
				<?php } ?>
			</ul>
		</div>
		<?php include_once(G5_MOBILE_PATH."/subcategory2.php"); ?>
	</div>
	<div class="search_etc">
		<div class="sort">
            <div class="left">
                <img src="" alt="">
            </div>
			<!--<div class="left">
				<label class="switch del" for="delete">
					<input type="checkbox" id="delete" >
					<span class="slider round"></span>
				</label>
			</div>-->
			<div class="right">
				<label class="switch align" for="paplur">
					<input type="checkbox" name="paplur" id="paplur" value="1" <?php if($_SESSION["list_basic_order"]=="location"){?>checked<?php }?>>
					<span class="slider round" style="<?php if($_SESSION["list_basic_order"]=="location"){?>text-align: left;<?php }?>"><?php if($_SESSION["list_basic_order"]=="hits"){?>최신<?php }else{?>거리<?php }?></span>
				</label>
				<label class="switch list" for="list_type">
					<input type="checkbox" name="list_type" id="list_type" <?php if($_SESSION["list_type"]=="list"){?>checked<?php }?>>
					<span class="slider round" style="<?php if($_SESSION["list_type"]=="list"){?>text-align: left; background-image: url(<?php echo G5_IMG_URL?>/ic_list.png); background-position: 2.2vw center;<?php }?>"></span>
				</label>
			</div>
		</div>
		<div class="sort_bg"></div>
		<div class="clear"></div>
	</div>
</div>
<script>
var slider;
var fnc = true;
function fnKeyword(){
    //엔터가 아닐때
    if(window.event.keyCode != 13) {
        if (fnc == true) {
            setTimeout(fnkeywordon, 1500);
        }
    }else {//엔터 일때
        if ($("#set").val() == "2") {
            // 설정에서 검색어 체크
            $("#sch_text").val($("#stx").val());
            //설정된 검색어 저장후 검색
            list_search();
            return false;
        } else {
            //바로 간단검색
            document.simplesearch.submit();
        }
    }
}

function fnSimpleSearch(){
    /*if($("#stx").val() == ""){
        alert("검색어를 입력해주세요.");
        return false;
    }*/
    if ($("#set").val() == "2") {
        // 설정에서 검색어 체크
        $("#sch_text").val($("#stx").val());
        //설정된 검색어 저장후 검색
        list_search();
        return false;
    } else {
        //바로 간단검색
        document.simplesearch.submit();
    }
}

//딜레이후 실행
function fnkeywordon(){
	fnc = false;
}

$(function(){
    var active = '';

    $("input[name^=orders]").each(function(e){
        if(e==0) {
            $("#order_sort").val($(this).val());
        }else{
            var align = $("#order_sort").val();
            var data = align + ","+$(this).val();
            $("#order_sort").val(data);
        }
        if($(this).is(":checked")==true){
            if(active == ''){
                    active = "1";
            }else{
                active = active + ",1";
            }
        }else{
            if(active == ''){
                active = "0";
            }else{
                active = active + ",0";
            }
        }
        $("#order_sort_active").val(active);
    })

    $("#stx").on("focus", function(){
        $(".write").hide();
       $("#ft").hide();
    });
    $("#stx").on("blur", function(){
        $(".write").show();
        $("#ft").show();
    });

    $("#mobile_header #mobile_menu_btn").click(function(){
        if($(".search_setting").attr("id") == "menuon"){
            $("#set").val(1);
            $(".search_setting").attr("id","");
            $(".search_setting").css("top","-100vh");
        }
        location.hash = "#menu";
    });

    //getRegid
    try{
        var regid = window.android.getRegid();
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.regid.update.php",
            method:"post",
            data:{regid:regid,mb_id:"<?php echo $member["mb_id"];?>"}
        }).done(function(data){
            console.log(data);
        })
    }catch(err){
        var regId = undefined;
        console.log(err);
    }


	$(".schtype .slider").click(function(){
		if($(this).prev().prop("checked") == true){
			$(this).html("물품");
			$(this).css({"text-align":"right"});
            $("#set_type").val(1);
			//카테고리 설정
			$.ajax({
				url:g5_url+"/mobile/page/ajax/ajax.category.php",
				method:"POST",
				data:{type1:"1"}
			}).done(function(data){
				$("#cate option").remove();
				$("#cate").append(data);
				$("#cate2 option").remove();
				$("#cate2").append("<option value=''>2차 카테고리</option>");
			});
		}else{
			$(this).html("능력");
			$(this).css({"text-align":"left"});
			$("#set_type").val(2);
			//카테고리 설정
			$.ajax({
				url:g5_url+"/mobile/page/ajax/ajax.category.php",
				method:"POST",
				data:{type1:"2"}
			}).done(function(data){
				$("#cate option").remove();
				$("#cate").append(data);
				$("#cate2 option").remove();
				$("#cate2").append("<option value=''>2차 카테고리</option>");
			});
		}
	});

	$("#cate").change(function(){
		var type1 = $(".schtype .slider").text();
		var ca_id = $("#cate option:selected").attr("id");
		console.log(ca_id);
		var text = $("#cate option:checked").text();

		$.ajax({
			url:g5_url+"/mobile/page/ajax/ajax.category2.php",
			method:"POST",
			data:{type1:type1,ca_id:ca_id}
		}).done(function(data){
			$("#cate2 option").remove();
			$("#cate2").append(data);
			$("#cate1").val(text);
		});
	});

	$("#cate2").change(function(){
		var cate2 = $(this).val();
		var cate1 = $("#cate").val();
		$.ajax({
			url:g5_url+"/mobile/page/ajax/ajax.category_minmax.php",
			method:"POST",
            dataType:"json",
			data:{cate2:cate2,cate1:cate1}
		}).done(function(data){
		    if(data.max != null) {
		        if(data.max==0){

                }else {
                    $("#priceFrom").val(data.max);
                    $("#schp").text(number_format(1000) + " ~ " + number_format(data.max));
                    slider.slider("option", "max", data.max);
                }
            }
		});
	});

	$(".radio_tag").click(function(){
		if($("#eight").prop("checked") == true){
			$(".sch_save_write").css("display","none");
		}else{
			$(".sch_save_write").css("display","inline");
		}
	});
    <?php if(!$sc_id){?>
    var max = 500000;
    <?php }else{?>
    var max = $("#sc_priceTo").val();
    <?php }?>

	slider = $( "#slider-range" ).slider({
		range: true,
		min: 1000,
		max: 500000,
		values: [1000 , max ],
        step:1000,
		slide: function( event, ui ) {
			$("#sc_priceTo").val(ui.values[1]);
			$("#sc_priceFrom").val(ui.values[0]);
			$("#schp").text( number_format(ui.values[0])+" ~ "+number_format(ui.values[1]));
		}
	});
	<?php if(!$sc_id){?>
	$("#priceTo").val($( "#slider-range" ).slider( "values", 0 ));
	$("#priceFrom").val($( "#slider-range" ).slider( "values", 1 ));
	$( "#schp" ).text(number_format($( "#slider-range" ).slider( "values", 0 )) + " ~ " + number_format($( "#slider-range" ).slider( "values", 1 )));
    <?php }else{?>
    $( "#schp" ).text(number_format($("#sc_priceFrom").val()) + " ~ " +number_format($("#sc_priceTo").val()));
    <?php }?>
	var change = false;

	$(".sch_ord").sortable({
        axis:"x",
        start:function(event,ui){
            var id = ui.item.context.firstElementChild;
        }
        ,
        stop:function(event,ui){
            var id = ui.item.context.firstElementChild;
            if(change==false){
                if($(id).is(":checked")==true){
                    $(id).prop("checked",false);
                    var disabled_align =  $("#un_order_sort").val();
                    if(disabled_align == ""){
                        $("#un_order_sort").val($(id).val());
                    }else{
                        $("#un_order_sort").val(disabled_align +","+ $(id).val());
                    }
                }else{
                    $(id).prop("checked",true);
                    var disabled_align =  $("#un_order_sort").val();
                    var aligns = disabled_align.split(",");
                    var data = '';
                    if(aligns.length == 1){
                        $("#un_order_sort").val('');
                    }else{
                        for(var i = 0; i < aligns.length; i ++){
                            if($(id).val() != aligns[i]){
                                if(data==''){
                                    data = aligns[i];
                                }else{
                                    data = data + "," + aligns[i];
                                }
                            }
                        }
                        $("#un_order_sort").val(data);
                    }
                }
            }
            change = false;
            var active = '';
            $("input[name^=orders]").each(function(e){
                if(e==0) {
                    $("#order_sort").val($(this).val());
                }else{
                    var align = $("#order_sort").val();
                    var data = align + ","+$(this).val();
                    $("#order_sort").val(data);
                }

                if($(this).is(":checked")==true){
                    if(active == ''){
                        active = "1";
                    }else{
                        active = active + ",1";
                    }
                }else{
                    if(active == ''){
                        active = "0";
                    }else{
                        active = active + ",0";
                    }
                }
                $("#order_sort_active").val(active);
            });
        },
        change:function(event,ui){
            change = true;
            var id = ui.item.context.firstElementChild;
            console.log("변경"+$(id).is(":checked"));
        }

    }).disableSelection();

	<?php if(!$chkMobile){?>

    $(".sch_ord .align input").each(function(e){
        $(this).change(function(){
            var active = '';
            if($(this).is(":checked") == false){
                var disabled_align = $("#un_order_sort").val();
                if(disabled_align == ""){
                    $("#un_order_sort").val($(this).val());
                }else{
                    $("#un_order_sort").val(disabled_align +","+ $(this).val());
                }
            }else{
                var disabled_align =  $("#un_order_sort").val();
                var aligns = disabled_align.split(",");
                var data = '';
                if(aligns.length == 1){
                    $("#un_order_sort").val('');
                }else{
                    for(var i = 0; i < aligns.length; i ++){
                        if($(this).val() != aligns[i]){
                            if(data==''){
                                data = aligns[i];
                            }else{
                                data = data + "," + aligns[i];
                            }
                        }
                    }
                    $("#un_order_sort").val(data);
                }
            }
        });
    });
    <?php }?>
});
function fnSaveSch(){
    var sort = "";
    var sortActive = "";
    $("input[name^=orders]").each(function(e){
        if(e==0) {
            sort = $(this).val();
        }else{
            sort = sort + "," + $(this).val();
        }
        if($(this).is(":checked") == true){
            if(e==0){
                sortActive = '1';
            }else{
                sortActive = sortActive +",1";
            }
        }else{
            if(e==0){
                sortActive = '0';
            }else{
                sortActive = sortActive + ",0";
            }
        }
    });
    $("#formtyp").val("svae");
    $("#order_sort").val(sort);
    $("#order_sort_active").val(sortActive);
    $("#sch_text").val($("#stx").val());
    if($("#set_type").val()==""){
        alert("물건/능력을 선택해주세요");
        return false;
    }
    if($("#sch_text").val() == ""){
        alert("검색어를 입력해 주세요");
        return false;
    }
    if($("#cate").val() == ""){
        alert("1차 카테고리를 선택해 주세요.");
        return false;
    }
    if($("#cate2").val() == ""){
        alert("2차 카테고리를 선택해 주세요.");
        return false;
    }
    $(".search_setting").attr("id","");
    $(".search_setting").css("top","-100vh");
    $("#id05").css("display","block");
    location.hash="#modal";
}

function fnSearchAgree(){
    $("#saveAgree").val('Y');

	document.savesch.submit();
}

function list_search(){
    $("#formtype").val("search");
    $("#sch_text").val($("#stx").val());
    if($("#set_type").val()==""){
        alert("물건/능력을 선택해주세요");
        return false;
    }
    if($("#sch_text").val() == ""){
        alert("검색어를 입력해주세요.");
        return false;
    }
    if($("#cate").val() == ""){
        alert("1차 카테고리를 선택해 주세요.");
        return false;
    }
    if($("#cate2").val() == ""){
        alert("2차 카테고리를 선택해 주세요.");
        return false;
    }
    document.savesch.submit();
}

function fnWrite(){
    $("#formtype").val("write");
    $("#sch_text").val($("#stx").val());
    if($("#set_type").val()==""){
        alert("물건/능력을 선택해주세요");
        return false;
    }
    if($("#sch_text").val() == ""){
        alert("검색어를 입력해 주세요");
        return false;
    }
    if($("#cate").val() == ""){
        alert("1차 카테고리를 선택해 주세요.");
        return false;
    }
    if($("#cate2").val() == ""){
        alert("2차 카테고리를 선택해 주세요.");
        return false;
    }
    document.savesch.submit();
}
</script>