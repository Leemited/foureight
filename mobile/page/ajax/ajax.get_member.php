<?php
include_once ("../../../common.php");

$sql = "select * from `product_pricing` where id = '{$id}'";
$pro = sql_fetch($sql);

$mb = get_member($pro["mb_id"]);
$result["member"] = $mb;
if($mb["mb_id"]){
    $sql = "select * from `mysetting` where mb_id = '{$mb["mb_id"]}'";
    $set = sql_fetch($sql);

    $result["set"] = $set;

    if($set["hp_set"]=="1"){
        if($mb["mb_hp"]) {
            $result["obj"] .= "<li onclick=\"window.open('tel:" . $mb["mb_hp"] . "')\"><img src='" . G5_IMG_URL . "/ic_call.svg' alt=''></li>";
        }
    }
    if($set["sms_set"]=="1"){
        if($mb["mb_hp"]) {
            $result["obj"] .= "<li onclick=\"window.open('sms:" . $mb["mb_hp"] . "')\"><img src='" . G5_IMG_URL . "/ic_sms.svg' alt=''></li>";
        }
    }
    if($set["chat_set"]=="1"){
        $result["obj"] .= "<li onclick=\"fnTalk()\"><img src='" . G5_IMG_URL . "/ic_chat.svg' alt=''></li>";
    }
}

echo json_encode($result);