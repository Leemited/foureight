<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$subject = "카테고리[보기]";
$view = sql_fetch("select * from `categorys` where `ca_id` = '{$ca_id}'");

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
                <input type="hidden" name="parent_ca_id" id="parent_ca_id" value="<?php echo $view["parent_ca_id"];?>">
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
							<th>카테고리명</th>
							<td class="subject" style="cursor:auto">
								<input type="text" name="cate_name" id="ca_name" class="write_input01 grid_90" value="<?php echo $view["cate_name"];?>">
								<!--<input type="button" value="수정" class="adm-btn01" style="border:none;width:auto" onclick="fnCateName('<?php /*echo $ca_id;*/?>');">-->
							</td>
						</tr>
                        <tr>
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
                                <!--<p>키워드는 ','구분하여 등록바랍니다.</p>-->
							</td>
						</tr>
                        <tr>
                            <th>구매자-카테고리안내문구</th>
                            <td class="subject" style="cursor:auto">
                                <input type="text" name="info_text2" id="info_text2" class="write_input01 grid_90" value="<?php echo $view["info_text2"];?>" placeholder="<?php if($view["info_text2"]==""){echo "미등록";}?>">
                            </td>
                        </tr>
					</tbody>
				</table>
			</div>
			<div class="submit_gr no_print">
				<input type="button" onclick="location.href='<?php echo G5_URL."/admin/category_view.php?bo_table=".$bo_table."&page=".$page."&cate_type=".$cate_type."&ca_id=".$view["parent_ca_id"]; ?>'" class="adm-btn01" style="border:none;width:auto" value="목록">
                <input type="submit" value="수정" class="adm-btn01" style="border:none;width:auto" >
			</div>
            </form>
		</article>
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
