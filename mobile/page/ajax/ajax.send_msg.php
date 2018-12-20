<?php
include_once ("../../../common.php");
/**
 * User: leemited
 * Date: 2018-10-16
 * Time: 오전 10:14
 */
if(!$roomid) {
    $roomid = $pd_id . "_" . mt_rand();
}
$sql = "insert into `product_chat` set pd_id = {$pd_id} , send_mb_id = '{$mb_id}', read_mb_id = '{$read_mb_id}', message = '{$message}',room_id = '{$roomid}', msg_datetime = now(), msg_date = now(), msg_time = now(), msg_status = 0, msg_type = 1 ";
if(sql_query($sql)){
    $id = sql_insert_id();
    $sql = "select * from `product_chat` where id = {$id}";
    $msg = sql_fetch($sql);
    $today = date("Y-m-d");
    if($msg["msg_date"] == $today){
        $date = (date("a",strtotime($msg["msg_time"]))=="am")?"오전":"오후";
        $date .= " ".substr($msg["msg_time"],0,5);
    }else{
        $ampm = (date("a",strtotime($msg["msg_time"]))=="am")?"오전":"오후";
        $date = $msg["msg_date"]."<br>".$ampm." ".substr($msg["msg_time"],0,5);
    }
    $mb = get_member($read_mb_id);
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $pd = sql_fetch($sql);
    if($pd["pd_images"]) {
        $imgs = explode(",",$pd["pd_images"]);
        $img = G5_DATA_URL."/product/".$imgs[0];
    }
    
    send_FCM($mb["regid"], $pd["pd_tag"], $mb_id."의 대화요청이 등록되었습니다.", G5_MOBILE_URL . "/page/talk/talk_view.php?pd_id=".$pd_id."&send_mb_id=".$member["mb_id"]."&roomid=".$roomid, "chat_alarm_set", "대화알림", $read_mb_id, $pd_id, $img);

    $data["msg"] = '<div class="msg_box my_msg"><div class="in_box"><div class="date">'.$date.'</div><div class="msg">'.$message.'</div></div></div>';
    $sql = "select id from `product_chat` where pd_id = {$pd_id} and room_id = '{$roomid}'";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)) {
        $ids[] = $row["id"];
    }
    $data["ids"] = implode(",",$ids);
    $data["room_id"] = $roomid;
    $data["status"] = "success";
}else{
    $data["status"] = "no-save";
}
echo json_encode($data);

