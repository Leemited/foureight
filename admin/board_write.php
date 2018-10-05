<?php
include_once("./_common.php");
include_once(G5_EDITOR_LIB);
include_once(G5_PATH."/admin/admin.head.php");

set_session('ss_bo_table', $_REQUEST['bo_table']);
set_session('ss_wr_id', $_REQUEST['wr_id']);

$table = "g5_write_".$_REQUEST["bo_table"];

$write = sql_fetch("select * from `{$table}` where `wr_id` = '{$wr_id}'");

if($wr_id){
	$w = 'u';
	$ca_name = $write["ca_name"];
}

$content = get_text($write['wr_content'], 0);

$is_dhtml_editor = false;
$is_dhtml_editor_use = false;
$editor_content_js = '';
if(!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE)
	$is_dhtml_editor_use = true;

// 모바일에서는 G5_IS_MOBILE_DHTML_USE 설정에 따라 DHTML 에디터 적용
if ($config['cf_editor'] && $is_dhtml_editor_use && $board['bo_use_dhtml_editor'] && $member['mb_level'] >= $board['bo_html_level']) {
	$is_dhtml_editor = true;

	if ( $w == 'u' && (! $is_member || ! $is_admin || $write['mb_id'] !== $member['mb_id']) ){
		// kisa 취약점 제보 xss 필터 적용
		$content = get_text(html_purifier($write['wr_content']), 0);
	}

	if(is_file(G5_EDITOR_PATH.'/'.$config['cf_editor'].'/autosave.editor.js'))
		$editor_content_js = '<script src="'.G5_EDITOR_URL.'/'.$config['cf_editor'].'/autosave.editor.js"></script>'.PHP_EOL;
}

$editor_html = editor_html('wr_content', $content, $is_dhtml_editor);
$editor_js = '';
$editor_js .= get_editor_js('wr_content', $is_dhtml_editor);
$editor_js .= chk_editor_js('wr_content', $is_dhtml_editor);

// 임시 저장된 글 수
$autosave_count = autosave_count($member['mb_id']);
if($bo_table=="help"){
	$title = "도움말";
}
?>
<!-- 본문 start -->
<div id="wrap">
	<section>
		<header class="admin_title">
			<h1><?php echo $title;?></h1>
		</header>
			<div class="write_form model_list">
				<form action="<?php echo G5_URL?>/admin/board_write_update.php" method="post" name="fwrite" id="fwrite" onsubmit="return fnsubmit(this)">
				<input type="hidden" name="bo_table" id="bo_table" value="<?php echo $bo_table;?>">
				<input type="hidden" name="ca_name" id="ca_name" value="<?php echo $ca_name;?>">
				<input type="hidden" name="wr_id" id="wr_id" value="<?php echo $wr_id;?>">
				<input type="hidden" name="w" id="w" value="<?php echo $w?>">
				<input type="hidden" name="wr_name" id="wr_name" value="<?php echo $member["mb_name"];?>">
				<input type="hidden" name="mb_id" id="mb_id" value="<?php echo $member["mb_id"];?>">
				<input type="hidden" name="wr_emiil" id="wr_emiil" value="<?php echo $member["mb_email"];?>">
				<table class="view_tbl">
					<colgroup>
						<col width="10%">
						<col width="90%">
					</colgroup>
					<tbody class="pcT">
						<tr>
							<th>제목</th>
							<td class="con"><input type="text" value="<?php echo $write["wr_subject"];?>" name="wr_subject" id="wr_subject" required class="write_input01 grid_60"/></td>
						</tr>
						<tr class="content">
							<th>내용</th>
							<td>
								<?php echo $editor_html;?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="write_gr">
				<input type="button" class="cancel_btn" value="취소" onclick="location.href='<?php echo G5_URL?>/admin/board_list.php?bo_table=<?php echo $bo_table;?>&page=<?php echo $page;?>&sfl=<?php echo $sfl;?>&stx=<?php echo $stx;?>&ca_name=<?php echo $ca_name;?>'">
				<input type="submit" value="<?php if($wr_id){?>수정<?php }else{ ?>등록<?php }?>" class="submit_btn" style="border:none"/>
			</div>
			</form>

	</section>
</div>
<script>
function fnsubmit(){
	<?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

	document.fwrite.submit();
}
</script>
<?php
	include_once(G5_PATH."/admin/admin.tail.php");
?>
