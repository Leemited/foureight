<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$mb_id = $_REQUEST["mb_id"];

$view = sql_fetch("select * from `{$g5[member_table]}` where `mb_id` = '{$mb_id}'");

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
							<th>아이디</th>
							<td colspan="3" class="subject"><?php echo $view["mb_id"];?></td>
						</tr>
						<tr>
							<th>이름</th>
							<td ><?php echo $view["mb_name"];?></td>
							<th>등록일</th>
							<td ><?php echo $view["mb_datetime"];?></td>
						</tr>
						<tr>
							<th>이메일</th>
							<td ><?php echo $view["mb_email"];?></td>
							<th>전화번호</th>
							<td ><?php echo ($view["mb_hp"])?$view["mb_hp"]:"등록되지 않았습니다.";?></td>
						</tr>
						<tr>
							<th>회원등급</th>
							<td ><?php echo $view["mb_level"];?></td>
							<th>접속일</th>
							<td ><?php echo $view["mb_today_login"];?></td>
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
				<a href="<?php echo G5_URL."/admin/member_list.php?page=".($page+1)."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>" class="adm-btn01">목록</a>
				<a href="<?php echo G5_URL."/admin/product_list.php?sch_id=".$view["mb_id"]; ?>" class="adm-btn01">등록 게시물 보기</a>
				<!-- <a href="<?php echo G5_URL."/admin/board_write.php"; ?>" class="adm-btn01">수정</a> -->
			</div>
		</article>
	</section>
</div>
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
