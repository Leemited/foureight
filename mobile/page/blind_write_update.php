<?php
include_once ("../../common.php");
if($blind_con=="기타") {
    if($blind_con_txt==""){
        alert("기타 내용을 입력해주세요.");
    }
    $blind_con = $blind_con_txt;
}


$sql = "select * from `product` where pd_id = '{$pd_id}'";
$mypro = sql_fetch($sql);
if($mypro["mb_id"] == $mb_id){
    alert("자신의 글은 신고할 수 없습니다.");
    return false;
}


$sql = "select count(*) as cnt from `product_blind` where mb_id = '{$member["mb_id"]}' and pd_id = '{$pd_id}'";
$myblind = sql_fetch($sql);
if($myblind["cnt"] > 0){
    alert("이미 신고한 게시물입니다.");
    return false;
}

$sql = "insert into `product_blind` set mb_id= '{$member["mb_id"]}', pd_id='{$pd_id}', blind_content = '{$blind_con}', blind_date = now()";
if(sql_query($sql)){
    $sql = "update `product` set pd_blind = pd_blind + 1 where pd_id ='{$pd_id}'";
    sql_query($sql);

    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $mypro = sql_fetch($sql);
    if($mypro["pd_blind"]>=10){
        $sql = "update `product` set pd_blind_status = 1 where pd_id = '{$pd_id}'";
        sql_query($sql);
    }
    //게시자
    $mb = get_member($mypro["mb_id"]);
    if($mb["regid"]) {
        if($mypro["pd_blind"] < 10){
            $msg = "\r\n현재 [".$mypro["pd_blind"]."]회, [".$blind_con."]으로 신고 되었습니다.";
        }
        if($mypro["pd_blind"]>=10){
            $msg = "\r\n현재 10회 누적 신고되어 블라인드 처리되었습니다.";
        }
        $img = "";
        if ($mypro["pd_images"]) {
            $imgs = explode(",", $mypro["pd_images"]);
            $img = G5_DATA_URL . "/product/" . $imgs[0];
        }
        send_FCM($mb["regid"],"48 신고 알림","[".cut_str($mypro["pd_tag"],10,"...")."]의 게시글에 대해 신고가 접수되었습니다.\r\n10회 누적시 자동 블라인드 처리됩니다.".$msg,G5_URL."/mobile/page/mypage/mypage.php?pd_id=".$pd_id,"notice_alarm_set","기본알림",$mb["mb_id"],$pd_id,$img,'','');

    }

    alert("신고처리 되었습니다. 신고는 게시물당 한번만 할 수 있으며 누락 10건일 경우 자동 블라인드 처리됩니다.");

}else{
   alert("신고처리가 제대로 되지 않았습니다. \r다시 시도해 주세요.");
}