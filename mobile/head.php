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

if($is_member) {
    if ($regid) {
        $_SESSION["regid"] = $regid;
    }
    if ($sdkVersion) {
        $_SESSION["sdkVersion"] = $sdkVersion;
    }
    if($regid) {
        $sql = "update `g5_member` set regid = '{$regid}', sdkVersion = '{$sdkVersion}' where mb_id ='{$member[mb_id]}'";
        sql_query($sql);
    }
}

if($lat && $lng && $is_member){
    $sql = "update `g5_member` set mb_1 = '{$lat}', mb_2 = '{$lng}', mb_3 = now() where mb_id ='{$member[mb_id]}'";
    sql_query($sql);
}

$sql = "select * from `categorys` where `cate_type` = 1 and `cate_depth` = 1 and `cate_status` = 0 order by cate_order";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$category1[] = $row;
}

$sql = "select * from `categorys` where `cate_type` = 2 and `cate_depth` = 1  and `cate_status` = 0  order by cate_order";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $category2[] = $row;
}

//??
/*
if($schopt["sc_cate1"] && $schopt["sc_cate2"]){
    $sql = "select ca_id from `categorys` where `cate_name` = '{$schopt[sc_cate1]}' and `cate_depth` = 1 ";
    $ca_id = sql_fetch($sql);
    $sql = "select * from `categorys` where `cate_type` = '{$schopt[sc_type]}' and `cate_depth` = 2 and parent_ca_id = '{$ca_id[ca_id]}'";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $set_cate2[] = $row;
    }
}*/

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

//내 물건 카운트
$sql = "select count(*) as cnt from `product` where mb_id ='{$member["mb_id"]}' and pd_status != 10 and pd_type = 1";
$my_pro1 = sql_fetch($sql);

//내 능력 카운트
$sql = "select count(*) as cnt from `product` where mb_id ='{$member["mb_id"]}' and pd_status != 10 and pd_type = 2";
$my_pro2 = sql_fetch($sql);
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
					<input type="text" value="" name="cate_name2" id="cate_name2" placeholder="해당 '상세카테고리' 가 필요해요!" >
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
            <input type="hidden" name="pd_mb_id" id="pd_mb_id" value="">
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
<div id="id08" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <h2>연락하기</h2>
                <div class="contacts">
                    <ul>

                    </ul>
                </div>
                <div>
                    <input type="button" value="닫기" onclick="modalClose2()">
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
                        <option value="">내 판매게시물 선택</option>
                        
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
	<!--<div class="top_header <?php /*if($_SESSION["type1"]==2){*/?>bg2<?php /*}*/?>" onclick="location.href='<?php /*echo G5_URL*/?>';" <?php /*if($set_type==2){*/?>style="background-color: rgb(255, 61, 0);"<?php /*}*/?>>-->
	<div class="top_header <?php if($set_type==2 || $set_type == ""){?>bg2<?php }?>" onclick="location.href='<?php echo G5_URL?>';">
		<div class="owl-carousel" id="helps">
			<?php for($i=0;$i<count($help);$i++){?>
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
        <div class="trash-ani2">
            <img src="<?php echo G5_IMG_URL?>/ic_index_trash2.svg" alt="">
        </div>
        <!--<header id="mobile_header" <?php /*if($_SESSION["type1"]==2){*/?>class="bg2"<?php /*}*/?>>-->
        <header id="mobile_header" <?php if($set_type==2 || $set_type == ""){?>class="bg2"<?php }?> >
            <!-- <h1><a href="<?php echo G5_URL; ?>" title="HOME" class="logos"><i></i></a></h1> -->
            <div class="search">
                <input type="text" style="display:none;">
                <input type="hidden" name="formtype" id="formtype" value="">
                <input type="hidden" name="set_type" id="set_type" value="<?php if($set_type){echo $set_type;}else{echo 2;}?>">
                <input type="hidden" name="set_type2" id="set_type2" value="<?php if($type2){echo $type2;}?>">
                <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="fnSetting2();">
                <?php //print_r2($member);?>
                <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" placeholder="원하는 물건이 있으신가요?" onkeyup="fnKeyword();" />
                <label class="switch schtype" >
                    <input type="checkbox" id="type1" name="type1" value="1" <?php if($set_type=="2" || $set_type==""){?>checked<?php }?> >
                    <span class="slider round" <?php if($set_type=="2"  || $set_type==""){?>style="text-align:left"<?php }?>>
                    <?php if($set_type=="2"  || $set_type==""){?>능력<?php }else{ ?>물건<?php }?>
                </span>
                </label>
            </div>
            <a href="javascript:" id="mobile_menu_btn" class="mobile_menu_btn" title="MENU"><i></i></a>
            <a href="javascript:fnSetting();" id="mobile_setting_btn" title="SETTING"><i></i></a>
        </header>
        <div class="keyword">
            <div class="close" onclick="$('.keyword').removeClass('active');">
                <img src="<?php echo G5_IMG_URL;?>/view_close.svg" alt="">
            </div>
            <div class="search_re">
                <span>연관검색어 :</span>
            </div>
            <ul class="tab">
                <li class="popular_tab">인기 검색어</li>
                <li class="recent_tab">최근 검색어</li>
            </ul>
            <div class="search_popular">
                <ul>
                    <li>인기 검색어가 없습니다.</li>
                </ul>
            </div>
            <div class="search_recent">
                <ul>
                    <li>최근 검색어가 없습니다.</li>
                </ul>
            </div>
            <div class="bg">

            </div>
        </div>
        <div class="search_setting <?php if($set_type==2||$set_type==""){?>bg2<?php }?>">
            <input type="hidden" value="<?php echo $searchActive;?>" name="searchActive" id="searchActive" >
            <input type="hidden" value="" name="set_status" id="set_status" >
            <input type="hidden" value="<?php echo $priceFrom?>" id="sc_priceFrom" name="priceFrom">
            <input type="hidden" value="<?php echo $priceTo; ?>" id="sc_priceTo" name="priceTo">
            <input type="hidden" value="<?php echo ($order_sort)?$order_sort:"pd_loc,pd_price,pd_date,pd_recome,pd_hits";?>" name="order_sort" id="order_sort">
            <input type="hidden" value="<?php if($order_sort_active){$order_sort_active;}else if(!$order_sort_active){if(!$app && !$app2){echo "0,1,0,0,0";}else{echo "1,1,0,0,0";}};?>" name="order_sort_active" id="order_sort_active">
            <input type="hidden" value="<?php echo $cate;?>" name="cate" id="cate">
            <input type="hidden" value="<?php echo $cate2;?>" name="cate2" id="cate2">
            <input type="hidden" value="<?php echo $pd_price_type;?>" name="pd_price_type" id="pd_price_type">
            <div style="background-color:#f4f4f4 ">
                <div class="sch_top">
                    <input type="button" value="<?php if($cate && $cate2){echo $cate." > ".$cate2; }else{ ?>카테고리선택<?php }?>" class="sch_btn" onclick="fnwrite2();">
                    <a href="javascript:fnsuggestion('1');">제안하기</a>
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
                        <span class="slider2 round">전문업체</span>
                    </label>
                </div>
                <div class="sch_ord" id="sc_sorts">
                    <?php if(count($order_item)==0){?>
                        <label class="align first" id="sortable" for="pd_loc" >
                            <input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" <?php if($app || $app2){?>checked <?php } ?> >
                            <span class="round">거리순</span>
                        </label>
                        <label class="align" id="sortable" for="pd_price">
                            <input type="checkbox" name="orders[]" value="pd_price" id="pd_price" checked >
                            <span class="round">가격순</span>
                        </label>
                        <label class="align" id="sortable" for="pd_date" >
                            <input type="checkbox" name="orders[]" value="pd_date" id="pd_date" >
                            <span class="round">최신순</span>
                        </label>
                        <label class="align" id="sortable" for="pd_recom" >
                            <input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" >
                            <span class="round">추천순</span>
                        </label>
                        <label class="align last" id="sortable" for="pd_hits" >
                            <input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" >
                            <span class="round">인기순</span>
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
                        <input type="radio" name="pd_price_type" id="workcnt" value="0" <?php if($pd_price_type ==0 || $pd_price_type==""){?>checked<?php }?>>
                        <span class="slider2 round">회당</span>
                    </label>
                    <label class="radio_tag" for="worktime">
                        <input type="radio" name="pd_price_type" id="worktime" value="1" <?php if($pd_price_type ==1){?>checked<?php }?>>
                        <span class="slider2 round">시간당</span>
                    </label>
                    <label class="radio_tag" for="workday">
                        <input type="radio" name="pd_price_type" id="workday" value="2" <?php if($pd_price_type ==2){?>checked<?php }?>>
                        <span class="slider2 round">하루당</span>
                    </label>
                </div>
                <div class="sch_btn_group meettime" style="<?php if($set_type==2){?>display:block<?php }else{ ?>display:none;<?php }?>">
                    <div>
                        <h2>거래가능시간</h2>
                    </div>
                    <div class="pd_times">
                        <select name="pd_timeFrom" id="pd_timeFrom" class="write_input3" style="width:17vw">
                            <option value="">시간선택</option>
                            <?php for($i = 1; $i< 25; $i++){
                                $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                ?>
                                <option value="<?php echo $time;?>" <?php if($pd_timeFrom == $time){?>selected<?php }?>><?php echo $time;?></option>
                            <?php }?>
                        </select> 시부터
                        ~
                        <input type="checkbox" name="pd_timeType" id="pd_timetype" value="1" <?php if($pd_timeType==1){?>checked<?php }?> style="display: none"><label for="pd_timetype"><img src="<?php echo G5_IMG_URL?>/ic_write_check.svg" alt=""> 익일</label>
                        <select name="pd_timeTo" id="pd_timeTo" class="write_input3" style="width:17vw;margin-left:1vw;">
                            <option value="">시간선택</option>
                            <?php for($i = 1; $i< 25; $i++){
                                $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                ?>
                                <option value="<?php echo $time;?>" <?php if($pd_timeTo == $time){?>selected<?php }?>><?php echo $time;?></option>
                            <?php }?>
                        </select> 시사이
                    </div>
                </div>
                <div class="sch_btn_group">
                    <!--<input type="button" value="제안하기" class="sch_btn btn_light" onclick="fnsuggestion();">	-->
                    <input type="button" value="현 검색저장" class="sch_btn" onclick="fnSaveSch()">
                    <input type="button" value="검색" class="sch_btn point" onclick="fnSetting2()">
                    <input type="button" value="삽니다 간편등록" class="sch_btn sch_save_write" onClick="fnWrite();">
                </div>
                <div class="search_close" onclick="fnSetting2()">
                    <img src="<?php echo G5_IMG_URL?>/search_close.svg" alt="">
                </div>
            </div>
            <div class="header_bg"></div>
            <?php if(count($list)==0){?>
                <div class="no-list" style="display:block">
                    <p>목록이 없습니다.</p>
                </div>
            <?php } ?>
	    </div>
    </form>
	<div class="mobile_menu" id="mobile_menu">
		<span></span>
		<div class="menu">
			<!--<div class="user_box <?php /*if($_SESSION["type1"]==2){*/?>bg2<?php /*}*/?>">-->
            <div class="user_box <?php if($set_type==2 || $set_type==""){?>bg2<?php }?>">
                <div class="close">
                    <img src="<?php echo G5_IMG_URL?>/ic_close.png" alt="">
                </div>
				<?php /*if(!$member["mb_id"]){*/?><!--
				<p><a href="<?php /*echo G5_URL*/?>/mobile/page/login_intro.php">로그인</a></p>
				<?php /*}else{*/?>
				<p><a href="<?php /*echo G5_BBS_URL*/?>/logout.php?url=../index.php?device=mobile">로그아웃</a></p>
				--><?php /*} */?>
				<div class="profiles" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php'">
					<?php if($member["mb_id"] && $member["mb_profile"]){?>
					<img src="<?php echo $member["mb_profile"];?>" alt="" class="user_profile">
					<?php }else{?>
					<img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
					<?php }?>
				</div>
				<?php if(!$member["mb_id"]){?>
				<h4><a href="<?php echo G5_URL?>/mobile/page/login_intro.php">로그인</a></h4>
				<?php }else{?>
				<h4 onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php'"><?php echo $member["mb_nick"];?></h4>
                <div class="mylist" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php'">
                    <div>내 물건 <span><?php echo number_format($my_pro1["cnt"]);?></span></div>
                    <div>내 능력 <span><?php echo number_format($my_pro2["cnt"]);?></span></div>
                </div>
				<?php }?>
                <div class="profile_menus">
                    <div class="search_list" onclick="fnRecent()">
                        <img src="<?php echo G5_IMG_URL;?>/ic_profile_search.svg" alt="">
                    </div>
                    <div class="wished" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/wish/wish.list.php'">
                        <img src="<?php echo G5_IMG_URL;?>/ic_profile_wished.svg" alt="">
                    </div>
                    <div class="alert" onclick="fnAlertView();">
                        <?php if($alarms['cnt'] > 0){?><div><?php echo $alarms['cnt'];?></div><?php }?>
                        <img src="<?php echo G5_IMG_URL?>/ic_profile_alarm.svg " alt="">
                    </div>
                </div>
                <div class="clear"></div>
			</div>
			<ul class="menu">
				<li class="menu1" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_profile.svg" alt="">내프로필</a></li>
				<li class="menu2" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/talk/talk.php'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_chat.svg" alt="">대화목록</a></li>
				<!--<li class="menu2"><a href="<?php /*echo G5_MOBILE_URL*/?>/page/wish/wish.list.php"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_wish.svg" alt="">위시리스트</a></li>-->
                <li class="menu3" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage.php?type=2'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_cart.svg" alt="">거래상태보기</a></li>
				<li class="menu4" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/order_history.php'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_order.svg" alt="">거래내역</a></li>
				<li class="menu6" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/trash/trash_list.php'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash.svg" alt="">휴지통</a></li>
                <li class="menu6" onclick="location.href='<?php echo G5_BBS_URL?>/board.php?bo_table=notice'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_customer.svg" alt="">고객센터</a></li>
                <!--<li class="menu6"><a href="<?php /*echo G5_BBS_URL*/?>/page/company/company.php"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_company.svg" alt="">회사소개</a></li>-->
				<li class="menu7" onclick="location.href='<?php echo G5_BBS_URL?>/board.php?bo_table=help'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">도움말</a></li>
				<li class="menu8" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/settings.php'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_settings.svg" alt="">설정</a>
                </li>

			</ul>
            <div class="sugg"><a href="javascript:fnsuggestion('1');">제안하기</a></div>
            <div class="clear"></div>
			<!--<div class="copyright <?php /*if($_SESSION["type1"]==2){*/?>bg2<?php /*}*/?>" style="">-->
			<div class="copyright" style="">
				<h2>디자인율 | 48</h2>
                <p>대표 : 김용호</p><p>사업자등록번호 : 541-44-00091</p><p>통신판매신고번호 : 제 2018-충북청주-1575 호</p><p>대표전화 : 070-4090-4811</p>
                <p style="padding-bottom:4vw">소재지 : 충청북도 청주시 흥덕구 <br>풍산로133번길 48, 304호(복대동)</p>
                <ul class="agreement" >
                    <li onclick="location.href=g5_url+'/mobile/page/company/agreement.php'">이용약관</li>
                    <li onclick="location.href=g5_url+'/mobile/page/company/privacy.php'">개인정보 취급방침</li>
                    <li onclick="location.href=g5_url+'/mobile/page/company/location.php'">위치정보 수집약관</li>
                    <li onclick="location.href=g5_url+'/mobile/page/company/refund.php'">환불 약관</li>
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
				<li class="cate<?php echo $category1[$i]["ca_id"]; ?> <?php if($i==0){?>active<?php }?>" id="scate<?php echo $category1[$i]["ca_id"]; ?>">
                    <a href="#"><?php if($category1[$i]["icon"]){?><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php }?><?php echo $category1[$i]["cate_name"];?></a>
                </li>
				<?php } ?>
                <li class="sugg" onclick="fnsuggestion('');"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">제안하기</li>
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
				<li class="cate<?php echo $category2[$i]["ca_id"]; ?> <?php if($i==0){?>active<?php }?>" id="scate<?php echo $category2[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category2[$i][icon]; ?>" alt=""><?php echo $category2[$i]["cate_name"];?></a></li>
				<?php } ?>
                <li class="sugg" onclick="fnsuggestion('');"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">제안하기</li>
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
                <li class="sugg" onclick="fnsuggestion('');"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">제안하기</li>
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
                    <li class="cate<?php echo $category2[$i]["ca_id"]; ?> " id="scate<?php echo $category2[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category2[$i][icon]; ?>" alt=""><?php echo $category2[$i]["cate_name"];?></a></li>
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
				<!--<label class="switch align" for="paplur">
					<input type="checkbox" name="paplur" id="paplur" value="1" <?php /*if($_SESSION["list_basic_order"]=="location"){*/?>checked<?php /*}*/?>>
					<span class="slider round" style="<?php /*if($_SESSION["list_basic_order"]=="location"){*/?>text-align: left;<?php /*}*/?>"><?php /*if($_SESSION["list_basic_order"]=="hits"){*/?>최신<?php /*}else{*/?>거리<?php /*}*/?></span>
				</label>-->
				<label class="switch list <?php if($_SESSION["list_type"]=="list"){?><?php }?>" for="list_type">
					<input type="checkbox" name="list_type" id="list_type" <?php if($_SESSION["list_type"]=="list"){?>checked<?php }?>>
					<span class="slider slider2 round" style="<?php if($_SESSION["list_type"]=="list"){?>background-image: url('<?php echo G5_IMG_URL?>/ic_switch_list.svg');<?php }else{?>background-position:calc(100% - 1vw);<?php }?>"></span>
				</label>
			</div>
		</div>
		<div class="sort_bg"></div>
		<div class="clear"></div>
	</div>
</div>
<script>
var slider = null;
var fnc = true;
var max = 0;
var chksearch = null;
function fnKeyword(){
    var text = $("#stx").val();
    fnfilter(text,"stx");
    $("#searchActive").val("search");
    var search_id = $(".search_setting").attr("id");
    if(search_id != "menuon"){
        if(chksearch!=null){
            clearTimeout(chksearch);
        }
        chksearch = setTimeout(function(){
            if(text.length >= 1) {
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.get_search_list.php",
                    method:"post",
                    data:{text:text},
                    dataType:"json"
                }).done(function(data){
                    chksearch = true;
                    var popular = '';
                    var recent = '';
                    var searchs = '';
                    if(data.searchs != null){
                        if(data.searchs.length > 0){
                            for(var i = 0 ; i < data.searchs.length; i++){
                                searchs += '<span>'+data.searchs[i].pd_tag+'</span>';
                            }
                        }
                        $(".search_re").html("<span>연관검색어 : </span>");
                        $(".search_re append").append(popular);
                    }else{
                        $(".search_re").html("<span>연관검색어 : </span>");
                        $(".search_re").append('연관 검색어가 없습니다.');
                    }

                    if(data.popular != null){
                        if(data.popular.length > 0){
                            for(var i = 0 ; i < data.popular.length; i++){
                               popular += '<li>'+(i+1)+'<span>'+data.popular[i].pp_word+'</span></li>';
                            }
                        }
                        $(".search_popular ul").html(popular);
                    }else{
                        $(".search_popular ul").html('<li class="no-list">인기 검색어가 없습니다.</li>');
                    }
                    if(data.recent != null) {
                        if (data.recent.length > 0) {
                            for (var i = 0; i < data.recent.length; i++) {
                                recent += '<li>' + data.recent[i].pp_word + '</li>';
                            }
                        }
                        $(".search_recent ul").html(recent);
                    }else{
                        $(".search_recent ul").html('<li class="no-list">최근 검색어가 없습니다.</li>');
                    }
                    $(".tab .popular_tab").addClass("active");
                    $(".search_popular").addClass("active");
                    location.hash = "#search"
                    $(".keyword").addClass("active");
                });
            }else{
                $(".keyword").removeClass("active");
            }
        },600);
    }else{
        $(".keyword").removeClass("active");
    }
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
            fnSetting2();
            return false;
        } else {
            //바로 간단검색
            document.simplesearch.submit();
        }
    }
}

function fnSimpleSearch(){
    $("#searchActive").val("search");
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

    var hmenus = document.getElementById("mobile_menu");
    var hammer_menu = new Hammer(hmenus);

    hammer_menu.on("swipeleft",function(e){
        $(".mobile_menu").fadeOut(300,function(){
            $(".mobile_menu").removeClass("active");
        });
        $("html").css("overflow","auto");
        $("body").css("overflow","unset");
    });

    $(".tab li").click(function(){
        if(!$(this).hasClass("active")){
            $(this).addClass("active");
            $(".tab li").not($(this)).removeClass("active");
            if($(this).text()=="인기 검색어"){
                $(".search_popular").addClass("active");
                $(".search_recent").removeClass("active");
            }else{
                $(".search_popular").removeClass("active");
                $(".search_recent").addClass("active");
            }
        }
    });
    $(document).on("click",".search_popular li , .search_recent li",function(){
        if(!$(this).hasClass("no-list")){
            var stx = $(this).children().text();
            $("#stx").val(stx);
            document.simplesearch.submit();
        }
    });

    $(".mobile_menu .menu .close ").click(function(){
        $(".mobile_menu").fadeOut(300,function(){
            $(".mobile_menu").removeClass("active");
        });
        $("html").css("overflow","auto");
        $("body").css("overflow","unset");
    });

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
        <?php if($app){?>
        var regid = window.android.getRegid();
        var sdkVersion = window.android.getSdkVersion();
        <?php }else if($app2 && $regid){ ?>
        var regid = "<?php echo $regid;?>";
        <?php } ?>
        if(regid != "") {
            $.ajax({
                url: g5_url + "/mobile/page/ajax/ajax.regid.update.php",
                method: "post",
                data: {regid: regid, mb_id: "<?php echo $member["mb_id"];?>", sdkVersion: sdkVersion}
            }).done(function (data) {
                console.log(data);
            })
        }
    }catch(err){
        var regId = undefined;
        console.log(err);
    }


	$(".schtype .slider").click(function(){
        var menuon = $(".search_setting").attr("id");
        if(menuon=="menuon"){
            $("#searchActive").val("simple");
        }else{
            $("#searchActive").val("search");
        }
		if($(this).prev().prop("checked") == true){
            $(".top_header").removeClass("bg2");
            $("#mobile_header").removeClass("bg2");
            $(".user_box").removeClass("bg2");
            $(".wished").removeClass("bg2");
            $(".search_setting").removeClass("bg2");
            $(".copyright").removeClass("bg2");
            $(".ft_menu_04 img").attr("src","<?php echo G5_IMG_URL;?>/bottom_icon_03.svg");
			$(this).html("물건");
			$(this).css({"text-align":"right"});


            $("#set_type").val(1);
            //$("#set_type2").val(1);
            $("#wr_type1").val(1);

            $(".timesel").css("display","none");
            $(".meettime").css("display","none");

            $(".top_header").css("background-color","#000");
            $("#search").attr("placeholder","원하는 물건이 있으세요?");
            //$(".text").css({"background-color":"#ffe400","color":"#000"});
            $(".text").addClass("bg1");
            $(".text").removeClass("bg2");
            $(".text img").attr("src","<?php echo G5_IMG_URL?>/write_text_1.svg");
            $(".write_btn img").attr("src","<?php echo G5_IMG_URL;?>/ic_write_btn.svg");
            $("#theme-color").attr("content","#000000");
            $("#wr_price").attr("placeholder","판매금액");
            $("#wr_price").css("width","70%");
            $("#wr_price2").css("display","none");
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

            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"type1",value:"1"}
            }).done(function(data){
                console.log(data);
            });
            fnlist(1,'');
		}else{
            $(".top_header").addClass("bg2");
            $("#mobile_header").addClass("bg2");
            $(".wished").addClass("bg2");
            $(".user_box").addClass("bg2");
            $(".search_setting").addClass("bg2");
            $(".copyright").addClass("bg2");
            $(".ft_menu_04 img").attr("src","<?php echo G5_IMG_URL;?>/bottom_icon_03_2.svg");
			$(this).html("능력");
			$(this).css({"text-align":"left"});
			$("#set_type").val(2);
			//$("#set_type2").val(2);
            $("#wr_type1").val(2);
            console.log($("#set_type2").val());
            if($("#set_type2").val() == 8 || $("#set_type2").val() == "") {
                $(".timesel").css("display", "block");
                $(".meettime").css("display", "block");
            }
            $(".top_header").css("background-color","#ff3d00");
            $("#search").attr("placeholder","누군가의 능력이 필요하세요?");
            //$(".text").css({"background-color":"#ff3d00","color":"#fff"});
            $(".text").addClass("bg2");
            $(".text").removeClass("bg1");
            $(".text img").attr("src","<?php echo G5_IMG_URL?>/write_text_2.svg");
            $(".write_btn img").attr("src","<?php echo G5_IMG_URL;?>/ic_write_btn_2.svg");
            $("#theme-color").attr("content","#000000");
            $("#wr_price").attr("placeholder","판매금액");
            $("#wr_price").css("width","30%");
            $("#wr_price2").css("display","none");
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

            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"type1",value:"2"}
            }).done(function(data){
                console.log(data);
            });
            fnlist(1,'');
		}
        priceSet();
	});
/*
	$("#cate").change(function(){
	    console.log("ADA");
		var type1 = $(".schtype .slider").text();
		var ca_id = $("#cate option:selected").attr("id");
		var text = $("#cate option:checked").text();
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
	    console.log("ADAVA");
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
*/
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
            if(c != "전체" && sc != "전체") {
                $("#cate").val(c);
                $("#cate2").val(sc);
                $(".sch_top .sch_btn").val(c + " > " + sc);
            }else{
                $("#cate").val('');
                $("#cate2").val('');
                $(".sch_top .sch_btn").val('카테고리선택');
            }
            cateClose();
            priceSet();
        });
    });

	$(".radio_tag").click(function(){
		if($("#eight").prop("checked") == true){
		    $("#set_type2").val(4);
			$(".sch_save_write").css("display","none");
			if($("#set_type").val()==2) {
                $(".timesel, .meettime").css("display", "none");
            }
		}else{
            $("#set_type2").val(8);
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

    max = priceSet();

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
	//$("#priceTo").val($( "#slider-range" ).slider( "values", 0 ));
	//$("#priceFrom").val($( "#slider-range" ).slider( "values", 1 ));
	//$( "#schp" ).text(number_format($( "#slider-range" ).slider( "values", 0 )) + " ~ " + number_format($( "#slider-range" ).slider( "values", 1 )));
    <?php }else{?>
    //$( "#schp" ).text(number_format($("#sc_priceFrom").val()) + " ~ " +number_format($("#sc_priceTo").val()));
    <?php }?>

    //드래그 소트
    //$(".sorter").amigoSorter();

	var change = false;

    var moveleft = false;
    var moveright = false;
    var leftcnt = 0;
    var rightcnt = 1;
    var itemclone = null;

    var startLeft = 0;
    var leftstart = 0;
    var startRight = 0;
    var rightstart = 0;
    var lefts = new Array();
    var rights = new Array();
    var order_sort_active = "";
    var order_sort = "";

    $(".align").each(function(e){
        var item = $(this);
        var order = this;
        var orderhammer = new Hammer(order);
        var width = $(this).width();
        item.css("left",(width*e)+"px");

        orderhammer.get('pan').set({direction:Hammer.DIRECTION_HORIZONTAL});

        orderhammer.on("pan",function(ev){
           console.log(ev);
        });

        orderhammer.on("panleft panright panup pandown", function(ev){
            console.log(ev.type);
            if(ev.type == "panleft" && moveright!=true && ev.type != "panup" && ev.type != "pandown"){
                if(change ==false) {
                    var text = ev.target.innerText;
                    $(".align").each(function(ee){
                        if($(this).context.innerText == text){
                            leftstart = ee;
                            startLeft = Number($(this).css("left").replace("px",""));
                            item = $(this);
                        }
                        lefts[ee] = Number($(this).css("left").replace("px",""));
                    });
                    itemclone = item.clone();
                    itemclone.css("opacity",".5");
                    itemclone.insertBefore(item);
                    change=true;
                }
                var x = Math.abs(ev.deltaX);
                var left = startLeft - x;
                item.css({"position": "absolute"});
                if(left > 0) {
                    item.css({"position": "absolute", "left": left + "px","z-index":"999"});
                }
                if(lefts[leftstart-leftcnt] > left) {
                    leftcnt++;
                    var i = item.prev().prev();
                    itemclone.insertBefore(i);
                    i.insertAfter(item);
                }

                moveleft = true;
            }
            if(ev.type == "panright" && moveleft!=true && ev.type != "panup" && ev.type != "pandown"){
                if(change == false) {
                    var text = ev.target.innerText;
                    $(".align").each(function(ee){
                        if($(this).context.innerText == text){
                            rightstart = ee;
                            startRight = Number($(this).css("left").replace("px",""));
                            item = $(this);
                        }
                        rights[ee] = Number($(this).css("left").replace("px",""));
                    });
                    itemclone = item.clone();
                    itemclone.css("opacity",".5");
                    itemclone.insertBefore(item);
                    change=true;
                }
                var x = Math.abs(ev.deltaX);
                var right = startRight + x;
                item.css({"position": "absolute"});
                if(right <= rights[4] + 20) {
                    item.css({"position": "absolute", "left": right + "px","z-index":"999"});
                }
                if(rights[rightstart+rightcnt] <= right && rightstart < 4) {
                    rightcnt++;
                    var i = item.next();
                    console.log(i);
                    itemclone.insertBefore(i);
                    i.insertBefore(item);
                }

                moveright = true;
            }
        });

        orderhammer.on("panend pancancel",function(ev){
            order_sort_active = "";
            order_sort = "";
            //이동확인
            itemclone.remove();
            $(".align").css("position","");
            $(".align").each(function(e){
                var width = $(this).width();
                $(this).css("left",(width*e)+"px");
                $(this).eq(e);
                if($(this).find("input").prop("checked")==true){
                    if(order_sort_active == ""){
                        order_sort_active = "1";
                    }else{
                        order_sort_active = order_sort_active + ",1";
                    }
                }else{
                    console.log(order_sort_active);
                    if(order_sort_active == ""){
                        order_sort_active = "0";
                    }else{
                        order_sort_active = order_sort_active + ",0";
                    }
                }
                if(order_sort=="") {
                    order_sort = $(this).find("input").attr("id");
                }else{
                    order_sort = order_sort + ","+$(this).find("input").attr("id");
                }

                $("#order_sort_active").val(order_sort_active);
                $("#order_sort").val(order_sort);

            });
            lefts = new Array();
            rights = new Array();
            change=false;
            // 햇지만 차이가 없을때 원상 복구
            if(moveleft == true){
                leftcnt = 0;
                moveleft = false;
                $(".align").each(function(){

                });
            }else{
                rightcnt = 0;
                moveright = false;
            }
        });
    });

    $(".align").click(function(){
        var chk = $(".align").length;
        var chkchk = $(".align input:checked").length;
        if(chkchk==0){
            alert("하나 이상은 선택해야 합니다.");
            $(this).prop("checked",true);
            return false;
        }
        order_sort_active = "";
        order_sort = "";
        $(".align").each(function(e){
            if($(this).find("input").prop("checked")==true){
                if(order_sort_active == ""){
                    order_sort_active = "1";
                }else{
                    order_sort_active = order_sort_active + ",1";
                }
            }else{
                console.log(order_sort_active);
                if(order_sort_active == ""){
                    order_sort_active = "0";
                }else{
                    order_sort_active = order_sort_active + ",0";
                }
            }
            if(order_sort=="") {
                order_sort = $(this).find("input").attr("id");
            }else{
                order_sort = order_sort + ","+$(this).find("input").attr("id");
            }

            $("#order_sort_active").val(order_sort_active);
            $("#order_sort").val(order_sort);

        });
    });

    $(".sch_ord").mouseout(function(){
        //이동확인
        if(itemclone != null){
            itemclone.remove();
        }

        $(".align").css("position","");
        $(".align").each(function(e){
            var width = $(this).width();
            $(this).css("left",(width*e)+"px");
            $(this).eq(e);
        });
        lefts = new Array();
        rights = new Array();
        change=false;
        // 햇지만 차이가 없을때 원상 복구
        if(moveleft == true){
            leftcnt = 0;
            moveleft = false;
        }else{
            rightcnt = 0;
            moveright = false;
        }
    });

    /*$(document).on("click",".align",function(){
        console.log("A");
        var id = $(this).prev().attr("id");
        var chk = false;
        $("input[name^=orders]").each(function(e){
            if($(this).prop("checked") == true){
                chk = true;
            }
        });
        //alert("Afeaea");
        var active = '';
        if(chk==true) {
            $("input[name^=orders]").each(function (e) {

                if (e == 0) {
                    $("#order_sort").val($(this).val());
                } else {
                    var align = $("#order_sort").val();
                    var data = align + "," + $(this).val();
                    $("#order_sort").val(data);
                }

                if ($(this).is(":checked") == true) {
                    if (active == '') {
                        active = "1";
                    } else {
                        active = active + ",1";
                    }

                } else {
                    if (active == '') {
                        active = "0";
                    } else {
                        active = active + ",0";
                    }
                }
                $("#order_sort_active").val(active);
            });
        }else{
            alert("하나 이상의 조건이 필요합니다.");
            $(id).attr("checked",true);
            return false;
        }
    });*/

    /*$(".align").bind("tab",function(){
        var id = $(this).prev().attr("id");
        var chk = false;
        $("input[name^=orders]").each(function(e){
            if($(this).prop("checked") == true){
                chk = true;
            }
        });
        //alert("Afeaea");
        var active = '';
        if(chk==true) {
            $("input[name^=orders]").each(function (e) {

                if (e == 0) {
                    $("#order_sort").val($(this).val());
                } else {
                    var align = $("#order_sort").val();
                    var data = align + "," + $(this).val();
                    $("#order_sort").val(data);
                }

                if ($(this).is(":checked") == true) {
                    if (active == '') {
                        active = "1";
                    } else {
                        active = active + ",1";
                    }

                } else {
                    if (active == '') {
                        active = "0";
                    } else {
                        active = active + ",0";
                    }
                }
                $("#order_sort_active").val(active);
            });
        }else{
            alert("하나 이상의 조건이 필요합니다.");
            $(id).attr("checked",true);
            return false;
        }
    });

    $(".sch_ord").sortable({
        axis:"x",
        forcePlaceholderSize:false,
        //delay:100,
        start:function(event,ui){
            var id = ui.item.context.firstElementChild;
        }
        ,
        stop:function(event,ui){
            /*var chk = false;
             $("input[name^=orders]").each(function(e){
             if($(this).prop("checked") == true){
             chk = true;
             }
             });
             var id = ui.item.context.firstElementChild;
             var active = '';

             if(chk==true) {
             $("input[name^=orders]").each(function (e) {
             if (e == 0) {
             $("#order_sort").val($(this).val());
             } else {
             var align = $("#order_sort").val();
             var data = align + "," + $(this).val();
             $("#order_sort").val(data);
             }

             if ($(this).is(":checked") == true) {
             if (active == '') {
             active = "1";
             } else {
             active = active + ",1";
             }

             } else {
             if (active == '') {
             active = "0";
             } else {
             active = active + ",0";
             }
             }
             $("#order_sort_active").val(active);
             });
             }else{
             alert("하나 이상의 조건이 필요합니다.");
             $(id).attr("checked",true);
             return false;
             }
        },
        change:function(event,ui){
            change = true;
            var id = ui.item.context.firstElementChild;
            console.log("변경"+$(id).is(":checked"));
        }

    }).disableSelection();
*/
});

function priceSet(){
    var cate1 = $("#cate").val();
    var cate2 = $("#cate2").val();
    var pd_type = $("#wr_type1").val();
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.category_minmax.php",
        method:"POST",
        dataType:"json",
        data:{cate2:cate2,cate1:cate1,pd_type:pd_type}
    }).done(function(data){
        console.log(data);
        if(data.max != null) {
            if(data.max==0){
                $("#priceFrom").val(50000);
                $("#schp").text('<?php echo number_format(1000);?>' + " ~ " + '<?php echo number_format(50000);?>');
                slider = $( "#slider-range" ).slider({
                    range: true,
                    min: 1000,
                    max: 50000,
                    values: [1000 , 50000 ],
                    step:1000,
                    slide: function( event, ui ) {
                        $("#sc_priceTo").val(ui.values[1]);
                        $("#sc_priceFrom").val(ui.values[0]);
                        $("#schp").text( number_format(ui.values[0])+" ~ "+number_format(ui.values[1]));
                    }
                });
            }else {
                $("#priceFrom").val(data.max);
                <?php if($priceFrom && $priceTo) { ?>
                $("#schp").text('<?php echo number_format($priceFrom);?>' + " ~ " + '<?php echo number_format($priceTo);?>');
                slider = $( "#slider-range" ).slider({
                    range: true,
                    min: 1000,
                    max: data.max,
                    values: [<?php echo $priceFrom;?> , <?php echo $priceTo;?> ],
                    step:1000,
                    slide: function( event, ui ) {
                        $("#sc_priceTo").val(ui.values[1]);
                        $("#sc_priceFrom").val(ui.values[0]);
                        $("#schp").text( number_format(ui.values[0])+" ~ "+number_format(ui.values[1]));
                    }
                });
                <?php }else{ ?>
                $("#schp").text(number_format(1000) + " ~ " + number_format(data.max));

                slider = $( "#slider-range" ).slider({
                    range: true,
                    min: 1000,
                    max: data.max,
                    values: [1000 , data.max ],
                    step:1000,
                    slide: function( event, ui ) {
                        $("#sc_priceTo").val(ui.values[1]);
                        $("#sc_priceFrom").val(ui.values[0]);
                        $("#schp").text( number_format(ui.values[0])+" ~ "+number_format(ui.values[1]));
                    }
                });
                <?php }?>
            }
        }
    });
}

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
    $("#searchActive").val("save");
    if($("#set_type").val()==""){
        alert("물건/능력을 선택해주세요");
        return false;
    }
    if($("#stx").val() == ""){
        alert("검색어를 입력해 주세요");
        return false;
    }

    $(".search_setting").attr("id","");
    $(".search_setting").css("top","-100vh");
    $("#id05").css("display","block");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
    location.hash="#modal";
}

function fnSearchAgree(status){
    //$("#searchActive").val('save');
    $("#set_status").val(status);
	document.simplesearch.submit();
}

function fnWrite(){
    $("#formtype").val("write");
    if($("#wr_type").val()==""){
        alert("물건/능력을 선택해주세요");
        return false;
    }
    if($("#stx").val() == ""){
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

    document.simplesearch.action = g5_url+"/mobile/page/savesearch/search_simple_update.php";
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