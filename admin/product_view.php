<?php
include_once ("./_common.php");
include_once(G5_PATH."/admin/admin.head.php");
$view = sql_fetch("select *,p.mb_id as `user_id` from `product` as p left join `product_blind` as pb on p.pd_id = pb.pd_id where p.pd_id = '{$pd_id}'");
$file = explode(",",$view["pd_images"]);
$basepath = G5_DATA_URL."/product/";
?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1>게시물 보기</h1>
        </header>
        <article>
            <div class="model_list">
                <table class="view_tb">
                    <tr>
                        <th>구분</th>
                        <td><?php echo ($view["pd_type"]==1)?"물건":"능력";?><?php echo ($view["pd_type2"]==1)?"[팝니다]":"[삽니다]";?></td>
                        <th>카테고리</th>
                        <td><?php echo $view["pd_cate"]."/".$view["pd_cate2"];?></td>
                        <th>조회수</th>
                        <td><?php echo $view["pd_hits"];?></td>
                    </tr>
                    <tr>
                        <th>등록자</th>
                        <td><?php echo $view["user_id"];?></td>
                        <th>등록일</th>
                        <td><?php echo $view["pd_date"];?></td>
                        <th>최종수정일</th>
                        <td><?php echo $view["pd_update"];?></td>
                    </tr>
                    <tr>
                        <th>검색어(제목)</th>
                        <td colspan="5"><?php echo $view["pd_tag"];?></td>
                    </tr>
                    <tr>
                        <th>상세내용</th>
                        <td colspan="5"><?php echo $view["pd_content"];?></td>
                    </tr>
                    <?php if(count($file)>0){?>
                    <tr>
                        <th>이미지</th>
                        <td colspan="5">
                            <?php
                            for($i=0;$i<count($file);$i++){
                            ?>
                                <img src="<?php echo $basepath.$file[$i];?>" alt="" style="width:260px;">
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } if($view["pd_video"]){?>
                    <tr>
                        <th>영상</th>
                        <td colspan="5">
                            <video controls  src="<?php echo G5_DATA_URL."/product/".$view["pd_video"];?>" width="400px" height="700px"></video>
                        </td>
                    </tr>
                    <?php }?>
                </table>
            </div>
            <div class="submit_gr no_print">
                <a href="<?php echo G5_URL."/admin/product_list.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id; ?>" class="adm-btn01">목록</a>
            </div>
        </article>
    </section>
</div>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
