<?php
include_once ("../../../common.php");

if($id) {
    $sql = "select * from `product_pricing` where id = '{$id}'";
    $pro = sql_fetch($sql);

    $mb = get_member($pro["mb_id"]);
}else{
    $mb = get_member($mb_id);
}
$result["member"] = $mb;
if($mb["mb_id"]){
    $sql = "select * from `mysetting` where mb_id = '{$mb["mb_id"]}'";
    $set = sql_fetch($sql);

    $result["set"] = $set;

    if($set["hp_set"]=="1"){
        if($mb["mb_hp"]) {
            $result["obj"] .= "<li onclick=\"window.open('tel:" . $mb["mb_hp"] . "')\"><img src='" . G5_IMG_URL . "/view_menu_call.svg' alt=''><span>전화하기</span></li>";
        }
    }
    if($set["sms_set"]=="1"){
        if($mb["mb_hp"]) {
            $result["obj"] .= "<li onclick=\"window.open('sms:" . $mb["mb_hp"] . "')\"><img src='" . G5_IMG_URL . "/view_menu_msg.svg' alt=''><span>문자하기</span></li>";
        }
    }
    if($set["chat_set"]=="1"){
        if($pd_id) {
            //$roomid = $pd_id . "_" . mt_rand();;
            $result["obj"] .= "<li onclick=\"fnTalk2('".$mb["mb_id"]."','".$pd_id."','".$roomid."')\"><img src='" . G5_IMG_URL . "/view_menu_talk.svg' alt=''><span>대화하기</span></li>";
        }else{
            $result["obj"] .= "<li onclick=\"fnTalk2('".$mb["mb_id"]."','".$pd_id."','".$roomid."')\"><img src='" . G5_IMG_URL . "/view_menu_talk.svg' alt=''><span>대화하기</span></li>";
        }
    }
}

echo json_encode($result);