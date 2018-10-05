<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.fn.php");
include_once(G5_PATH."/admin/admin.head.php");

if(!$fm_id) {
    $fm_id = 1;
}

$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);

$sql_common = " from {$g5['faq_table']} where fm_id = '$fm_id' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row[cnt];

$sql = "select * $sql_common order by fa_order , fa_id ";
$result = sql_query($sql);

?>
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1>FAQ[등록]</h1>
        </header>
<!--        <div class="local_desc01 local_desc">
            <ol>
                <li>FAQ는 무제한으로 등록할 수 있습니다</li>
            </ol>
        </div>-->
        <div class="model_list">
            <table class="">
                <caption><?php echo $g5['title']; ?> 목록</caption>
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="*">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">번호</th>
                    <th scope="col">구분</th>
                    <th scope="col">제목</th>
                    <th scope="col">순서</th>
                    <th scope="col">관리</th>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++)
                {
                    $row1 = sql_fetch("select COUNT(*) as cnt from {$g5['faq_table']} where fm_id = '{$row['fm_id']}' ");
                    $cnt = $row1[cnt];

                    //$s_mod = icon("수정", "");
                    //$s_del = icon("삭제", "");

                    $num = $i + 1;

                    $bg = 'bg'.($i%2);
                    ?>

                    <tr class="<?php echo $bg; ?>">
                        <td class="td_num"><?php echo $num; ?></td>
                        <td><?php echo stripslashes($row['fa_cate']); ?></td>
                        <td><?php echo stripslashes($row['fa_subject']); ?></td>
                        <td class="td_num"><?php echo $row['fa_order']; ?></td>
                        <td class="td_mngsmall">
                            <a href="./faq_write.php?w=u&amp;fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span><img
                                        src="<?php echo G5_IMG_URL; ?>/ic_edit.png" alt=""></a>
                            <a href="./faq_delete.php?w=d&amp;fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>" onclick="return delete_confirm(this);"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span><img
                                        src="<?php echo G5_IMG_URL; ?>/ic_del.png" alt=""></a>
                        </td>
                    </tr>

                    <?php
                }

                if ($i == 0) {
                    echo '<tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>
                </tbody>
            </table>

        </div>
        <div class="submit_gr no_print">
            <a href="<?php echo G5_URL?>/admin/faq_write.php?fm_id=<?php echo $fm['fm_id']; ?>" class="adm-btn01">FAQ 등록</a>
        </div>
    </section>
</div>

<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>