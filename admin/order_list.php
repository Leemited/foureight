<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

if($sfl && $stx){
    $sfl2 = explode("||",$sfl);
    for($i=0;$i<count($sfl2);$i++){
        $search .= " and {$sfl2[$i]} like '%{$stx}%'";
    }
}


$total=sql_fetch("select count(*) as cnt from `order` as o left join `product` as p on o.pd_id = p.pd_id where od_status = 1 and od_pay_status = 1 {$where} {$search} ");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select *,p.mb_id as pd_mb_id,o.mb_id as mb_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where od_status = 1 and od_pay_status = 1 {$where} {$search} order by `od_id` desc limit {$start},{$rows}";
$query=sql_query($sql);
$j=0;
while($data=sql_fetch_array($query)){
    $list[$j]=$data;
    $list[$j]['num']=$total-($start)-$j;
    $j++;
}

$title = "거래 목록";
?>
<style>
    table tr{border-left:none !important}
</style>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1><?php echo $title;?></h1>
            <p></p>
        </header>
        <article>
            <div class="model_list">
                <div class="search">
                    <div>
                        <form action="" method="get">
                            <input type="hidden" name="bo_table" value="<?php echo $bo_table;?>">
                            <select name="sfl" id="sfl" class="serch_input01">
                                <option value="">전체</option>
                                <option value="o.mb_id||p.mb_id" <?php if($sfl=="o.mb_id||p.mb_id"){echo "selected";}?>>아이디</option>
                                <option value="o.pay_oid" <?php if($sfl=="o.pay_oid"){echo "selected";}?>>결제번호</option>
                                <option value="o.od_id" <?php if($sfl=="o.od_id"){echo "selected";}?>>주문번호</option>
                                <option value="p.pd_tag" <?php if($sfl=="p.pd_tag"){echo "selected";}?>>제목</option>
                                <option value="p.mb_id" <?php if($sfl=="p.mb_id"){echo "selected";}?>>판매자</option>
                                <option value="o.mb_id" <?php if($sfl=="o.mb_id"){echo "selected";}?>>구매자</option>
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
                    <span class="total_list">전체 | <?php echo count($list);?></span>
                </div>
                <table>
                    <colgroup class="md_none">
                        <col width="6%" class="md_none">
                        <col width="5%" class="md_none">
                        <col width="*">
                        <col width="18%" class="md_none">
                        <col width="18%" class="md_none">
                        <col width="10%" class="md_none">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="md_none">번호</th>
                        <th class="md_none">구분</th>
                        <th>구매제품</th>
                        <th class="md_none">구매자</th>
                        <th class="md_none">판매자</th>
                        <th class="md_none">가격</th>
                        <th class="md_none">상태</th>
                        <th class="md_none">관리</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for($i=0;$i<count($list);$i++){
                        if($list[$i]["od_direct_status"]==0 || $list[$i]["od_direct_status"]==""){
                            if($list[$i]["pd_type"]==1){
                                $pd_price = number_format($list[$i]["od_price"])." 원";
                            }else{
                                $pd_price = number_format($list[$i]["pd_price"]+$list[$i]["pd_price2"])." 원";
                            }
                        }else{
                            $pd_price = "직거래";
                        }
                        if($list[$i]["od_status"]==1){
                            $od_status = "주문완료";
                            if($list[$i]["od_pay_status"]==1){
                                $od_status = "결제완료";
                                if($list[$i]["delivery_name"]!=""){
                                    $od_status="배송중";
                                }
                            }
                        }
                        if($list[$i]["od_status"]==1 && $list[$i]["od_step"]==1 && $list[$i]["pd_type"]==2){
                            $od_status = "계약중";
                        }
                        if($list[$i]["od_fin_status"]==1){
                            $od_status = "거래완료";
                        }
                        if($list[$i]["od_status"]==10){
                            $od_status = "거래취소";
                        }


                        ?>
                        <tr>
                            <td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/order_view.php?od_id=".$list[$i]['od_id']."&page=".$page; ?>'"><?php echo $list[$i]['num']; ?></td>
                            <td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/order_view.php?od_id=".$list[$i]['od_id']."&page=".$page; ?>'"><?php echo ($list[$i]['od_pd_type'] == 1)?"물건":"능력"; ?></td>
                            <td class="subject" onclick="location.href='<?php echo G5_URL."/admin/order_view.php?od_id=".$list[$i]['od_id']."&page=".$page; ?>'"><?php echo $list[$i]['pd_tag']; ?></td>
                            <td onclick="location.href='<?php echo G5_URL."/admin/order_view.php?od_id=".$list[$i]['od_id']."&page=".$page; ?>'" class="md_none"><?php echo $list[$i]["mb_id"]; ?></td>
                            <td onclick="location.href='<?php echo G5_URL."/admin/order_view.php?od_id=".$list[$i]['od_id']."&page=".$page; ?>'" class="md_none"><?php echo $list[$i]["pd_mb_id"]; ?></td>
                            <td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/order_view.php?od_id=".$list[$i]['od_id']."&page=".$page; ?>'"><?php echo $pd_price; ?></td>
                            <td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/order_view.php?od_id=".$list[$i]['od_id']."&page=".$page; ?>'"><?php echo $od_status; ?></td>
                            <td class="md_none" >
                                <input type="button" value="상세보기">
                            </td>
                        </tr>
                        <?php
                        $stat = "";
                    }
                    if(count($list)==0){
                        ?>
                        <tr>
                            <td colspan="6" class="text-center" style="padding:50px 0;">거래 목록이 없습니다.</td>
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
                                <li class="prev"><a href="<?php echo G5_URL."/admin/order_list.php?bo_table=".$bo_table."&page=".($page-1); ?>">&lt;</a></li>
                            <?php } ?>
                            <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                                <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/order_list.php?bo_table=".$bo_table."&page=".$i; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <?php if($page<$total_page){?>
                                <li class="next"><a href="<?php echo G5_URL."/admin/order_list.php?bo_table=".$bo_table."&page=".($page+1); ?>">&gt;</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!--<div class="submit_gr no_print">
                <a href="<?php /*echo G5_URL."/admin/board_write.php?bo_table=".$bo_table; */?>" class="adm-btn01">글쓰기</a>
            </div>-->
        </article>
    </section>
</div>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
