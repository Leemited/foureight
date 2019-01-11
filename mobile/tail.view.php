<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/tail.php');
    return;
}
?>

<div id="debug" style="">삭제상태</div>
<!--
<div id="ft">
    <!-- <?php /*echo popular('basic'); // 인기검색어 */?>
    <?php /*echo visit('basic'); // 방문자수 */?>
    <div id="ft_copy">
        <ul>
			<li onclick="location.href=g5_url+'/mobile/page/mypage/mypage.php'">
				<img src="<?php /*echo G5_IMG_URL*/?>/bottom_icon_01.svg" alt="마이페이지">
			</li>
			<li onclick="location.href=g5_url+'/mobile/page/wish/wish.list.php'"><img src="<?php /*echo G5_IMG_URL*/?>/bottom_icon_02.svg" alt="위시리스트" class="wished_tail"></li>
			<?php /*if($p == "index"){*/?>
			<li onclick="scroll_top()"><img src="<?php /*echo G5_IMG_URL*/?>/bottom_icon_05.svg" alt="최상위로"></li>
			<?php /*}else{*/?>
			<li onclick="location.href='<?php /*echo G5_URL*/?>'"><img src="<?php /*echo G5_IMG_URL*/?>/bottom_icon_home.svg" alt="홈"></li>
			<?php /*}*/?>
			<li onclick="fnRecent()"><img src="<?php /*echo G5_IMG_URL*/?>/bottom_icon_03.svg" alt="최근본항목"></li>
			<li onclick="location.href=g5_url+'/mobile/page/productmap/'"><img src="<?php /*echo G5_IMG_URL*/?>/bottom_icon_04.svg" alt="지도보기"></li>
		</ul>
    </div>
    <div class="footer">
        디자인율 | 대표 : 김용호 | 사업자등록번호 : 541-44-00091 | 대표전화 : 010-3034-1746
    </div>
</div>-->

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
<script src="https://hammerjs.github.io/dist/hammer.js"></script>

<script>
function modalClose(){
    console.log("AAA");
	$("[id^=id]").each(function(){
		$(this).css("display","none");
	});
	//$("#id01").css("display","none");
}


function fnRecent(){
	location.href=g5_url+'/mobile/page/recent/recent.list.php';
	/*$.ajax({
		url:g5_url+"/mobile/page/ajax/ajax.recent.list.php",
		method:"POST"
	}).done(function(data){
		//새리스트
		$(".grid").remove();
		var item = '<div class="list_item grid are-images-unloaded"></div>';
		$(".post").append(item);
		$(".grid").append(data);
		initpkgd();
		page=1;	
	});*/
}

$(function() {

    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));

});
function removeDebug(){
	$("#debug").removeClass("active");
}
</script>

<?php
include_once(G5_PATH."/tail.sub.php");
?>