<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

if($order){
    $orders = " order by {$order} {$desc}";
}else {
    $orders = " order by `cp_id` desc";
}

if($sfl && $stx){
	$sfl2 = explode("||",$sfl);
	for($i=0;$i<count($sfl2);$i++){
		$search .= " and {$sfl2[$i]} like '%{$stx}%'";
	}
}


$total=sql_fetch("select count(*) as cnt from `company_info` where cp_id != 'admin' and status != 3 {$where} {$search} ");
if(!$page)
	$page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select * from `company_info` where cp_id != 'admin' and status != 3 {$where} {$search} {$orders} limit {$start},{$rows}";
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
			<h1>회원관리</h1>
		</header>
		<article>
			<div class="model_list">
				<div class="search">
					<div>
						<form action="" method="get">
						<input type="hidden" name="bo_table" value="<?php echo $bo_table;?>">
						<select name="sfl" id="sfl" class="serch_input01">
							<option value="">전체</option>
							<option value="mb_id" <?php if($sfl=="mb_id"){echo "selected";}?>>아이디</option>
							<option value="com_name" <?php if($sfl=="com_name"){echo "selected";}?>>사업자명</option>
							<option value="com_num" <?php if($sfl=="com_num"){echo "selected";}?>>사업자등록번호</option>
							<option value="com_addr1" <?php if($sfl=="com_addr1"){echo "selected";}?>>주소</option>
                            <option value="com_datetime" <?php if($sfl=="com_datetime"){echo "selected";}?>>등록일</option>
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
						<col width="8%" class="md_none">
						<col width="15%"class="md_none">
						<col width="12%" >
						<col width="20%" class="md_none">
						<col width="*" class="md_none">
						<col width="10%" class="md_none">
						<col width="10%">	
					</colgroup>
					<thead>
						<tr>
							<th class="md_none ">번호</th>
							<th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=mb_id"; ?>','desc');">아이디</th>
                            <th class=" link" onclick="fnOrder('<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=com_name"; ?>','desc');">사업자명</th>
							<th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=com_num"; ?>','desc');">사업자등록번호</th>
							<th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=com_addr1"; ?>','desc');">주소</th>
							<th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=com_datetime"; ?>','desc');">등록일</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
					<?php
						for($i=0;$i<count($list);$i++){
						    $date = explode(" ", $list[$i]["com_datetime"]);
						    $today = date("Y-m-d");
						    if($date[0]==$today){
						        $todate = $date[1];
                            }else{
						        $todate = $date[0];
                            }

					?>
						<tr>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_company_view.php?page=".$page."&cp_id=".$list[$i]["cp_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]['num']; ?></td>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_company_view.php?page=".$page."&cp_id=".$list[$i]["cp_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]["mb_id"];?></td>
                            <td onclick="location.href='<?php echo G5_URL."/admin/member_company_view.php?page=".$page."&cp_id=".$list[$i]["cp_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'" class="md_none"><?php echo $list[$i]["com_name"]; ?></td>
							<td clss="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_company_view.php?page=".$page."&cp_id=".$list[$i]["cp_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'" class="md_none"><?php echo $list[$i]["com_num"]; ?></td>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_company_view.php?page=".$page."&cp_id=".$list[$i]["cp_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo "[".$list[$i]["com_zip"]."]".$list[$i]["com_addr1"]." ".$list[$i]["com_addr2"]; ?></td>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_company_view.php?page=".$page."&cp_id=".$list[$i]["cp_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $todate; ?></td>
							<td class="">
								<a href="<?php echo G5_URL."/admin/member_company_view.php?cp_id=".$list[$i]['cp_id']; ?>"><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
								<a href="<?php echo G5_URL."/admin/member_company_delete.php?cp_id=".$list[$i]["cp_id"]; ?>" class=""><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
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
			</div>
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
					<li class="prev"><a href="<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>">&lt;</a></li>
				<?php } ?>
				<?php for($i=$start_page;$i<=$end_page;$i++){ ?>
					<li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".$i."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>"><?php echo $i; ?></a></li>
				<?php } ?>
				<?php if($page<$total_page){?>
					<li class="next"><a href="<?php echo G5_URL."/admin/member_company_list.php?bo_table=".$bo_table."&page=".($page+1)."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>">&gt;</a></li>
				<?php } ?>
				</ul>
			</div>
			<?php
			}
			?>
			<!-- <div class="submit_gr no_print">
				<a href="<?php echo G5_URL."/admin/board_write.php?bo_table=".$bo_table; ?>" class="adm-btn01">글쓰기</a>
			</div> -->
		</article>
	</section>
</div>
<script>
    function fnOrder(url, order, desc) {
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
