<?php 
include_once("./_common.php");
// 접근 권한 검사
if (!$member['mb_id'])
{
    alert('로그인이 필요합니다..', G5_MOBILE_URL.'/page/login_intro.php?sub=login&url=' . urlencode(G5_URL."/admin/"));
}else if ($member["mb_level"] < 10)
{
	alert("접근 권한이 없습니다.",G5_URL);
}
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_PATH."/head.sub.php");
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/default.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/admin.css">', 0);

$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 
if (preg_match('/chrome/', $userAgent)) {
	$browser = "other";
}else if (preg_match('/safari/', $userAgent)) {
	$browser = "other";
}else if (preg_match('/opera/', $userAgent)) {
	$browser = "other";
}
?>
<div class="modal"></div>
<header>
	<div class="top">
		<!-- <div class="logo" >
			<a href="<?php echo G5_URL?>/admin/"><img src="<?php echo G5_IMG_URL?>/" alt=""></a>
		</div> -->
		<div class="title">
			<a href="<?php echo G5_URL?>/admin/"><h2>ADMIN PAGE</h2></a>
		</div>
		<div class="clear"></div>
		<div class="loginfo">
			<ul>
				<li><a href="<?php echo G5_BBS_URL?>/logout.php">로그아웃</a></li>
				<li><a href="<?php echo G5_URL?>">HOMEPAGE</a></li>
			</ul>
		</div>
		<div class="top_bg"></div>
	</div>
	<div class="left">
		<div class="lnb">
			<ul data-accordion-group id="admin-menu">
				<li class="accordion" data-accordion>
					<div data-control class="list-title">카테고리관리</div>
					<div data-content class="list-item">
						<div><a href="<?php echo G5_URL."/admin/category_list.php"; ?>">카테고리 관리</a></div>
						<!-- <div><a href="<?php echo G5_URL."/admin/.php"; ?>">카테고리 예문 관리</a></div> -->
						<div><a href="<?php echo G5_URL."/admin/category_temp_list.php"; ?>">제안하기 관리</a></div>
					</div>
				</li>
				<li class="accordion" data-accordion>
					<div data-control class="list-title">홈페이지관리</div>
					<div data-content class="list-item">
						<div><a href="<?php echo G5_URL."/admin/filter_write.php"; ?>">금칙어관리</a></div>
						<div><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=help"; ?>">도움말관리</a></div>
					</div>
				</li>
				<li class="accordion" data-accordion>
					<div data-control class="list-title">게시물관리</div>
					<div data-content class="list-item">
						<div><a href="<?php echo G5_URL."/admin/product_list.php"; ?>">게시글 관리</a></div>
						<div><a href="<?php echo G5_URL."/admin/product_ad_list.php"; ?>">광고관리</a></div>
					</div>
				</li>
				<li class="accordion" data-accordion>
					<div data-control class="list-title">회원관리</div>
					<div data-content class="list-item">
						<div><a href="<?php echo G5_URL."/admin/member_list.php"; ?>">회원관리</a></div>
						<div><a href="<?php echo G5_URL."/admin/member_company_list.php"; ?>">사업자신청관리</a></div>
					</div>
				</li>
                <li class="accordion" data-accordion>
                    <div data-control class="list-title">고객센터</div>
                    <div data-content class="list-item">
                        <div><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=notice"; ?>">공지사항</a></div>
                        <div><a href="<?php echo G5_URL."/admin/qa_list.php?"; ?>">1:1문의</a></div>
                        <div><a href="<?php echo G5_URL."/admin/faq_list.php?fm_id=1"; ?>">faq</a></div>
                    </div>
                </li>
				<li class="accordion" data-accordion>
					<div data-control class="list-title">정산관리</div>
					<div data-content class="list-item">
						<div><a href="<?php echo G5_URL."/admin/order_list.php"; ?>">거래내역</a></div>
						<!--<div><a href="<?php /*echo G5_URL."/admin/order_delivery.php"; */?>">배송상태</a></div>-->
						<div><a href="<?php echo G5_URL."/admin/order_refund.php"; ?>">환불내역</a></div>
						<div><a href="<?php echo G5_URL."/admin/order_confirm.php"; ?>">정산</a></div>
					</div>
				</li>
			</ul>
		</div>
		<!-- <div class="left">
			<div><?php //echo visit("basic")?></div>
		</div> -->
		<div class="left_bg"></div>
	</div>
</header>
<header id="mobile_header">
	<h1><a href="<?php echo G5_URL; ?>/admin" title="HOME" class="logos"><img src="<?php echo G5_IMG_URL?>/logo.svg" alt=""></a></h1>
	<a href="javascript:mobile_menu('mobile_menu','right');" id="mobile_menu_btn" title="MENU"><img src="<?php echo G5_IMG_URL?>/ic_menu_2.svg" alt=""></a>
</header>
<div id="mobile_menu" class="mobile_menu">
	<span></span>
	<div>
		<div>     
			<div class="closeli" style="">ADMIN <span class="close"><i></i></span></div>
			<ul class="menu">
				<li class="back"><a href="javascript:mobile_back();">BACK</a></li>
				<?php if($is_member){ ?>
				<li>
					<?php if($member["mb_level"] >= 5){?>
					<a href="<?php echo G5_URL ?>">홈페이지</a>	
					<?php }?>
                    <a href="<?php echo G5_BBS_URL ?>/logout.php?sub=login">Logout</a>				
                </li>
				<?php }else{ ?>
                <li>
                    <a href="<?php echo G5_BBS_URL ?>/login.php?sub=login">LOGIN</a>				
                </li>
                <?php } ?>
				<li>
					<a href="#" onclick="javascript:mobile_sub_menu(this);">기본정보관리</a>
					<div>
						<ul>
							<li><a href="<?php echo G5_URL."/admin/home_info_write.php"; ?>">홈페이지 정보 관리</a></li>
							<li><a href="<?php echo G5_URL."/admin/tour_list.php"; ?>">주변여행지 관리</a></li>
							<li><a href="<?php echo G5_URL."/admin/room_list.php"; ?>">객실 목록</a></li>
						</ul>
					</div>
				</li>	
				<li>
					<a href="#" onclick="javascript:mobile_sub_menu(this);">예약 관리</a>
					<div>
						<ul>
                            <li><a href="<?php echo G5_URL."/admin/reservation_list.php"; ?>">예약 목록</a></li>		
						</ul>
					</div>
				</li>
				<li>
					<a href="#" onclick="javascript:mobile_sub_menu(this);">상담 관리</a>
					<div>
						<ul>
                            <li><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=free&ca_name="; ?>">상담 목록</a></li>		
						</ul>
					</div>
				</li>
				<li>
					<a href="#" onclick="javascript:mobile_sub_menu(this);">게시판 관리</a>
					<div>
						<ul>
                            <li><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=free&ca_name=공지사항"; ?>">공지사항</a></li>		
						</ul>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
