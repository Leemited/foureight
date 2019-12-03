<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

if($sfl && $stx){
	$sfl2 = explode("||",$sfl);
	for($i=0;$i<count($sfl2);$i++){
		$search .= " and {$sfl2[$i]} like '%{$stx}%'";
	}
}

$table = "g5_write_".$_REQUEST["bo_table"];
$bo_table = $_REQUEST["bo_table"];

$total=sql_fetch("select count(*) as cnt from `{$table}` where `wr_is_comment` = 0 {$where} {$search} ");
if(!$page)
	$page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select * from `{$table}` where `wr_is_comment` = 0 {$where} {$search} order by `wr_id` desc limit {$start},{$rows}";
$query=sql_query($sql);
$j=0;
while($data=sql_fetch_array($query)){
	$list[$j]=$data;
	$list[$j]['num']=$total-($start)-$j;
	$j++;
}

$token = get_write_token($bo_table);
if($bo_table=="help"){
	$title = "도움말";
}else if($bo_table=="notice"){
    $title = "공지사항";
}
?>
<!-- 본문 start -->
<div id="wrap">
	<section>
		<header class="admin_title">
			<h1><?php echo $title;?></h1>
		</header>
		<article>
			<div class="model_list">
				<div class="search">
					<div>
						<form action="" method="get">
						<input type="hidden" name="bo_table" value="<?php echo $bo_table;?>">
						<select name="sfl" id="sfl" class="serch_input01">
							<option value="">전체</option>
							<option value="wr_subject" <?php if($sfl=="wr_subject"){echo "selected";}?>>제목</option>
							<option value="wr_content" <?php if($sfl=="wr_content"){echo "selected";}?>>내용</option>
							<option value="wr_subject||wr_content" <?php if($sfl=="wr_subject||wr_content"){echo "selected";}?>>제목+내용</option>
							<option value="wr_name" <?php if($sfl=="wr_name"){echo "selected";}?>>작성자</option>
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
						<col width="*">
						<col width="10%" class="md_none">
						<col width="12%" class="md_none">
						<col width="10%" class="md_none">
						<col width="10%">	
					</colgroup>
					<thead>
						<tr>
							<th class="md_none">번호</th>							
							<th>제목</th>							
                            <th class="md_none">작성자</th>
							<th class="md_none">등록일</th>
							<th class="md_none">조회수</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
					<?php
						for($i=0;$i<count($list);$i++){
					?>
						<tr>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/board_view.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>'"><?php echo $list[$i]['num']; ?></td>						
							<td class="subject" onclick="location.href='<?php echo G5_URL."/admin/board_view.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>'"><?php if($title=="상담목록" || $title=="렌탈상담" || $title=="분양상담"){echo "<b>[".$list[$i]["ca_name"]."]</b>";} echo $list[$i]['wr_subject']; ?></td>
                            <td onclick="location.href='<?php echo G5_URL."/admin/board_view.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>'" class="md_none"><?php echo $list[$i]["wr_name"]; ?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/board_view.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>'" class="md_none"><?php echo $list[$i]["wr_datetime"]; ?></td> 
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/board_view.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>'"><?php echo $list[$i]["wr_hit"]; ?></td> 
							<td class="img">
							<a href="<?php echo G5_URL."/admin/board_write.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>"><!-- <img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt="">-->수정</a>
							<a href="<?php echo "./delete.php?chk=1&wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table."&ca_name=".$list[$i]["ca_name"]."&token=".$token; ?>" class="lasttd"><!-- <img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""> -->삭제</a>
							</td>
							<!-- <a href="<?php echo G5_URL."/admin/board_write.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>" class="adm-btn01 btn" style="padding:5px 10px;">수정</a> <?php if($bo_table=="questions" && $list[$i]["wr_comment"]==0){?><a href="<?php echo G5_URL."/admin/board_comment_write.php?wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table; ?>" class="adm-btn01 btn" style="padding:5px 10px;">답변</a><?php }?> <a href="<?php echo G5_BBS_URL."/delete.php?chk=1&wr_id=".$list[$i]['wr_id']."&page=".$page."&bo_table=".$bo_table."&token=".$token; ?>" class="adm-btn01 btn" style="padding:5px 10px;">삭제</a> -->
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
					<li class="prev"><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=".$bo_table."&page=".($page-1); ?>">&lt;</a></li>
				<?php } ?>
				<?php for($i=$start_page;$i<=$end_page;$i++){ ?>
					<li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=".$bo_table."&page=".$i; ?>"><?php echo $i; ?></a></li>
				<?php } ?>
				<?php if($page<$total_page){?>
					<li class="next"><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=".$bo_table."&page=".($page+1); ?>">&gt;</a></li>
				<?php } ?>
				</ul>
			</div>
			<?php
			}
			?>
			</div>
			<div class="submit_gr no_print">
				<a href="<?php echo G5_URL."/admin/board_write.php?bo_table=".$bo_table; ?>" class="adm-btn01">글쓰기</a>
			</div>
		</article>
	</section>
</div>
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
