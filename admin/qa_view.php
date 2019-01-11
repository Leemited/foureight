<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$bo_table = $_REQUEST["bo_table"];

$table = "g5_write_".$bo_table;

$view = sql_fetch("select * from g5_qa_content where `qa_id` = '{$qa_id}'");

$comment = sql_query("select * from g5_qa_content where `qa_parent` = '{$qa_id}' and qa_type = 1");
while($row = sql_fetch_array($comment)){
    $cm[] = $row;
}
?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1>1:1문의보기</h1>
        </header>
        <article>
            <div class="model_list">
                <div class="etc_gr">
                </div>
                <table class="view_tb">
                    <colgroup class="pcT2">
                        <col width="12%">
                        <col width="23%">
                        <col width="12%">
                        <col width="23%">
                    </colgroup>
<!--                    <colgroup class="mobileT2">
                        <col width="23%">
                        <col width="*">
                        <col width="*">
                        <col width="*">
                        <col width="*">
                    </colgroup>-->
                    <tbody class="pcT">
                    <tr>
                        <th>제목</th>
                        <td class="subject"><?php echo $view["qa_subject"];?></td>
                        <th>구분</th>
                        <td class="subject"><?php echo $view["qa_category"];?></td>
                    </tr>
                    <tr>
                        <th>작성자</th>
                        <td class="subject"><?php echo $view["qa_name"];?></td>
                        <th>작성일</th>
                        <td class="subject"><?php echo $view["qa_datetime"];?></td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td colspan="3" class="subject"><?php echo nl2br($view["qa_content"]);?></td>
                    </tr>
                    <?php if(count($cm)>0){?>
                    <tr>
                        <th>답변 내용</th>
                        <td colspan="3" class="subject">
                            <table class="qa_com_tb" style="width:100%">
                                <colgroup>
                                    <col width="*">
                                    <col width="18%">
                                </colgroup>
                            <?php for ($i=0;$i<count($cm);$i++) {?>
                                <tr style="border:none">
                                    <td class="subject" style="">
                                        <h2><?php echo $cm[$i]["qa_subject"]."[".$cm[$i]['qa_name']."]";?></h2>
                                        <p><?php echo nl2br($cm[$i]["qa_content"]); ?></p>
                                        <a href="qa_comment_delete.php?qa_id=<?php echo $cm[$i]['qa_id']."&qa_parent=".$cm[$i]["qa_parent"];?>" class="qa_delete"><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
                                    </td>
                                    <td>
                                        <p><?php echo $cm[$i]["qa_datetime"];?></p>
                                    </td>
                                </tr>
                            <?php
                            } ?>
                            </table>
                        </td>
                    </tr>
                    <?php }?>
                    </tbody>
                    <!--<tbody class="mobileT">
                    <tr>
                        <th>제목</th>
                        <td colspan="3" class="subject"><?php /*echo $view["wr_subject"];*/?></td>
                    </tr>
                    <tr>
                        <th>작성자</th>
                        <td colspan="3" class="subject"><?php /*echo $view["wr_name"];*/?></td>
                    </tr>
                    <tr>
                        <th>작성일</th>
                        <td colspan="3" class="subject"><?php /*echo $view["wr_datetime"];*/?></td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td colspan="3" class="con"><?php /*echo nl2br($view["wr_content"]);*/?></td>
                    </tr>
                    </tbody>-->
                </table>
            </div>
            <div class="submit_gr no_print">
                <?php if($view["qa_1"]){?>
                    <input type="button" onclick="location.href='<?php echo G5_URL."/admin/product_blind_view.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&pd_id=".$view["qa_1"]."&back=qa&qa_id=".$view["qa_id"]; ?>'" class="adm-btn01" value="사유보기" style="width:auto;border:none;">
                <?php }?>
                <input type="button" onclick="location.href='<?php echo G5_URL."/admin/qa_list.php?page=".$page."&sfl=".$sfl."&stx=".$stx; ?>'" class="adm-btn01" value="목록" style="width:auto;border:none;">
            </div>

            <header class="admin_title">
                <h1>답변달기</h1>
            </header>
            <article>
                <form action="<?php echo G5_URL?>/admin/qa_comment_update.php" method="post">
                    <input type="hidden" name="qa_id" value="<?php echo $view['qa_id']; ?>">
                    <input type="hidden" name="w" value="a">
                    <input type="hidden" name="page" value="<?php echo $page;?>">
                    <input type="hidden" name="sfl" value="<?php echo $sfl;?>">
                    <input type="hidden" name="stx" value="<?php echo $stx;?>">
                    <input type="hidden" name="mb_id" value="<?php echo $view["mb_id"];?>">
                <div class="model_list">
                    <table class="view_tbl">
                        <colgroup class="">
                            <col width="12%">
                            <col width="*">
                        </colgroup>
                        <tbody class="">
                            <tr>
                                <th>제목</th>
                                <td colspan="3" class="con">
                                    <input type="text" name="qa_subject" id="qa_subject" class="write_input01 grid_50">
                                </td>
                            </tr>
                            <tr>
                                <th>답변</th>
                                <td colspan="3" class="con">
                                    <textarea name="qa_content" id="qa_content" cols="30" rows="10"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="submit_gr no_print">
                    <input type="submit" value="답변" class="adm-btn01" style="width:auto;border:none;">
                </div>
                </form>
        </article>
    </section>
</div>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
