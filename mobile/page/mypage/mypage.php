<?php 
include_once("../../../common.php");
include_once(G5_PATH."/head.sub.php");

if(!$is_member){
    alert("로그인이 필요합니다.",G5_MOBILE_URL.'/page/login_intro.php?url='.G5_MOBILE_URL."/page/mypage/mypage.php");
    return false;
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


if($pro_id){
    $mb_id = $pro_id;
    if(!$type){
        $type = 1;
    }
    $mode = "";
}else{
    $mb_id = $member["mb_id"];
    if(!$type){
        $type = 1;
    }
    $mode = "profile";


    $sql = "select * from `wish_product` where mb_id = '{$mb_id}'";
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
    $search .= " and pd_id in ({$pd_ids})";
}
if($mode != "profile" && $member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/mypage.php");
}
$sns_login = sql_fetch("select * from `g5_social_member` where mb_id = '{$mb_id}'");

$mb=get_member($mb_id);

//설정 가져오기
if($mb["mb_id"]!=$member["mb_id"]){
    $settings = sql_fetch("select * from `mysetting` where mb_id = '{$mb["mb_id"]}'");
}

$total=sql_fetch("select count(*) as cnt from `product` where mb_id = '{$mb_id}' and pd_status < 10 ");
$res=sql_query("select * from `product` where mb_id = '{$mb_id}'");
while($row = sql_fetch_array($res)){
    $all_pd_ids[] = $row["pd_id"];
}
if(count($all_pd_ids)>0) {
    $my_pd_ids = implode(",", $all_pd_ids);
}else{
    $my_pd_ids = '';
}


$count1 = sql_fetch("select count(*) as cnt from `order` as o left join `product` as p on o.pd_id = p.pd_id where p.mb_id = '{$mb["mb_id"]}' and o.od_status = 1 and o.od_pay_status = 1 and od_step = 2 and p.pd_type = 1");
$count2 = sql_fetch("select count(*) as cnt from `order` as o left join `product` as p on o.pd_id = p.pd_id where p.mb_id = '{$mb["mb_id"]}' and o.od_status = 1 and o.od_pay_status = 1 and od_step = 2 and p.pd_type = 2");

$like = sql_fetch("select count(*) as cnt from `product_like` where pd_type = 1 and pd_mb_id = '".$mb["mb_id"]."' ");

if($_SESSION["type1"]==2 || $_SESSION["type1"]==""){
    $where = " and p.pd_type = 2";
}else{
    $where = " and p.pd_type = 1";
}

$sql = "select *,m.mb_id as mb_id from `product` as p left join `g5_member` as m  on p.mb_id = m.mb_id where p.mb_id = '{$mb["mb_id"]}' and p.pd_status < 10 {$where} order by p.pd_date desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}

$addr = explode(" ",$mb["mb_addr1"]);
$mb_address = $addr[0]." ".$addr[1]." ".$addr[2]." ".$addr[3];
?>
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
<div id="id06" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>사유보기</h2>
            <div class="con">
                <p></p>
            </div>
            <div>
                <input type="button" value="확인" onclick="modalClose(this)">
                <input type="button" value="상세보기" id="blind_view_btn" style="width:auto" onclick="">
                <input type="button" value="관리자문의" id="admin_qa" style="width:auto"  onclick="">
            </div>
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
<div id="id09" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form action="<?php echo G5_MOBILE_URL?>/page/mypage/member_block_update.php" method="post" name="blockform">
                <input type="hidden" name="target_id" id="target_id" value="<?php echo $mb["mb_id"];?>">
                <input type="hidden" name="mb_id"  id="mb_id" value="<?php echo $member["mb_id"];?>">
                <input type="hidden" name="block_date" id="block_date" value="1">
            <h2>유저차단</h2>
            <div class="con">
                <ul class="modal_sel">
                    <li id="status1" class="active" >1개월 차단</li>
                    <li id="status2" class="" >6개월 차단</li>
                    <li id="status3" class="" >영구차단</li>
                </ul>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)">
                <input type="button" value="차단하기" onclick="fn_block();" style="width:auto">
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
    <div id="id00" class="w3-modal w3-animate-opacity no-view">
        <div class="w3-modal-content w3-card-4">
            <div class="w3-container">
                <input type="hidden" value="<?php echo $order["od_id"];?>" name="od_id" id="od_id">
                <h2>배송정보 입력</h2>
                <div>
                    <input type="hidden" id="deli_od_id" value="">
                    <input type="text" name="delivery_name" id="delivery_name" required style="width:50%">
                    <select name="deli_sel" id="deli_sel" onchange="$('#delivery_name').val(this.value)" style="width:calc(50% - 8vw);text-align: center;background-color: #FFF;color: #000;position: relative;    margin: 4vw auto;padding: 2vw;font-size: 3.6vw;border-radius: 20vw;border: none;font-family: 'nsr', sans-serif;">
                        <option value="">택배사선택</option>
                        <option value="한진택배">한진택배</option>
                        <option value="우체국택배">우체국택배</option>
                        <option value="옐로우캡">옐로우캡</option>
                        <option value="로젠택배">로젠택배</option>
                        <option value="대한통운">대한통운</option>
                        <option value="경동택배">경동택배</option>
                        <option value="">직접입력</option>
                    </select>
                    <!--<input type="text" value="" name="delivery_name" id="delivery_name" placeholder="택배사" required >-->
                    <input type="text" value="" name="delivery_number" id="delivery_number" placeholder="운송장번호" required style="margin-top:0;">
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="배송정보 등록" onclick="fnConfirmDelivery();" style="width:auto;margin-left:1vw" >
                </div>
            </div>
        </div>
    </div>
<div class="loader" >
    <img src="<?php echo G5_IMG_URL?>/loader.svg" alt="" style="width:100%;position:relative;z-index:1">
    <!--<div style="background-color:#000;opacity: 0.4;width:100%;height:100%;position:absolute;top:0;left:0;"></div>-->
</div>
<input type="hidden" value="<?php echo $page;?>" id="page">
<div id="mypage" <?php if($_SESSION["type1"]==2 || $_SESSION["type1"]==""){?>class="bg2"<?php }?>>
	<section class="user_info">
        <div class="mypage_back" onclick="location.href=g5_url">
            <img src="<?php echo G5_IMG_URL;?>/ic_mypage_back.svg" alt="">
        </div>
        <div class="search_bar" style="">
            <div>
                <input type="checkbox" name="pd_type" id="pd_type" class="mypage_pd_type" <?php if($_SESSION["type1"]==2 || $_SESSION["type1"]==""){?>checked<?php }?> >
                <label for="pd_type"></label>
                <p class="placeholder">현재 리스트 내 검색</p>
                <input type="text" class="" name="stx" id="stx">
                <div class="search_btn"></div>
            </div>
        </div>
        <?php if($mb_id==$member["mb_id"] && $is_member){?>

		<div class="settings" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/settings.php'"><img src="<?php echo G5_IMG_URL?>/ic_profile_settings.svg" alt=""></div>
        <?php }else{?>
		<div class="settings menus"><img src="<?php echo G5_IMG_URL?>/ic_profile_menu.svg" alt=""></div>
        <div class="menu_list">
            <ul>
                <li onclick="fnBlinds('<?php echo $mb["mb_id"];?>')">신고</li>
                <li onclick="fnUserHidden('<?php echo $mb["mb_id"];?>');">차단</li>
            </ul>
        </div>
        <?php } ?>
		<div class="user_con">
            <div>
                <?php if($mb["mb_level"] == 4){ echo "<span class='company'>기업</span>";} ?>
                <div class="user_profile" style="<?php if($mb["mb_profile"]){?>background-image:url('<?php echo $mb["mb_profile"];?>');<?php }else{ ?>background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg')<?php } ?>;background-size:cover;background-position:center;background-repeat: no-repeat"></div>
                <div class="user_text">
                    <h4>
                    <?php if($sns_login["sm_service"]){?>
                    <div class="sns <?php echo $sns_login["sm_service"];?>">
                    <?php if($sns_login["sm_service"]=="naver"){?>
                        <img src="<?php echo G5_IMG_URL?>/sns_login_naver.svg" alt="">
                    <?php } if($sns_login["sm_service"]=="facebook"){?>
                        <img src="<?php echo G5_IMG_URL?>/sns_login_facebook.svg" alt="">
                    <?php } if($sns_login["sm_service"]=="kakao"){?>
                        <img src="<?php echo G5_IMG_URL?>/sns_login_kakao.svg" alt="">
                    <?php }?>
                    </div>
                    <?php } ?>
                        <?php echo $mb["mb_nick"];?>
                    <?php /*if($member["mb_id"]!=$mb["mb_id"]){*/?><!--<span onclick="fnUserHidden('<?php /*echo $mb["mb_id"];*/?>');" class="">차단하기</span>--><?php /*}*/?>
                    </h4>
                    <p><img src="" alt="">가입일 : <?php echo substr($mb["mb_datetime"],0 ,10);?></p>
                </div>
            </div>
            <div>
                <div>능력 거래완료 : <span><?php echo number_format($count2["cnt"]);?></span> <label for="">/</label> <div class="like_btns2" onclick="fnReview2()">능력 후기</div></div>
                <div>물건 거래완료 : <span class="last"><?php echo number_format($count1["cnt"]);?></span> <label for="">/</label> <div class="like_btns" onclick="fnReview('<?php echo $mb["mb_id"];?>',1);"><img src="<?php echo G5_IMG_URL;?>/mypage_like.svg" alt=""><span><?php echo number_format($like["cnt"]);?></span></div></div>
            </div>
		</div>
        <?php /*if($member["mb_id"]!=$mb["mb_id"]){*/?><!--
        <div onclick="fnUserHidden('<?php /*echo $mb["mb_id"];*/?>');" class="user_block">
            <img src="<?php /*echo G5_IMG_URL*/?>/ic_mypage_hidden.svg" alt="">
        </div>
        --><?php /*}*/?>
		<!--<div class="bg"></div>-->
	</section>
	<section class="user_tab">
        <?php if($mode=="profile"){?>
        <ul id="my_ul">
            <li class="myprofile myboard <?php if($type==1){echo 'active';}?>">
                <div>My List</div>
                <!--<h2><img src="<?php /*echo G5_IMG_URL*/?>/ic_mypage_mylist.svg" alt=""><span><?php /*echo number_format($total);*/?></span></h2>-->
            </li>
            <li class="myprofile order_tab <?php if($type==2){echo 'active';}?>">
                <div>거래진행중</div>
                <!--<h2><img src="<?php /*echo G5_IMG_URL*/?>/ic_mypage_orders.svg" alt=""><span><?php /*echo number_format($total3);*/?></span></h2>-->
            </li>
            <li class="myprofile wishes <?php if($type==3){echo 'active';}?>">
                <div>거래완료</div>
                <!--<h2><img src="<?php /*echo G5_IMG_URL*/?>/ic_mypage_wish.svg" alt=""><span><?php /*echo number_format($wishtotal);*/?></span></h2>-->
            </li>
        </ul>
        <!--<ul class="sub_ul">
            <li class="<?php /*if($type==1){echo 'active';}*/?>" id="mul">
                <div>물품 <label><?php /*echo number_format($total1);*/?></label></div>
            </li>
            <li class="<?php /*if($type==2){echo 'active';}*/?>" id="avil">
                <div>능력 <label><?php /*echo number_format($total2);*/?></label></div>
            </li>
        </ul>-->
        <?php }else{ ?>
		<!--<ul id="pro_ul">
			<li class="<?php /*if($type==1){echo 'active';}*/?>">
                <div>물품</div>
                <h2><?php /*echo number_format($total1);*/?></h2>
            </li>
			<li class="<?php /*if($type==2){echo 'active';}*/?>">
                <div>능력</div>
                <h2><?php /*echo number_format($total2);*/?></h2>
            </li>
		</ul>-->
        <?php } ?>
	</section>
    <section class="user_list">
        <article class="post" id="post">
            <div class="list_item grid are-images-unloaded" id="test">
                <?php
                for($i=0;$i<count($list);$i++){
                    for($j=0;$j<count($wished);$j++){
                        if($wished[$j]["pd_id"]==$list[$i]["pd_id"]){
                            $flag = true;
                            break;
                        }else{
                            $flag = false;
                        }
                    }
                    ?>
                    <!-- <div class="grid__item" onclick="fn_viewer('<?php echo $list[$i]['pd_id'];?>')"> -->
                    <div class="grid__item <?php if($list[$i]["pd_blind"]>=10){?>blinds<?php }?>" onclick="<?php if($list[$i]["pd_blind"]<10){?>fn_viewer('<?php echo $list[$i]["pd_id"];?>')<?php }?>">
                        <?php if($list[$i]["pd_blind"]>=10){?>
                            <div class="blind_bg">
                                <div>
                                    <input type="button" value="사유보기" class="list_btn"  onclick="fnBlindView('<?php echo $list[$i]["pd_id"];?>')">
                                    <input type="button" value="게시물보기" class="list_btn"  onclick="fn_viewer('<?php echo $list[$i]["pd_id"];?>')">
                                </div>
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
                                    $tags = explode("/",$list[$i]["pd_tag"]);
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
                            <?php }else{
                                $tags = explode("/",$list[$i]["pd_tag"]);
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
                                    <h2><?php if($list[$i]["pd_status"]==0){echo "판";}else if($list[$i]["pd_status"]==1){echo "거";}?></h2>
                                    <div>
                                        <ul>
                                            <li><img src="<?php echo G5_IMG_URL?>/ic_hit.svg" alt=""> <?php echo $list[$i]["pd_hits"];?></li>
                                            <?php if($app){?><li><img src="<?php echo G5_IMG_URL?>/ic_loc.svg" alt=""> 0</li><?php }?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom">
                                <?php if($list[$i]["pd_name"]){?>
                                    <h2><?php echo $list[$i]["pd_name"];?></h2>
                                <?php }?>
                                <div>
                                    <h1>￦ <?php echo number_format($list[$i]["pd_price"]+$list[$i]["pd_price2"]);?></h1>
                                </div>
                            </div>

                        </div>
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
var type1 = "";
var scrollchk = true;
var finish = false;
var timer = null;
$(document).ready(function(){
    $("#id09 ul li").click(function(){
        $(this).addClass("active");
        $("#id09 ul li").not($(this)).removeClass("active");
       var text = $(this).text();
       switch (text){
           case "1개월 차단":
               $("#block_date").val(1);
               break;
           case "6개월 차단":
               $("#block_date").val(2);
               break;
           case "영구차단":
               $("#block_date").val(3);
               break;
       }
    });

    $("#pro_ul li").click(function(){
        var stx = $("#stx").val();
        if(!$(this).hasClass("active")){
            $("#pro_ul li").not($(this)).removeClass("active");
            $(this).addClass("active");
            type1 = $(this).find("div").text();
            switch (type1){
                case "물품":
                    type1 = 1;
                    break;
                case "능력":
                    type1 = 2;
                    break;
            }
            fnlist(1,type1,"<?php echo $mb_id;?>",stx);
        }
    });

    $("#my_ul li").click(function(){
        var stx = $("#stx").val();
        if(!$(this).hasClass("active")){
            $("#my_ul li").not($(this)).removeClass("active");
            $(this).addClass("active");
            type1 = $(this).find("div").text();
            switch (type1){
                case "My List":
                    type1 = 1;
                    break;
                case "거래진행중":
                    type1 = 2;
                    break;
                case "거래완료":
                    type1 = 3;
                    break;
            }
            if($(".mypage_pd_type").prop("checked") != true) {
                fnlist2(1, type1, 1, "<?php echo $mb_id;?>",stx);
            }else{
                fnlist2(1, type1, 2, "<?php echo $mb_id;?>",stx);
            }
        }
    });

    $(".mypage_pd_type").click(function(){
        var stx = $("#stx").val();
        switch (type1){
            case "My List":
                type1 = 1;
                break;
            case "거래진행중":
                type1 = 2;
                break;
            case "거래완료":
                type1 = 3;
                break;
        }
        if($(this).prop("checked")!=true){
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"type1",value:"1"}
            }).done(function(data){
                console.log(data);
            });
            //물건
            $("#mypage").removeClass("bg2");
            fnlist2(1,type1,1,"<?php echo $mb_id;?>",stx);
        }else{
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                method:"post",
                data:{key:"type1",value:"2"}
            }).done(function(data){
                console.log(data);
            });
            //능력
            $("#mypage").addClass("bg2");
            fnlist2(1,type1,2,"<?php echo $mb_id;?>",stx);
        }
    });

    $("#stx").keyup(function(){
        var stx = $(this).val();
        $(".placeholder").hide();
        if(timer!=null){
            clearTimeout(timer);
        }
        timer = setTimeout(function(){
            if($(".mypage_pd_type").prop("checked") != true) {
                fnlist2(1, type1, 1, "<?php echo $mb_id;?>",stx);
            }else{
                fnlist2(1, type1, 2, "<?php echo $mb_id;?>",stx);
            }
        },1000);
    });

    $("#stx").blur(function(){
        if($("#stx").val().length == 0) {
            $(".placeholder").show();
        }
    });

	<?php if($mb['mb_profile']){?>
	//$(".user_info").css({"background-image":"url('<?php echo $mb[mb_profile];?>')","background-size":"cover","background-position":"center","background-repeat":"no-repeat","backgroud-color":"#000"});
	<?php }else{ ?>
	//$(".user_info").css({"background-color":"#ddd"});
	<?php } ?>

    <?php if($_REQUEST["type"]){?>
    <?php if($type==1){?>
        fnlist2(1, 1, 1, "<?php echo $mb_id;?>",'');
    <?php }else if($type==2){?>
        fnlist2(1, 2, 1, "<?php echo $mb_id;?>",'');
    <?php }else if($type==3){?>
        fnlist2(1, 3, 1, "<?php echo $mb_id;?>",'');
    <?php }?>
    <?php }?>


    $(".settings.menus").click(function(){
        $(".menu_list").toggleClass("active");
    });
});

var page=1;
var $grid;
function initpkgd(){
//-------------------------------------//
    // init Masonry
    $grid = $('.grid').masonry({
        itemSelector: 'none', // select none at first
        columnWidth: '.grid__item',
        gutter: 5,
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
$(function(){
    initpkgd();

});

function fnBlind(pd_id){
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.blind_view.php",
        method:"post",
        data:{pd_id:pd_id}
    }).done(function(data){
       console.log(data);
    });
    $("#id06").css("display","block");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
}

function fnlist(num,type1,mb_id,stx){
    if(type1==""){
        type1 = 2;
    }
    if(num == 1){
        page=0;
    }
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.mypage.list.php",
        method:"POST",
        data:{type1:type1,page:page,mb_id:mb_id,stx:stx},
        beforeSend:function(){
            $(".loader").css("display","block");
        },
        complete:function(){
            $(".loader").css("display","none");
        }
    }).done(function(data){
        if(data.indexOf("no-list")==-1){
            if(num == 1){
                //새리스트
                $(".grid").remove();
                var item = '<div class="list_item grid are-images-unloaded"></div>';
                $(".post").append(item);
                $(".grid").append(data);

                initpkgd();
                page=1;
            }else{
                //스크롤
                var items = $(data);
                $grid.append(items).masonry( 'appended', items );

                page++;
            }
        }else{
            finish = true;
            $("#debug").addClass("active");
            $("#debug").html("목록이 없습니다.");
            setTimeout(removeDebug,1500);
        }
        scrollchk=true;
    });
}

function fnlist2(num,type1,type,mb_id,stx){
    if(type1==""){
        type1 = 1;
    }
    var url = g5_url+"/mobile/page/ajax/ajax.mypage.list.php";
    switch(type1){
        case 1:
            url = g5_url+"/mobile/page/ajax/ajax.mypage.list.php";
            break;
        case 2:
            url = g5_url+"/mobile/page/ajax/ajax.mypage.orders.php";
            break;
        case 3:
            url = g5_url+"/mobile/page/ajax/ajax.mypage.order_confirm.php";
            break;
    }
    $.ajax({
        url:url,
        method:"POST",
        data:{mb_id:mb_id,page:page,type1:type,stx:stx}
    }).done(function(data){
        console.log(data);
        if(data.indexOf("no-member")>0){
            alert("회원정보가 없습니다.");
            return false;
        }

        if(data.indexOf("no-list")==-1){
            if(num == 1){
                //새리스트
                $(".grid").remove();
                var item = '<div class="list_item grid are-images-unloaded"></div>';
                $(".post").append(item);
                $(".grid").append(data);

                initpkgd();
                if(type1==2){
                    $(".list_item").css("height","auto");
                }
                page=1;
            }else{
                //스크롤
                var items = $(data);
                $grid.append(items).masonry( 'appended', items );

                page++;
            }

            /*if(type1 == 1 || type1 == 2){
                var cnt1 = $("#listCount1").val();
                var ncnt1 = Number(cnt1);
                var incnt1 = ncnt1.numberFormat();
                var cnt2 = $("#listCount2").val();
                var ncnt2 = Number(cnt2);
                var incnt2 = ncnt2.numberFormat();
                var total = ncnt1 + ncnt2;
                total = total.numberFormat();
                $("#mul label").html(incnt1);
                $("#avil label").html(incnt2);
                if(type1 == 1){
                    $(".myboard span").html(total);
                }else{
                    $(".order_tab span").html(total);
                }
            }*/
            //if(type1 == 2){

            //}
            /*if(type1 == 3){
                var total = $("#listCount").val();
                total = Number(total);
                total = total.numberFormat();
                $("#wishes span").html(total);
            }*/
        }else{
            //data = data.split("//");

           /* if(type1 == 1 || type1 == 2) {
                $("#mul label").html(data[1]);
                $("#avil label").html(data[2]);
            }*/
            if(num==1){
                $(".grid").html('');
            }
            finish = true;
            $("#debug").addClass("active");
            $("#debug").html("목록이 없습니다.");
            setTimeout(removeDebug,1500);
        }
        scrollchk=true;
    });
}

function fnUserHidden(mb_id){
    $("#id09").css("display","block");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
}
function fn_block(){
    if($("#target_id").val()==""){
        alert("차단할 유저정보가 없습니다.\r다시 시도해 주세요.");
        return false;
    }if($("#mb_id").val()==""){
        alert("로그인이 필요합니다.");
        location.href=g5_url+'/mobile/page/login_intro.php';
    }
    if($("#block_date").val()==""){
        alert("차단 기간을 설정해 주세요.");
        return false;
    }
    if(confirm("해당 유저를 차단 하시겠습니까?")){
        document.blockform.submit();
    }else{
        return false;
    }

}

function fnBlinds(user){
    $.ajax({
        url:g5_url+"/mobile/page/blind_write.php",
        method:"post",
        data:{pd_id:'',type:"modal",cm_id:'',user:user}
    }).done(function(data){
        $("#id01s").css({"display":"block","z-index":"9002"});
        $("#id01s .con").html('');
        $("#id01s .con").append(data);
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        location.hash = "#blind";
    });
}

// 숫자 타입에서 쓸 수 있도록 format() 함수 추가
Number.prototype.numberFormat = function(){
    if(this==0) return 0;

    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');

    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

    return n;
};
function fnReview(mb_id,pd_type){
    $.ajax({
        url:g5_url+"/mobile/page/mypage/mypage_like_list.php",
        method:"post",
        data:{mb_id:mb_id,pd_type:pd_type}
    }).done(function(data){
        $("#id01s").css({"display":"block","z-index":"9002"});
        $("#id01s .con").html('');
        $("#id01s .con").append(data);
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        location.hash = "#blind";
    });
}

function fnReview2(){
    $('.mypage_pd_type').attr('checked',true);
    var stx = $("#stx").val();
    switch (type1){
        case "My List":
            type1 = 1;
            break;
        case "거래진행중":
            type1 = 2;
            break;
        case "거래완료":
            type1 = 3;
            break;
    }
    //능력
    $("#mypage").addClass("bg2");
    fnlist2(1,type1,2,"<?php echo $mb_id;?>",stx);

}

//간편대화 시작
//내게시글이 아닐때 이무로 게시물 작성자는 무조건 read_mb_id로 선언
function fnTalk2(read_mb_id,pd_id,roomid){
    location.href=g5_url+'/mobile/page/talk/talk_view.php?pd_id='+pd_id+"&read_mb_id="+read_mb_id+"&send_mb_id=<?php echo $member["mb_id"];?>&roomid="+roomid+"&type=payment";
}

function fnConfirmDelivery(){
    var od_id = $("#deli_od_id").val();
    var delivery_name = $("#delivery_name").val();
    var delivery_number = $("#delivery_number").val();

    if(od_id == ""){
        alert("선택된 주문 정보가 없습니다.");
        return false;
    }
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.order_delivery_update.php",
        method:"post",
        data:{od_id:od_id,delivery_name:delivery_name,delivery_number:delivery_number},
        dataType:"json"
    }).done(function(data){
        if(data.result==1){
            alert("주문정보를 찾지 못했습니다. \n다시 시도해 주세요.")
        }else if(data.result == 2){
            alert("배송정보 입력 실패");
        }else{
            $("#deli_name").html(delivery_name);
            $("#deli_num").html(delivery_number);
            $("#deli_date").html(data.deli_date);
            modalClose();
            location.reload();
        }
    });
}

</script>

<?php 
include_once(G5_MOBILE_PATH."/tail.php");
?>