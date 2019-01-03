<?php
include_once ("./_common.php");
include_once(G5_PATH."/admin/admin.head.php");

if($order){
    $orders = " order by {$order} {$desc}";
}else{
    $orders = " order by `ad_id` desc ";
}

if($ad_cate){
    $where .= " and ad_cate = '{$ad_cate}'";
}

$total=sql_fetch("select count(*) as cnt from `product_ad` where 1 {$where} {$search} ");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select * from `product_ad` where 1 {$where} {$search} {$orders} limit {$start},{$rows}";
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
            <h1>광고관리</h1>
        </header>
        <article>
            <div class="model_list">
                <div class="search">
                    <div>
                        <form action="" method="get">
                            <input type="hidden" name="bo_table" value="<?php echo $bo_table;?>">
                            <select name="sfl" id="sfl" class="serch_input01">
                                <option value="">전체</option>
                                <option value="ad_subject">제목</option>
                                <option value="ad_con">내용</option>
                                <option value="ad_from">시작일</option>
                                <option value="ad_to">종료일</option>
                            </select>
                            <input type="text" name="stx" id="stx" class="serch_input02" value="<?php echo $stx;?>" placeholder="검색어를 입력하세요">
                            <div class="btn_gr">
                                <input type="submit" value="검색" class="search_btn" >
                            </div>
                        </form>
                    </div>
                </div>

                <div class="etc_gr">
                    <!--<ul class="cate">
                        <li onclick="location.href='<?php /*echo G5_URL*/?>/admin/product_ad_list.php?pd_type=1'" <?php /*if($pd_type==1){*/?>class="active"<?php /*} */?>>물품</li>
                        <li onclick="location.href='<?php /*echo G5_URL*/?>/admin/product_ad_list.php?pd_type=2'" <?php /*if($pd_type==2){*/?>class="active"<?php /*} */?>>능력</li>
                    </ul>-->

                    <!-- <input type="button" value="선택삭제" >  -->
                    <h2 class="board_t"><?php echo $subject;?></h2>
                    <span class="total_list">전체 | <?php echo count($list);?></span>
                </div>
                <table>
                    <colgroup class="">
                        <col width="8%" class="">
                        <col width="10%">
                        <col width="10%" class="">
                        <col width="20%" class="">
                        <col width="*" class="">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="">번호</th>
                        <th >구분</th>
                        <th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/product_ad_list.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=pd_cate,pd_cate2"; ?>','desc');">카테고리</th>
                        <th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/product_ad_list.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=mb_id"; ?>','desc');">제목</th>
                        <th class="" >설명</th>
                        <th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/product_ad_list.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=pd_blind"; ?>','desc');">등록일</th>
                        <th>관리</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for($i=0;$i<count($list);$i++){
                        $date = explode(" ", $list[$i]["ad_date"]);
                        $today = date("Y-m-d");
                        if($date == $today){
                            $dates = $date[1];
                        }else{
                            $dates = $date[0];
                        }
                        $sql = "select cate_name from `categorys` where ca_id = '{$list[$i]["ad_cate"]}'";
                        $cate = sql_fetch($sql);
                        $cate = ($cate["cate_name"])?$cate["cate_name"]:"선택된 카테고리 없음";
                        $sql = "select cate_name from `categorys` where ca_id = '{$list[$i]["ad_cate2"]}'";
                        $cate2 = sql_fetch($sql);
                        $cate2 = ($cate2["cate_name"])?$cate2["cate_name"]:"선택된 카테고리 없음";

                        ?>
                        <tr <?php if($list[$i]["pd_blind"]>=10){ ?>class="blind"<?php } ?>>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_ad_view.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]['num']; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_ad_view.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo ($list[$i]["ad_type"]==1)?"물건":"능력";?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_ad_view.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'" ><?php echo $cate." > ".$cate2; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_ad_view.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'" ><?php echo $list[$i]["ad_subject"]; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_ad_view.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]["ad_con"]; ?></td>
                            <td class="" onclick="location.href='<?php echo G5_URL."/admin/product_ad_view.php?page=".$page."&ad_id=".$list[$i]["ad_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $dates; ?></td>
                            <td class="">
                                <a href="<?php echo G5_URL."/admin/product_ad_write.php?ad_id=".$list[$i]['ad_id']; ?>"><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
                                <a href="<?php echo G5_URL."/admin/product_ad_delete.php?ad_id=".$list[$i]["ad_id"]; ?>" class=""><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
                            </td>

                        </tr>
                        <?php
                        $stat = "";
                    }
                    if(count($list)==0){
                        ?>
                        <tr>
                            <td colspan="7" class="text-center" style="padding:50px 0;">등록된 광고가 없습니다.</td>
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
                                <li class="prev"><a href="<?php echo G5_URL."/admin/product_ad_list.php?page=".($page-1)."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>">&lt;</a></li>
                            <?php } ?>
                            <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                                <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/product_ad_list.php?page=".$i."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <?php if($page<$total_page){?>
                                <li class="next"><a href="<?php echo G5_URL."/admin/product_ad_list.php?page=".($page+1)."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>">&gt;</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
             <div class="submit_gr no_print">
				<a href="<?php echo G5_URL."/admin/product_ad_write.php"; ?>" class="adm-btn01">등록</a>
			</div> 
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
</script>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
