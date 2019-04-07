<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.fn.php");
include_once(G5_PATH."/admin/admin.head.php");

if($sfl && $stx){
    $where = " and {$sfl} like '%{$stx}%'";
}

$total=sql_fetch("select count(*) as cnt from g5_qa_content where qa_type = '0' and qa_status != 3 {$where} ");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql = "select * from g5_qa_content where qa_type = '0' and qa_status != 3 {$where} order by qa_num limit {$start},{$rows}";
$query=sql_query($sql);
$j=0;
while($data=sql_fetch_array($query)){
    $list[$j]=$data;
    $list[$j]['num']=$total-($start)-$j;
    $j++;
}
?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1>1:1문의 관리</h1>
        </header>
        <article>
            <div class="model_list">
                <div class="search">
                    <div>
                        <form action="" method="get">
                            <input type="hidden" name="bo_table" value="<?php echo $bo_table;?>">
                            <select name="sfl" id="sfl" class="serch_input01">
                                <option value="">전체</option>
                                <option value="qa_subject">제목</option>
                                <option value="qa_content">내용</option>
                                <option value="qa_name">작성자</option>
                            </select>
                            <input type="text" name="stx" id="stx" class="serch_input02" value="<?php echo $stx;?>" placeholder="검색어를 입력하세요">
                            <div class="btn_gr">
                                <input type="submit" value="검색" class="search_btn" >
                            </div>
                        </form>
                    </div>
                </div>

                <div class="etc_gr">
                    <!-- <input type="button" value="선택삭제" >  -->
                    <h2 class="board_t"><?php echo $subject;?></h2>
                    <span class="total_list">전체 <?php echo count($list);?> | <?php echo $total;?></span>
                </div>
                <table>
                    <colgroup class="">
                        <col width="8%" class="">
                        <col width="*">
                        <col width="10%">
                        <col width="10%">
                        <col width="15%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="">번호</th>
                        <th>제목</th>
                        <th>아이디</th>
                        <th>상태</th>
                        <th>등록일</th>
                        <th>관리</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for($i=0;$i<count($list);$i++){
                        switch ($list[$i]['qa_status']){
                            case 1:
                                $status = "답변완료";
                                break;
                            case 0:
                                $status = "답변대기";
                                break;
                        }
                        ?>
                        <tr <?php if($list[$i]["qa_1"]){?>class="qa_blind"<?php } if($list[$i]["qa_category"]){?>class="qa_price"<?php } if($list[$i]["qa_status"]==1){?>style="background-color:#eee;" <?php }?> >
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/qa_view.php?page=".$page."&qa_id=".$list[$i]["qa_id"]."&sfl=".$sfl."&stx=".$stx; ?>'"><?php echo $list[$i]['num']; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/qa_view.php?page=".$page."&qa_id=".$list[$i]["qa_id"]."&sfl=".$sfl."&stx=".$stx; ?>'"><?php echo $list[$i]["qa_subject"];?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/qa_view.php?page=".$page."&qa_id=".$list[$i]["qa_id"]."&sfl=".$sfl."&stx=".$stx; ?>'"><?php echo $list[$i]["qa_name"]; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/qa_view.php?page=".$page."&qa_id=".$list[$i]["qa_id"]."&sfl=".$sfl."&stx=".$stx; ?>'"><?php echo $status; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/qa_view.php?page=".$page."&qa_id=".$list[$i]["qa_id"]."&sfl=".$sfl."&stx=".$stx; ?>'"><?php echo $list[$i]["qa_datetime"]; ?></td>
                            <td class="">
                                <a href="<?php echo G5_URL."/admin/qa_view.php?qa_id=".$list[$i]['qa_id']; ?>"><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
                                <a href="<?php echo G5_URL."/admin/qa_delete.php?qa_id=".$list[$i]['qa_id']; ?>" class=""><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
                            </td>

                        </tr>
                        <?php
                        $stat = "";
                    }
                    if(count($list)==0){
                        ?>
                        <tr>
                            <td colspan="6" class="text-center" style="padding:50px 0;">등록된 게시물이 없습니다.</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            <?php
            if($total_page>1){
                $start_page=1;
                $end_page=$total_page;
                if($total_page>5){
                    if($total_page<($page+2)){
                        $start_page=$total_page-4;
                        $end_page=$total_page;
                    }else if($page>3){
                        $start_page=$page-2;
                        $end_page=$page+2;
                    }else{
                        $start_page=1;
                        $end_page=5;
                    }
                }
                ?>
                <div class="num_list01">
                    <ul>
                        <?php if($page!=1){?>
                            <li class="prev"><a href="<?php echo G5_URL."/admin/qa_list.php?page=".($page-1)."&sfl=".$sfl."&stx=".$stx; ?>">&lt;</a></li>
                        <?php } ?>
                        <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                            <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/qa_list.php?page=".$i."&sfl=".$sfl."&stx=".$stx; ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                        <?php if($page<$total_page){?>
                            <li class="next"><a href="<?php echo G5_URL."/admin/qa_list.php?page=".($page+1)."&sfl=".$sfl."&stx=".$stx; ?>">&gt;</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php
            }
            ?>
            </div>
        </article>
    </section>
</div>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
