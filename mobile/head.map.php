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
    if($_SESSION["stx"]){
        $stx = $_SESSION["stx"];
    }
    if ($regid) {
        $_SESSION["regid"] = $regid;
    }
    if ($sdkVersion) {
        $_SESSION["sdkVersion"] = $sdkVersion;
    }

    if ($device) {
        $_SESSION["device"] = $device;
    }
    if ($mac) {
        $_SESSION["mac"] = $mac;
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

include_once (G5_PATH."/mobile/head.popup.php");
/*
if($sc_id){
    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);
}

if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

if($stx){
    //검색어 업데이트
    $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip) values ('{$stx}', now(), '".$_SERVER["REMOTE_ADDR"]."')";
    sql_query($sql);
}

if($lat && $lng && $is_member){
    $sql = "update `g5_member` set mb_1 = '{$lat}', mb_2 = '{$lng}', mb_3 = now() where mb_id ='{$member[mb_id]}'";
    sql_query($sql);
}

$sql = "select * from `categorys` where `cate_type` = 1 and `cate_depth` = 1  and `cate_status` = 0 order by cate_order";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $category1[] = $row;
}

$sql = "select * from `categorys` where `cate_type` = 2 and `cate_depth` = 1  and `cate_status` = 0  order by cate_order";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $category2[] = $row;
}

$sql = "select * from `g5_write_help` where wr_is_comment = 0;";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $help[] = $row;
}

//내 물건 카운트
$sql = "select count(*) as cnt from `product` where mb_id ='{$member["mb_id"]}' and pd_status != 10 and pd_type = 1";
$my_pro1 = sql_fetch($sql);

//내 능력 카운트
$sql = "select count(*) as cnt from `product` where mb_id ='{$member["mb_id"]}' and pd_status != 10 and pd_type = 2";
$my_pro2 = sql_fetch($sql);
include_once (G5_MOBILE_PATH."/head.popup.php");*/
?>
<!-- 모바일 헤더 시작 -->
<div id="head">
    <div class="top_header <?php if($set_type==2){?>bg2<?php }?>" onclick="location.href='<?php echo G5_URL?>';">
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
        <header id="mobile_header" <?php if($set_type==2){?>class="bg2"<?php }?>>
            <!-- <h1><a href="<?php echo G5_URL; ?>" title="HOME" class="logos"><i></i></a></h1> -->
            <div class="search">
                <input type="text" style="display:none;">
                <input type="hidden" name="formtype" id="formtype" value="">
                <input type="hidden" name="set_type" id="set_type" value="<?php if($set_type==1 && $set_type!=""){echo 1;}else{echo 2;}?>">
                <input type="hidden" name="set_type2" id="set_type2" value="<?php if($type2){echo $type2;}?>">
                <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="fnSetting2();">
                <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" placeholder="<?php if($set_type==1){?>원하는 물건이 있으세요?<?php }else{?>누군가의 도움이 필요한가요?<?php }?>" onkeyup="fnKeyword();" onfocus="fnKeywordShow()" />
                <label class="switch schtype" >
                    <input type="checkbox" id="type1" name="type1" value="1" <?php if($set_type=="2" || $set_type==""){?>checked<?php }?> >
                    <span class="slider round" <?php if($set_type=="2"){?>style="text-align:left"<?php }?>>
                        <?php if($set_type=="2"){?>능력<?php }else{ ?>물건<?php }?>
                    </span>
                </label>
            </div>
            <a href="javascript:" id="mobile_menu_btn" class="mobile_menu_btn" title="MENU"><i></i></a>
            <a href="javascript:fnSetting();" id="mobile_setting_btn" title="SETTING"><i></i></a>
        </header>
        <div class="search_setting <?php if($set_type==2){?>bg2<?php }?>">
            <input type="hidden" value="<?php echo $searchActive;?>" name="searchActive" id="searchActive" >
            <input type="hidden" value="<?php echo $sc_id;?>" name="sc_id" id="sc_id" >
            <input type="hidden" value="" name="set_status" id="set_status" >
            <input type="hidden" value="<?php echo ($priceFrom==0)?0:$priceFrom;?>" id="sc_priceFrom" name="priceFrom">
            <input type="hidden" value="<?php echo ($priceTo==0)?500000:$priceTo; ?>" id="sc_priceTo" name="priceTo">
            <input type="hidden" value="<?php echo ($order_sort)?$order_sort:"pd_date,pd_hits,pd_loc,pd_price,pd_recom";?>" name="order_sort" id="order_sort">
            <input type="hidden" value="<?php if($order_sort_active){echo $order_sort_active;}else if(!$order_sort_active){echo "1,1,1,0,0";};?>" name="order_sort_active" id="order_sort_active">
            <input type="hidden" value="<?php echo $cate;?>" name="cate" id="cate">
            <input type="hidden" value="<?php echo $cate2;?>" name="cate2" id="cate2">
            <input type="hidden" value="<?php echo ($pd_price_type1=="")?0:$pd_price_type1;?>" name="price_type1" id="price_type1">
            <input type="hidden" value="<?php echo ($pd_price_type2=="")?1:$pd_price_type2;?>" name="price_type2" id="price_type2">
            <input type="hidden" value="<?php echo ($pd_price_type3=="")?2:$pd_price_type3;?>" name="price_type3" id="price_type3">
            <input type="hidden" value="<?php echo ($pd_timeType=="")?0:$pd_timeType;?>" name="timeType" id="timeType">
            <input type="hidden" value="on" name="mb_level" id="mb_level">
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
                    <label class="radio_tag" for="levels">
                        <input type="checkbox" name="levels" id="levels" <?php if($mb_level=="on"){?>checked<?php }?>>
                        <span class="slider2 round">전문업체</span>
                    </label>
                </div>
                <div class="sch_ord" id="sc_sorts">
                    <?php if(count($order_item)==0){?>
                        <label class="align" id="sortable" for="pd_date" >
                            <input type="checkbox" name="orders[]" value="pd_date" id="pd_date" checked>
                            <span class="round">최신순</span>
                        </label>
                        <label class="align last" id="sortable" for="pd_hits" >
                            <input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" checked>
                            <span class="round">인기순</span>
                        </label>
                        <label class="align first" id="sortable" for="pd_loc" >
                            <input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" <?php if($app || $app2){?>checked <?php } ?> >
                            <span class="round">거리순</span>
                        </label>
                        <label class="align" id="sortable" for="pd_price">
                            <input type="checkbox" name="orders[]" value="pd_price" id="pd_price" >
                            <span class="round">가격순</span>
                        </label>
                        <label class="align" id="sortable" for="pd_recom" >
                            <input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" >
                            <span class="round">추천순</span>
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
                        <input type="checkbox" name="pd_price_type1" id="workcnt" value="0" <?php if($pd_price_type1=="0"){?>checked<?php }?>>
                        <span class="slider2 round">회당</span>
                    </label>
                    <label class="radio_tag" for="worktime">
                        <input type="checkbox" name="pd_price_type2" id="worktime" value="1" <?php if($pd_price_type2=="1"){?>checked<?php }?>>
                        <span class="slider2 round">시간당</span>
                    </label>
                    <label class="radio_tag" for="workday">
                        <input type="checkbox" name="pd_price_type3" id="workday" value="2" <?php if($pd_price_type3=="2"){?>checked<?php }?>>
                        <span class="slider2 round">하루당</span>
                    </label>
                </div>
                <div class="sch_btn_group meettime" style="<?php if($set_type==2 && $type2==8){?>display:block<?php }else{ ?>display:none;<?php }?>">
                    <div>
                        <h2>거래가능시간</h2>
                    </div>
                    <div class="pd_times">
                        <select name="pd_timeFrom" id="pd_timeFrom" class="write_input3" style="width:17vw">
                            <option value="">시간</option>
                            <?php for($i = 1; $i< 25; $i++){
                                $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                ?>
                                <option value="<?php echo $time;?>" <?php if($pd_timeFrom == $time){?>selected<?php }?>><?php echo $time;?></option>
                            <?php }?>
                        </select> 시부터
                        ~
                        <input type="checkbox" name="pd_timeType" id="pd_timetype" value="1" <?php if($pd_timeType==1){?>checked<?php }?> style="display: none"><label for="pd_timetype"><img src="<?php echo G5_IMG_URL?>/ic_write_check.svg" alt=""> 익일</label>
                        <select name="pd_timeTo" id="pd_timeTo" class="write_input3" style="width:17vw;margin-left:1vw;">
                            <option value="">시간</option>
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
                    <!--<input type="button" value="현 검색저장" class="sch_btn" onclick="fnSaveSch()">-->
                    <input type="submit" value="검색" class="sch_btn point" >
                    <!--<input type="button" value="삽니다 간편등록" class="sch_btn sch_save_write" onClick="<?php /*if($is_member){*/?>fnWrite();<?php /*}else{*/?>location.href=g5_bbs_url+'/login.php';<?php /*}*/?>">-->
                </div>
                <div class="search_close" onclick="fnSetting2()">
                    <img src="<?php echo G5_IMG_URL?>/search_close.svg" alt="">
                </div>
            </div>
            <div class="header_bg"></div>
            <div class="no-list" style="<?php if(count($list)==0){?>display:block;<?php }else{?>display:none;<?php }?>">
                <p>목록이 없습니다.</p>
            </div>
        </div>

    </form>
    <div class="mobile_menu" id="mobile_menu">
        <span></span>
        <div class="menu">
            <!--<div class="user_box <?php /*if($_SESSION["type1"]==2){*/?>bg2<?php /*}*/?>">-->
            <div class="user_box <?php if($set_type==2){?>bg2<?php }?>">
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
                <li class="menu3" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/mypage_order.php?type=2'"><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_menu_cart.svg" alt="">거래진행중</a></li>
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
            <div class="copyright <?php if($_SESSION["type1"]=="" || $_SESSION["type1"]==2){echo "bg2";}?>" style="">
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
</div>
    <div class="category_menu3">
        <div class="cate_header">
            <img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" onclick="cateClose('');">
            <h2>카테고리 설정</h2>
        </div>
        <div class="category catetype1">
            <ul>
                <li class="cate000 active" id="scate000" ><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_cate_all.svg" alt="">전체</a></li>
                <?php for($i=0;$i<count($category1);$i++){ ?>
                    <li class="cate<?php echo $category1[$i]["ca_id"]; ?>" id="scate<?php echo $category1[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category1[$i]["cate_name"];?></a></li>
                <?php } ?>
                <li class="sugg" onclick="fnsuggestion('');"><img src="<?php echo G5_IMG_URL?>/ic_menu_help.svg" alt="">제안하기</li>
            </ul>
        </div>
        <?php include_once(G5_MOBILE_PATH."/subcategory3.php"); ?>
    </div>
    <div class="category_menu4">
        <div class="cate_header">
            <img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="" onclick="cateClose('');">
            <h2>카테고리 설정</h2>
        </div>
        <div class="category catetype2">
            <ul>
                <li class="cate0000 active" id="scate0000" ><a href="#"><img src="<?php echo G5_IMG_URL?>/ic_cate_all.svg" alt="">전체</a></li>
                <?php for($i=0;$i<count($category2);$i++){ ?>
                    <li class="cate<?php echo $category2[$i]["ca_id"]; ?>" id="scate<?php echo $category2[$i]["ca_id"]; ?>"><a href="#"><img src="<?php echo G5_DATA_URL."/cate/".$category1[$i][icon]; ?>" alt=""><?php echo $category2[$i]["cate_name"];?></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php include_once(G5_MOBILE_PATH."/subcategory4.php"); ?>
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
        <?php if($set_type==1){?>
        $("#type1").removeAttr("checked");
        <?php }?>

        var hmenus = document.getElementById("mobile_menu");
        var hammer_menu = new Hammer(hmenus);

        hammer_menu.on("swipeleft",function(e){
            $(".mobile_menu").fadeOut(300,function(){
                $(".mobile_menu").removeClass("active");
            });
            $("html").css("overflow","auto");
            $("body").css("overflow","unset");
        });

    <?php if(count($list)==0){?>
        $("#set").val(2);
        location.hash = "#search";
        $(".search_setting").attr("id","menuon");
        $(".search_setting").css("top","20vw");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        <?php } ?>

        $(".mobile_menu .menu .close ").click(function(){
            $(".mobile_menu").fadeOut(300,function(){
                $(".mobile_menu").removeClass("active");
            });
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
            $(".current").css("bottom","3vw");
            $(".write").hide();
            $("#ft").hide();
        });
        $("#stx").on("blur", function(){
            $(".current").css("bottom","19vw");
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
            var sdkVersion = window.android.getSdkVersion();
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.regid.update.php",
                method:"post",
                data:{regid:regid,mb_id:"<?php echo $member["mb_id"];?>",sdkVersion:sdkVersion}
            }).done(function(data){
                console.log(data);
            })
        }catch(err){
            var regId = undefined;
            console.log(err);
        }


        $(".schtype .slider").click(function(){
            if($(this).prev().prop("checked") == true){
                $(".top_header").removeClass("bg2");
                $("#mobile_header").removeClass("bg2");
                $(".user_box").removeClass("bg2");
                $(".wished").removeClass("bg2");
                $(".copyright").removeClass("bg2");
                $(".search_setting").removeClass("bg2");
                $(".ft_menu_04 img").attr("src","<?php echo G5_IMG_URL;?>/bottom_icon_03.svg");
                $(this).html("물건");
                $(this).css({"text-align":"right"});
                $("#set_type").val(1);
                $("#set_type2").val(1);
                $(".timesel").css("display","none");
                $(".meettime").css("display","none");
                $("#cate").val('');
                $("#cate2").val('');
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
            }else{
                $(".top_header").addClass("bg2");
                $("#mobile_header").addClass("bg2");
                $(".wished").addClass("bg2");
                $(".user_box").addClass("bg2");
                $(".copyright").addClass("bg2");
                $(".search_setting").addClass("bg2");
                $(".ft_menu_04 img").attr("src","<?php echo G5_IMG_URL;?>/bottom_icon_03_2.svg");
                $(this).html("능력");
                $(this).css({"text-align":"left"});
                $("#set_type").val(2);
                $("#set_type2").val(2);
                $(".timesel").css("display","block");
                $(".meettime").css("display","block");
                $("#cate").val('');
                $("#cate2").val('');
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
            }
            priceSet();
        });

        $("#cate").change(function(){
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
            if(c=="전체" && sc == "전체"){
                $("#cate").val('');
                $("#cate2").val('');
                $(".sch_top .sch_btn").val("카테고리선택");
                cateClose('chk');
            }else {
                $.ajax({
                    url: g5_url + "/mobile/page/ajax/ajax.category_info.php",
                    method: "post",
                    data: {cate: c, type: type}
                }).done(function (data) {
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

                    cateClose('chk');
                });
            }
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

    function priceSet(){
        var cate1 = $("#cate").val();
        var cate2 = $("#cate2").val();
        var pd_type = $("#set_type").val();
        console.log(cate1+"//"+pd_type);
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

        $(".search_setting").attr("id","");
        $(".search_setting").css("top","-100vh");
        $("#id05").css("display","block");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
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