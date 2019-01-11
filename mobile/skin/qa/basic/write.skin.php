<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
if($pd_id){
    $pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
}
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>1:1 문의</h2>
</div>
<section id="bo_w">
    <?php if($bo_table!="help"){?>
        <ul class="board_ul">
            <li onclick="location.href='<?php echo G5_BBS_URL?>/board.php?bo_table=notice'">공지사항</li>
            <li onclick="location.href='<?php echo G5_BBS_URL?>/faq.php?fm_id=1'">FAQ</li>
            <li class="active" >1:1문의</li>
        </ul>
        <div class="clear"></div>
    <?php } ?>
    <div class="inquiry_info">
        <h2>1:1문의 시 친절히 답변해 드립니다.</h2>
        <p>궁금하신 사항이 있으시면 1대1 문의를 통해 질문해 주십시오.<br>최선을 다해 답변해 드리겠습니다.</p>
    </div>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="qa_1" value="<?php echo $pd_id;?>">
        <?php
        $option = '';
        $option_hidden = '';
        $option = '';

        if ($is_dhtml_editor) {
            $option_hidden .= '<input type="hidden" name="qa_html" value="1">';
        } else {
            $option .= "\n".'<input type="checkbox" id="qa_html" name="qa_html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'."\n".'<label for="qa_html">html</label>';
        }

        echo $option_hidden;
        ?>
        <?php if($pd_id){?>
            <input type="hidden" value="게시물" name="qa_category">
        <?php }else{ ?>
        <?php if ($category_option) { ?>
            <div class="tbl_frm01 tbl_wrap">
                <select name="qa_category" id="qa_category" required class="write_input" >
                    <option value="">선택하세요</option>
                    <?php echo $category_option ?>
                </select>
            </div>
        <?php } ?>
        <?php } ?>
        <?php if ($is_email) { ?>
            <div class="tbl_frm01 tbl_wrap">
                <input type="email" name="qa_email" value="<?php echo get_text($write['qa_email']); ?>" id="qa_email" <?php echo $req_email; ?> class="<?php echo $req_email.' '; ?> write_input email" maxlength="100" placeholder="이메일">
                <input type="checkbox" name="qa_email_recv" value="1" id="qa_email_recv" <?php if($write['qa_email_recv']) echo 'checked="checked"'; ?>>
                <label for="qa_email_recv" class="qa_email_label">이메일로 답변받기 <img src="<?php echo G5_IMG_URL?>/ic_write_check.svg" alt=""></label>
            </div>
        <?php } ?>
        <?php if ($is_hp) { ?>
            <div class="tbl_frm01 tbl_wrap">
                <input type="text" name="qa_hp" value="<?php echo ($member["mb_hp"])?$member["mb_hp"]:get_text($write['qa_hp']); ?>" id="qa_hp" <?php echo $req_hp; ?> class="<?php echo $req_hp.' '; ?> write_input" size="30" placeholder="연락처">
                <?php if($qaconfig['qa_use_sms']) { ?>
                <input type="checkbox" name="qa_sms_recv" value="1" <?php if($write['qa_sms_recv']) echo 'checked="checked"'; ?>> 답변등록 SMS알림 수신
                <?php } ?>
            </div>
        <?php } ?>
        <div class="tbl_frm01 tbl_wrap">
            <input type="text" name="qa_subject" value="<?php echo ($pd_id)?$pro["pd_tag"]."의 블라인드 사유 문의":get_text($write['qa_subject']); ?>" id="qa_subject" required class="write_input" maxlength="255" placeholder="문의제목">
        </div>
        <div class="tbl_frm01 tbl_wrap">
                <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
        </div>
        <?php if(!$pd_id) {?>
        <div class="tbl_frm01 tbl_wrap">
                <input type="file" name="bf_file[1]" title="파일첨부 1 :  용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능" class="frm_file write_input">
                <?php if($w == 'u' && $write['qa_file1']) { ?>
                <input type="checkbox" id="bf_file_del1" name="bf_file_del[1]" value="1"> <label for="bf_file_del1"><?php echo $write['qa_source1']; ?> 파일 삭제</label>
                <?php } ?>
        </div>
        <div class="tbl_frm01 tbl_wrap">
                <input type="file" name="bf_file[2]" title="파일첨부 2 :  용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능" class="frm_file write_input">
                <?php if($w == 'u' && $write['qa_file2']) { ?>
                <input type="checkbox" id="bf_file_del2" name="bf_file_del[2]" value="1"> <label for="bf_file_del2"><?php echo $write['qa_source2']; ?> 파일 삭제</label>
                <?php } ?>
        </div>
        <?php }?>
        <div class="btn_confirm">
            <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_b02">
            <a href="<?php echo $list_href; ?>" class="btn_b01">목록</a>
        </div>
    </form>

    <script>
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "2";
            else
                obj.value = "1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.qa_subject.value,
                "content": f.qa_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.qa_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_qa_content) != "undefined")
                ed_qa_content.returnFalse();
            else
                f.qa_content.focus();
            return false;
        }

        <?php if ($is_hp) { ?>
        var hp = f.qa_hp.value.replace(/[0-9\-]/g, "");
        if(hp.length > 0) {
            alert("휴대폰번호는 숫자, - 으로만 입력해 주십시오.");
            return false;
        }
        <?php } ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    $(function () {
       $("body").css("background-color","#e7e7e7");
    });
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->