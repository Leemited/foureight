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

if($lat && $lng && $is_member){
    $sql = "update `g5_member` set mb_1 = '{$lat}', mb_2 = '{$lng}', mb_3 = now() where mb_id ='{$member[mb_id]}'";
    sql_query($sql);
}

$sql = "select * from `categorys` where `cate_type` = 1 and `cate_depth` = 1 order by cate_order";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$category1[] = $row;
}

$sql = "select * from `categorys` where `cate_type` = 2 and `cate_depth` = 1 order by cate_order";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $category2[] = $row;
}

//??
if($schopt["sc_cate1"] && $schopt["sc_cate2"]){
    $sql = "select ca_id from `categorys` where `cate_name` = '{$schopt[sc_cate1]}' and `cate_depth` = 1 ";
    $ca_id = sql_fetch($sql);
    $sql = "select * from `categorys` where `cate_type` = '{$schopt[sc_type]}' and `cate_depth` = 2 and parent_ca_id = '{$ca_id[ca_id]}'";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $set_cate2[] = $row;
    }
}

$sql = "select * from `g5_write_help` where wr_is_comment = 0;";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$help[] = $row;
}

//새알림
$start = date("Y-m-d");
$end = date("Y-m-d", strtotime("-3 month"));
$sql = "select count(*) as cnt from `my_alarms` where mb_id = '{$mb_id}' and alarm_status = 0 and alarm_date BETWEEN '{$end}' and '{$start}'";
$alarms = sql_fetch($sql);

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
                <input type="button" value="아니오" onclick="fnSearchAgree('0')">
                <input type="button" value="네" onclick="fnSearchAgree('1');">
            </div>
        </div>
    </div>
</div>
<div id="id02" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" name="like_id" id="like_id" value="">
            <input type="hidden" name="view_pd_type" id="view_pd_type" value="">
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
                <input type="hidden" name="up_pd_id" id="up_pd_id" value="">
                <h2>상태변경</h2>
                <div>
                    <ul class="modal_sel">
                        <li id="status1" class="active" >판매중</li>
                        <li id="status2" class="" >거래중</li>
                        <li id="status3" class="" >판매보류</li>
                        <li id="status4" class="" >판매완료</li>
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
                    <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="제시하기" onclick="fnPricingUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>
<div id="id07" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <input type="hidden" name="p_pd_id" id="p_pd_id" value="">
                <input type="hidden" name="p_type" id="p_type" value="">
                <h2>제시하기</h2>
                <div>
                    <select name="prcing_pd_id" id="prcing_pd_id" required>
                        <option value="">게시물 선택</option>
                    </select>
                    <ul class="blind_ul">
                        <li>
                            <input type="text" placeholder="제시내용을 입력하세요." name="pricing_content" id="pricing_content" required>
                        </li>
                        <li>
                            <input type="text" placeholder="가격을 입력해주세요." name="pricing_price" id="pricing_price" style="margin-top:0;" onkeyup="number_only(this)">
                        </li>
                    </ul>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="제시등록" style="width:auto;padding:2vw 3vw" id="up_btn" onclick="fnPricingUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 모바일 헤더 시작 -->
<div id="head">
	<div class="top_header" onclick="location.href='<?php echo G5_URL?>';" <?php if($set_type==2){?>style="background-color: rgb(255, 61, 0);"<?php }?>>
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
    <form action="./" method="get" name="simplesearch" id="simplesearch" >
        <header id="mobile_header">
            <!-- <h1><a href="<?php echo G5_URL; ?>" title="HOME" class="logos"><i></i></a></h1> -->
            <div class="search">
                <input type="text" style="display:none;">
                <input type="hidden" name="set_type" id="set_type" value="<?php if($set_type){echo $set_type;}else{echo 1;}?>">
                <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="fnSimpleSearch();">
                <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" placeholder="원하는 물건이 있으세요?" onkeyup="fnKeyword();" />
                <label class="switch schtype" >
                    <input type="checkbox" id="type1" name="type1" value="1" <?php if($set_type=="2"){?>checked<?php }?> >
                    <span class="slider round" <?php if($set_type=="2"){?>style="text-align:left"<?php }?>>
                    <?php if($set_type=="2"){?>능력<?php }else{ ?>물건<?php }?>
                </span>
                </label>
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
            <input type="hidden" value="search" name="searchActive" id="searchActive" >
            <input type="hidden" value="" name="set_status" id="set_status" >
            <input type="hidden" value="<?php echo $priceFrom?>" id="sc_priceFrom" name="priceFrom">
            <input type="hidden" value="<?php echo $priceTo; ?>" id="sc_priceTo" name="priceTo">
            <input type="text" value="<?php echo $order_sort;?>" name="order_sort" id="order_sort">
            <input type="text" value="<?php echo $order_sort_active;?>" name="order_sort_active" id="order_sort_active">
            <input type="hidden" value="<?php echo $cate;?>" name="cate" id="cate">
            <input type="hidden" value="<?php echo $cate2;?>" name="cate2" id="cate2">
            <input type="text" value="<?php echo $pd_price_type;?>" name="pd_price_type" id="pd_price_type">
            <div class="sch_top">
                <input type="button" value="<?php if($cate && $cate2){echo $cate." > ".$cate2; }else{ ?>카테고리선택<?php }?>" class="sch_btn" onclick="fnwrite2();">
                <a href="javascript:fnsuggestion();">제안하기</a>
            </div>
            <div class="types sch_mid">
                <label class="radio_tag" for="four">
                    <input type="radio" name="type2" id="four" value="8" <?php if($type2=="8" || $type2 == ""){?>checked<?php  }?>>
                    <span class="slider2 round">팝니다</span>
                </label>
                <label class="radio_tag" for="eight">
                    <input type="radio" name="type2" id="eight" value="4" <?php if($type2=="4"){?>checked<?php } ?>>
                    <span class="slider2 round">삽니다</span>
                </label>
                <label class="radio_tag" for="mb_level">
                    <input type="checkbox" name="mb_level" id="mb_level" value="4" <?php if($mb_level=="4"){?>checked<?php } ?>>
                    <span class="slider2 round">전문가</span>
                </label>
            </div>
            <div class="sch_ord">
                <?php if(count($order_item)==0){?>
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
                    <label class="align" id="sortable" for="pd_hits">
                        <input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" checked>
                        <span class="round">인기순</span>
                    </label>
                    <label class="align" id="sortable" for="pd_loc">
                        <input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" checked>
                        <span class="round">거리순</span>
                    </label>
                <?php }else{
                    for($i=0;$i<count($order_item);$i++){
                        echo $order_item[$i];
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
            <div class="types sch_mid timesel" style="<?php if($set_type==2){?>display:block<?php }else{ ?>display:none;<?php }?>">
                <label class="radio_tag" for="workcnt">
                    <input type="radio" name="workcate" id="workcnt" value="0" <?php if($pd_price_type==0 || $pd_price_type==""){?>checked<?php }?>>
                    <span class="slider2 round">회당</span>
                </label>
                <label class="radio_tag" for="worktime">
                    <input type="radio" name="workcate" id="worktime" value="1" <?php if($pd_price_type==1){?>checked<?php }?>>
                    <span class="slider2 round">시간당</span>
                </label>
                <label class="radio_tag" for="workday">
                    <input type="radio" name="workcate" id="workday" value="2" <?php if($pd_price_type==2){?>checked<?php }?>>
                    <span class="slider2 round">하루당</span>
                </label>
            </div>
            <div class="sch_btn_group meettime" style="<?php if($set_type==2){?>display:block<?php }else{ ?>display:none;<?php }?>">
                <div>
                    <h2>거래가능시간</h2>
                </div>
                <div>
                    <select name="pd_timeFrom" id="pd_timeForm" class="write_input3" style="width:15vw">
                        <option value="">시간선택</option>
                        <?php for($i = 0; $i< 24; $i++){
                            $time = str_pad($i,"2","0",STR_PAD_LEFT);
                            ?>
                            <option value="<?php echo $time;?>" <?php if($pd_timeFrom==$time){?>selected<?php }?>><?php echo $time;?></option>
                        <?php }?>
                    </select> 시부터
                    ~
                    <select name="pd_timeTo" id="pd_timeTo" class="write_input3" style="width:15vw">
                        <option value="">시간선택</option>
                        <?php for($i = 0; $i< 24; $i++){
                            $time = str_pad($i,"2","0",STR_PAD_LEFT);
                            ?>
                            <option value="<?php echo $time;?>" <?php if($pd_timeTo==$time){?>selected<?php }?>><?php echo $time;?></option>
                        <?php }?>
                    </select> 시사이
                </div>
            </div>
			<div class="sch_btn_group">
				<!--<input type="button" value="제안하기" class="sch_btn btn_light" onclick="fnsuggestion();">	-->
				<input type="button" value="현검색저장" class="sch_btn" onclick="fnSaveSch()">
				<input type="button" value="검색" class="sch_btn" onclick="fnSetting2()">
				<input type="button" value="삽니다 간편등록" class="sch_btn sch_save_write" onClick="fnWrite();">
			</div>
            <div class="search_close" onclick="fnSetting2()">
                <img src="<?php echo G5_IMG_URL?>/search_close.png" alt="">
            </div>
            <div class="header_bg"></div>
            <?php if(count($list)==0){?>
                <div class="no-list" style="display:block">
                    <p>목록이 없습니다.</p>
                </div>
            <?php } ?>
	    </div>
    </form>
	<div class="mobile_menu">
		<span></span>
		<div class="menu">
			<div class="user_box">
                <div class="close">
                    <img src="<?php echo G5_IMG_URL?>/ic_close.png" alt="">
                </div>
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
				<div class="addr">로그인 상태가 아닙니다.</div>
				<?php }else{?>
				<h4><?php echo $member["mb_nick"];?></h4>
				<div class="addr"><?php echo ($member["mb_addr1"])?$member["mb_addr1"]:"저장된 주소가 없습니다.";?></div>
                <div class="alert" onclick="fnAlertView();">
                    <?php if($alarms['cnt'] > 0){?><div><?php echo $alarms['cnt'];?></div><?php }?>
                    <img src="<?php echo G5_IMG_URL?>/ic_alert.svg " alt="">
                </div>
				<?php }?>
			</div>
			<ul>
				<li class="menu1"><a href="<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_profile.svg" alt="">내프로필</a></li>
				<li class="menu2"><a href="<?php echo G5_MOBILE_URL?>/page/talk/talk.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_chat.svg" alt="">대화목록</a></li>
				<li class="menu2"><a href="<?php echo G5_MOBILE_URL?>/page/wish/wish.list.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_wish.svg" alt="">위시리스트</a></li>
				<li class="menu3"><a href="<?php echo G5_MOBILE_URL?>/page/mypage/cart.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_cart.svg" alt="">장바구니</a></li>
				<li class="menu4"><a href="<?php echo G5_MOBILE_URL?>/page/mypage/order_history.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_order.svg" alt="">거래내역</a></li>
				<li class="menu6"><a href="<?php echo G5_MOBILE_URL?>/page/trash/trash_list.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash.svg" alt="">휴지통</a></li>
                <li class="menu6"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=notice"><img src="<?php echo G5_IMG_URL?>/ic_menu_customer.svg" alt="">고객센터</a></li>
                <!--<li class="menu6"><a href="<?php /*echo G5_BBS_URL*/?>/page/company/company.php"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_company.svg" alt="">회사소개</a></li>-->
				<li class="menu7"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=help"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">도움말</a></li>
				<li class="menu8"><a href="<?php echo G5_MOBILE_URL?>/page/mypage/settings.php"><img src="<?php echo G5_IMG_URL?>/ic_menu_settings.svg" alt="">설정</a>
                    <div class="sugg"><a href="javascript:fnsuggestion();">제안하기</a></div>
                </li>

			</ul>
			<div class="copyright" style="">
				<h2>디자인율 | 48</h2>
                <p>대표 : 김용호</p><p>사업자등록번호 : 541-44-00091</p><p>대표전화 : 010-3034-1746</p>
                <ul class="agreement">
                    <li onclick="location.href=g5_url+'/mobile/page/company/agreement.php'">이용약관</li>
                    <li onclick="location.href=g5_url+'/mobile/page/company/privacy.php'">개인정보 취급방침</li>
                    <li onclick="location.href=g5_url+'/mobile/page/company/location.php'">위치정보 수집약관</li>
                </ul>
			</div>
		</div>
	</div>
	<div class="category_menu">
		<div class="cate_header">
			<img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" onclick="cateClose();">
			<h2>카테고리 설정</h2>
		</div>
		<div class="category catetype1">
			<ul>
                <!--<li class="cate000 active" id="scate000" ><a href="#"><img src="<?php /*echo G5_IMG_URL*/?>/ic_cate_all.svg" alt="">전체</a></li>-->
				<?php for($i=0;$i<count($category1);$i++){ ?>
				<li class="cate<?php echo $category1[$i]["ca_id"]; ?> <?php if($i==0){?>active<?php }?>" id="scate<?php echo $category1[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category1[$i]["cate_name"];?></a></li>
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
                <!--<li class="cate0000 active" id="scate0000" ><a href="#"><img src="<?php /*echo G5_IMG_URL*/?>/ic_cate_all.svg" alt="">전체</a></li>-->
				<?php for($i=0;$i<count($category2);$i++){ ?>
				<li class="cate<?php echo $category2[$i]["ca_id"]; ?> <?php if($i==0){?>active<?php }?>" id="scate<?php echo $category2[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category2[$i]["cate_name"];?></a></li>
				<?php } ?>
                <li class="sugg" onclick="fnsuggestion();"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">제안하기</li>
			</ul>
		</div>
		<?php include_once(G5_MOBILE_PATH."/subcategory2.php"); ?>
	</div>
    <div class="category_menu3">
        <div class="cate_header">
            <img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" onclick="cateClose();">
            <h2>카테고리 설정</h2>
        </div>
        <div class="category catetype1">
            <ul>
                <li class="cate00000 active" id="scate00000" ><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_cate_all.svg" alt="">전체</a></li>
                <?php for($i=0;$i<count($category1);$i++){ ?>
                    <li class="cate<?php echo $category1[$i]["ca_id"]; ?> " id="scate<?php echo $category1[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category1[$i]["cate_name"];?></a></li>
                <?php } ?>
                <li class="sugg" onclick="fnsuggestion();"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">제안하기</li>
            </ul>
        </div>
        <?php include_once(G5_MOBILE_PATH."/subcategory3.php"); ?>
    </div>
    <div class="category_menu4">
        <div class="cate_header">
            <img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" onclick="cateClose();">
            <h2>카테고리 설정</h2>
        </div>
        <div class="category catetype2">
            <ul>
                <li class="cate000000 active" id="scate000000" ><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_cate_all.svg" alt="">전체</a></li>
                <?php for($i=0;$i<count($category2);$i++){ ?>
                    <li class="cate<?php echo $category2[$i]["ca_id"]; ?> " id="scate<?php echo $category2[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category2[$i]["cate_name"];?></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php include_once(G5_MOBILE_PATH."/subcategory4.php"); ?>
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
					<span class="slider round" style="<?php if($_SESSION["list_type"]=="list"){?>background-image: url('<?php echo G5_IMG_URL?>/ic_switch_list.svg');<?php }?>"></span>
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
    var text = $("#stx").val();
    fnfilter(text,"stx");

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
    $(".mobile_menu .menu .close ").click(function(){
        $(".mobile_menu").fadeOut(300,function(){
            $(".mobile_menu").removeClass("active");
        });
        $("html").css("overflow","auto");
        $("body").css("overflow","unset");
    });

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
        $(this).attr("placeholder","");
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
            $("#set_type2").val(1);
            $(".timesel").css("display","none");
            $(".meettime").css("display","none");
			//카테고리 설정
			$.ajax({
				url:g5_url+"/mobile/page/ajax/ajax.category.php",
				method:"POST",
				data:{type1:"1"}
			}).done(function(data){
				$("#cate option").remove();
				$("#cate").append(data);
				$("#cate2 option").remove();
				$("#cate2").append("<option value=''>전체</option>");
			});
		}else{
			$(this).html("능력");
			$(this).css({"text-align":"left"});
			$("#set_type").val(2);
			$("#set_type2").val(2);
			$(".timesel").css("display","block");
			$(".meettime").css("display","block");
			//카테고리 설정
			$.ajax({
				url:g5_url+"/mobile/page/ajax/ajax.category.php",
				method:"POST",
				data:{type1:"2"}
			}).done(function(data){
				$("#cate option").remove();
				$("#cate").append(data);
				$("#cate2 option").remove();
				$("#cate2").append("<option value=''>전체</option>");
			});
		}
	});

	$("#cate").change(function(){
		var type1 = $(".schtype .slider").text();
		var ca_id = $("#cate option:selected").attr("id");
		var text = $("#cate option:checked").text();
        console.log("A");
		$.ajax({
			url:g5_url+"/mobile/page/ajax/ajax.category2.php",
			method:"POST",
			data:{type1:type1,ca_id:ca_id}
		}).done(function(data){
			$("#cate2 option").remove();
			$("#cate2").append(data);
			$("#cate").val(text);
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

    $(".category_menu3 .category2 ul li, .category_menu4 .category2 ul li").click(function(){
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
            $("#type").val(type);
            $("#cate").val(c);
            $("#cate2").val(sc);
            $(".sch_top .sch_btn").val(c+" > "+sc);
            cateClose();
        });
    });

	$(".radio_tag").click(function(){
		if($("#eight").prop("checked") == true){
		    $("#type2").val(8);
			$(".sch_save_write").css("display","none");
			if($("#set_type").val()==2) {
                $(".timesel, .meettime").css("display", "none");
            }
		}else{
            $("#type2").val(4);
			$(".sch_save_write").css("display","inline");
			if($("#set_type").val()==2) {
                $(".timesel, .meettime").css("display", "block");
            }
		}

		if($("#workcnt").prop("checked") == true){
		    $("#pd_price_type").val($("#workcnt").val());
        }
        if($("#worktime").prop("checked") == true){
            $("#pd_price_type").val($("#worktime").val());
        }
        if($("#workday").prop("checked") == true){
            $("#pd_price_type").val($("#workday").val());
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

$(document).on("change","#pd_timeForm",function(){
    var time = $(this).val();
    $("#pd_timeTo option").each(function(e){
        if(Number($(this).val()) < Number(time)){
            $(this).attr("disabled",true);
        }else{
            $(this).attr("disabled",false);
        }
        if(Number(time)+1 == e){
            console.log(e+1);
            $(this).attr("selected",true);
        }
    })
});

function fnSaveSch(){
    if($("#set_type").val()==""){
        alert("물건/능력을 선택해주세요");
        return false;
    }
    /*if($("#sch_text").val() == ""){
        alert("검색어를 입력해 주세요");
        return false;
    }*/
    $(".search_setting").attr("id","");
    $(".search_setting").css("top","-100vh");
    $("#id05").css("display","block");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
    location.hash="#modal";
}

function fnSearchAgree(status){
    $("#searchActive").val('save');
    $("#set_status").val(status);
	document.simplesearch.submit();
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
    document.simplesearch.submit();
}


function fnwrite2(){
    var type = $("#set_type").val();
    if(type == 1){
        //물건
        $(".category_menu3").fadeIn(300,function(){
            $(".category_menu3").addClass("active");
            location.hash='#category';
        });
    }else if(type == 2){
        //능력
        $(".category_menu4").fadeIn(300,function(){
            $(".category_menu4").addClass("active");
            location.hash='#category';
        });
    }else{
        alert("정상적인 방법으로 등록 바랍니다.");
        return false;
    }
}



function fnAlertView(){
    location.href=g5_url+'/mobile/page/mypage/alarm.php';
}
</script>