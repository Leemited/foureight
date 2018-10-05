<?php
include_once('./_common.php');
include_once(G5_PATH."/admin/admin.head.php");
include_once(G5_PATH."/admin/admin.fn.php");

include_once(G5_EDITOR_LIB);

$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);

$html_title = 'FAQ '.$fm['fm_subject'];

if ($w == "u")
{
    $html_title .= " 수정";
    $readonly = " readonly";

    $sql = " select * from {$g5['faq_table']} where fa_id = '$fa_id' ";
    $fa = sql_fetch($sql);
    if (!$fa['fa_id']) alert("등록된 자료가 없습니다.");
}
else
    $html_title .= ' 항목 입력';

$g5['title'] = $html_title.' 관리';

?>
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1><?php echo $g5['title'] ;?></h1>
        </header>
        <article>
             <form name="frmfaqform" action="<?php echo G5_URL?>/admin/faq_update.php" onsubmit="return frmfaqform_check(this);" method="post">
             <div class="model_list">
                <input type="hidden" name="w" value="<?php echo $w; ?>">
                <input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">
                <input type="hidden" name="fa_id" value="<?php echo $fa_id; ?>">
                <input type="hidden" name="token" value="">

                    <table class="view_tb">
                        <colgroup>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="row"><lebel for="fa_cate">구분</lebel></th>
                            <td style="text-align: left;">
                                <select name="fa_cate" id="fa_cate" class="write_input02" required>
                                    <option value="">카테고리선택</option>
                                    <option value="주문결제" <?php if($fa["fa_cate"]=="주문결제"){echo "selected";}?>>주문결제</option>
                                    <option value="배송문의" <?php if($fa["fa_cate"]=="배송문의"){echo "selected";}?>>배송문의</option>
                                    <option value="취소/환불/반품" <?php if($fa["fa_cate"]=="취소/환불/반품"){echo "selected";}?>>취소/환불/반품</option>
                                    <option value="기타" <?php if($fa["fa_cate"]=="기타"){echo "selected";}?>>기타</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="fa_order">출력순서</label></th>
                            <td style="text-align:left">
                                <?php echo help('숫자가 작을수록 FAQ 페이지에서 먼저 출력됩니다.'); ?>
                                <input type="text" name="fa_order" value="<?php echo $fa['fa_order']; ?>" id="fa_order" class="write_input01" maxlength="10" >
                                <?php if ($w == 'u') { ?><a href="<?php echo G5_BBS_URL; ?>/faq.php?fm_id=<?php echo $fm_id; ?>" class="btn_frmline">내용보기</a><?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">질문</th>
                            <td><?php echo editor_html('fa_subject', get_text($fa['fa_subject'], 0)); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">답변</th>
                            <td><?php echo editor_html('fa_content', get_text($fa['fa_content'], 0)); ?></td>
                        </tr>
                        </tbody>
                    </table>


            </div>
            <div class="submit_gr">
                <input type="submit" value="<?php if($w){echo "수정";}else{echo "등록";}?>" class="adm-btn01" accesskey="s" style="width:auto;border:none">
                <input type="button" onclick="location.href='./faq_list.php?fm_id=<?php echo $fm_id; ?>'" class="adm-btn01" value="목록" style="width:auto;border:none">
            </div>
            </form>
        </article>
    </section>
</div>

<script>
function frmfaqform_check(f)
{
    errmsg = "";
    errfld = "";

    //check_field(f.fa_subject, "제목을 입력하세요.");
    //check_field(f.fa_content, "내용을 입력하세요.");

    if (errmsg != "")
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    <?php echo get_editor_js('fa_subject'); ?>
    <?php echo get_editor_js('fa_content'); ?>

    return true;
}

// document.getElementById('fa_order').focus(); 포커스 해제
</script>

<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>