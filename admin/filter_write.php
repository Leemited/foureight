<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");
$config = sql_fetch(" select * from {$g5['config_table']} ");

?>
<!-- 본문 start -->
<div id="wrap">
	<section>
		<header class="admin_title">
			<h1>금칙어 관리</h1>
		</header>
		<article>
			<form action="<?php echo G5_URL?>/admin/filter_update.php" method="post" name="filter">
			<div class="model_list">
				<div class="etc_gr">
				</div>
				<table >
					<colgroup >
						<col width="12%">
						<col width="23%">
					</colgroup>
					<tbody >
						<tr>
							<th>금칙어</th>
							<td colspan="5" class="subject">
								<textarea name="cf_filter" id="cf_filter" cols="30" rows="10"><?php echo $config["cf_filter"];?></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="submit_gr no_print">
				<a href="javascript:document.filter.submit()" class="adm-btn01">수정</a>
			</div>
			</form>
		</article>
	</section>
</div>
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
