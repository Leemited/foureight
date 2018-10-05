<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

if($order){
    $orders = " order by {$order} {$desc}";
}else {
    $orders = " order by mb_no desc";
}

if($sfl && $stx){
	$sfl2 = explode("||",$sfl);
	for($i=0;$i<count($sfl2);$i++){
		$search .= " and {$sfl2[$i]} like '%{$stx}%'";
	}
}


$total=sql_fetch("select count(*) as cnt from `{$g5[member_table]}` where mb_id != 'admin' {$where} {$search} ");
if(!$page)
	$page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select * from `{$g5[member_table]}` where mb_id != 'admin' {$where} {$search} {$orders} limit {$start},{$rows}";
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
							<option value="mb_name" <?php if($sfl=="mb_name"){echo "selected";}?>>이름</option>
							<option value="mb_email" <?php if($sfl=="mb_email"){echo "selected";}?>>이메일</option>
							<option value="mb_hp" <?php if($sfl=="mb_hp"){echo "selected";}?>>연락처</option>
						</select>
						<input type="text" name="stx" id="stx" class="serch_input02" value="<?php echo $stx;?>" placeholder="검색어를 입력하세요">
						<div class="btn_gr">
							<input type="submit" value="검색" class="search_btn" >
						</div>
						</form>
					</div>
				</div>

				<div class="etc_gr">
                   <!-- <ul class="cate">
                        <li onclick="location.href='<?php /*echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx."&order=mb_datetime";*/ ?>'" <?php /*if($cate_type==1){*/?>class="active"<?php /*} */?>>가입일순</li>
                        <li onclick="location.href='<?php /*echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx."&order=mb_id"; */?>'" <?php /*if($cate_type==2){*/?>class="active"<?php /*} */?>>아이디순</li>
                        <li onclick="location.href='<?php /*echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx."&order=mb_name"; */?>'" <?php /*if($cate_type==1){*/?>class="active"<?php /*} */?>>이름순</li>
                    </ul>-->
					<h2 class="board_t"><?php echo $subject;?></h2>
					<span class="total_list">전체 | <?php echo count($list);?></span>	
				</div>
				<table>
					<colgroup class="md_none">
						<col width="8%" class="md_none">
						<col width="10%">
						<col width="10%" class="md_none">
						<col width="10%" class="md_none">
						<col width="10%" class="md_none">
						<col width="10%" class="md_none">
						<col width="10%">	
					</colgroup>
					<thead>
						<tr>
							<th class="md_none">번호</th>
							<th class="link" onclick="fnOrder('<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=mb_id"; ?>','desc');">아이디</th>
                            <th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=mb_name"; ?>','desc');">회원이름</th>
							<th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=mb_level"; ?>','desc');">회원등급</th>
							<th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=mb_email"; ?>','desc');">이메일</th>
							<th class="md_none link" onclick="fnOrder('<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx;?>','<?php echo "&order=mb_datetime"; ?>','desc');">가입일</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
					<?php
						for($i=0;$i<count($list);$i++){
					?>
						<tr>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_view.php?page=".$page."&mb_id=".$list[$i]["mb_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]['num']; ?></td>
							<td class="" onclick="location.href='<?php echo G5_URL."/admin/member_view.php?page=".$page."&mb_id=".$list[$i]["mb_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]["mb_id"];?></td>
                            <td onclick="location.href='<?php echo G5_URL."/admin/member_view.php?page=".$page."&mb_id=".$list[$i]["mb_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'" class="md_none"><?php echo $list[$i]["mb_name"]; ?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/member_view.php?page=".$page."&mb_id=".$list[$i]["mb_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'" class="md_none"><?php echo $list[$i]["mb_level"]; ?></td>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_view.php?page=".$page."&mb_id=".$list[$i]["mb_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]["mb_email"]; ?></td>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/member_view.php?page=".$page."&mb_id=".$list[$i]["mb_id"]."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>'"><?php echo $list[$i]["mb_datetime"]; ?></td>
							<td class="">
								<a href="<?php echo G5_URL."/admin/member_view.php?mb_id=".$list[$i]['mb_id']; ?>"><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
								<a href="<?php echo G5_BBS_URL."/delete.php?"; ?>" class=""><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
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
					<li class="prev"><a href="<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page-1)."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>">&lt;</a></li>
				<?php } ?>
				<?php for($i=$start_page;$i<=$end_page;$i++){ ?>
					<li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".$i."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>"><?php echo $i; ?></a></li>
				<?php } ?>
				<?php if($page<$total_page){?>
					<li class="next"><a href="<?php echo G5_URL."/admin/member_list.php?bo_table=".$bo_table."&page=".($page+1)."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>">&gt;</a></li>
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
