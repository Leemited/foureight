<?php
include_once ("./_common.php");

$sql = "update `product` set pd_blind = 10, pd_blind_status = 1 where pd_id = {$pd_id}";
if(sql_query($sql)){
    $sql = "select *,m.mb_id as mb_id from `product` as p left join `g5_member` as m on m.mb_id = p.mb_id where p.pd_id = '{$pd_id}'";
    $mb = sql_fetch($sql);
    
    //해당 요청자에게 푸시 알림
    if ($mb["pd_images"]) {
        $imgs = explode(",", $mb["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    //요청알림
    send_FCM($mb["regid"], $mb["pd_tag"], $mb["pd_tag"] . "의 블라인드가 해제 되었습니다.", G5_MOBILE_URL . "/page/mypage/mypage.php?pd_type=".$mb["pd_type"], 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);
    
    alert("정상 처리되었습니다.");
}else{
    alert("잘못된 요청입니다.");
}