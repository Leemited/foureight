<?php
include_once('../../../common.php');
$subject = strip_tags($_REQUEST['subject']);
$content = strip_tags($_REQUEST['content']);
$sub_title = strip_tags($_REQUEST['content']);
//$filter = explode(",", strtolower(trim($config['cf_filter'])));
// strtolower 에 의한 한글 변형으로 아래 코드로 대체 (곱슬최씨님이 알려 주셨습니다.)
$filter = explode(",", trim($config['cf_filter']));

for ($i=0; $i<count($filter); $i++) {
    $str = $filter[$i];

    // 제목 필터링 (찾으면 중지)
    $subj = "";
    $pos = stripos($subject, $str);
    if ($pos !== false) {
        $subj = $str;
        break;
    }

    // 내용 필터링 (찾으면 중지)
    $cont = "";
    $pos = stripos($content, $str);
    if ($pos !== false) {
        $cont = $str;
        break;
    }

    // 내용 필터링 (찾으면 중지)
    $subt = "";
    $pos = stripos($sub_title, $str);
    if ($pos !== false) {
        $subt = $str;
        break;
    }
}

$data["subject"] = $subj;
$data["content"] = $cont;
$data["subtitle"] = $subt;

echo json_encode($data);
?>