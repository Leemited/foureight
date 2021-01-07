<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$faq_skin_url.'/style.css">', 0);
?>

<!-- FAQ 시작 { -->
<?php
// 상단 HTML
echo '<div id="faq_hhtml">'.conv_content($fm['fm_mobile_head_html'], 1).'</div>';
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>자주하는 질문</h2>
</div>
<?php
/*if( count($faq_master_list) ){
*/?><!--
<nav id="bo_cate">
    <h2>자주하시는질문 분류</h2>
    <ul id="bo_cate_ul">
        <?php
/*        foreach( $faq_master_list as $v ){
            $category_msg = '';
            $category_option = '';
            if($v['fm_id'] == $fm_id){ // 현재 선택된 카테고리라면
                $category_option = ' id="bo_cate_on"';
                $category_msg = '<span class="sound_only">열린 분류 </span>';
            }
        */?>
        <li><a href="<?php /*echo $category_href;*/?>?fm_id=<?php /*echo $v['fm_id']*/?>" <?php /*echo $category_option;*/?> ><?php /*echo $category_msg.$v['fm_subject'];*/?></a></li>
        <?php
/*        }
        */?>
    </ul>
</nav>
--><?php /*} */?>

<div id="faq_wrap" class="faq_<?php echo $fm_id; ?>">
    <?php if($bo_table!="help"){?>
        <ul class="board_ul">
            <li onclick="location.href='<?php echo G5_BBS_URL?>/board.php?bo_table=notice'">공지사항</li>
            <li class="active"  >FAQ</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/qalist.php'">1:1문의</li>
        </ul>
        <div class="clear"></div>
    <?php } ?>
    <div class="faq_cate">
        <ul>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1';" <?php if($fa_cate==""){echo "class='active'";}?>>전체</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1&fa_cate=주문결제';" <?php if($fa_cate=="주문결제"){echo "class='active'";}?>>주문결제</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1&fa_cate=배송문의';" <?php if($fa_cate=="배송문의"){echo "class='active'";}?>>배송문의</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1&fa_cate=취소/환불/반품';" <?php if($fa_cate=="취소/환불/반품"){echo "class='active'";}?>>취소/환불/반품</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1&fa_cate=회원관리';" <?php if($fa_cate=="회원관리"){echo "class='active'";}?>>회원관리</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1&fa_cate=이용안내';" <?php if($fa_cate=="이용안내"){echo "class='active'";}?>>이용안내</li>
        </ul>
        <div class="clear"></div>
    </div>
    <!--<div id="bo_sch">
        <form name="faq_search_form" method="get">
            <input type="hidden" name="fm_id" value="<?php /*echo $fm_id;*/?>">
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" value="<?php /*echo $stx;*/?>" id="stx" class="input01" size="15" maxlength="15" placeholder="검색어">
            <input type="submit" value="" class="search_btn">
        </form>
    </div>-->
    <?php // FAQ 내용
    if( count($faq_list) ){
    ?>
    <section id="faq_con">
        <h2><?php echo $g5['title']; ?> 목록</h2>
        <ol>
            <?php
            foreach($faq_list as $key=>$v){
                if(empty($v))
                    continue;
            ?>
            <li>
                <div class="left">
                    <h3><a href=""><p><span><?php echo $v["fa_cate"];?></span></p></a></h3>
                </div>
                <div class="right">
                    <h3><a href="#none" onclick="return faq_open(this);"><?php echo conv_content($v['fa_subject'], 1); ?></a></h3>
                </div>
                <div class="clear"></div>
                <div class="con_inner ">
                    <div class="right-align">
                    <?php echo nl2br($v['fa_content']); ?>
                    <!--<div class="con_closer"><button type="button" class="closer_btn"><img src="<?php /*echo G5_IMG_URL; */?>/ic_close_b.png" alt=""></button></div>-->
                    </div>
                    <div class="clear"></div>
                </div>
            </li>
            <?php
            }
            ?>
        </ol>
    </section>
    <?php

    } else {
        if($stx){
            echo '<p class="empty_list"><div>검색된 게시물이 없습니다.</p>';
        } else {
            echo '<div class="empty_table"><div>등록된 FAQ가 없습니다.';
            if($is_admin)
                echo '<br><a href="'.G5_ADMIN_URL.'/faqmasterlist.php">FAQ를 새로 등록하시려면 FAQ관리</a> 메뉴를 이용하십시오.';
            echo '</div></div>';
        }
    }
    ?>
    <?php echo get_paging($page_rows, $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>
</div>


<?php
// 하단 HTML
echo '<div id="faq_thtml">'.conv_content($fm['fm_mobile_tail_html'], 1).'</div>';
?>

<!-- } FAQ 끝 -->

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<script>
$(function() {
    $(".closer_btn").on("click", function() {
        $(this).parent().parent().parent().removeClass("view");
        $(this).closest(".con_inner").slideToggle();
    });
});

function faq_open(el)
{
    var $con = $(el).closest("li").find(".con_inner");
    $("#faq_con li").not($(el).closest("li")).removeClass("view");
    if($con.is(":visible")) {
        $con.slideUp();
        $(el).closest("li").removeClass("view");
    } else {
        $(el).closest("li").addClass("view");
        $("#faq_con .con_inner:visible").css("display", "none");

        $con.slideDown(
            function() {
                // 이미지 리사이즈
                $con.viewimageresize2();
            }
        );
    }

    return false;
}
</script>
<div class="copyright2 <?php if($_SESSION["type1"]=="" || $_SESSION["type1"]==2){echo "bg2";}?>" style="margin-bottom:12vw;">
    <h2>디자인율 | 48</h2>
    <p>대표 : 김용호</p><p>사업자등록번호 : 541-44-00091</p><p>통신판매신고번호 : 제 2018-충북청주-1575 호</p><p>대표전화 : 070-4090-4811</p>
    <p style="padding-bottom:4vw">소재지 : 충청북도 청주시 흥덕구 <br>풍산로133번길 48, 304호(복대동)</p>
</div>