<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>1:1 문의</h2>
</div>
<div id="qa_list">
    <?php if($bo_table!="help"){?>
        <ul class="board_ul">
            <li onclick="location.href='<?php echo G5_BBS_URL?>/board.php?bo_table=notice'">공지사항</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1'">FAQ</li>
            <li class="active" >1:1문의</li>
        </ul>
        <div class="clear"></div>
    <?php } ?>
    <?php if ($category_option) { ?>
    <!-- 카테고리 시작 { -->
    <nav id="bo_cate">
        <h2><?php echo $qaconfig['qa_title'] ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <!-- } 카테고리 끝 -->
    <?php } ?>
    <div class="customer_info">
        <h2>고객센터 지원안내</h2>
        <p>09:00~18:00 (주말, 공휴일 휴무)</p>
        <p>이메일:mave01@naver.com</p>
        <p>업무시간 외 장애상담 (070-4090-4811)</p>
    </div>


    <div class="qa_container">

         <!-- 게시판 페이지 정보 및 버튼 시작 { --
        <div class="bo_fx">
            <!--<div id="bo_list_total">
                <span>Total <?php /*echo number_format($total_count) */?>건</span>
                <?php /*echo $page */?> 페이지
            </div>-

            <?php if ($admin_href || $write_href) { ?>
            <ul class="btn_bo_user">
                <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_b02">관리자</a></li><?php } ?>
                <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02">문의등록</a></li><?php } ?>
            </ul>
            <?php } ?>
        </div>
        -- } 게시판 페이지 정보 및 버튼 끝 -->

        <form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post">
        <input type="hidden" name="stx" value="<?php echo $stx; ?>">
        <input type="hidden" name="sca" value="<?php echo $sca; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">

        <?php if ($is_checkbox) { ?>
        <div id="list_chk">
            <label for="chkall" class="sound_only">게시물 전체</label>
            <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
        </div>
        <?php } ?>

        <div class="ul_wrap ul_01">
            <ul>
                <?php
                for ($i=0; $i<count($list); $i++) {
                ?>
                <li class="bo_li<?php if ($is_checkbox) echo ' bo_adm'; ?>">
                    <?php if ($is_checkbox) { ?>
                    <div class="li_chk">
                        <label for="chk_qa_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject']; ?></label>
                        <input type="checkbox" name="chk_qa_id[]" value="<?php echo $list[$i]['qa_id'] ?>" id="chk_qa_id_<?php echo $i ?>">
                    </div>
                    <?php } ?>
                    <div class="li_title">
                        <a href="<?php echo $list[$i]['view_href']; ?>">
                            <strong>[<?php echo $list[$i]['category']; ?>]</strong>
                            <?php echo $list[$i]['subject']; ?>
                        </a>
                    </div>
                    <div class="li_info">
                        <!--<span><?php /*echo $list[$i]['num']; */?></span>-->
                        <span><?php echo $list[$i]['name']; ?></span>
                        <span><?php echo $list[$i]['date']; ?></span>
                        <span><?php echo $list[$i]['icon_file']; ?></span>
                    </div>
                    <div class="li_stat <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($list[$i]['qa_status'] ? '답변완료' : '답변대기'); ?></div>
                </li>
                <?php
                }
                ?>

                <?php if ($i == 0) { echo '<li class="empty_list"><div>게시물이 없습니다.</div></li>'; } ?>
            </ul>
        </div>


        <div class="bo_fx">
            <?php if ($is_checkbox) { ?>
            <ul class="btn_bo_adm">
                <li><input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value"></li>
            </ul>
            <?php } ?>

        </div>
        </form>
        <ul class="btn_bo_user">
            <?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>" class="btn_b01">목록</a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02">문의등록</a></li><?php } ?>
        </ul>
        <div class="clear"></div>
    </div>
    <!-- 게시판 검색 시작 { -->
    <!--<fieldset id="bo_sch">
        <legend>게시물 검색</legend>

        <form name="fsearch" method="get">
            <input type="hidden" name="sca" value="<?php /*echo $sca */?>">
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" value="<?php /*echo stripslashes($stx) */?>" required id="stx" class="input01" size="" maxlength="20" placeholder="검색어">
            <input type="submit" value="" class="search_btn">
        </form>
    </fieldset>-->
    <!-- } 게시판 검색 끝 -->
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $list_pages;  ?>



<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fqalist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]")
            f.elements[i].checked = sw;
    }
}

function fqalist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
            return false;
    }

    return true;
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->