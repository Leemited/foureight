<?php
include_once('./_common.php');

// 이호경님 제안 코드
session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함

// 자동로그인 해제 --------------------------------
set_cookie('ck_mb_id', '', 0);
set_cookie('ck_auto', '', 0);
// 자동로그인 해제 end --------------------------------

$app = false;
if(stripos($_SERVER["HTTP_USER_AGENT"],"foureight")){
    $app = true;
}

$app2 = false;
if($_SERVER["HTTP_USER_AGENT"] == "iosApp"){
    $app2 = true;
}


if ($url) {
    if ( substr($url, 0, 2) == '//' )
        $url = 'http:' . $url;

    $p = @parse_url(urldecode($url));
    /*
        // OpenRediect 취약점관련, PHP 5.3 이하버전에서는 parse_url 버그가 있음 ( Safflower 님 제보 ) 아래 url 예제
        // http://localhost/bbs/logout.php?url=http://sir.kr%23@/
    */
    if (preg_match('/^https?:\/\//i', $url) || $p['scheme'] || $p['host']) {
        alert('url에 도메인을 지정할 수 없습니다.', G5_URL);
    }

    $link = $url;
} else if ($bo_table) {
    $link = G5_BBS_URL.'/board.php?bo_table='.$bo_table;
} else {
    $link = G5_URL;
}

?>
<script>
    var chk = false;
    <?php if($app){?>
    chk = window.android.setLogout();
    <?php }?>
    <?php if($app2){?>
    webkit.messageHandlers.onLogout.postMessage("logout");
    <?php }?>
    if(chk) {
        location.href = '<?php echo $link;?>';
    }else{
        location.href = '<?php echo $link;?>';
    }
</script>

