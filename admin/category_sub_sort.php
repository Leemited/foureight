<?php 
include_once("./_common.php");

$last_cate = sql_fetch("select cate_order from `categorys` where cate_type = '{$cate_type}' and parent_ca_id = '{$parent_ca_id}' and cate_depth = 2 order by cate_order desc limit 0, 1");

if($sort_type == "up"){
	
	if($order == $last_cate["cate_order"]){
		echo "1";
		return false;
	}
	$cate_order = $order + 1;

	$sql = "update `categorys` set cate_order = {$order} where cate_order = '{$cate_order}' and cate_type = '{$cate_type}' and parent_ca_id = {$parent_ca_id}";
	
	sql_query($sql);

	$sql = "update `categorys` set cate_order = {$cate_order} where ca_id = '{$ca_id}'";

	sql_query($sql);

	$total=sql_fetch("select count(*) as cnt from `categorys` where  cate_type = {$cate_type} and cate_depth = 2 and parent_ca_id = '{$parent_ca_id}'");
	if(!$page)
		$page=1;
	$total=$total['cnt'];
	$rows=10;
	$start=($page-1)*$rows;
	$total_page=ceil($total/$rows);
	$sql="select * from `categorys` where  cate_type = {$cate_type} and cate_depth = 2 and parent_ca_id = '{$parent_ca_id}' order by cate_order desc limit {$start},{$rows}";
	$query=sql_query($sql);
	$j=0;
	while($data=sql_fetch_array($query)){
		$list[$j]=$data;
		$list[$j]['num']=$total-($start)-$j;
		$j++;
	}
	for($i=0;$i<count($list);$i++){
	?>
		<tr>
			<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]['num']; ?></td>						
			<td class="" onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]['cate_name']; ?></td>
			<td onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]["cate_type"]; ?></td> 
			<td>
				<a href="javascript:fnsort('up','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>','<?php echo $list[$i]["parent_ca_id"];?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_up.png" alt=""></a>
				<a href="javascript:fnsort('down','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>','<?php echo $list[$i]["parent_ca_id"];?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_down.png" alt=""></a>
			</td>
			<td>
				<a href="<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>" class="" ><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
				<a href="javascript:fnDel('<?php echo G5_URL."/admin/category_delete.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>');" class="" ><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
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
}else if($sort_type == "down"){
	if($order == 0){
		echo "1";
		return false;
	}
	$cate_order = $order - 1;

	$sql = "update `categorys` set cate_order = {$order} where cate_order = '{$cate_order}' and cate_type = '{$cate_type}' and parent_ca_id = {$parent_ca_id}";
	
	sql_query($sql);

	$sql = "update `categorys` set cate_order = {$cate_order} where ca_id = '{$ca_id}'";

	sql_query($sql);

	$total=sql_fetch("select count(*) as cnt from `categorys` where  cate_type = {$cate_type} and parent_ca_id = '{$parent_ca_id}' and cate_depth = 2");
	if(!$page)
		$page=1;
	$total=$total['cnt'];
	$rows=10;
	$start=($page-1)*$rows;
	$total_page=ceil($total/$rows);
	$sql="select * from `categorys` where  cate_type = {$cate_type} and parent_ca_id = '{$parent_ca_id}' and cate_depth = 2 order by cate_order desc limit {$start},{$rows}";
	$query=sql_query($sql);
	$j=0;
	while($data=sql_fetch_array($query)){
		$list[$j]=$data;
		$list[$j]['num']=$total-($start)-$j;
		$j++;
	}
	for($i=0;$i<count($list);$i++){
	?>
		<tr>
			<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]['num']; ?></td>						
			<td class="" onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]['cate_name']; ?></td>
			<td onclick="location.href='<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>'"><?php echo $list[$i]["cate_type"]; ?></td> 
			<td>
				<a href="javascript:fnsort('up','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>','<?php echo $list[$i]["parent_ca_id"];?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_up.png" alt=""></a>
				<a href="javascript:fnsort('down','<?php echo $list[$i]["ca_id"];?>','<?php echo $page; ?>','<?php echo $cate_type; ?>','<?php echo $list[$i]["cate_order"];?>','<?php echo $list[$i]["parent_ca_id"];?>')" class="" ><img src="<?php echo G5_IMG_URL?>/ic_down.png" alt=""></a>
			</td>
			<td>
				<a href="<?php echo G5_URL."/admin/category_view.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>" class="" ><img src="<?php echo G5_IMG_URL?>/ic_edit.png" alt=""></a>
				<a href="javascript:fnDel('<?php echo G5_URL."/admin/category_delete.php?ca_id=".$list[$i]['ca_id']."&page=".$page."&cate_type=".$cate_type; ?>');" class="" ><img src="<?php echo G5_IMG_URL?>/ic_del.png" alt=""></a>
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
}

?>