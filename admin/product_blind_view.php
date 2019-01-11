<?php
include_once ("./_common.php");
include_once(G5_PATH."/admin/admin.head.php");
$res = sql_query("select * from `product_blind` where pd_id = '{$pd_id}'");
while($row = sql_fetch_array($res)){
    $list[] = $row;
}
?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1>블라인드 사유</h1>
        </header>
        <article>
            <div class="model_list">
                <table>
                    <colspan>
                        <col width="10%">
                        <col width="*">
                        <col width="20%">
                        <col width="15%">
                    </colspan>
                    <tr>
                        <th>번호</th>
                        <th>사유</th>
                        <th>신고자</th>
                        <th>신고일</th>
                    </tr>
                    <?php for($i=0;$i<count($list);$i++){
                    ?>
                        <tr>
                            <td><?php echo count($list)-$i;?></td>
                            <td><?php echo $list[$i]["blind_content"];?></td>
                            <td><?php echo $list[$i]["mb_id"];?></td>
                            <td><?php echo $list[$i]["blind_date"];?></td>
                        </tr>
                    <?php
                    }
                    if(count($list) == 0){
                    ?>
                        <tr>
                            <td colspan="4">사유가 검색되지 않거나 관리자가 직접 블라인드 처리 하였습니다.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <div class="submit_gr no_print">
                <?php if($back=="qa"){?>
                    <a href="<?php echo G5_URL."/admin/product_blind_reset.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&qa_id=".$qa_id."&pd_id=".$pd_id; ?>" class="adm-btn01">블라인드 해제</a>
                    <a href="<?php echo G5_URL."/admin/qa_view.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&qa_id=".$qa_id; ?>" class="adm-btn01">돌아가기</a>
                <?php }else{ ?>
                    <a href="<?php echo G5_URL."/admin/product_list.php?"; ?>" class="adm-btn01">게시글 목록</a>
                <?php }?>
            </div>
        </article>
    </section>
</div>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
