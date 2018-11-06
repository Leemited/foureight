<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/tail.php');
    return;
}
?>

<?php
//if(G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) { 
?>

<!-- <a href="<?php echo get_device_change_url(); ?>" id="device_change">PC 버전으로 보기</a> -->

<?php
//}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>


<?php
include_once(G5_PATH."/tail.sub.php");
?>