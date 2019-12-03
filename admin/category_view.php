<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$subject = "카테고리[보기]";
$view = sql_fetch("select * from `categorys` where `ca_id` = '{$ca_id}'");
$total=sql_fetch("select count(*) as cnt from `categorys` where  parent_ca_id = {$ca_id} and cate_depth = 2");

if(!$page2)
	$page2=1;

$total = $total['cnt'];
$rows=10;
$start=($page2-1)*$rows;
$total_page=ceil($total/$rows);

$sql="select * from `categorys` where parent_ca_id = {$ca_id} and cate_depth = 2 order by cate_order desc limit {$start},{$rows}";
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
			<h1><?php echo $subject;?></h1>
		</header>
		<article>
            <form action="<?php echo G5_URL?>/admin/category_update.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="ca_id" id="ca_id" value="<?php echo $ca_id;?>">
			<div class="model_list">
				<div class="etc_gr">
				</div>
				<table class="view_tb">
					<colgroup >
						<col width="20%">
						<col width="*">
					</colgroup>
					<tbody >
                    <tr>
                        <th>카테고리 아이콘</th>
                        <td class="subject" style="cursor:auto">
                            <img src="<?php echo G5_DATA_URL."/cate/".$view["icon"];?>" alt="" style="width:30px">
                            <input type="file" name="icon" id="icon" class="" >
                            <!--<input type="button" value="수정" class="adm-btn01" style="border:none;width:auto" onclick="fnCateDesc('<?php /*echo $ca_id;*/?>');">-->
                        </td>
                    </tr>
						<tr>
							<th>카테고리명</th>
							<td class="subject" style="cursor:auto">
								<input type="text" name="cate_name" id="ca_name" class="write_input01 grid_90" value="<?php echo $view["cate_name"];?>">
								<!--<input type="button" value="수정" class="adm-btn01" style="border:none;width:auto" onclick="fnCateName('<?php /*echo $ca_id;*/?>');">-->
							</td>
						</tr>
                        <!--<tr>
                            <th>카테고리 키워드 등록</th>
                            <td class="subject" style="cursor:auto">
                                <input type="text" name="cate_tag" id="cate_tag" class="write_input01 grid_90" value="<?php echo $view["cate_tag"];?>" placeholder="<?php if($view["cate_tag"]==""){echo "미등록";}?>">
                                <p>키워드는 ','구분하여 등록바랍니다.</p>
                            </td>
                        </tr>
						<tr>
							<th>판매자-카테고리안내문구</th>
							<td class="subject" style="cursor:auto">
								<input type="text" name="info_text1" id="info_text1" class="write_input01 grid_90" value="<?php echo $view["info_text1"];?>" placeholder="<?php if($view["info_text1"]==""){echo "미등록";}?>">
                                <!--<p>키워드는 ','구분하여 등록바랍니다.</p>--
							</td>
						</tr>
                        <tr>
                            <th>구매자-카테고리안내문구</th>
                            <td class="subject" style="cursor:auto">
                                <input type="text" name="info_text2" id="info_text2" class="write_input01 grid_90" value="<?php echo $view["info_text2"];?>" placeholder="<?php if($view["info_text2"]==""){echo "미등록";}?>">
                            </td>
                        </tr>
                        -->
					</tbody>
				</table>
			</div>
			<div class="submit_gr no_print">
				<input type="button" onclick="location.href='<?php echo G5_URL."/admin/category_list.php?bo_table=".$bo_table."&page=".$page."&cate_type=".$cate_type; ?>'" class="adm-btn01" style="border:none;width:auto" value="목록">
                <input type="submit" value="수정" class="adm-btn01" style="border:none;width:auto" >
			</div>
            </form>
		</article>
		<header class="admin_title">
			<h1>2차 카테고리 목록</h1>
		</header>
		<article>
			<div class="model_list">
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
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/category_sub_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]['num']; ?></td>						
							<td class="" onclick="location.href='<?php echo G5_URL."/admin/category_sub_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]['cate_name']; ?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/category_sub_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]["cate_type"]; ?></td> 
							<td>
								<a href="javascript:fnsort('up','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>','<?php echo $ca_id;?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_up.png" alt=""></a>
								<a href="javascript:fnsort('down','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>','<?php echo $ca_id;?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_down.png" alt=""></a>
							</td>
							<td>
								<!--<a href="<?php /*echo G5_URL."/admin/category_sub_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; */?>" class="" ><img src="<?php /*echo G5_IMG_URL*/?>/ic_edit.png" alt=""></a>-->
								<a href="javascript:fnDel('<?php echo G5_URL."/admin/category_delete.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>');" class="" ><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
							</td> 
						</tr>
					<?php
						}
						if(count($list)==0){
					?>
						<tr>
							<td colspan="5" class="text-center" style="padding:50px 0;">등록된 게시물이 없습니다.</td>
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
							if($total_page<($page2+2)){
								$start_page=$total_page-4;
								$end_page=$total_page;
							}else if($page2>3){
								$start_page=$page2-2;
								$end_page=$page2+2;
							}else{
								$start_page=1;
								$end_page=5;
							}
						}
				?>
				<div class="num_list01">
					<ul>
					<?php if($page2!=1){?>
						<li class="prev"><a href="<?php echo G5_URL."/admin/category_view.php?page=".$page."&page2=".($page2-1)."&cate_type=".$cate_type."&ca_id=".$ca_id; ?>">&lt;</a></li>
					<?php } ?>
					<?php for($i=$start_page;$i<=$end_page;$i++){ ?>
						<li class="<?php echo $page2==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/category_view.php?page=".$page."&page2=".$i."&cate_type=".$cate_type."&ca_id=".$ca_id; ?>"><?php echo $i; ?></a></li>
					<?php } ?>
					<?php if($page2<$total_page){?>
						<li class="next"><a href="<?php echo G5_URL."/admin/category_view.php?page=".$page."&page2=".($page2+1)."&cate_type=".$cate_type."&ca_id=".$ca_id; ?>">&gt;</a></li>
					<?php } ?>
					</ul>
				</div>
				<?php
				}
				?>
			</div>
		</article>
		<header class="admin_title">
			<h1>2차 카테고리[등록]</h1>
		</header>
		<article>
			<div class="mall_view">
			<form action="<?php echo G5_URL?>/admin/category_add.php">
				<input type="hidden" name="cate_type" value="<?php echo $cate_type;?>">
				<input type="hidden" name="parent_ca_id" value="<?php echo $view["ca_id"];?>">
				<table class="cate_add">
					<colgroup>
						<col width="200px;">
						<col width="*;">
						<col width="200px;">
					</colgroup>	
					<tr>
						<th>카테고리명</th>
						<td><input type="text" name="cate_name" id="cate_name" class="write_input01 grid_100" ></td>
						<td class="btns"><input type="submit" value="등록" class="adm_btn"></td>
					</tr>
				</table>
			</form>
		</article>
		<div class="submit_gr no_print">
				<a href="<?php echo G5_URL."/admin/category_list.php?bo_table=".$bo_table."&page=".$page."&cate_type=".$cate_type; ?>" class="adm-btn01">목록</a>
			</div>
	</section>
</div>
<script>
function fnCateName(ca_id){
	var cate_name=$("#ca_name").val();
	$.ajax({
		url:g5_url+"/admin/category_name_update.php",
		method:"POST",
		data:{ca_id:ca_id,cate_name:cate_name}
	}).done(function(data){
		if(data==1){
			alert("수정 되었습니다.");
		}else{
			alert("잘못된 접근입니다.");
		}
	});
}
function fnCateDesc(ca_id){
	var info_text=$("#info_text").val();
	$.ajax({
		url:g5_url+"/admin/category_infotext_update.php",
		method:"POST",
		data:{ca_id:ca_id,info_text:info_text}
	}).done(function(data){
		if(data==1){
			alert("수정 되었습니다.");
		}else{
			alert("잘못된 접근입니다.");
		}
	});
}
function fnsort(sort_type,ca_id,page,cate_type,order,parent_ca_id){
	$.ajax({
		url:g5_url+"/admin/category_sub_sort.php",
		method:"POST",
		data:{sort_type:sort_type,ca_id:ca_id,page:page,cate_type:cate_type,order:order,parent_ca_id:parent_ca_id}
	}).done(function(data){
		if(data == "1"){
			alert("더이상 이동 할 수 없습니다.");
		}else{
			$(".list_body").html(data);
		}
	});
}
function fnDel(url){
	if(confirm("해당 카테고리를 삭제하시겠습니까?")){
		location.href=url;
	}else{
		return false;
	}
}
</script>
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
