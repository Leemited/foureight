<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$bo_table = $_REQUEST["bo_table"];

$table = "g5_write_".$bo_table;

$view = sql_fetch("select * from `{$table}` where `wr_id` = '{$wr_id}'");

switch($bo_table){
	case "notice":
		$subject = "공지사항";
		break;
	case "help":
		$subject = "도움말";
		break;
	case "렌탈상담":
		$subject = "렌탈상담";
		break;
}

?>
<!-- 본문 start -->
<div id="wrap">
	<section>
		<header class="admin_title">
			<h1><?php echo $subject;?></h1>
		</header>
		<article>
			<div class="model_list">
				<div class="etc_gr">
				</div>
				<table class="view_tbl">
					<colgroup class="pcT2">
						<col width="12%">
						<col width="23%">
						<col width="12%">
						<col width="23%">
					</colgroup>
					<colgroup class="mobileT2">
						<col width="23%">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
					</colgroup>
					<tbody class="pcT">
						<tr>
							<th>제목</th>
							<td colspan="3" class="subject"><?php echo $view["wr_subject"];?></td>
						</tr>
						<tr>
							<th>작성자</th>
							<td ><?php echo $view["wr_name"];?></td>
							<th>작성일</th>
							<td ><?php echo $view["wr_datetime"];?></td>
						</tr>
						<tr>
							<th>내용</th>
							<td colspan="3" class="con"><?php echo nl2br($view["wr_content"]);?></td>
						</tr>
					</tbody>
					<tbody class="mobileT">
						<tr>
							<th>제목</th>
							<td colspan="3" class="subject"><?php echo $view["wr_subject"];?></td>
						</tr>
						<tr>
							<th>작성자</th>
							<td colspan="3" class="subject"><?php echo $view["wr_name"];?></td>
						</tr>
						<tr>
							<th>작성일</th>
							<td colspan="3" class="subject"><?php echo $view["wr_datetime"];?></td>
						</tr>
						<tr>
							<th>내용</th>
							<td colspan="3" class="con"><?php echo nl2br($view["wr_content"]);?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="submit_gr no_print">
				<a href="<?php echo G5_URL."/admin/board_list.php?bo_table=".$bo_table."&ca_name=".$view["ca_name"]; ?>" class="adm-btn01">목록</a>
				<a href="<?php echo G5_URL."/admin/board_write.php?bo_table=".$bo_table."&wr_id=".$wr_id."&ca_name".$view["ca_name"]; ?>" class="adm-btn01">수정</a>
			</div>
		</article>
	</section>
</div>
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
