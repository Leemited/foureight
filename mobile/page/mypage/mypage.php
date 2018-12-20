<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");

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

if($wish_id) {
    $wish = sql_fetch("select count(*) as cnt from `wish_product` where mb_id = '{$mb_id}' and pd_id != '' and pd_status < 10  {$search}");
}
$total1=sql_fetch("select count(*) as cnt from `product` where mb_id = '{$mb_id}' and pd_type = 1 and pd_status < 10 ");
$total2=sql_fetch("select count(*) as cnt from `product` where mb_id = '{$mb_id}' and pd_type = 2 and pd_status < 10 ");
$total3=sql_fetch("select count(*) as cnt from `cart` where pd_id in ({$my_pd_ids}) and c_status != 2");
$total4=sql_fetch("select count(*) as cnt from `cart` as c left join `product` as p on c.pd_id = p.pd_id  where c.pd_id in ({$my_pd_ids}) and c_status != 2 and p.pd_type = 1");
$total5=sql_fetch("select count(*) as cnt from `cart` as c left join `product` as p on c.pd_id = p.pd_id  where c.pd_id in ({$my_pd_ids}) and c_status != 2 and p.pd_type = 2");

$total = $total["cnt"];
$wishtotal = $wish["cnt"];
$total1 = $total1["cnt"];
$total2 = $total2["cnt"];
$total3 = $total3['cnt'];
$total4 = $total4['cnt'];
$total5 = $total5['cnt'];

$sql = "select *,m.mb_id as mb_id from `product` as p left join `g5_member` as m  on p.mb_id = m.mb_id where p.mb_id = '{$mb_id}' and p.pd_type = 1 and p.pd_status < 10 order by p.pd_date desc";
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
<div class="loader" >
    <img src="<?php echo G5_IMG_URL?>/loader.svg" alt="" style="width:100%;position:relative;z-index:1">
    <!--<div style="background-color:#000;opacity: 0.4;width:100%;height:100%;position:absolute;top:0;left:0;"></div>-->
</div>
    <input type="hidden" value="<?php echo $page;?>" id="page">
<div id="mypage">
	<section class="user_info">
        <?php if($mb_id==$member["mb_id"] && $is_member){?>
        <?php if($mb["mb_level"] != 4){?>
        <div class="company_btn">
            <a href="<?php echo G5_MOBILE_URL?>/page/mypage/company_write.php">기업회원 신청</a>
        </div>
        <?php }?>
		<div class="settings" onclick="location.href='<?php echo G5_MOBILE_URL?>/page/mypage/settings.php'"><img src="<?php echo G5_IMG_URL?>/ic_profile_settings.svg" alt=""></div>
        <?php } ?>
		<div class="user_con">
			<div class="user_profile" >

			<?php if($mb["mb_profile"]){?>
				<img src="<?php echo $mb["mb_profile"];?>" alt="" class="profile">
			<?php }else{ ?>
				<img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
			<?php } ?>
			</div>
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
				<?php echo $mb["mb_nick"]; if($mb["mb_level"] == 4){ echo "<span>기업회원</span>";}?></h4>
                <p><img src="" alt=""><?php if($settings["hp_set"]==0 && count($settings) > 0){echo "비공개";}else{echo ($mb["mb_hp"])?$mb["mb_hp"]:"전화번호 정보가 없습니다.";}?></p>
				<!--<p><img src="" alt=""><?php /*echo ($mb["mb_email"])?$mb["mb_email"]:"이메일정보가 없습니다.";*/?></p>-->
				<p><img src="" alt="">가입일 : <?php echo $mb["mb_datetime"];?></p>
				<p><img src="" alt=""><?php echo ($mb["mb_addr1"])?$mb_address:"등록된 주소가 없습니다.";?></p>
			</div>
		</div>
        <?php if($member["mb_id"]!=$mb["mb_id"]){?>
        <div onclick="fnUserHidden('<?php echo $mb["mb_id"];?>');" class="user_block">
            <img src="<?php echo G5_IMG_URL?>/ic_mypage_hidden.svg" alt="">
        </div>
        <?php }?>
		<div class="bg"></div>
	</section>
	<section class="user_tab">
        <?php if($mode=="profile"){?>
        <ul id="my_ul">
            <li class="myprofile <?php if($type==1){echo 'active';}?>">
                <div>My List</div>
                <h2><img src="<?php echo G5_IMG_URL?>/ic_mypage_mylist.svg" alt=""><?php echo number_format($total);?></h2>
            </li>
            <li class="myprofile <?php if($type==2){echo 'active';}?>">
                <div>거래진행중</div>
                <h2><img src="<?php echo G5_IMG_URL?>/ic_mypage_orders.svg" alt=""><?php echo number_format($total3);?></h2>
            </li>
            <li class="myprofile <?php if($type==3){echo 'active';}?>">
                <div>관심상품</div>
                <h2><img src="<?php echo G5_IMG_URL?>/ic_mypage_wish.svg" alt=""><?php echo number_format($wishtotal);?></h2>
            </li>
        </ul>
        <ul class="sub_ul">
            <li class="<?php if($type==1){echo 'active';}?>" id="mul">
                <div>물품 <label><?php echo number_format($total1);?></label></div>
            </li>
            <li class="<?php if($type==2){echo 'active';}?>" id="avil">
                <div>능력 <label><?php echo number_format($total2);?></label></div>
            </li>
        </ul>
        <?php }else{ ?>
		<ul id="pro_ul">
			<li class="<?php if($type==1){echo 'active';}?>">
                <div>물품</div>
                <h2><?php echo number_format($total1);?></h2>
            </li>
			<li class="<?php if($type==2){echo 'active';}?>">
                <div>능력</div>
                <h2><?php echo number_format($total2);?></h2>
            </li>
		</ul>
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
                                    <h1>￦ <?php echo number_format($list[$i]["pd_price"]);?></h1>
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

$(document).ready(function(){
    $("#id02 ul li").click(function(){
        $(this).addClass("active");
        $("#id02 ul li").not($(this)).removeClass("active");
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
            fnlist(1,type1,"<?php echo $mb_id;?>");
        }
    });

    $("#my_ul li").click(function(){
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
                case "관심상품":
                    type1 = 3;
                    break;
            }
            if(type1 == 3){
                $(".sub_ul").css("display","none");
            }else{
                $(".sub_ul").css("display","inline-block");
                $(".sub_ul li").removeClass("active");
                $(".sub_ul #mul").addClass("active");
                if(type1 == 2){
                    $(".sub_ul #mul div label").html("<?php echo $total4;?>");
                    $(".sub_ul #avil label").html("<?php echo $total5;?>");
                }
            }
            fnlist2(1,type1,1,"<?php echo $mb_id;?>");
        }
    });

    $(".sub_ul li").click(function(){
        if(!$(this).hasClass("active")){
            $(this).addClass("active");
            $(".sub_ul li").not($(this)).removeClass("active");
        }
        switch (type1){
            case "My List":
                type1 = 1;
                break;
            case "거래진행중":
                type1 = 2;
                break;
            case "관심상품":
                type1 = 3;
                break;
        }
        if($(this).attr("id") == "mul"){
            fnlist2(1,type1,1,"<?php echo $mb_id;?>");
        }else if($(this).attr("id")=="avil"){
            fnlist2(1,type1,2,"<?php echo $mb_id;?>");
        }
    });

	<?php if($mb['mb_profile']){?>
	$(".user_info").css({"background-image":"url('<?php echo $mb[mb_profile];?>')","background-size":"cover","background-position":"center","background-repeat":"no-repeat","backgroud-color":"#000"});
	<?php }else{ ?>
	$(".user_info").css({"background-color":"#ddd"});
	<?php } ?>
});

var page=1;
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

function fnlist(num,type1,mb_id){
    if(type1==""){
        type1 = 1;
    }
    if(num == 1){
        page=0;
    }
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.mypage.list.php",
        method:"POST",
        data:{type1:type1,page:page,mb_id:mb_id},
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

function fnlist2(num,type1,type,mb_id){
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
            url = g5_url+"/mobile/page/ajax/ajax.mypage_wish.list.php";
            break;
    }

    $.ajax({
        url:url,
        method:"POST",
        data:{mb_id:mb_id,page:page,type1:type},
        beforeSend:function(){
            $(".loader").css("display","block");
        },
        complete:function(){
            $(".loader").css("display","none");
        }
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
        }else{
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
</script>

<?php 
include_once(G5_MOBILE_PATH."/tail.php");
?>