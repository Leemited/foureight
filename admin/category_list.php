<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

if(!$cate_type){
	$cate_type = 1;
}
$total=sql_fetch("select count(*) as cnt from `categorys` where  cate_type = {$cate_type} and cate_depth = 1");
if(!$page)
	$page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$sql="select * from `categorys` where  cate_type = {$cate_type} and cate_depth = 1 order by cate_order desc limit {$start},{$rows}";
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
			<h1>카테고리[등록]</h1>
		</header>
		<article>

			<div class="mall_view">
				<form action="<?php echo G5_URL?>/admin/category_add.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="cate_type" value="<?php echo $cate_type;?>">
					<table class="cate_add">
						<colgroup>
							<col width="200px;">
							<col width="*;">
							<col width="200px;">
						</colgroup>	
						<tr>
							<th>카테고리명</th>
							<td><input type="text" name="cate_name" id="cate_name" class="write_input01 grid_50" required>
                                CATEGORY ICON : <input type="file" name="cate_icon" id="cate_icon" required>
                            </td>
							<td class="btns"><input type="submit" value="등록" class="adm_btn"></td>
						</tr>
					</table>
				</form>
            </div>
		</article>
		<header class="admin_title">
			<h1>카테고리[목록]</h1>
		</header>
		<article>
			<div class="model_list">
                <div class="search">
                    <div>
                        <form action="" method="get">
                            <input type="hidden" name="bo_table" value="<?php echo $bo_table;?>">
                            <select name="sfl" id="sfl" class="serch_input01">
                                <option value="">전체</option>
                                <option value="cate_name" <?php if($sfl=="wr_subject"){echo "selected";}?>>카테고리명</option>
                                <option value="cate_name" <?php if($sfl=="wr_subject"){echo "selected";}?>>카테고리키워드</option>
                                <option value="cate_name" <?php if($sfl=="wr_subject"){echo "selected";}?>>카테고리명</option>
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
						<li onclick="location.href='<?php echo G5_URL?>/admin/category_list.php?cate_type=1'" <?php if($cate_type==1){?>class="active"<?php } ?>>물품</li>
						<li onclick="location.href='<?php echo G5_URL?>/admin/category_list.php?cate_type=2'" <?php if($cate_type==2){?>class="active"<?php } ?>>능력</li>
					</ul>
					<h2 class="board_t"><?php echo $subject;?></h2>
					<span class="total_list">전체 <?php echo $total;?> | <?php echo count($list);?></span>	
				</div>
				<table>
					<colgroup>
						<col width="8%" class="md_none">
						<col width="*">	
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th class="md_none">번호</th>							
							<th class="">카테고리명</th>							
							<th class="">카테고리구분</th>
							<th class="">정렬</th>
							<th class="">관리</th>
						</tr>
					</thead>
					<tbody class="list_body">
					<?php
						for($i=0;$i<count($list);$i++){
					?>
						<tr>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]['num']; ?></td>						
							<td class="" onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'">
                                <img src="<?php echo G5_DATA_URL."/cate/".$list[$i]["icon"];?>" alt="" style="width:30px">
                                <?php echo $list[$i]['cate_name']; ?>
                            </td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]["cate_type"]; ?></td> 
							<td>
								<a href="javascript:fnsort('up','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_up.png" alt=""></a>
								<a href="javascript:fnsort('down','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_down.png" alt=""></a>
							</td>
							<td>
								<a href="<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>" class="" ><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
								<a href="javascript:fnDel('<?php echo G5_URL."/admin/category_delete.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>');" class="" ><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
							</td> 
							<!--<td class="img">
							<a href="<?php echo G5_URL."/admin/room_write.php?ca_id=".$list[$i]['ca_id']."&page=".$page; ?>"><!-- <img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""> --관리</a>
							<!-- <a href="<?php echo G5_BBS_URL."/delete.php?chk=1&ca_id=".$list[$i]['ca_id']."&page=".$page."&token=".$token; ?>"><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a> --
							</td>
							<!-- <a href="<?php echo G5_URL."/admin/board_write.php?ca_id=".$list[$i]['ca_id']."&page=".$page; ?>" class="adm-btn01 btn" style="padding:5px 10px;">수정</a> <?php if($bo_table=="questions" && $list[$i]["wr_comment"]==0){?><a href="<?php echo G5_URL."/admin/board_comment_write.php?ca_id=".$list[$i]['ca_id']."&page=".$page; ?>" class="adm-btn01 btn" style="padding:5px 10px;">답변</a><?php }?> <a href="<?php echo G5_BBS_URL."/delete.php?chk=1&ca_id=".$list[$i]['ca_id']."&page=".$page."&token=".$token; ?>" class="adm-btn01 btn" style="padding:5px 10px;">삭제</a> -->
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
						<li class="prev"><a href="<?php echo G5_URL."/admin/category_list.php?page=".($page-1)."&cate_type=".$cate_type; ?>">&lt;</a></li>
					<?php } ?>
					<?php for($i=$start_page;$i<=$end_page;$i++){ ?>
						<li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/category_list.php?page=".$i."&cate_type=".$cate_type; ?>"><?php echo $i; ?></a></li>
					<?php } ?>
					<?php if($page<$total_page){?>
						<li class="next"><a href="<?php echo G5_URL."/admin/category_list.php?page=".($page+1)."&cate_type=".$cate_type; ?>">&gt;</a></li>
					<?php } ?>
					</ul>
				</div>
				<?php
				}
				?>
			</div>
		</article>
		<header class="admin_title">
			<h1>카테고리[등록]</h1>
		</header>
		<article>
			<div class="mall_view">
                <form action="<?php echo G5_URL?>/admin/category_add.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="cate_type" value="<?php echo $cate_type;?>">
                    <table class="cate_add">
                        <colgroup>
                            <col width="200px;">
                            <col width="*;">
                            <col width="200px;">
                        </colgroup>
                        <tr>
                            <th>카테고리명</th>
                            <td><input type="text" name="cate_name" id="cate_name" class="write_input01 grid_50" required>
                                CATEGORY ICON : <input type="file" name="cate_icon" id="cate_icon" required>
                            </td>
                            <td class="btns"><input type="submit" value="등록" class="adm_btn"></td>
                        </tr>
                    </table>
                </form>
            </div>
		</article>
	</section>
</div>
<script>
function fnsort(sort_type,ca_id,page,cate_type,order){
	$.ajax({
		url:g5_url+"/admin/category_sort.php",
		method:"POST",
		data:{sort_type:sort_type,ca_id:ca_id,page:page,cate_type:cate_type,order:order}
	}).done(function(data){
		if(data == "1"){
			alert("더이상 이동 할 수 없습니다.");
		}else{
			$(".list_body").html(data);
		}
	});
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
