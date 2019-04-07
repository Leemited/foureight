<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

if(!$cate_type){
	$cate_type = 1;
}
$total=sql_fetch("select count(*) as cnt from `category_user_temp` where 1");
if(!$page)
	$page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select * from `category_user_temp` where  1 order by insert_date desc limit {$start},{$rows}";
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
			<h1>카테고리제안[목록]</h1>
		</header>
		<article>
			<div class="model_list">
				<div class="etc_gr">
					<h2 class="board_t"><?php echo $subject;?></h2>
					<span class="total_list">전체 <?php echo $total;?> | <?php echo count($list);?></span>	
				</div>
				<table>
					<colgroup>
						<col width="8%" class="md_none">
						<col width="*">
						<col width="18%">
						<col width="15%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th class="md_none">번호</th>							
							<th class="">제안사항1</th>
							<th class="">제안사항2</th>
							<th class="">등록자</th>
							<th class="">등록일</th>
							<th class="">상태</th>
							<th>구분</th>
							<th class="">관리</th>
						</tr>
					</thead>
					<tbody class="list_body">
					<?php
						for($i=0;$i<count($list);$i++){
							switch($list[$i]["status"]){
								case 0:
									$status = "등록대기";
									break;
								case 1:
									$status = "등록완료";
									break;
							}
					?>
						<tr>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo $list[$i]['num']; ?></td>		
                            <?php if($list[$i]["cate_name"]){?>
							<td class="" onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo $list[$i]['cate_name']; ?></td>
							<td class="" onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo ($list[$i]['cate_name2'])?$list[$i]["cate_name2"]:"-"; ?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo $list[$i]["mb_id"]; ?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo $list[$i]["insert_date"];?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo $status;?></td>
							<td>
								<select name="cate_type" id="cate_type" class="write_input01">
									<option value="1">물품</option>
									<option value="2">능력</option>
								</select>
							</td>
                            <?php }else{?>
							<td class="" onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'" colspan="2"><?php echo "일반 제안"?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo $list[$i]["mb_id"]; ?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo $list[$i]["insert_date"];?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo "-";?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_temp_view.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>'"><?php echo "-";?></td>
                            <?php }?>
							<td>
								<?php if($list[$i]["status"]!=1 && $list[$i]["cate_name"]){?>
								<a href="javascript:fnCateAdd('<?php echo G5_URL."/admin/category_temp_update.php?cate_name=".$list[$i]["cate_name"]."&cate_name2=".$list[$i]["cate_name2"]."&ca_temp_id=".$list[$i]["ca_temp_id"]; ?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_add.png" alt="바로등록"></a>
								<?php }?>
								<a href="javascript:fnDel('<?php echo G5_URL."/admin/category_temp_delete.php?ca_temp_id=".$list[$i]["ca_temp_id"]; ?>');" class="" ><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt="삭제"></a>
							</td> 
						</tr>
					<?php
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
						<li class="prev"><a href="<?php echo G5_URL."/admin/category_temp_list.php?page=".($page-1)."&cate_type=".$cate_type; ?>">&lt;</a></li>
					<?php } ?>
					<?php for($i=$start_page;$i<=$end_page;$i++){ ?>
						<li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/category_temp_list.php?page=".$i."&cate_type=".$cate_type; ?>"><?php echo $i; ?></a></li>
					<?php } ?>
					<?php if($page<$total_page){?>
						<li class="next"><a href="<?php echo G5_URL."/admin/category_temp_list.php?page=".($page+1)."&cate_type=".$cate_type; ?>">&gt;</a></li>
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
<script>
function fnCateAdd(url){
	var type = $("#cate_type").val();
	var type_name = $("#cate_type option:selected").text();
	if(confirm('해당 카테고리를 ['+type_name+']에 바로 등록하시겠습니까?')){
		location.href=url+"&cate_type="+type+"&temp=true";
	}else{
		return false;
	}
}
function fnDel(url){
	if(confirm("해당 카테고리를 삭제하시겠습니까?\r카테고리 삭제시 하위 카테고리도 삭제됩니다.")){
		location.href=url;
	}else{
		return false;
	}
}
</script>	
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
