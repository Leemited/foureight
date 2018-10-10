<?php
include_once ("./_common.php");
include_once(G5_PATH."/admin/admin.head.php");

if($order){
    $orders = " order by {$order} {$desc}";
}else{
    $orders = " order by `pd_date` desc ";
}

if(!$pd_type){ $pd_type = 1;}

if($pd_type){
    $where .= " and pd_type = '{$pd_type}'";
}

if($sch_id) {
    $where .= " and mb_id = '{$sch_id}'";
}

$total=sql_fetch("select count(*) as cnt from `product` where 1 {$where} {$search} ");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select * from `product` where 1 {$where} {$search} {$orders} limit {$start},{$rows}";
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
            <h1>게시물관리</h1>
        </header>
        <article>
            <div class="model_list">
                <div class="search">
                    <div>
                        <form action="" method="get">
                            <input type="hidden" name="bo_table" value="<?php echo $bo_table;?>">
                            <select name="sfl" id="sfl" class="serch_input01">
                                <option value="">전체</option>
                                <option value="wr_subject">제목</option>
                                <option value="wr_content">내용</option>
                                <option value="wr_subject||wr_content">제목+내용</option>
                                <option value="wr_name">작성자</option>
                                <option value="wr_name">가격</option>
                            </select>
                            <input type="text" name="stx" id="stx" class="serch_input02" value="<?php echo $stx;?>" placeholder="검색어를 입력하세요">
                            <div class="btn_gr">
                                <input type="submit" value="검색" class="search_btn" >
                            </div>
                        </form>
                    </div>
                </div>

                <div class="etc_gr">
                    <ul class="cate">
                        <li onclick="location.href='<?php echo G5_URL?>/admin/product_list.php?pd_type=1'" <?php if($pd_type==1){?>class="active"<?php } ?>>물품</li>
                        <li onclick="location.href='<?php echo G5_URL?>/admin/product_list.php?pd_type=2'" <?php if($pd_type==2){?>class="active"<?php } ?>>능력</li>
                    </ul>

                    <!-- <input type="button" value="선택삭제" >  -->
                    <h2 class="board_t"><?php echo $subject;?></h2>
                    <span class="total_list">전체 | <?php echo count($list);?></span>
                </div>
                <table>
                    <colgroup class="">
                        <col width="8%" class="">
                        <col width="10%">
                        <col width="*" class="">
                        <col width="20%" class="">
                        <col width="13%" class="">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="">번호</th>
                        <th >구분</th>
                        <th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/product_list.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type;?>','<?php echo "&order=pd_cate,pd_cate2"; ?>','desc');">카테고리</th>
                        <th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/product_list.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type;?>','<?php echo "&order=mb_id"; ?>','desc');">등록자</th>
                        <th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/product_list.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type;?>','<?php echo "&order=pd_date"; ?>','desc');">등록일</th>
                        <th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/product_list.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type;?>','<?php echo "&order=pd_blind"; ?>','desc');">신고수</th>
                        <th>관리</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for($i=0;$i<count($list);$i++){
                        ?>
                        <tr <?php if($list[$i]["pd_blind"]>=10){ ?>class="blind"<?php } ?>>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_view.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]['num']; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_view.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>'"><?php echo ($list[$i]["pd_type"]==1)?"물건":"능력";?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_view.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>'" ><?php echo "[".$list[$i]["pd_cate"]."]".$list[$i]["pd_cate2"]; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_view.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>'" ><?php echo $list[$i]["mb_id"]; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_view.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]["pd_date"]; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_view.php?page=".$page."&pd_id=".$list[$i]["pd_id"]."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]["pd_blind"]; ?></td>
                            <td class="">
                                <?php if($list[$i]["pd_blind"]>=10){ ?>
                                <a href="<?php echo G5_URL."/admin/product_blind_view.php?page=".$page."&pd_id=".$list[$i]['pd_id']; ?>"><img src="<?php echo G5_IMG_URL?>/ic_blind.png" alt=""></a>
                                <?php }else{ ?>
                                <a href="javascript:fnBlind('<?php echo G5_URL."/admin/product_blind_update.php?page=".$page."&pd_id=".$list[$i]['pd_id']; ?>')"><img src="<?php echo G5_IMG_URL?>/ic_blind_edit.png" alt=""></a>
                                <?php }?>
                                <a href="<?php echo G5_URL."/admin/product_view.php?page=".$page."&pd_id=".$list[$i]['pd_id']; ?>"><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
                                <a href="<?php echo G5_URL."/admin/product_delete.php?page=".$page."&pd_id=".$list[$i]['pd_id']; ?>" class=""><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
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
                            <li class="prev"><a href="<?php echo G5_URL."/admin/product_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>">&lt;</a></li>
                        <?php } ?>
                        <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                            <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/product_list.php?bo_table=".$bo_table."&page=".$i."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                        <?php if($page<$total_page){?>
                            <li class="next"><a href="<?php echo G5_URL."/admin/product_list.php?bo_table=".$bo_table."&page=".($page+1)."&sfl=".$sfl."&stx=".$stx."&sch_id=".$sch_id."&pd_type=".$pd_type."&order=".$order."&desc=".$desc; ?>">&gt;</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php
            }
            ?>
            </div>
            <!-- <div class="submit_gr no_print">
				<a href="<?php echo G5_URL."/admin/board_write.php?bo_table=".$bo_table; ?>" class="adm-btn01">글쓰기</a>
			</div> -->
        </article>
    </section>
</div>
<script>
function fnOrder(url,order,desc){
    var desc1 = "<?php echo $desc;?>";
    if(desc == desc1){
        desc = "asc";
    }
    location.href=url+order+"&desc="+desc;
}
function fnBlind(url){
    if(confirm("해당 게시글을 블라인드 처리하시겠습니까?")){
        location.href=url;
    }else{
        return false;
    }
}
</script>
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
