<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/tail.php');
    return;
}
//새알림
$start = date("Y-m-d");
$end = date("Y-m-d", strtotime("-3 month"));
if($member["mb_id"]) {
    $sql = "select count(*) as cnt from `my_alarms` where mb_id = '{$member["mb_id"]}' and alarm_status = 0 and alarm_date BETWEEN '{$end}' and '{$start}' ";
    $alarms = sql_fetch($sql);
}else{
    $alarms["cnt"]=0;
}
?>

<div id="debug" style="">삭제상태</div>

<div id="ft">
    <div id="ft_copy">
        <ul>
			<li onclick="location.href=g5_url+'/mobile/page/mypage/mypage.php'">
				<img src="<?php echo G5_IMG_URL?>/bottom_icon_01.svg" alt="마이페이지">
			</li>
			<li onclick="location.href=g5_url+'/mobile/page/mypage/alarm.php'">
                <?php if($alarms["cnt"] > 0){?>
                    <div class="new"></div>
                <?php }?>
                <img src="<?php echo G5_IMG_URL?>/bottom_icon_02.svg" alt="알람리스트" class="wished_tail">

            </li>
			<?php if($p == "index"){?>
			<li id="home" onclick="fnHome()"><img src="<?php echo G5_IMG_URL?>/bottom_icon_home.svg" alt="최상위로"></li>
			<?php }else{?>
			<li onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/bottom_icon_home.svg" alt="홈"></li>
			<?php }?>
			<li onclick="fnRecent()" class="ft_menu_04"><img src="<?php echo G5_IMG_URL?>/bottom_icon_03.svg" alt="검색항목"></li>
			<li onclick="location.href=g5_url+'/mobile/page/productmap/'"><img src="<?php echo G5_IMG_URL?>/bottom_icon_04.svg" alt="지도보기"></li>
		</ul>
    </div>
    <!--<div class="footer">
        <img src="<?php /*echo G5_IMG_URL*/?>/footer_img.svg" alt="">
    </div>-->
</div>

<?php
//if(G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) { 
?>

<!-- <a href="<?php echo get_device_change_url(); ?>" id="device_change">PC 버전으로 보기</a> -->

<?php
//}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>


<script src="<?php echo G5_JS_URL ?>/jquery.ui.touch-punch.js"></script>
<script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<script src="<?php echo G5_JS_URL?>/hammer.js"></script>
<script src="<?php echo G5_URL?>/node_modules/clipboard/dist/clipboard.min.js"></script>

<!-- iamport.payment.js -->
<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script>

function fnHome(){
    /*$.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
        method:"post",
        data:{key:"type1",value:"2"}
    }).done(function(data){

    });*/
    // 이거 누르면 초기화?
    var chk = false;
    $.ajax({
        url:g5_url+'/mobile/page/ajax/ajax.search_reset.php',
        method:"post",
        async:false
    }).done(function(data){
        if(data=="" || data==null){
            chk = true;
        }else{
            fnHome();
        }
    });
    if(chk==true) {
        location.href = g5_url;
    }
}
function fnPricingUpdate(){
    var pd_id = $("#p_pd_id").val();
    var pd_type = $("#p_type").val();
    var pricing_pd_id = $("#id07 select").val();
    var pricing_content = $("#pricing_content").val();
    var pricing_price = $("#pricing_price").val();
    var mb_id = "<?php echo $member["mb_id"];?>";
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.pricing_update.php",
        method:"POST",
        data:{pd_id:pd_id,pricing_pd_id:pricing_pd_id,pricing_content:pricing_content,mb_id:mb_id,pd_type:pd_type,pricing_price:pricing_price}
    }).done(function(data){
       if(pd_type==1){
           if (data == "0") {
               alert("로그인이 필요합니다.");
               location.href = g5_url + '/mobile/page/login_intro.php';
               return false;
           }
           if (data == "5") {
               alert("잘못된 요청입니다. 다시 시도해 주세요.");
               return false;
           }
           if (data == "4") {
               alert("딜하기가 등록되었습니다.");
               modalClose();
           }
       }
       if(pd_type==2) {
           if (data == "0") {
               alert("로그인이 필요합니다.");
               location.href = g5_url + '/mobile/page/login_intro.php';
               return false;
           }
           if (data == "1") {
               alert("게시글 정보가 없습니다.");
               return false;
           }
           if (data == "2") {
               alert("제시할 게시물을 선택해 주세요.");
               return false;
           }
           if (data == "3") {
               alert("내용을 입력해주세요.");
               return false;
           }
           if (data == "5") {
               alert("잘못된 요청입니다. 다시 시도해 주세요.");
               return false;
           }
           if (data == "4") {
               alert("제시가 등록되었습니다.");
               modalClose();
           }
       }
    });
}

function fnRecent(){
    location.href = g5_url + '/mobile/page/recent/recent.list.php';
}

function fnAdminWrite(pd_id){
    location.href= g5_bbs_url+"/qawrite.php?pd_id="+pd_id;
}

$(function() {
    $(document).scroll(function(){
        if($(this).scrollTop() <= 0){
            var home = "<?php echo G5_IMG_URL;?>/bottom_icon_home.svg";
            $("#home").attr("onclick","fnHome()");
            $("#home img").attr("src",home);
        }else{
            var home = "<?php echo G5_IMG_URL;?>/bottom_icon_05.svg";
            $("#home").attr("onclick","scroll_top()");
            $("#home img").attr("src",home);
        }
    });
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));

});


function addSell2(pd_id,price,sell_mb_id,id){

    if(confirm("해당 판매글의 상태가 거래중으로 변경됩니다.\r판매 하시겠습니까?")) {
        //바로 지목 판매이므로 상태는 판매중으로 변경
        $.ajax({
            url: g5_url + "/mobile/page/ajax/ajax.insert_cart.php",
            method: "POST",
            data: {price: price, pd_id: pd_id, sell_mb_id: sell_mb_id, status: 1,id:id}
        }).done(function (data) {
            console.log(data);
        });
    }else{
        return false;
    }
}

function showMenu(){
    $(".write").show();
    $("#ft").show();
    $(".current").css("bottom","19vw");
}

</script>

<?php
include_once(G5_PATH."/tail.sub.php");
?>