<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

$sql = "select * from `g5_write_help` where wr_is_comment = 0;";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$help[] = $row;
}
?>
<style>
	#head{position:relative;}
</style>
<!-- 모바일 헤더 시작 -->
<div id="head">
	<div class="top_header relative <?php if($_SESSION["type1"]==2){?>bg2<?php }?>" onclick="location.href='<?php echo G5_URL?>';" data-direction="reverse">
		<div class="owl-carousel" id="helps">
			<?php for($i=0;$i<count($help);$i++){?>
			<div class="item"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=help&wr_id=<?php echo $help[$i]["wr_id"];?>"><?php echo $help[$i]["wr_subject"];?></a></div>
			<div class="item"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=help&wr_id=<?php echo $help[$i]["wr_id"];?>"><?php echo $help[$i]["wr_subject"];?></a></div>
			<?php }?>
		</div>
	</div>
</div>
<script>
    var owl = $("#helps");
    owl.owlCarousel({
        center: true,
        items:1,
        loop:true,
        autoplay:true,
        smartSpeed:3000,
        autoplaySpeed:3000,
        nav:false,
        navText:['',''],
        dot:false
    });
</script>
