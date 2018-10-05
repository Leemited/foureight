<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/*if($w != 'u' || $is_guest)
    return;
	*/

if((defined('G5_NAVER_OAUTH_CLIENT_ID') && G5_NAVER_OAUTH_CLIENT_ID) || (defined('G5_KAKAO_OAUTH_REST_API_KEY') && G5_KAKAO_OAUTH_REST_API_KEY) || (defined('G5_FACEBOOK_CLIENT_ID') && G5_FACEBOOK_CLIENT_ID) || (defined('G5_GOOGLE_CLIENT_ID') && G5_GOOGLE_CLIENT_ID)) {

add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/oauth/style.css">', 10);
add_javascript('<script src="'.G5_PLUGIN_URL.'/oauth/jquery.oauth.login.js"></script>', 10);

$social_oauth_url = G5_PLUGIN_URL.'/oauth/login.php?mode=connect&amp;service=';

include_once(G5_PLUGIN_PATH.'/oauth/functions.php');

// 연동여부 확인
$nid_ico = '';
$kko_ico = '';
$fcb_ico = '';
$ggl_ico = '';

if($member['mb_id']) {
    if(!is_social_connected($member['mb_id'], 'naver'))
        $nid_class = ' sns-icon-not';

    if(!is_social_connected($member['mb_id'], 'kakao'))
        $kko_class = ' sns-icon-not';

    if(!is_social_connected($member['mb_id'], 'facebook'))
        $fcb_class = ' sns-icon-not';

    if(!is_social_connected($member['mb_id'], 'google'))
        $ggl_class = ' sns-icon-not';
}
?>
<div class="<?php echo (G5_IS_MOBILE ? 'm-' : ''); ?>login-sns sns-wrap-32 sns-wrap-over">
	<h2>SNS 간편 가입</h2>
    <div class="sns-wrap">
        <?php if(defined('G5_FACEBOOK_CLIENT_ID') && G5_FACEBOOK_CLIENT_ID) { ?>
        <a href="<?php echo $social_oauth_url.'facebook'; ?>" target="_blank" class="sns-icon social_oauth sns-fb"><span class="ico"><img src="<?php echo G5_IMG_URL?>/sns_login_facebook.svg" alt=""></span>페이스북 회원가입</a>
        <?php } ?>
        <?php if(defined('G5_NAVER_OAUTH_CLIENT_ID') && G5_NAVER_OAUTH_CLIENT_ID) { ?>
        <a href="<?php echo $social_oauth_url.'naver'; ?>" target="_blank" class="sns-icon social_oauth sns-naver"><span class="ico"><img src="<?php echo G5_IMG_URL?>/sns_login_naver.svg" alt=""></span>네이버 회원가입</a>
        <?php } ?>
        <?php if(defined('G5_KAKAO_OAUTH_REST_API_KEY') && G5_KAKAO_OAUTH_REST_API_KEY) { ?>
        <a href="<?php echo $social_oauth_url.'kakao'; ?>" target="_blank" class="sns-icon social_oauth sns-kk"><span class="ico"><img src="<?php echo G5_IMG_URL?>/sns_login_kakao.svg" alt=""></span>카카오 회원가입</a>
        <?php } ?>
        <?php if(defined('G5_GOOGLE_CLIENT_ID') && G5_GOOGLE_CLIENT_ID) { ?>
        <a href="<?php echo $social_oauth_url.'google'; ?>" target="_blank" class="sns-icon social_oauth sns-gg"><span class="ico"></span><span class="txt">구글 회원가입</span></a>
        <?php } ?>
    </div>
</div>

<?php
}
?>