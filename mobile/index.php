<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

if($is_member){
    $sql = "select * from `mysetting` where mb_id = '{$member["mb_id"]}'";
    $myset = sql_fetch($sql);
    //정산 계좌
    /*$sql ="select * from `my_bank` where mb_id = '{$member["mb_id"]}' ab";*/
}
include_once (G5_MOBILE_PATH."/index.set.php");
include_once(G5_MOBILE_PATH.'/head.php');
include_once (G5_MOBILE_PATH."/index.html.php");


include (G5_MOBILE_PATH."/index.view.php");
?>

<?php
$p = "index";
include_once(G5_MOBILE_PATH.'/tail.php');
?>